<?php
class Booking_model extends MY_Model {
  
	public function __construct()
	{
		parent::__construct();
		$this->load->model("facility_model");
		$this->load->model("user_model");
	}
	
	//--------------------------UI--------------------------
	public function add_by_checkbox($f_IDs,$user_ID,$checkboxes,$purpose = "DIY")
	{
		//取得相關儀器
		$facilities_IDs = $this->facility_model->get_vertical_group_facilities($f_IDs,array("facility_only"=>TRUE));
		$facilities = $this->facility_model->get_facility_list(array("ID"=>$facilities_IDs))->result_array();
		$max_unit_sec = max(sql_column_to_key_value_array($facilities,"unit_sec"));
		
		$max_time = max($checkboxes)+$max_unit_sec;
		$min_time = min($checkboxes);
		
		//確認輸入了正確的時間
		$this->check_input_time($checkboxes,$max_unit_sec);
		
		//預約
		return $this->add($f_IDs,$user_ID,$min_time,$max_time,$purpose);
	}
	//------------------------------------------------------
	
	public function add($f_ID,$user_ID,$start_time,$end_time,$purpose = "DIY")
	{
	  	//取得儀器基本資料
		$facilities = $this->facility_model->get_facility_list(
					array("ID"=>$f_ID)
					)->result_array();
		if(!$facilities) throw new Exception("無此儀器！",ERROR_CODE);
		//取得使用者基本資料
		$user_profile = $this->user_model->get_user_profile_list(
						array("user_ID"=>$user_ID)
						)->row_array();
		if(!$user_profile) throw new Exception("無此使用者！",ERROR_CODE);		
		//確認使用者有卡片
		if(empty($user_profile['card_num']))
			throw new Exception("您尚未擁有磁卡，請先向本中心申請，謝謝。",ERROR_CODE);
		
		//取得所有關聯的儀器
		$f_IDs = sql_result_to_column($facilities,"ID");
		$facilities_ID = $this->facility_model->get_vertical_group_facilities($f_IDs,array("facility_only"=>TRUE));
		$facilities = $this->facility_model->get_facility_list(array(
			"ID"=>$facilities_ID
		))->result_array();
		//確認起始時間大於現在時間(以單位時間為準)
		$max_unit_sec = max(sql_result_to_column($facilities,"unit_sec"));
		if( time() > $start_time+$max_unit_sec)
			throw new Exception("預約時間不可早於現在時間。",WARNING_CODE);
		//確認預約時段無人預約
		foreach($facilities as $facility)
		{
			if(!$facility['enable_occupation'])
			{
				$facilities_ID = array_diff($facilities_ID,array($facility['ID']));
			}
		}
		$booking = $this->facility_model->get_facility_booking_list(
		array("facility_ID"=>$facilities_ID,
			  "start_time"=>date("Y-m-d H:i:s",$start_time),
			  "end_time"=>date("Y-m-d H:i:s",$end_time),
		))->result_array();
		if($booking)
			throw new Exception("該時段已被預約，請選擇其他時段。",WARNING_CODE);
		
		//寫入預約紀錄
		foreach($f_IDs as $f_ID){
			$booking_ID[] = $this->facility_model->add_facility_booking(
				array("user_ID"=>$user_profile['ID'],
					  "facility_ID"=>$f_ID,
					  "purpose"=>$purpose,
					  "start_time"=>date("Y-m-d H:i:s",$start_time),
					  "end_time"=>date("Y-m-d H:i:s",$end_time))
			);
		}
		
		
		//寫入卡機控制
		$this->load->model('facility/access_ctrl_model');
		foreach($f_IDs as $f_ID){
			$this->access_ctrl_model->add($f_ID,$user_ID,$start_time,$end_time);
		}
		
		
		return count($booking_ID)==1?$booking_ID[0]:$booking_ID;
	}
	public function update_time($booking_ID,$start_time,$end_time)
	{
		$booking = $this->facility_model->get_facility_booking_list(
						array("serial_no"=>$booking_ID)
					)->row_array();
		//確認有此預約單
		if(!$booking)
		{
			throw new Exception("無此預約紀錄！",ERROR_CODE);
		}

		//確認還未被取消
		if(!empty($booking['cancel_time']))
		{
			throw new Exception("此筆預約紀錄已被取消，無法變更時段。","","error");
		}
		//確認不與原時段相同
		if($start_time==strtotime($booking['start_time']) && $end_time==strtotime($booking['end_time']))
		{
			throw new Exception("變更不可與原時段相同。",WARNING_CODE);
		}
		//確認起始時間大於現在時間(以單位時間為準)		
		if(time() > strtotime($booking['start_time']) && $start_time != strtotime($booking['start_time'])){//若已過原預約時段，只能延長
			throw new Exception("已過原預約起始時間，故起始時間不可變更。",WARNING_CODE);
		}
		if(time() > $end_time){
			throw new Exception("結束時間不可早於現在時間。",WARNING_CODE);
		}
			
		//取得所有關聯的儀器
		$facilities_ID = $this->facility_model->get_vertical_group_facilities($booking['facility_ID'],array("facility_only"=>TRUE));
		//確認預約時段無人預約
		$bookings = $this->facility_model->get_facility_booking_list(
		array("facility_ID"=>$facilities_ID,
			  "start_time"=>date("Y-m-d H:i:s",$start_time),
			  "end_time"=>date("Y-m-d H:i:s",$end_time),
		))->result_array();
		if($bookings){
			//確認該時段不是本次要修改的預約時段
			foreach($bookings as $b)
			{
				if($b['serial_no'] != $booking['serial_no'])
				{
					throw new Exception("該時段已被預約，請選擇其他時段。",WARNING_CODE);
				}
			}
		}


		//變更預約紀錄
		$this->facility_model->update_facility_booking(
			array("serial_no"=>$booking['serial_no'],
				  "update_time"=>date("Y-m-d H:i:s"),
				  "start_time"=>date("Y-m-d H:i:s",$start_time),
				  "end_time"=>date("Y-m-d H:i:s",$end_time))
		);	

		//先刪
		$this->load->model('facility/access_ctrl_model');
		$this->access_ctrl_model->del($booking['facility_ID'],$booking['user_ID'],strtotime($booking['start_time']),strtotime($booking['end_time']));
		//後增
		$this->access_ctrl_model->add($booking['facility_ID'],$booking['user_ID'],$start_time,$end_time);

	}
	public function update_user($booking_ID,$user_ID)
	{
		$booking = $this->facility_model->get_facility_booking_list(
						array("serial_no"=>$booking_ID)
					)->row_array();
		//確認有此預約單
		if(!$booking)
		{
			throw new Exception("無此預約紀錄！",ERROR_CODE);
		}

		//確認還未被取消
		if(!empty($booking['cancel_time']))
		{
			throw new Exception("此筆預約紀錄已被取消，無法變更。","","error");
		}
		//確認不與原使用者相同
		if($booking['user_ID'] == $user_ID)
		{
			throw new Exception("變更不可與原使用者相同。",WARNING_CODE);
		}
			
		//變更預約紀錄
		$this->facility_model->update_facility_booking(
			array("serial_no"=>$booking['serial_no'],
				  "update_time"=>date("Y-m-d H:i:s"),
				  "user_ID"=>$user_ID)
		);	

		//先刪
		$this->load->model('facility/access_ctrl_model');
		$this->access_ctrl_model->del($booking['facility_ID'],$booking['user_ID'],strtotime($booking['start_time']),strtotime($booking['end_time']));
		//後增
		$this->access_ctrl_model->add($booking['facility_ID'],$user_ID,strtotime($booking['start_time']),strtotime($booking['end_time']));
	}
  public function del($SNs)
  {
  	foreach((array)$SNs as $SN){
		$booking = $this->facility_model->get_facility_booking_list(array("serial_no"=>$SN))->row_array();
		//確認預約記錄存在
		if(!$booking)
		{
			throw new Exception("無此預約紀錄！",ERROR_CODE);
		}
		//取得使用者資料
		$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$booking['user_ID']))->row_array();
		if(!$user_profile)
		{
			throw new Exception("無此使用者！",ERROR_CODE);
		}
		
