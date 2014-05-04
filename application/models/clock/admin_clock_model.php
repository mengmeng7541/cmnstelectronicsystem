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
	}
	//---------------------------權限-------------------------------
	public function is_super_admin($u_ID = NULL)
	{
		if(!isset($u_ID)) $u_ID = $this->session->userdata('ID');
		
		$this->clock_db->where("admin_ID",$u_ID);
		$this->clock_db->where("privilege","clock_super_admin");
		return $this->clock_db->get("clock_admin_privilege")->num_rows();
	}
}