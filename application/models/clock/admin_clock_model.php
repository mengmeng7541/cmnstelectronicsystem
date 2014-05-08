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
		if(!$this->is_super_admin())
		{
			//先檢查
			$clock = $this->admin_model->get_manual_clock_list(array(
				"clock_user_ID"=>$this->session->userdata('ID')
			))->result_array();
			if(!$clock)
			{
				throw new Exception("權限不足");
			}
		}
		
		$this->admin_model->del_clock(array(
			"clock_ID"=>$clock_ID
		));
	}
}