		//確認還未被取消
		if(!empty($booking['cancel_time']))
		{
			throw new Exception("此筆預約紀錄已被取消，請勿重複動作。",ERROR_CODE);
		}
		
		//確認結束時間還未到達
		if(time() > strtotime($booking['end_time'])){
			throw new Exception("預約時段已過，不可刪除",ERROR_CODE);
		}
		
		//紀錄刪除時間
		$this->facility_model->update_facility_booking(
		array("serial_no"=>$booking['serial_no'],
			  "cancel_time"=>date("Y-m-d H:i:s"))
		);
		
		//更新總使用時數與到期日
		$this->load->model('facility/user_privilege_model');
		$this->user_privilege_model->update($booking['user_ID'],$booking['facility_ID']);
		
		//刪除卡機的時段開關預約紀錄
		$this->load->model('facility/access_ctrl_model');
		$this->access_ctrl_model->del($booking['facility_ID'],$user_profile['ID'],strtotime($booking['start_time']),strtotime($booking['end_time']));
		
		//寄信給使用者
		if(empty($user_profile['email']))
		{
			return;
		}
		$this->email->to($user_profile['email']);
		if(!empty($user_profile['boss_email']))
		{
			$this->email->cc($user_profile['boss_email']);
		}
		$this->email->subject("成大微奈米科技研究中心 -儀器預約取消通知-");
		$this->email->message("親愛的使用者 {$booking['user_name']} 您好：<br>
							   您預約的儀器：{$booking['facility_cht_name']} ({$booking['facility_eng_name']})<br>
							   預約時段：{$booking['start_time']} ~ {$booking['end_time']}<br>
							   已成功取消，歡迎您再度使用本中心儀器，謝謝您。<br>");
		$this->email->send();
	}
  	
  }
  //-------------------common function-------------------
  public function check_input_time($booking_time = array(),$unit_sec)
  {
  	$min_time = min($booking_time);
  	$max_time = max($booking_time)+$unit_sec;
  	//確認選擇了連續時段
	for($i=$min_time;$i<$max_time;$i+=$unit_sec)
	{
		if(!in_array($i,$booking_time))
		{
			throw new Exception("請選擇連續時段。",WARNING_CODE);
		}
	}
	//確認選擇了整點時段
	$legal_mins = array("00",date("i",$unit_sec));
	$legal_secs = array("00");
	if( !in_array(date("s",$max_time),$legal_secs) ||
		!in_array(date("s",$min_time),$legal_secs) ||
		!in_array(date("i",$max_time),$legal_mins) ||
		!in_array(date("i",$min_time),$legal_mins) )
	{
		throw new Exception("非整點時段！",ERROR_CODE);
	}
  }
	public function get_booking_time($input_array_time,$f_ID){
		$facilities = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->result_array();
		if(!$facilities){
			throw new Exception("無此儀器！",ERROR_CODE);
		}
		
		$unit_sec = max(sql_result_to_column($facilities,"unit_sec"));
		$min_time = min($input_array_time);
  		$max_time = max($input_array_time)+$unit_sec;
		//確認選擇了連續時段
		for($i=$min_time;$i<$max_time;$i+=$unit_sec)
		{
			if(!in_array($i,$input_array_time))
			{
				throw new Exception("請選擇連續時段。",WARNING_CODE);
			}
		}
		//確認選擇了整點時段
		if($min_time%$unit_sec!=0 || $max_time%$unit_sec!=0){
			throw new Exception("非整點時段！",ERROR_CODE);
		}
		
		return array($min_time,$max_time);
	}
}