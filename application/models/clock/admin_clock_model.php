<?php
class Admin_clock_model extends MY_Model {
  
	protected $common_db;
	protected $facility_db;
	protected $clock_db;
	public function __construct()
	{
		parent::__construct();
		$this->common_db = $this->load->database('common',TRUE);
		$this->facility_db = $this->load->database('facility',TRUE);
		$this->clock_db = $this->load->database('clock',TRUE);
		
		$this->load->model('admin_model');
	}
	//---------------------------權限-------------------------------
	public function is_super_admin($u_ID = NULL)
	{
		if(!isset($u_ID)) $u_ID = $this->session->userdata('ID');
		
		$this->clock_db->where("admin_ID",$u_ID);
		$this->clock_db->where("privilege","clock_super_admin");
		return $this->clock_db->get("clock_admin_privilege")->num_rows();
	}
	//
	public function del($clock_ID)
	{
		$clock = $this->admin_model->get_manual_clock_list(array(
			"clock_ID"=>$clock_ID
		))->row_array();
		
		if(!$clock)
		{
			throw new Exception("無此打卡紀錄");
		}
		
		if(!$this->is_super_admin())
		{
			//先檢查是否為本人
			if($clock['clock_user_ID'] != $this->session->userdata('ID'))
			{
				throw new Exception("權限不足");
			}
		}
		
		$this->admin_model->del_clock(array(
			"clock_ID"=>$clock_ID
		));
		
		//寄信給組長
		$org_charts = $this->admin_model->get_org_chart_list(array("admin_ID"=>$clock['clock_user_ID']))->result_array();
		foreach($org_charts as $org_chart){
			$managers = $this->admin_model->get_org_chart_list(array("team_no"=>$org_chart['team_no'],"status_ID"=>"section_chief"))->result_array();
			foreach($managers as $manager){
				$this->email->to($manager['admin_email']);
				$this->email->subject("成大微奈米科技研究中心 -中心人員外出取消通知-");
				$message = "
					{$manager['team_name']} {$manager['status_name']} {$manager['admin_name']} 您好：<br>
					{$org_chart['team_name']} {$org_chart['status_name']} {$org_chart['admin_name']} 已取消原本預計於 {$clock['clock_start_time']} 因 {$clock['clock_reason']} 外出至 {$clock['clock_location']} 之該行程，謝謝。
				";
				$this->email->message($message);
				$this->email->send();
			}
		}
	}
}