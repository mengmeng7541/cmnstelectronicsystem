<?php
class Access_ctrl_model extends MY_Model {
  
  public function __construct()
  {
  	parent::__construct();
    $this->load->model("facility_model");
    $this->load->model("user_model");
  }
  public function get_user_ID_select_options()
  {
  	$user_profile = $this->user_model->get_user_profile_list()->result_array();
	$user_ID_select_options = array(""=>"");
	foreach($user_profile as $u){
		if(!empty($u['card_num']))
			$user_ID_select_options[$u['ID']] = $u['name']." ({$u['ID']}) [{$u['card_num']}]";
	}
	return $user_ID_select_options;
  }
  /**
  * 
  * @param undefined $facility_ID	
  * @param undefined $user_ID
  * @param undefined $start			UNIX_TIME
  * @param undefined $end			UNIX_TIME
  * @param undefined $door_only
  * 
  * @return
  */
  public function add($facility_ID,$user_ID,$start,$end,$door_only = FALSE)
  {//door_only目前主要在課程時會用到
  	$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
  	if($door_only && $user_profile['group']=="admin") return;
  	if(!$user_profile) throw new Exception("無此使用者",ERROR_CODE);
  	if(empty($user_profile['card_num'])) throw new Exception("此使用者無卡片，請先申請。",WARNING_CODE);
  	
  	
  	//判斷是否有借用卡
  	$this->load->model('access_model');
  	$temp_app = $this->access_model->get_access_card_temp_application_list(array(
  		"application_type_ID"=>"user",
  		"used_by"=>$user_profile['ID'],
  		"guest_access_start_time"=>date("Y-m-d H:i:s",$start),
  		"guest_access_end_time"=>date("Y-m-d H:i:s",$end),
  		"application_checkpoint_ID"=>"issued"
  	))->row_array();
  	if($temp_app){
		$this->add_by_card_num($facility_ID,$temp_app['guest_access_card_num'],$start,$end,$door_only);
	}
  	
	$f_IDs = $this->facility_model->get_vertical_group_facilities($facility_ID,array("no_child"=>TRUE,"facility_only"=>$user_profile['group']=="admin","door_only"=>$door_only));
  	//寫入卡機(關聯的父儀器都要開)
	$data = array();
	foreach($f_IDs as $f_ID){
		//取得儀器資訊
		$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
		if(!$facility)	continue;
		if(empty($facility['ctrl_no'])) continue;
		
		if($start && $end)
		{
			//檢查該儀器在預開到關閉之時段是否有開關紀錄，有就刪掉避開BUG
			$options = array(
				"start_time"=>date("Y-m-d H:i:s",$start-$facility['pre_open_sec']),
				"end_time"=>date("Y-m-d H:i:s",$end),
				"card_num"=>$user_profile['card_num'],
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrls = $this->facility_model->get_access_ctrl_list($options)->result_array();
			foreach($ctrls as $ctrl){
				$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
			}

			
			//若之前無資料或第一筆是刪除或是執行失敗了，就要新增
			$options = array(
				"end_time"=>date("Y-m-d H:i:s",max($start-$facility['pre_open_sec'],time())),
				"card_num"=>$user_profile['card_num'],
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrl = $this->facility_model->get_access_ctrl_list($options)->row_array();
			if(!$ctrl || $ctrl['action']=="Del" || $ctrl['status']=="F")
			{
				$row = array();
				$row["date_time"] = date("Y-m-d H:i:s",$start-$facility['pre_open_sec']);
				$row["fun"] = "Add";
				$row["card_num"] = $user_profile['card_num'];
				$row["ctrl_no"] = $facility['ctrl_no'];
				$data[] = $row;
			}
			
			
			//若之後無資料或第一筆是新增，就要刪除
			$options = array(
				"start_time"=>date("Y-m-d H:i:s",$end),
				"card_num"=>$user_profile['card_num'],
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrl = $this->facility_model->get_access_ctrl_list($options)->row_array();
			if(!$ctrl || $ctrl['action']=="Add")
			{
				$row = array();
				$row["date_time"] = date("Y-m-d H:i:s",$end);
				$row["fun"] = "Del";
				$row["card_num"] = $user_profile['card_num'];
				$row["ctrl_no"] = $facility['ctrl_no'];
				$data[] = $row;
			}
			
			
		}else if($start){//只設定起始時間者，會強制開啟
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$start);
			$row["fun"] = "Add";
			$row["card_num"] = $user_profile['card_num'];
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
		}else if($end){//只設定結束時間者，會強制關閉
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$end);
			$row["fun"] = "Del";
			$row["card_num"] = $user_profile['card_num'];
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
		}
	}
	$this->facility_model->add_access_ctrl($data);
  }
  /**
  * 
  * @param array $f_IDs	已經判斷好要開的儀器了，不用再判斷父子或平行關係
  * @param string $card_num
  * @param int $start
  * @param int $end
  * 
  * @return
  */
  public function add_by_card_num($f_IDs,$card_num,$start,$end,$door_only = FALSE){
  	
  	$f_IDs = $this->facility_model->get_vertical_group_facilities($f_IDs,array("no_child"=>TRUE,"door_only"=>$door_only));
  	//寫入卡機(關聯的父儀器都要開)
	$data = array();
	foreach((array)$f_IDs as $f_ID){
		//取得儀器資訊
		$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
		if(!$facility)	continue;
		if(empty($facility['ctrl_no'])) continue;
		
		if($start && $end)
		{
			//檢查該儀器在預開到關閉之時段是否有開關紀錄，有就刪掉避開BUG
			$options = array(
				"start_time"=>date("Y-m-d H:i:s",$start-$facility['pre_open_sec']),
				"end_time"=>date("Y-m-d H:i:s",$end),
				"card_num"=>$card_num,
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrls = $this->facility_model->get_access_ctrl_list($options)->result_array();
			foreach($ctrls as $ctrl){
				$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
			}

			
			//若之前無資料或第一筆是刪除或是執行失敗了，就要新增
			$options = array(
				"end_time"=>date("Y-m-d H:i:s",max($start-$facility['pre_open_sec'],time())),
				"card_num"=>$card_num,
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrl = $this->facility_model->get_access_ctrl_list($options)->row_array();
			if(!$ctrl || $ctrl['action']=="Del" || $ctrl['status']=="F")
			{
				$row = array();
				$row["date_time"] = date("Y-m-d H:i:s",$start-$facility['pre_open_sec']);
				$row["fun"] = "Add";
				$row["card_num"] = $card_num;
				$row["ctrl_no"] = $facility['ctrl_no'];
				$data[] = $row;
			}
			
			
			//若之後無資料或第一筆是新增，就要刪除
			$options = array(
				"start_time"=>date("Y-m-d H:i:s",$end),
				"card_num"=>$card_num,
				"facility_ctrl_no"=>$facility['ctrl_no']
			);
			$ctrl = $this->facility_model->get_access_ctrl_list($options)->row_array();
			if(!$ctrl || $ctrl['action']=="Add")
			{
				$row = array();
				$row["date_time"] = date("Y-m-d H:i:s",$end);
				$row["fun"] = "Del";
				$row["card_num"] = $card_num;
				$row["ctrl_no"] = $facility['ctrl_no'];
				$data[] = $row;
			}
			
			
		}else if($start){
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$start);
			$row["fun"] = "Add";
			$row["card_num"] = $card_num;
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
		}else if($end){
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$end);
			$row["fun"] = "Del";
			$row["card_num"] = $card_num;
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
		}
	}
	$this->facility_model->add_access_ctrl($data);
  }
  
  //
  public function del($facility_ID,$user_ID,$start,$end)
  {
  	$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
  	if(!$user_profile) throw new Exception("無此使用者",ERROR_CODE);
  	if(empty($user_profile['card_num'])) throw new Exception("此使用者無卡片，請先申請。",WARNING_CODE);
  	if(is_array($facility_ID)){
		$f_IDs = $facility_ID;
	}else{
		$f_IDs = $this->facility_model->get_vertical_group_facilities($facility_ID,array("no_child"=>TRUE,"facility_only"=>$user_profile['group']=="admin"));
	}
	
	//判斷是否有借用卡
  	$this->load->model('access_model');
  	$temp_app = $this->access_model->get_access_card_temp_application_list(array(
  		"application_type_ID"=>"user",
  		"used_by"=>$user_profile['ID'],
  		"guest_access_start_time"=>date("Y-m-d H:i:s",$start),
  		"guest_access_end_time"=>date("Y-m-d H:i:s",$end),
  		"application_checkpoint_ID"=>"issued"
  	))->row_array();
  	if($temp_app){
		$this->del_by_card_num($f_IDs,$temp_app['guest_access_card_num'],$start,$end);
	}
	
  	//刪除卡機的時段開關預約紀錄
	foreach($f_IDs as $f_ID){
		//取得儀器資訊
		$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
		if(!$facility)	continue;
		if(empty($facility['ctrl_no'])) continue;
		

		$ctrl = $this->facility_model->get_access_ctrl_list(array(
			"facility_ctrl_no"=>$facility['ctrl_no'],
			"card_num"=>$user_profile['card_num'],
			"fun"=>"Add",
			"f_time"=>date("Y-m-d H:i:s",$start-$facility['pre_open_sec'])
		))->row_array();
		if($ctrl){
			$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
		}
		

		$ctrl = $this->facility_model->get_access_ctrl_list(array(
			"facility_ctrl_no"=>$facility['ctrl_no'],
			"card_num"=>$user_profile['card_num'],
			"fun"=>"Del",
			"f_time"=>date("Y-m-d H:i:s",$end)
		))->row_array();
		if($ctrl){
			$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
		}
		
		//若現在時間在預開到結束之間，表示突然被取消，此時要加DEL堵住，否則會無敵
		if(date("Y-m-d H:i:s") >= $start-$facility['pre_open_sec'] && date("Y-m-d H:i:s") <= $end){
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$start-$facility['pre_open_sec']);
			$row["fun"] = "Del";
			$row["card_num"] = $user_profile['card_num'];
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
			$this->facility_model->add_access_ctrl($data);
		}
	}
	
	//再把預約記錄抓出來重做一次(盡量縮小時間範圍了，不然會很慢)
	$max_pre_open_sec = $this->facility_model->get_max_pre_open_sec($f_IDs);
	$bookings = $this->facility_model->get_facility_booking_list(array(
		"user_ID"=>$user_profile['ID'],
		"start_time"=>date("Y-m-d H:i:s",max($start-$max_pre_open_sec,time())-1),
		"end_time"=>date("Y-m-d H:i:s",$end+$max_pre_open_sec+1)
	))->result_array();//+1-1是為了觸碰到邊緣
	foreach($bookings as $booking){
		$this->add($booking['facility_ID'],$booking['user_ID'],strtotime($booking['start_time']),strtotime($booking['end_time']));
	}
  }
  public function del_by_card_num($f_IDs,$card_num,$start,$end)
  {
  	foreach((array)$f_IDs as $f_ID){
		//取得儀器資訊
		$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
		if(!$facility)	continue;
		if(empty($facility['ctrl_no'])) continue;
		

		$ctrl = $this->facility_model->get_access_ctrl_list(array(
			"facility_ctrl_no"=>$facility['ctrl_no'],
			"card_num"=>$card_num,
			"fun"=>"Add",
			"f_time"=>date("Y-m-d H:i:s",$start-$facility['pre_open_sec'])
		))->row_array();
		if($ctrl){
			$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
		}
		

		$ctrl = $this->facility_model->get_access_ctrl_list(array(
			"facility_ctrl_no"=>$facility['ctrl_no'],
			"card_num"=>$card_num,
			"fun"=>"Del",
			"f_time"=>date("Y-m-d H:i:s",$end)
		))->row_array();
		if($ctrl){
			$this->facility_model->del_access_ctrl(array("serial_no"=>$ctrl['serial_no']));
		}
		
		//若現在時間在預開到結束之間，表示突然被取消，此時要加DEL堵住，否則會無敵
		if(date("Y-m-d H:i:s") >= $start-$facility['pre_open_sec'] && date("Y-m-d H:i:s") <= $end){
			$row = array();
			$row["date_time"] = date("Y-m-d H:i:s",$start-$facility['pre_open_sec']);
			$row["fun"] = "Del";
			$row["card_num"] = $card_num;
			$row["ctrl_no"] = $facility['ctrl_no'];
			$data[] = $row;
			$this->facility_model->add_access_ctrl($data);
		}
	}
  }
  
