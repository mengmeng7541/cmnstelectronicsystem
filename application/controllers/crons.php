<?php
class Crons extends MY_Controller {

	public function __construct()
	{

		parent::__construct();

//		if(!$this->input->is_cli_request())	die('F');

	}
	//---------------------打卡系統----------------------------
	//偵測中心人員是否逾時未歸(建議每十分鐘執行一次)
	public function send_admin_clock_timeout_notification()
	{
		//非上班時段不運作
		if(date("H:i:s")<"08:00:00"||date("H:i:s")>"17:00:00")
		{
			return;
		}
		$this->load->model('admin_model');
		$this->load->model('facility_model');
		//取出啟始時間在前一小時，無結束時間，且為今天打卡的打卡紀錄
		//還有結束時間小於現在時間，且結束時間為今天的打卡紀錄
		$clocks = $this->admin_model->get_manual_clock_list(array(
			"clock_end_time_start_time"=>"2038-01-01 00:00:00",
			"clock_start_time_start_time"=>date("Y-m-d 00:00:00"),
			"clock_start_time_end_time"=>date("Y-m-d H:i:s",strtotime("-1hour")),
		))->result_array();
		
		$clocks = array_merge($clocks,$this->admin_model->get_manual_clock_list(array(
			"clock_end_time_start_time"=>date("Y-m-d 00:00:00"),
			"clock_end_time_end_time"=>date("Y-m-d H:i:s"),
		))->result_array());
		foreach($clocks as $clock){
			if(!empty($clock['clock_checkpoint'])){
				continue;
			}
			//取得本日最後刷卡紀錄
			$auto_clock = $this->admin_model->get_auto_clock_list(array("admin_ID"=>$clock['clock_user_ID']))->row_array();
			if($auto_clock['access_last_datetime']==NULL || strtotime($auto_clock['access_last_datetime'])<strtotime($clock['clock_start_time'])){
				//如果沒有刷卡紀錄或最後刷卡時間小於起始時間
				//逾時未歸，發信通知
				$this->admin_model->update_clock(array("clock_checkpoint"=>"notified","clock_ID"=>$clock['clock_ID']));
				//取得組長資訊
				$org_charts = $this->admin_model->get_org_chart_list(array(
					"admin_ID"=>$clock['clock_user_ID'],
				))->result_array();
				$this->load->model('common/admin_org_chart_model');
				foreach($org_charts as $org_chart)
				{
					$manager_org_charts = $this->admin_model->get_org_chart_list(array(
						"group_no"=>$org_chart['group_no'],
						"team_no"=>$org_chart['team_no'],
						"status_ID"=>"section_chief"
					))->result_array();
					foreach($manager_org_charts as $manager_org_chart)
					{
						$this->email->to($manager_org_chart['admin_email']);
						$this->email->subject("成大微奈米科技研究中心 -逾時未歸通知-");
						if(empty($clock['clock_end_time']))
						{
							$this->email->message(
								"{$manager_org_chart['admin_name']} 您好<br>
								{$org_chart['admin_name']} 於 {$clock['clock_start_time']} 因 {$clock['clock_reason']} 至 {$clock['clock_location']} 外出，已超過一小時，但目前仍尚未歸來，請留意，謝謝。"
							);
						}else{
							$this->email->message(
								"{$manager_org_chart['admin_name']} 您好<br>
								{$org_chart['admin_name']} 於 {$clock['clock_start_time']} 因 {$clock['clock_reason']} 至 {$clock['clock_location']} 外出，預計於 {$clock['clock_end_time']} 結束，但目前仍尚未歸來，請留意，謝謝。"
							);
						}
						$this->email->send();
					}
				}
			}else{
				$this->admin_model->update_clock(array("clock_checkpoint"=>"returned","clock_ID"=>$clock['clock_ID']));
			}
		}
	}
	//偵測使用者是否短時間內連續刷卡(每十分鐘執行一次)
	public function send_user_access_duplicately_notification()
	{
		//取得門禁管制人員資料
		$this->load->model('access_model');
		$admins = $this->access_model->get_privilege_list(array(
			"privilege"=>"access_super_admin"
		))->result_array();
		$admin_emails = sql_column_to_key_value_array($admins,"admin_email");
		
		//取得中心人員資料
		$this->load->model('admin_model');
		$all_admins = $this->admin_model->get_admin_profile_list(array("suspended"=>FALSE))->result_array();
		$all_admin_card_nums = sql_column_to_key_value_array($all_admins,"card_num");
		
		//只要在主要入口偵測就好
		$this->load->model('facility_model');
		$doors = $this->facility_model->get_facility_list(array("type"=>"door"))->result_array();
		$filtered_doors = array_filter($doors,function($door) use($doors){
			$parent_door_ID = $door['parent_ID'];
			$parent_door = array_filter($doors,function($parent_door) use($parent_door_ID){
				return $parent_door['ID'] == $parent_door_ID;
			});
			$parent_door = reset($parent_door);
			return empty($parent_door) || $door['location_ID']!=$parent_door['location_ID'];
		});
		$ctrl_nos = sql_column_to_key_value_array($filtered_doors,"ctrl_no");
		foreach($ctrl_nos as $ctrl_no)
		{
			//取出前十分鐘~前二十分的狀態，因為卡機接收軟體是pulling，會比較慢收到資料
			$card_logs = $this->access_model->get_access_card_log_list(array(
				"start_time"=>date("Y-m-d H:i:s",strtotime("-20minutes")),
				"end_time"=>date("Y-m-d H:i:s",strtotime("-10minutes")),
				"ctrl_no"=>$ctrl_no,
				"state"=>array("00","01")//有通行權且是進入與離開的狀態
			))->result_array();
			
			//取出卡號
			$card_nums = sql_column_to_key_value_array($card_logs,"log_card_num");
			$card_nums = array_unique($card_nums);
			foreach($card_nums as $card_num)
			{
				//取出同一張卡的所有記錄
				$logs = array_filter($card_logs,function($card_log) use($card_num){
					return $card_log['log_card_num'] == $card_num;
				});
				
				$pre_log = NULL;//清空，已便偵測下個卡片
				foreach($logs as $log){
					if(isset($pre_log))
					{
						if($log['log_state']==$pre_log['log_state'])//都是00或01
						{
							$diff_secs = strtotime($log['log_time'])-strtotime($pre_log['log_time']);
							if($diff_secs < 60)//一分鐘內連續刷卡
							{
								//太相近，發出警報
								if(isset($log['user_name']))
								{
									
									if(in_array($log['log_card_num'],$all_admin_card_nums))
									{
										//是中心人員卡片
									}else{
										//一般使用者卡片
										$state = $log['log_state']=="00"?"進入":($log['log_state']=="01"?"離開":"未知動作");
										$this->email->to($admin_emails);
										$this->email->subject("成大微奈米科技研究中心 -重複刷卡通知-");
										$this->email->message("
											使用者 {$log['user_name']}(卡號{$log['log_card_num']}) {$state} {$log['facility_cht_name']} 時，分別於 {$pre_log['log_time']} 與 {$log['log_time']} 短時間內連續刷卡，系統特此通知，請留意。
										");
										$this->email->send();
									}
									
								}else{
									//可能是臨時卡
								}
							}
						}
					}
					$pre_log = $log;
				}
			}
		}
		
	}
	//偵測使用者是否在同一地方待超過十二小時
	
	//----------------------卡機系統---------------------------
	//corntab中設定每分鐘同步舊資料庫的刷卡紀錄到新資料庫一次
	public function sync_card_access()
	{
		$this->load->model('access_model');
		$this->access_model->sync_access_card_log_list();

		echo "DONE".PHP_EOL;
	}
	
	//寫一個卡機失敗就重新執行的程式(他媽的卡機經常連線失敗，幹，爛廠商)
	public function fix_fault_access_ctrl()
	{
		$this->load->model('facility_model');
		
		$options = array(
			"start_time"=>date("Y-m-d H:i:s",strtotime("-10minutes")),
			"end_time"=>date("Y-m-d H:i:s"),
			"fun"=>"add",
			"state"=>"F",
			"flag"=>1
		);
		$ctrls = $this->facility_model->get_access_ctrl_list($options)->result_array();
		foreach($ctrls as $ctrl)
		{
			$this->facility_model->update_access_ctrl(array(
				"serial_no"=>$ctrl['serial_no']
			));
		}
		
		echo "DONE";
	}
	//-----------------------儀器預約系統-----------------------
	public function send_privilege_expiration_notification(){
		$this->load->model('facility_model');
		//兩周前通知
		$user_privileges = $this->facility_model->get_user_privilege_list(array(
			"expiration_date_start"=>date("Y-m-d H:i:s",strtotime("+2weeks")),
			"expiration_date_end"=>date("Y-m-d H:i:s",strtotime("+2weeks+1day"))
		))->result_array();
		//一周前通知
		$user_privileges = array_merge($user_privileges,$this->facility_model->get_user_privilege_list(array(
			"expiration_date_start"=>date("Y-m-d H:i:s",strtotime("+1weeks")),
			"expiration_date_end"=>date("Y-m-d H:i:s",strtotime("+1weeks+1day"))
		))->result_array());
		//3天前通知
		$user_privileges = array_merge($user_privileges,$this->facility_model->get_user_privilege_list(array(
			"expiration_date_start"=>date("Y-m-d H:i:s",strtotime("+3days")),
			"expiration_date_end"=>date("Y-m-d H:i:s",strtotime("+3days+1day"))
		))->result_array());
		
		$this->load->model('user_model');
		foreach($user_privileges as $user_privilege)
		{
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_privilege['user_ID']))->row_array();
			$this->email->to($user_profile['email']);
			$this->email->subject("成大微奈米科技研究中心 -儀器權限即將過期通知-");
			$this->email->message("
				{$user_profile['name']} 您好：<br>
				您於本中心 {$user_privilege['facility_cht_name']}({$user_privilege['facility_eng_name']}) 儀器之使用權限因久未使用，將於 {$user_privilege['expiration_date']} 過期，<br>
				若您尚有使用的需求，請盡早預約以便延長權限到期日，謝謝！
			");
			$this->email->send();
		}
	} 	
	//------------------------課程系統--------------------------
	//判斷開課或停開
	//如果上個月選課人有選但未開，要強制開課，一般課程與認證課程都要判斷
	public function update_curriculum_class_state()
	{
		$this->load->model('curriculum_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/lesson_model');
		$this->load->model('user_model');
		$this->load->model('facility/booking_model');
		
		//取得課務員email
		$admins = $this->curriculum_model->get_admin_privilege_list(array("privilege"=>"curriculum_super_admin"))->result_array();
		$admin_emails = sql_column_to_key_value_array($admins,"admin_email");
		
		$group_classes = $this->curriculum_model->get_class_list(array(
			"class_reg_end_time_start"=>date("Y-m-d 00:00:01",strtotime("-1day")),
			"class_reg_end_time_end"=>date("Y-m-d 00:00:00"),
			"class_state"=>"normal",
			"group_class_suite"=>TRUE
		))->result_array();//加開的不用判斷
		
		
		
		foreach($group_classes as $group_class)
		{
			//把相關課程都取出來
			$classes = $this->curriculum_model->get_class_list(array(
				"course_ID"=>$group_class['course_ID'],
				"class_code"=>$group_class['class_code'],
				"class_state"=>"normal"
			))->result_array();
			
			
			//判斷是否滿足大於等於最小開課人數
			$reg_num = $this->curriculum_model->get_reg_list(array("class_ID"=>$group_class['class_ID']))->num_rows();
			if($reg_num >= $group_class['class_min_participants']){
				foreach($classes as $class)
				{
						
						//還要注意只開認證時，要排除正規課程未開的人員
								
						//認證課程用特殊處理方式
						$original_canceled_reg_nums = 0;
						$original_canceled_reg_user_IDs = array();
						if($this->class_model->is_certification_class_only($class['class_type']))
						{
							//先取得原本沒開課的報名人數
							$original_canceled_regs = $this->curriculum_model->get_reg_list(array(
								"course_ID"=>$class['course_ID'],
								"class_code"=>$class['class_code'],
								"class_state"=>"canceled",
								"group_class_suite"=>TRUE
							));
							$original_canceled_reg_nums = $original_canceled_regs->num_rows();
							$original_canceled_reg_user_IDs = sql_result_to_column($original_canceled_regs->result_array(),"user_ID");
						}
						
						$regs = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']))->result_array();
						foreach($regs as $reg){
							if(
								empty($class['class_max_participants']) || 
								$reg['reg_rank']-$original_canceled_reg_nums <= $class['class_max_participants']
							)
							{
								if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
								{
									//正常開課，發信通知正取學員
									$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
									$this->email->to($user_profile['email'])->cc($admin_emails);
									$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [正常開課]");
									$this->email->message(
										"{$user_profile['name']} 您好：<br>
										<br>
										本中心將預定於 {$class['class_start_time']}<br>
										開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
										您的報名順序為 正取{$reg['reg_rank']}，請準時前往上課，謝謝。<br>
										日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
										時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
										課程名稱：{$class['course_cht_name']}<br>
										地點：{$class['location_cht_name']}<br>
										授課者：{$class['prof_name']}<br>
										"
									);
									$this->email->send();
								}
								
								
								
								
								
							}else{
								if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
								{
									//發信通知備取學員，請他改選其他同課程
									$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
									$this->email->to($user_profile['email'])->cc($admin_emails);
									$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [備取未上]");
									$this->email->message(
										"{$user_profile['name']} 您好：<br>
										<br>
										本中心將預定於 {$class['class_start_time']}<br>
										開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
										您的報名順序為 備取".($reg['reg_rank']-$class['class_max_participants'])."，很遺憾截至目前為止您尚未遞補上本次課程<br>
										竭誠歡迎您盡速上本中心網站預約下一期的課程，以免向隅，謝謝。"
									);
									$this->email->send();
								}
								
							}
						}
					}
					
					
			}else{
				
				foreach($classes as $class)
				{
						//確認選課人中是否有上個月沒開成的選課人
						//先取得這次選課人
						$regs = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']))->result_array();
						//再取得上個月未開成的選課人
						$last_canceled_classes = $this->curriculum_model->get_class_list(array(
							"class_state"=>"canceled",
							"course_ID"=>$class['course_ID'],
							"class_start_time"=>date("Y-m-01 00:00:00",strtotime($class['class_start_time']." -1month")),
							"class_end_time"=>date("Y-m-01 00:00:00",strtotime($class['class_start_time']))
						))->result_array();
						$last_canceled_regs = $this->curriculum_model->get_reg_list(array(
							"class_ID"=>sql_column_to_key_value_array($last_canceled_classes,"class_ID")
						))->result_array();
						if(array_in_array(sql_column_to_key_value_array($regs,"user_ID"),sql_column_to_key_value_array($last_canceled_regs,"user_ID")))
						{
							//還要注意只開認證時，要排除正規課程未開的人員
								
							//認證課程用特殊處理方式
							$original_canceled_reg_nums = 0;
							$original_canceled_reg_user_IDs = array();
							if($this->class_model->is_certification_class_only($class['class_type']))
							{
								//先取得原本沒開課的報名人數
								$original_canceled_regs = $this->curriculum_model->get_reg_list(array(
									"course_ID"=>$class['course_ID'],
									"class_code"=>$class['class_code'],
									"class_state"=>"canceled",
									"group_class_suite"=>TRUE
								));
								$original_canceled_reg_nums = $original_canceled_regs->num_rows();
								$original_canceled_reg_user_IDs = sql_result_to_column($original_canceled_regs->result_array(),"user_ID");
							}
							
							//要強制開
							foreach($regs as $reg){
								
								if(
									empty($class['class_max_participants']) || 
									$reg['reg_rank']-$original_canceled_reg_nums <= $class['class_max_participants']
								)
								{
									if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
									{
										//正常開課，發信通知正取學員
										$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
										$this->email->to($user_profile['email'])->cc($admin_emails);
										$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [強制開課]");
										$this->email->message(
											"{$user_profile['name']} 您好：<br>
											<br>
											本中心將預定於 {$class['class_start_time']}<br>
											開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
											因您於上個月報名此課程時人數未達開課人數而停開，故本月特此強制開成<br>
											您的報名順序為 正取{$reg['reg_rank']}，請準時前往上課，謝謝。<br>
											日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
											時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
											課程名稱：{$class['course_cht_name']}<br>
											地點：{$class['location_cht_name']}<br>
											授課者：{$class['prof_name']}<br>
											"
										);
										$this->email->send();
									}
									
									
								}else{
									if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
									{
										//發信通知備取學員，請他改選其他同課程
										$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
										$this->email->to($user_profile['email'])->cc($admin_emails);
										$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [備取未上]");
										$this->email->message(
											"{$user_profile['name']} 您好：<br>
											<br>
											本中心將預定於 {$class['class_start_time']}<br>
											開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
											您的報名順序為 備取".($reg['reg_rank']-$class['class_max_participants'])."，很遺憾截至目前為止您尚未遞補上本次課程<br>
											竭誠歡迎您盡速上本中心網站預約下一期的課程，以免向隅，謝謝。"
										);
										$this->email->send();
									}
									
								}
							}
						}else{
							
							//停開，人數不足
							$this->curriculum_model->update_class(array(
								"class_ID"=>$class['class_ID'],
								"class_state"=>"canceled"
							));
							
							//發信通知授課者
							$profs_ID = $this->class_model->get_class_profs_ID($class['class_ID']);
							
							if(!empty($profs_ID))
							{
								$prof_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$profs_ID))->result_array();
								$this->email->to(sql_result_to_column($prof_profiles,"email"))->cc($admin_emails);
								$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
								$this->email->message(
									"您好：<br>
									<br>
									本中心預定於 {$class['class_start_time']}<br>
									開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
									因人數不足停開，請勿前來上課，特此通知，謝謝。"
								);
								$this->email->send();
							}
							
							//發信通知已選課學員
							$users_ID = sql_result_to_column($regs,"user_ID");
							if(!empty($users_ID))
							{
								$user_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$users_ID))->result_array();
								$this->email->to(sql_result_to_column($user_profiles,"email"))->cc($admin_emails);
								$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
								$this->email->message(
									"您好：<br>
									<br>
									本中心預定於 {$class['class_start_time']}<br>
									開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
									因人數不足停開，請勿前來上課，特此通知，謝謝。"
								);
								$this->email->send();
							}
							
							//把預約的儀器全部取消
							$bookings = $this->curriculum_model->get_lesson_booking_map(array("class_ID"=>$class['class_ID']))->result_array();
							if($bookings){
								
								$b_IDs = sql_result_to_column($bookings,"booking_ID");
								$this->booking_model->del($b_IDs);
							}
						}
					}
				
			}
			
		}
		
		echo "DONE";
	}
