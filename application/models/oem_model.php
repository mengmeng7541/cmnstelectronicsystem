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
		if(isset($options['form_parent_SN']))
		{
			$this->oem_db->where("form_parent_SN",$options['form_parent_SN']);
		}
		
		
		return $this->oem_db->get($sTable);
	}
	public function add_form($data)
	{
		if(isset($data['form_parent_SN']))
		{
			$this->oem_db->set("form_parent_SN",$data['form_parent_SN']);
		}
		$this->oem_db->set("form_cht_name",$data['form_cht_name']);
		$this->oem_db->set("form_eng_name",$data['form_eng_name']);
		$this->oem_db->set("form_note",$data['form_note']);
		$this->oem_db->set("form_description",$data['form_description']);
		$this->oem_db->set("form_remark",$data['form_remark']);
		$this->oem_db->set("form_enable",$data['form_enable']);
		$this->oem_db->set("form_add_time",date("Y-m-d H:i:s"));
		$this->oem_db->set("form_update_time",date("Y-m-d H:i:s"));
		$this->oem_db->set("form_admin_ID",$data['form_admin_ID']);
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
		$this->oem_db->set("form_admin_ID",$data['form_admin_ID']);
		$this->oem_db->where("form_SN",$data['form_SN']);
		$this->oem_db->update("oem_form");
	}
	public function del_form($data)
	{
		$this->oem_db->where("form_SN",$data['form_SN']);
		$this->oem_db->delete("oem_form");
	}
	//-------------------FORM COLUMN-----------------------------------
	public function get_form_col_list($options = array())
	{
		if(isset($options['form_SN']))
		{
			$this->oem_db->where("form_SN",$options['form_SN']);
		}
		if(isset($options['form_col_SN']))
		{
			$this->oem_db->where("form_col_SN",$options['form_col_SN']);
		}
		if(isset($options['col_enable']))
		{
			$this->oem_db->where("col_enable",$options['col_enable']);
		}
		return $this->oem_db->get("oem_form_col");
	}
	public function add_form_col($data)
	{
		$this->oem_db->set("form_SN",$data['form_SN']);
		$this->oem_db->set("col_cht_name",$data['col_cht_name']);
		$this->oem_db->set("col_eng_name",$data['col_eng_name']);
		$this->oem_db->set("col_type",$data['col_type']);
		$this->oem_db->set("col_length",$data['col_length']);
		$this->oem_db->set("col_rule",$data['col_rule']);
		$this->oem_db->set("col_enable",$data['col_enable']);
		$this->oem_db->insert("oem_form_col");
		return $this->oem_db->insert_id();
	}
	public function update_form_col($data)
	{
		$this->oem_db->set("form_SN",$data['form_SN']);
		$this->oem_db->set("col_cht_name",$data['col_cht_name']);
		$this->oem_db->set("col_eng_name",$data['col_eng_name']);
		$this->oem_db->set("col_type",$data['col_type']);
		$this->oem_db->set("col_length",$data['col_length']);
		$this->oem_db->set("col_rule",$data['col_rule']);
		$this->oem_db->set("col_enable",$data['col_enable']);
		$this->oem_db->where("form_col_SN",$data['form_col_SN']);
		$this->oem_db->update("oem_form_col");
		return ;
	}
	public function del_form_col()
	{
		
	}
	//---------------------APP COLUMN-----------------------------
	public function get_app_col_list($options = array())
	{
		$sTable = "oem_application_col";
		$sJoinTable = array("form_col"=>"oem_form_col");
		$this->oem_db->join($sJoinTable['form_col'],"{$sJoinTable['form_col']}.form_col_SN = $sTable.form_col_SN","LEFT");
		if(isset($options['app_SN']))
		{
			$this->oem_db->where("app_SN",$options['app_SN']);
		}
		if(isset($options['app_col_SN']))
		{
			$this->oem_db->where("app_col_SN",$options['app_col_SN']);
		}
		return $this->oem_db->get($sTable);
	}
	public function add_app_col($data)
	{
		$this->oem_db->set("app_SN",$data['app_SN']);
		$this->oem_db->set("form_col_SN",$data['form_col_SN']);
		$this->oem_db->set("col_value",$data['col_value']);
		$this->oem_db->insert("oem_application_col");
		return $this->oem_db->insert_id();
	}
	public function update_app_col($data)
	{
		$this->oem_db->set("app_SN",$data['app_SN']);
		$this->oem_db->set("form_col_SN",$data['form_col_SN']);
		$this->oem_db->set("col_value",$data['col_value']);
		$this->oem_db->where("app_col_SN",$data['app_col_SN']);
		$this->oem_db->update("oem_application_col");
	}
	public function del_app_col()
	{
		
	}
	//--------------------FORM FACILITY MAP-----------------------
	public function get_form_facility_map_list($options = array())
	{
		$sTable = "oem_form_facility_map";
		$sJoinTable = array(
			"facility"=>"cmnst_facility.facility_list"
		);
		
		$this->oem_db->select("
			$sTable.*,
			{$sJoinTable['facility']}.cht_name AS facility_cht_name,
			{$sJoinTable['facility']}.eng_name AS facility_eng_name
		");
		$this->oem_db->from($sTable);
		$this->oem_db->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = $sTable.facility_SN");
		if(isset($options['form_SN']))
		{
			if(empty($options['form_SN'])&&is_array($options['form_SN']))
			{
				$options['form_SN'] = array('');
			}
			$this->oem_db->where_in("$sTable.form_SN",(array)$options['form_SN']);
		}
		if(isset($options['facility_SN']))
		{
			$this->oem_db->where("$sTable.facility_SN",$options['facility_SN']);
		}
		return $this->oem_db->get();
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
	//----------------------APPLICATION--------------------------
	public function get_app_list($options = array())
	{
		$sTable = "oem_application";
		$sJoinTable = array("user"=>"cmnst_common.user_profile","org"=>"cmnst_common.organization","boss"=>"cmnst_common.boss_profile","form"=>"oem_form");
		
		$this->oem_db->select("
			$sTable.*,
			{$sJoinTable['form']}.*,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['user']}.email AS user_email,
			{$sJoinTable['user']}.mobile AS user_mobile,
			{$sJoinTable['user']}.department AS user_department,
			{$sJoinTable['org']}.name AS user_org_name,
			{$sJoinTable['boss']}.name AS user_boss_name
		");
		
		$this->oem_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.app_user_ID","LEFT")
					 ->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = {$sJoinTable['user']}.organization","LEFT")
					 ->join($sJoinTable['boss'],"{$sJoinTable['boss']}.serial_no = {$sJoinTable['user']}.boss_no","LEFT")
					 ->join($sJoinTable['form'],"{$sJoinTable['form']}.form_SN = $sTable.form_SN","LEFT");
		if(isset($options['app_SN']))
		{
			$this->oem_db->where("$sTable.app_SN",$options['app_SN']);
		}
		if(isset($options['app_token']))
		{
			$this->oem_db->where("$sTable.app_token",$options['app_token']);
		}
		if(isset($options['app_user_ID']))
		{
			$this->oem_db->where("$sTable.app_user_ID",$options['app_user_ID']);
		}
		
		return $this->oem_db->get($sTable);
	}
	public function add_app($data)
	{
		$this->oem_db->set("form_SN",$data['form_SN']);
		$this->oem_db->set("app_ID",isset($data['app_ID'])?$data['app_ID']:"");
		$this->oem_db->set("app_type",$data['app_type']);
		$this->oem_db->set("app_user_ID",isset($data['app_user_ID'])?$data['app_user_ID']:$this->session->userdata('ID'));
		$this->oem_db->set("app_description",$data['app_description']);
		$this->oem_db->set("app_time",date("Y-m-d H:i:s"));
		$this->oem_db->set("app_checkpoint",$data['app_checkpoint']);
		$this->oem_db->set("app_token",sha1(rand()));
		$this->oem_db->insert("oem_application");
		return $this->oem_db->insert_id();
	}
	public function update_app($data)
	{
		if(isset($data['form_SN']))
		{
			$this->oem_db->set("form_SN",$data['form_SN']);
		}
		if(isset($data['app_description']))
		{
			$this->oem_db->set("app_description",$data['app_description']);
		}
		if(isset($data['app_estimated_hour']))
		{
			$this->oem_db->set("app_estimated_hour",$data['app_estimated_hour']);
		}
		if(isset($data['app_checkpoint']))
		{
			$this->oem_db->set("app_checkpoint",$data['app_checkpoint']);
		}
		$this->oem_db->where("app_SN",$data['app_SN']);
		$this->oem_db->update("oem_application");
	}
	public function del_app()
	{
		
	}
	//---------------------APP CHECKPOINT----------------------------
	public function get_app_checkpoint_list($options = array())
	{
		$sTable = "oem_application_checkpoint";
		$sJoinTable = array("app"=>"oem_application","admin"=>"cmnst_common.admin_profile");
		
		$this->oem_db->select("
			$sTable.*,
			{$sJoinTable['admin']}.stamp
		");
		$this->oem_db->join($sJoinTable['admin'],"{$sJoinTable['admin']}.ID = $sTable.checkpoint_admin_ID","LEFT");
//		$this->oem_db->join($sJoinTable['app'],"{$sJoinTable['app']}.app_SN = $sTable.app_SN","LEFT");
		
		if(isset($options['app_SN']))
		{
			$this->oem_db->where("$sTable.app_SN",$options['app_SN']);
		}
		if(isset($options['checkpoint_ID']))
		{
			$this->oem_db->where("$sTable.checkpoint_ID",$options['checkpoint_ID']);
		}
		if(isset($options['checkpoint_result']))
		{
			$this->oem_db->where("$sTable.checkpoint_result",$options['checkpoint_result']);
		}
		
		return $this->oem_db->get($sTable);
	}
	public function add_app_checkpoint($data)
	{
		$this->oem_db->set("app_SN",$data['app_SN']);
		$this->oem_db->set("checkpoint_ID",$data['checkpoint_ID']);
		$this->oem_db->set("checkpoint_admin_ID",$data['checkpoint_admin_ID']);
		$this->oem_db->set("checkpoint_comment",$data['checkpoint_comment']);
		$this->oem_db->set("checkpoint_result",$data['checkpoint_result']);
		$this->oem_db->insert("oem_application_checkpoint");
		return $this->oem_db->insert_id();
	}
	//-----------------------APP BOOKING-----------------------------
	public function get_app_booking_map_list($options = array())
	{
		$table = "cmnst_oem.oem_application_booking_map";
		$joinTable = array(
			"booking"=>"cmnst_facility.facility_booking",
			"user"=>"cmnst_common.user_profile",
			"state"=>"cmnst_oem.enum_oem_booking_state"
		);
		
		$this->oem_db->select("
			$table.*,
			{$joinTable['booking']}.user_ID,
			{$joinTable['booking']}.facility_ID AS facility_SN,
			{$joinTable['booking']}.purpose,
			{$joinTable['booking']}.start_time,
			{$joinTable['booking']}.end_time,
			{$joinTable['user']}.name AS user_name,
			{$joinTable['state']}.state_name AS booking_state
		",FALSE);
		$this->oem_db->join($joinTable['booking'],"$table.booking_SN = {$joinTable['booking']}.serial_no","LEFT")
					 ->join($joinTable['user'],"{$joinTable['booking']}.user_ID = {$joinTable['user']}.ID","LEFT")
					 ->join($joinTable['state'],"$table.booking_state = {$joinTable['state']}.state_ID","LEFT");
		
		if(isset($options['map_SN']))
		{
			$this->oem_db->where("$table.map_SN",$options['map_SN']);
		}
		if(isset($options['app_SN']))
		{
			$this->oem_db->where("$table.app_SN",$options['app_SN']);
		}
		
		return $this->oem_db->get($table);
	}
	public function add_app_booking_map($data)
	{
		foreach((array)$data['booking_SN'] as $booking_SN)
		{
			$this->oem_db->set("app_SN",$data['app_SN']);
			$this->oem_db->set("booking_SN",$booking_SN);
			$this->oem_db->set("booking_state",$data['booking_state']);
			$this->oem_db->insert("oem_application_booking_map");
		}
		return $this->oem_db->insert_id();
	}
}
