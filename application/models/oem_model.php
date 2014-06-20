<?php
class Oem_model extends MY_Model {
	protected $oem_db;

	public function __construct()
	{
		parent::__construct();
		$this->oem_db = $this->load->database("oem",TRUE);
	}
	
	//--------------------------PRIVILEGE-------------------------
	public function is_super_admin($admin_ID = "")
	{
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		return $this->get_admin_privilege_list(array("admin_ID"=>$admin_ID,"privilege"=>"oem_super_admin"))->num_rows();
	}
	public function get_admin_privilege_list($options = array())
	{
		if(isset($options['admin_ID']))
			$this->oem_db->where("admin_ID",$options['admin_ID']);
		if(isset($options['privilege']))
			$this->oem_db->where("privilege",$options['privilege']);
		return $this->oem_db->get("oem_admin_privilege");
	}
	public function add_admin_privilege($data)
	{
		$this->oem_db->set("admin_ID",$data['admin_ID']);
		$this->oem_db->set("privilege",$data['privilege']);
		$this->oem_db->insert("oem_admin_privilege");
	}
	public function del_admin_privilege($data)
	{
		$this->oem_db->where("serial_no",$data['serial_no']);
		$this->oem_db->delete("oem_admin_privilege");
	}
	//---------------------FORM-------------------------------
	public function get_form_list($options = array())
	{
		$sTable = "oem_form";
		$sJoinTable = array();
		
		$this->oem_db->select("
			$sTable.*
		");
		
		if(isset($options['form_SN']))
		{
			$this->oem_db->where("form_SN",$options['form_SN']);
		}
		
		return $this->oem_db->get($sTable);
	}
	public function add_form($data)
	{
		$this->oem_db->set("form_cht_name",$data['form_cht_name']);
		$this->oem_db->set("form_eng_name",$data['form_eng_name']);
		$this->oem_db->set("form_note",$data['form_note']);
		$this->oem_db->set("form_description",$data['form_description']);
		$this->oem_db->set("form_remark",$data['form_remark']);
		$this->oem_db->set("form_enable",$data['form_enable']);
		$this->oem_db->set("form_add_time",date("Y-m-d H:i:s"));
		$this->oem_db->set("form_update_time",date("Y-m-d H:i:s"));
		$this->oem_db->insert("oem_form");
		return $this->oem_db->insert_id();
	}
	public function update_form($data)
	{
		$this->oem_db->set("form_cht_name",$data['form_cht_name']);
		$this->oem_db->set("form_eng_name",$data['form_eng_name']);
		$this->oem_db->set("form_note",$data['form_note']);
		$this->oem_db->set("form_description",$data['form_description']);
		$this->oem_db->set("form_remark",$data['form_remark']);
		$this->oem_db->set("form_enable",$data['form_enable']);
		$this->oem_db->set("form_update_time",date("Y-m-d H:i:s"));
		$this->oem_db->where("form_SN",$data['form_SN']);
		$this->oem_db->update("oem_form");
	}
	public function del_form($data)
	{
		$this->oem_db->where("form_SN",$data['form_SN']);
		$this->oem_db->delete("oem_form");
	}
	//--------------------FORM FACILITY MAP-----------------------
	public function get_form_facility_map_list($options = array())
	{
		$sTable = "oem_form_facility_map";
		$sJoinTable = array();
		
		$this->oem_db->select("
			$sTable.*
		");
		if(isset($options['form_SN']))
		{
			$this->oem_db->where("form_SN",$options['form_SN']);
		}
		if(isset($options['facility_SN']))
		{
			$this->oem_db->where("facility_SN",$options['facility_SN']);
		}
		return $this->oem_db->get($sTable);
	}
	public function add_form_facility_map($data)
	{
		$this->oem_db->set("form_SN",$data['form_SN']);
		$this->oem_db->set("facility_SN",$data['facility_SN']);
		$this->oem_db->insert("oem_form_facility_map");
		return $this->oem_db->insert_id();
	}
	public function del_form_facility_map($data)
	{
		if(isset($data['form_SN']))
		{
			$this->oem_db->where("form_SN",$data['form_SN']);
		}
		if(isset($data['map_SN']))
		{
			$this->oem_db->where("map_SN",$data['map_SN']);
		}
		$this->oem_db->delete("oem_form_facility_map");
	}
}
