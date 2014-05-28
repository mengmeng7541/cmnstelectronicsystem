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
	//偵測使用者是否短時間內連續刷卡
	public function send_user_access_duplicately_notification()
	{
		
	}
	//偵測使用者是否在同一地方待超過十二小時
	
	//----------------------卡機系統---------------------------
	//corntab中設定每分鐘同步舊資料庫的刷卡紀錄到新資料庫一次
	public function sync_card_access()
	{
		$this->load->model('facility_model');
		$this->facility_model->sync_access_card_list();

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
	public function update_curriculum_class_state()
	{
		$this->load->model('curriculum_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/lesson_model');
		$this->load->model('user_model');
		$this->load->model('facility/booking_model');
		
		
		$classes = $this->curriculum_model->get_class_list(array(
			"class_reg_start_time"=>date("Y-m-d 00:00:01",strtotime("-1day")),
			"class_reg_end_time"=>date("Y-m-d 00:00:00"),
			"class_state"=>"normal"
		))->result_array();
		
		
		
		foreach($classes as $class){
			
			//如果昨天截止報名
			if(strtotime(date("Y-m-d 00:00:01",strtotime("-1day"))) <= strtotime($class['class_reg_end_time']) && strtotime($class['class_reg_end_time']) <= strtotime(date("Y-m-d 00:00:00")))
			{
				
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
				
				//判斷是否滿足大於等於最小開課人數
				$reg_query = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']));
				if($reg_query->num_rows()-$original_canceled_reg_nums >= $class['class_min_participants']){
					foreach($reg_query->result_array() as $reg){
						if(
							empty($class['class_max_participants']) || 
							$reg['reg_rank']-$original_canceled_reg_nums <= $class['class_max_participants']
						)
						{
							if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
							{
								//正常開課，發信通知正取學員
								$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
								$this->email->to($user_profile['email']);
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
							//發信通知備取學員，請他改選其他同課程
							$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
							$this->email->to($user_profile['email']);
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
					
				}else{
					//確認是否上一期是否沒開成且又同一人選課
					
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
						$this->email->to(sql_result_to_column($prof_profiles,"email"));
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
					$users_ID = sql_result_to_column($reg_query->result_array(),"user_ID");
					if(!empty($users_ID))
					{
						$user_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$users_ID))->result_array();
						$this->email->to(sql_result_to_column($user_profiles,"email"));
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
		
		echo "DONE";
	}
	//通知明天上課
	public function send_curriculum_tomorrow_lesson_notification(){
		$this->load->model('curriculum_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/course_model');
		$this->load->model('user_model');
		$this->load->model('facility/access_ctrl_model');
		
		//找出明天有開課的課堂
		$lessons = $this->curriculum_model->get_lesson_list(array(
			"lesson_start_time"=>date("Y-m-d 00:00:00",strtotime("+1day")),
			"lesson_end_time"=>date("Y-m-d 00:00:00",strtotime("+2day")),
			"class_state"=>"normal"
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
						
	//					//取得簽到單(後來又不要了!@#$%^&)
	//					$signature = $this->curriculum_model->get_signature_list(array(
	//						"lesson_ID"=>$lesson['lesson_ID'],
	//						"reg_ID"=>$reg['reg_ID']
	//					))->row_array();
	//					if(empty($signature['signature_ID']))
	//					{
	//						//新增一筆
	//						$this->load->model('curriculum/signature_model');
	//						$signature_ID = $this->signature_model->add($lesson['lesson_ID'],$reg['reg_ID']);
	//						$signature = $this->curriculum_model->get_signature_list(array(
	//							"signature_ID"=>$signature_ID
	//						))->row_array();
	//					}

						$this->email->to($user_profile['email']);
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