  //不能解決使用到一半被取消的狀況，但目前還沒開放這樣做，所以暫時不會有問題(BUG:當下的儀器不會被關起來)
  public function update($u_ID){
  	$this->load->model('user_model');
  	$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$u_ID))->row_array();
  	if(!$user_profile) throw new Exception("無此帳號",ERROR_CODE);
  	if(!$user_profile['card_num']) throw new Exception("使用者無卡號",ERROR_CODE);
  	//取得所有的預約記錄
  	$bookings = $this->facility_model->get_facility_booking_list(array("user_ID"=>$user_profile['ID'],"start_time"=>date("Y-m-d H:i:s")))->result_array();
  	//刪掉所有尚未執行的
  	$ctrls = $this->facility_model->get_access_ctrl_list(array("card_num"=>$user_profile['card_num'],"start_time"=>date("Y-m-d H:i:s")))->result_array();
  	foreach($ctrls as $key => $value){
	  	$this->facility_model->del_access_ctrl(array("serial_no"=>$value['serial_no']));
	}
	//重排
	foreach($bookings as $key => $value){
		$this->add($value['facility_ID'],$value['user_ID'],strtotime($value['start_time']),strtotime($value['end_time']));
	}
  }
  
//  public function del_by_range_time($f_ID,$u_ID,$fun,$start_time,$end_time)
//  {
//  	$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
//  	$this->load->model('user_model');
//  	$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$u_ID))->row_array();
//  	$options = array("card_num"=>$user_profile['card_num'],
//  					 "facility_ctrl_no"=>$facility['ctrl_no'],
//  					 "fun"=>$fun,
//  					 "start_time");
//  	$this->facility_model->get_access_ctrl_list()->result_array();
//  }
  
  public function reset_flag($SN)
  {
  	$data = array("serial_no"=>$SN);
  	
  	$this->facility_model->update_access_ctrl($data);
  }
  
  //--------------------------換卡號-----------------------------------
  public function exchange($user_ID,$new_card_num,$start_time = NULL,$end_time = NULL){
  	$this->load->model('user_model');
  	$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
  	if(!$user_profile){
		throw new Exception("無此使用者",ERROR_CODE);
	}
	//把時間區間的預約紀錄抽出來
	$bookings = $this->facility_model->get_facility_booking_list(array(
		"user_ID"=>$user_profile['ID'],
		"start_time"=>isset($start_time)?$start_time:date("Y-m-d H:i:s"),
		"end_time"=>$end_time
	))->result_array();
	//全部重開一次
	foreach($bookings as $booking){
		$f_IDs = $this->facility_model->get_vertical_group_facilities($booking['facility_ID'],array("no_child"=>TRUE,"facility_only"=>$user_profile['group']=="admin"));
		$this->add_by_card_num($f_IDs,$new_card_num,strtotime($booking['start_time']),strtotime($booking['end_time']));
	}
  }
  
	//------------------------------無帳號專區---------------------------
	public function open_all_door_by_num($card_num,$start,$end)
	{
		//取得所有門禁資訊
		$doors = $this->facility_model->get_facility_list(array("type"=>"door"))->result_array();
		//取得所有門禁的編號
		$door_IDs = sql_result_to_column($doors,"ID");
		//新增
		$this->add_by_card_num($door_IDs,$card_num,$start,$end);
	}
}