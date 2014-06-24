<?php
class Maintenance_model extends MY_Model {
  
	public function __construct()
	{
		parent::__construct();
		$this->load->model("facility_model");
	}
	
	public function add($f_ID,$user_ID,$subject,$content,$start_time,$end_time)
	{
	  	//取得使用者基本資料
		$user_profile = $this->user_model->get_user_profile_list(
						array("user_ID"=>$user_ID)
						)->row_array();
		//先確認有權限
		$privilege =	$this->facility_model->get_user_privilege_list(
						array("facility_ID"=>$f_ID,
							  "user_ID"=>$user_profile['ID'],
							  "privilege"=>"admin")
						)->row_array();
		if(!$privilege)
		{
			echo $this->info_modal("權限不足！","","error");
			return;
		}
		//取得儀器基本資料
		$facility = $this->facility_model->get_facility_list(
					array("ID"=>$f_ID)
					)->row_array();
		
		//取得共同實驗室組組長資料
		$this->load->model('admin_model');
		$managers = $this->admin_model->get_org_chart_list(array(
			"team_ID"=>"common_lab",
			"status_ID"=>"section_chief"
		))->result_array();
		
		//預約
		$this->load->model('facility/booking_model');
		$booking_ID = $this->booking_model->add($facility['ID'],$user_profile['ID'],$start_time,$end_time,"maintenance");
		
		//寫入維修單並取得維修調教單號
		$data = array("applicant_ID"=>$this->session->userdata('ID'),
				  "facility_ID"=>$facility['ID'],
				  "subject"=>$subject,
				  "content"=>$content,
				  "booking_ID"=>$booking_ID,
				  "result"=>"1");
		$serial_no = $this->facility_model->add_maintenance($data);
		
		//寄信通知組長
		foreach($managers as $manager){
			$this->email->to($manager['admin_email']);
			$this->email->subject("成大微奈米科技研究中心 -儀器維修調教通知-");
			$this->email->message("{$manager['team_name']} {$manager['status_name']} {$manager['admin_name']} 您好：<br>
									中心儀器：{$facility['cht_name']}<br>
									被該儀器管理員 {$user_profile['name']} 申請 {$subject}<br>
									申請原因： {$content}<br>
									使用時段為：".date("Y-m-d H:i",$start_time)."~".date("Y-m-d H:i",$end_time)."<br>
									系統特此通知，謝謝");
			$this->email->send();
		}
	}
}