//	public function update_curriculum_class_state()
//	{
//		$this->load->model('curriculum_model');
//		$this->load->model('curriculum/class_model');
//		$this->load->model('curriculum/lesson_model');
//		$this->load->model('user_model');
//		$this->load->model('facility/booking_model');
//		
//		//取得課務員email
//		$admins = $this->curriculum_model->get_admin_privilege_list(array("privilege"=>"curriculum_super_admin"))->result_array();
//		$admin_emails = sql_column_to_key_value_array($admins,"admin_email");
//		
//		$classes = $this->curriculum_model->get_class_list(array(
//			"class_reg_start_time"=>date("Y-m-d 00:00:01",strtotime("-1day")),
//			"class_reg_end_time"=>date("Y-m-d 00:00:00"),
//			"class_state"=>"normal"
//		))->result_array();//加開的不用判斷
//		
//		
//		
//		foreach($classes as $class){
//			
//			//如果昨天截止報名
//			if(strtotime(date("Y-m-d 00:00:01",strtotime("-1day"))) <= strtotime($class['class_reg_end_time']) && strtotime($class['class_reg_end_time']) <= strtotime(date("Y-m-d 00:00:00")))
//			{
//				
//				//認證課程用特殊處理方式
//				$original_canceled_reg_nums = 0;
//				$original_canceled_reg_user_IDs = array();
//				if($this->class_model->is_certification_class_only($class['class_type']))
//				{
//					//先取得原本沒開課的報名人數
//					$original_canceled_regs = $this->curriculum_model->get_reg_list(array(
//						"course_ID"=>$class['course_ID'],
//						"class_code"=>$class['class_code'],
//						"class_state"=>"canceled",
//						"group_class_suite"=>TRUE
//					));
//					$original_canceled_reg_nums = $original_canceled_regs->num_rows();
//					$original_canceled_reg_user_IDs = sql_result_to_column($original_canceled_regs->result_array(),"user_ID");
//				}
//				
//				//判斷是否滿足大於等於最小開課人數
//				$reg_query = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']));
//				if($reg_query->num_rows()-$original_canceled_reg_nums >= $class['class_min_participants']){
//					foreach($reg_query->result_array() as $reg){
//						if(
//							empty($class['class_max_participants']) || 
//							$reg['reg_rank']-$original_canceled_reg_nums <= $class['class_max_participants']
//						)
//						{
//							if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
//							{
//								//正常開課，發信通知正取學員
//								$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
//								$this->email->to($user_profile['email'])->cc($admin_emails);
//								$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [正常開課]");
//								$this->email->message(
//									"{$user_profile['name']} 您好：<br>
//									<br>
//									本中心將預定於 {$class['class_start_time']}<br>
//									開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//									您的報名順序為 正取{$reg['reg_rank']}，請準時前往上課，謝謝。<br>
//									日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
//									時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
//									課程名稱：{$class['course_cht_name']}<br>
//									地點：{$class['location_cht_name']}<br>
//									授課者：{$class['prof_name']}<br>
//									"
//								);
//								$this->email->send();
//							}
//							
//						}else{
//							//發信通知備取學員，請他改選其他同課程
//							$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
//							$this->email->to($user_profile['email'])->cc($admin_emails);
//							$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [備取未上]");
//							$this->email->message(
//								"{$user_profile['name']} 您好：<br>
//								<br>
//								本中心將預定於 {$class['class_start_time']}<br>
//								開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//								您的報名順序為 備取".($reg['reg_rank']-$class['class_max_participants'])."，很遺憾截至目前為止您尚未遞補上本次課程<br>
//								竭誠歡迎您盡速上本中心網站預約下一期的課程，以免向隅，謝謝。"
//							);
//							$this->email->send();
//						}
//					}
//					
//				}else{
//					
//					
//					//停開，人數不足
//					$this->curriculum_model->update_class(array(
//						"class_ID"=>$class['class_ID'],
//						"class_state"=>"canceled"
//					));
//					
//					//發信通知授課者
//					$profs_ID = $this->class_model->get_class_profs_ID($class['class_ID']);
//					
//					if(!empty($profs_ID))
//					{
//						$prof_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$profs_ID))->result_array();
//						$this->email->to(sql_result_to_column($prof_profiles,"email"))->cc($admin_emails);
//						$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
//						$this->email->message(
//							"您好：<br>
//							<br>
//							本中心預定於 {$class['class_start_time']}<br>
//							開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//							因人數不足停開，請勿前來上課，特此通知，謝謝。"
//						);
//						$this->email->send();
//					}
//					
//					//發信通知已選課學員
//					$users_ID = sql_result_to_column($regs,"user_ID");
//					if(!empty($users_ID))
//					{
//						$user_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$users_ID))->result_array();
//						$this->email->to(sql_result_to_column($user_profiles,"email"))->cc($admin_emails);
//						$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
//						$this->email->message(
//							"您好：<br>
//							<br>
//							本中心預定於 {$class['class_start_time']}<br>
//							開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//							因人數不足停開，請勿前來上課，特此通知，謝謝。"
//						);
//						$this->email->send();
//					}
//					
//					//把預約的儀器全部取消
//					$bookings = $this->curriculum_model->get_lesson_booking_map(array("class_ID"=>$class['class_ID']))->result_array();
//					if($bookings){
//						
//						$b_IDs = sql_result_to_column($bookings,"booking_ID");
//						$this->booking_model->del($b_IDs);
//					}
//				}
//			}
//			
//		}
//		
//		echo "DONE";
//	}
	//通知明天上課
	public function send_curriculum_tomorrow_lesson_notification(){
		$this->load->model('curriculum_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/course_model');
		$this->load->model('user_model');
		$this->load->model('facility/access_ctrl_model');
		
		//取得課務員email
		$admins = $this->curriculum_model->get_admin_privilege_list(array("privilege"=>"curriculum_super_admin"))->result_array();
		$admin_emails = sql_column_to_key_value_array($admins,"admin_email");
		
		//找出明天有開課的課堂
		$lessons = $this->curriculum_model->get_lesson_list(array(
			"lesson_start_time"=>date("Y-m-d 00:00:00",strtotime("+1day")),
			"lesson_end_time"=>date("Y-m-d 00:00:00",strtotime("+2day")),
			"class_state"=>array("normal","additional")
		))->result_array();
		
		foreach($lessons as $lesson)
		{
			$class = $this->curriculum_model->get_class_list(array("class_ID"=>$lesson['class_ID']))->row_array();
			$regs = $this->curriculum_model->get_reg_list(array("class_ID"=>$lesson['class_ID']))->result_array();
			
			//認證課程用特殊處理方式
			$original_canceled_reg_nums = 0;
			$original_canceled_reg_user_IDs = array();
			if($this->class_model->is_certification_class_only($class['class_type']))
			{
				//先取得原本沒開課的報名人數
				$original_canceled_regs = $this->curriculum_model->get_reg_list(array(
					"course_ID"=>$class['course_ID'],
					"class_code"=>$class['class_code'],
					"class_state"=>"canceled",
					"group_class_suite"=>TRUE
				));
				$original_canceled_reg_nums = $original_canceled_regs->num_rows();
				$original_canceled_reg_user_IDs = sql_result_to_column($original_canceled_regs->result_array(),"user_ID");
			}
			
			foreach($regs as $reg)
			{
				//通知正取生
				if(
					empty($class['class_max_participants'])||
					$reg['reg_rank']-$original_canceled_reg_nums<=$class['class_max_participants']
				)
				{
					if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
					{
						//幫他們開門禁權限
						$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
						if(!empty($user_profile['card_num']))//如果沒有卡片就沒辦法開門禁
						{
							$facility_IDs = $this->course_model->get_course_map_facility_ID($reg['course_ID']);
							if(!empty($facility_IDs))
							{
								foreach($facility_IDs as $facility_ID)
								{
									$this->access_ctrl_model->add($facility_ID,$user_profile['ID'],strtotime($lesson['lesson_start_time']),strtotime($lesson['lesson_end_time']),TRUE);
								}
							}
						}
						

						$this->email->to($user_profile['email'])->cc($admin_emails);
						$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [明天上課]");
						$this->email->message(
							"{$user_profile['name']} 您好，<br>
							<br>
							明日是 {$lesson['course_cht_name']} 課程 ".$this->curriculum_model->get_class_type_str($lesson['lesson_type'])." 的上課日，請準時出席，謝謝。<br>
							日期：".date("Y-m-d",strtotime($lesson['lesson_start_time']))."<br>
							時間：".date("H:i:s",strtotime($lesson['lesson_start_time']))."<br>
							課程名稱：{$lesson['course_cht_name']}<br>
							地點：{$lesson['location_cht_name']}<br>
							授課者：{$lesson['lesson_prof_name']}<br>"
						);
						$this->email->send();
					}
					
				}
			}
		}
		
		
		echo "DONE";
	}
}
?>
