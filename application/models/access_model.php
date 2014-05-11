<?php
class Access_model extends MY_Model {
	protected $access_db;

	public function __construct()
	{
		parent::__construct();
		$this->access_db = $this->load->database("access",TRUE);
	}
	
	public function get_access_card_temp_application_list($options = array())
	{
		$sTable = "access_card_temp_application";
		$sJoinTable = array();
		
		$this->access_db->select("*");
		
		if(isset($options['serial_no']))
		{
			$this->access_db->where("$sTable.serial_no",$options['serial_no']);
		}
		
		return $this->access_db->get($sTable);
	}
	public function add_access_card_temp_application($data)
	{
		$this->access_db->set("applied_by",$data['applied_by'])
						->set("application_type",$data['application_type'])
						->set("guest_name")
						->set("guest_mobile")
						->set("guest_purpose")
						->set("guest_access_start_time")
						->set("guest_access_start_time");
		$this->access_db->insert("access_card_temp_application");
		return $this->access_db->insert_id();
	}
	public function update_access_card_temp_application($data)
	{
		if(isset($data['application_remark']))
		{
			$this->access_db->set("application_remark",$data['application_remark']);
		}
		if(isset($data['guest_access_card_num']))
		{
			$this->access_db->set("guest_access_card_num",$data['guest_access_card_num']);
		}
		if(isset($data['issued_by']))
		{
			$this->access_db->set("issued_by",$data['issued_by']);
			$this->access_db->set("issuance_time",date("Y-m-d H:i:s"));
		}
		if(isset($data['refunded_by']))
		{
			$this->access_db->set("refunded_by",$data['refunded_by']);
			$this->access_db->set("refundation_time",date("Y-m-d H:i:s"));
		}
		if(isset($data['application_checkpoint']))
		{
			$this->access_db->set("application_checkpoint",$data['application_checkpoint']);
		}
		$this->access_db->where("serial_no",$data['serial_no']);
		$this->access_db->update("access_card_temp_application");
	}
	public function del_access_card_temp_application($data)
	{
		$this->access_db->where("serial_no",$data['serial_no']);
		$this->access_db->delete("access_card_temp_application");
	}
}
