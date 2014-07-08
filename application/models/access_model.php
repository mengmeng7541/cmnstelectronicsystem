<?php
class Access_model extends MY_Model {
	protected $access_db;

	public function __construct()
	{
		parent::__construct();
		$this->access_db = $this->load->database("access",TRUE);
		$this->facility_db = $this->load->database("facility",TRUE);
		$this->mssql_old_db = $this->load->database('order_machine',TRUE);
	}
	//--------------------SYSTEM PRIVILEGE-----------------------
	public function get_privilege_list($options = array())
	{
		$sTable = "access_admin_privilege";
		$sJoinTable = array("user"=>"cmnst_common.user_profile");
		$this->access_db->select("
			$sTable.*,
			{$sJoinTable['user']}.email AS admin_name,
			{$sJoinTable['user']}.email AS admin_email
		");
		$this->access_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.admin_ID","LEFT");
		if(isset($options['admin_ID']))
		{
			$this->access_db->where("admin_ID",$options['admin_ID']);	
		}
		if(isset($options['privilege']))
		{
			$this->access_db->where("privilege",$options['privilege']);
		}
		return $this->access_db->get("access_admin_privilege");
	}
	public function add_privilege($data)
	{
		foreach((array)$data['admin_ID'] as $admin_ID){
			$this->access_db->set("admin_ID",$admin_ID);
			$this->access_db->set("privilege","access_super_admin");
			$this->access_db->insert("access_admin_privilege");
		}
	}
	public function del_privilege($data)
	{
		$this->access_db->where("serial_no",$data['serial_no']);
		$this->access_db->delete("access_admin_privilege");
	}
	public function is_super_admin($admin_ID = NULL)
	{
		if(!isset($admin_ID)) $admin_ID = $this->session->userdata('ID');
		return $this->get_privilege_list(array("admin_ID"=>$admin_ID,"privilege"=>"access_super_admin"))->num_rows();
	}
	//----------------------------CARD POOL----------------------
	public function get_access_card_pool_list($options = array())
	{
		if(isset($options['access_card_num']))
		{
			$this->access_db->where("access_card_num",$options['access_card_num']);
		}
		if(isset($options['occupied']))
		{
			$this->access_db->where("occupied",$options['occupied']);
		}
		return $this->access_db->get("access_card_pool");
	}
	public function add_access_card_pool($data)
	{
		foreach((array)$data['access_card_num'] as $access_card_num)
		{
			$exist = $this->get_access_card_pool_list(array("access_card_num"=>$access_card_num))->num_rows();
			if($exist){
				continue;
			}//存在就略過
			$this->access_db->set("access_card_num",$access_card_num);
			$this->access_db->set("occupied",0);
			$this->access_db->insert("access_card_pool");
		}
	}
	public function update_access_card_pool($data)
	{
		$this->access_db->set("occupied",$data['occupied']);
		$this->access_db->where("access_card_num",$data['access_card_num']);
		$this->access_db->update("access_card_pool");
	}
	public function del_access_card_pool($data)
	{
		foreach((array)$data['access_card_num'] as $access_card_num)
		{
			$this->access_db->where("access_card_num",$access_card_num);
			$this->access_db->delete("access_card_pool");
		}
	}
	//-----------------------------ENUM--------------------------
	public function get_enum_access_card_temp_application_checkpoint_list($options = array())
	{
		if(isset($options['checkpoint_ID']))
		{
			$this->access_db->where("checkpoint_ID",$options['checkpoint_ID']);
		}
		return $this->access_db->get("enum_access_card_temp_application_checkpoint");
	}
	//----------------------ACCESS CARD APPLICATION-------------------------------
	public function update_card_application($data)
	{
		if(isset($data['AB_form_verified_by']))
		{
			$this->facility_db->set("AB_form_verified_by",$data['AB_form_verified_by']);
			$this->facility_db->set("AB_form_verification_time",isset($data['AB_form_verification_time'])?$data['AB_form_verification_time']:date("Y-m-d H:i:s"));
		}
		$this->facility_db->where("serial_no",$data['serial_no']);
		$this->facility_db->update("facility_card_application");
	}
	//----------------------ACCESS CARD TEMP APPLICATION--------------------------
	public function get_access_card_temp_application_list($options = array())
	{
		$sTable = "access_card_temp_application";
		$sJoinTable = array(
			"checkpoint"=>"enum_access_card_temp_application_checkpoint",
			"enum_type"=>"enum_access_card_temp_application_type",
			"enum_purpose"=>"enum_access_card_temp_application_purpose",
			"user"=>"cmnst_common.user_profile"
		);
		
		$this->access_db->select("
			$sTable.*,
			{$sJoinTable['user']}.name AS applicant_name,
			{$sJoinTable['enum_type']}.type_ID AS application_type_ID,
			{$sJoinTable['enum_type']}.type_name AS application_type_name,
			{$sJoinTable['enum_purpose']}.purpose_ID AS guest_purpose_ID,
			{$sJoinTable['enum_purpose']}.purpose_name AS guest_purpose_name,
			{$sJoinTable['checkpoint']}.checkpoint_ID AS application_checkpoint_ID,
			{$sJoinTable['checkpoint']}.checkpoint_name AS application_checkpoint_name,
		");
		$this->access_db->join($sJoinTable['checkpoint'],"{$sJoinTable['checkpoint']}.checkpoint_no = $sTable.application_checkpoint","LEFT");
		$this->access_db->join($sJoinTable['enum_type'],"{$sJoinTable['enum_type']}.type_no = $sTable.application_type","LEFT");
		$this->access_db->join($sJoinTable['enum_purpose'],"{$sJoinTable['enum_purpose']}.purpose_no = $sTable.guest_purpose","LEFT");
		$this->access_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.applied_by","LEFT");
		if(isset($options['serial_no']))
		{
			$this->access_db->where("$sTable.serial_no",$options['serial_no']);
		}
		if(isset($options['application_type_ID']))
		{
			$this->access_db->where("{$sJoinTable['enum_type']}.type_ID",$options['application_type_ID']);
		}
		if(isset($options['guest_access_start_time']))
		{
			$this->access_db->where("$sTable.guest_access_end_time >=",$options['guest_access_start_time']);
		}
		if(isset($options['guest_access_end_time']))
		{
			$this->access_db->where("$sTable.guest_access_start_time <=",$options['guest_access_end_time']);
		}
		$this->access_db->order_by("$sTable.issuance_time","DESC");
		return $this->access_db->get($sTable);
	}
	public function add_access_card_temp_application($data)
	{
		$this->access_db->set("applied_by",$data['applied_by'])
						->set("application_type",$data['application_type'])
						->set("guest_purpose",$data['guest_purpose'])
						->set("guest_access_start_time",$data['guest_access_start_time'])
						->set("guest_access_end_time",$data['guest_access_end_time']);
		if(isset($data['used_by'])){
			$this->access_db->set("used_by",$data['used_by']);
		}
		if(isset($data['guest_name']))
		{
			$this->access_db->set("guest_name",$data['guest_name']);
		}
		if(isset($data['guest_mobile']))
		{
			$this->access_db->set("guest_mobile",$data['guest_mobile']);
		}	
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
	//---------------------------ACCESS LOG----------------------------
	public function sync_access_card_log_list()
	{
		$this->facility_db->select("SerNo");
		$this->facility_db->order_by("SerNo","DESC");
		$this->facility_db->limit(1);
		$result = $this->facility_db->get("Card")->row_array();
		
		$this->mssql_old_db->where("SerNo >",$result['SerNo']);
		$results = $this->mssql_old_db->get("Card")->result_array();
		foreach($results as $row)
		{
			//好醜的寫法..............卡機廠商做不到只好自己來
			if($row['CtrlNo']=="11")//無塵室刷出門禁
			{
				$row['CtrlNo'] = "54";//無塵室門禁
				$row['Status'] = "01";//用01代表刷出
			}else if($row['CtrlNo']=="12")//B1實驗室刷出門禁
			{
				$row['CtrlNo'] = "71";//B1實驗室門禁
				$row['Status'] = "01";
			}
			$row['FDateTime'] = $row['FDate'].' '.$row['FTime'];
			$this->facility_db->insert("Card",$row);
		}
	}
	public function get_access_card_log_list($options = array())
	{
		$sTable = "cmnst_facility.Card";
		$sJoinTable = array(
			"user"=>"cmnst_common.user_profile",
			"facility"=>"cmnst_facility.facility_list"
		);
		
		$this->access_db->select("
			$sTable.SerNo AS log_serial_no,
			$sTable.CtrlNo AS log_ctrl_no,
			$sTable.Status AS log_state,
			$sTable.CardNo AS log_card_num,
			$sTable.FDateTime AS log_time,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['facility']}.cht_name AS facility_cht_name,
			{$sJoinTable['facility']}.eng_name AS facility_eng_name
		");
		
		$this->access_db->join($sJoinTable['user'],"$sTable.CardNo = {$sJoinTable['user']}.card_num","LEFT");
		$this->access_db->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = (SELECT ID from {$sJoinTable['facility']} WHERE ctrl_no = $sTable.CtrlNo LIMIT 1)","LEFT",FALSE);
		
		if(isset($options['start_time']))
		{
			$this->access_db->where("$sTable.FDateTime >=",$options['start_time']);
		}
		if(isset($options['end_time']))
		{
			$this->access_db->where("$sTable.FDateTime <=",$options['end_time']);
		}
		if(isset($options['card_num']))
		{
			$this->access_db->where("$sTable.CardNo",$options['card_num']);
		}
		if(isset($options['ctrl_no']))
		{
			if(is_array($options['ctrl_no'])&&empty($options['ctrl_no']))
			{
				$options['ctrl_no'] = array("");
			}
			$this->access_db->where_in("$sTable.CtrlNo",$options['ctrl_no']);
		}
		if(isset($options['state']))
		{
			$this->access_db->where("$sTable.Status",$options['state']);
		}
		
		$this->access_db->order_by("FDateTime","DESC");
		
		$result = $this->access_db->get($sTable);
		
		return $result;
	}
}
