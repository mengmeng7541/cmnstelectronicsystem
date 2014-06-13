<?php
class Facility_model extends MY_Model {
	protected $facility_db;
	protected $mssql_old_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->facility_db = $this->load->database('facility',TRUE);
		$this->mssql_old_db = $this->load->database('order_machine',TRUE);
	}
	//-----------------------------儀器-------------------------------------
	/**
	* 
	* @param string 
	* 
	* @return
	*/
	public function get_facility_list($options = array())
	{
		if(!empty($options['type']))
			$this->facility_db->where("type",$options['type']);
		if(!empty($options['ID']))
		{
			$this->facility_db->where_in("ID",$options['ID']);
		}	
		if(!empty($options['parent_ID']))
			$this->facility_db->where("parent_ID",$options['parent_ID']);
		if(isset($options['ctrl_no']))
			$this->facility_db->where("ctrl_no",$options['ctrl_no']);
		if(isset($options['horizontal_group_ID']))
		{
			$this->facility_db->where("horizontal_group_ID",$options['horizontal_group_ID']);
		}
		return $this->facility_db->get("facility_list");
	}
	public function get_facility_list_array($input_data)
	{
		$sTable = "cmnst_facility.facility_list";
		$sJoinTable = array("cmnst_facility.facility_user_privilege","cmnst_common.user_profile");
		$aColumns = array("$sTable.ID"=>"facility_ID",
						  "$sTable.new_ID"=>"facility_new_ID",
						  "$sTable.ctrl_no"=>"facility_ctrl_no",
						  "$sTable.cht_name"=>"facility_cht_name",
						  "$sTable.eng_name"=>"facility_eng_name",
						  "$sTable.Facility_Tech"=>"facility_tech",
						  "$sTable.Facility_Class"=>"facility_class",
						  "$sTable.state"=>"facility_state",
						  "GROUP_CONCAT($sJoinTable[1].name SEPARATOR ',')"=>"admin_name");
		$sJoin = "LEFT OUTER JOIN $sJoinTable[0] ON $sJoinTable[0].facility_ID = $sTable.ID AND $sJoinTable[0].privilege='admin'
				  LEFT OUTER JOIN $sJoinTable[1] ON $sJoinTable[1].ID = $sJoinTable[0].user_ID";
		$sWhere = " WHERE type='facility'";
		$sGroupBy = "GROUP BY $sTable.ID ";
		
		foreach($aColumns as $key => $val)
		{
			if(!empty($aColumns[$key]))
				$aColumns[$key] = "{$key} AS {$val}";
		}
		$sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM $sTable $sJoin $sWhere $sGroupBy";
		$query = $this->facility_db->query($sql);
		
		return $query->result_array();
	}
	public function get_facility_by_ID($ID)
	{
		$sql = "SELECT * FROM facility_list WHERE ID = '{$ID}'";
		$query = $this->facility_db->query($sql);
		return $query->row_array();
	}
	public function get_facility_by_ctrl_no($ctrl_no = NULL)
	{
		$sql = "SELECT * FROM facility_list WHERE ctrl_no = '{$ctrl_no}'";
		$query = $this->facility_db->query($sql);
		return $query->row_array();
	}
	public function add_facility($input_data)
	{
		$this->facility_db->set("ID",$input_data['ID']);
		$this->facility_db->set("new_ID",$input_data['new_ID']);
		$this->facility_db->set("parent_ID",$input_data['parent_ID']);
		$this->facility_db->set("horizontal_group_ID",$input_data['horizontal_group_ID']);
		$this->facility_db->set("Facility_Tech",$input_data['facility_tech_ID']);
		$this->facility_db->set("Facility_Class",$input_data['facility_class_ID']);
		$this->facility_db->set("state",$input_data['status']);
		$this->facility_db->set("eng_name",$input_data['eng_name']);
		$this->facility_db->set("cht_name",$input_data['cht_name']);
		$this->facility_db->set("location_ID",$input_data['location_ID']);
		$this->facility_db->set("note",$input_data['note']);
		$this->facility_db->set("ctrl_no",$input_data['ctrl_no']);
		$this->facility_db->set("tel_ext",$input_data['tel_ext']);
		$this->facility_db->set("error_comment",$input_data['error_comment']);
		$this->facility_db->set("min_sec",$input_data['min_sec']);
		$this->facility_db->set("unit_sec",$input_data['unit_sec']);
		$this->facility_db->set("extension_sec",$input_data['extension_sec']);
		$this->facility_db->set("enable_booking",$input_data['enable_booking']);
		$this->facility_db->set("enable_privilege",$input_data['enable_privilege']);
		$this->facility_db->set("enable_occupation",$input_data['enable_occupation']);
		$this->facility_db->set("pause_start_time",$input_data['pause_start_time']);
		$this->facility_db->set("pause_end_time",$input_data['pause_end_time']);
		$this->facility_db->insert("facility_list");
		return $this->facility_db->affected_rows();
	}
	public function update_facility_by_ID($input_data)
	{
		$this->facility_db->set("new_ID",$input_data['new_ID']);
		$this->facility_db->set("parent_ID",$input_data['parent_ID']);
		$this->facility_db->set("horizontal_group_ID",$input_data['horizontal_group_ID']);
		$this->facility_db->set("Facility_Tech",$input_data['facility_tech_ID']);
		$this->facility_db->set("Facility_Class",$input_data['facility_class_ID']);
		$this->facility_db->set("state",$input_data['status']);
		$this->facility_db->set("eng_name",$input_data['eng_name']);
		$this->facility_db->set("cht_name",$input_data['cht_name']);
		$this->facility_db->set("location_ID",$input_data['location_ID']);
		$this->facility_db->set("note",$input_data['note']);
		$this->facility_db->set("ctrl_no",$input_data['ctrl_no']);
		$this->facility_db->set("tel_ext",$input_data['tel_ext']);
		$this->facility_db->set("error_comment",$input_data['error_comment']);
		$this->facility_db->set("min_sec",$input_data['min_sec']);
		$this->facility_db->set("unit_sec",$input_data['unit_sec']);
		$this->facility_db->set("extension_sec",$input_data['extension_sec']);
		$this->facility_db->set("enable_booking",$input_data['enable_booking']);
		$this->facility_db->set("enable_privilege",$input_data['enable_privilege']);
		$this->facility_db->set("enable_occupation",$input_data['enable_occupation']);
		$this->facility_db->set("pause_start_time",$input_data['pause_start_time']);
		$this->facility_db->set("pause_end_time",$input_data['pause_end_time']);
		$this->facility_db->where("ID",$input_data['ID']);
		$this->facility_db->update("facility_list");
		return $this->facility_db->affected_rows();
	}
	public function update_batch_facility($input_data = array())
	{
		foreach($input_data as $row)
		{
			$this->facility_db->set("pause_start_time",$row['pause_start_time']);
			$this->facility_db->set("pause_end_time",$row['pause_end_time']);
			$this->facility_db->where("ID",$row['ID']);
			$this->facility_db->update("facility_list");
		}
	}
	//----------------------------門禁-----------------------------------------
	public function get_door_list()
	{
		$sql = "SELECT * FROM facility_list WHERE type='door'";
		$query = $this->facility_db->query($sql);
		return $query->result_array();
	}
	public function get_door_list_array()
	{
		$sTable = "cmnst_facility.facility_list";
		$aColumns = array( "$sTable.ID"=>"ID","$sTable.parent_ID"=>"parent_ID","$sTable.cht_name"=>"name","$sTable.ctrl_no"=>"ctrl_no");
		$sWhere = " WHERE type='door'";
		foreach($aColumns as $key => $val)
		{
			if(!empty($aColumns[$key]))
				$aColumns[$key] = "{$key} AS {$val}";
		}
		$sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM $sTable $sWhere ";
		$query = $this->facility_db->query($sql);
		
		return $query->result_array();
	}
	public function add_door($input_data)
	{
		$this->facility_db->set("ID",$input_data['ID'])
						  ->set("parent_ID",$input_data['parent_ID'])
						  ->set("cht_name",$input_data['name'])
						  ->set("location_ID",$input_data['location_ID'])
						  ->set("tel_ext",$input_data['tel_ext'])
						  ->set("note",$input_data['note'])
						  ->set("ctrl_no",$input_data['ctrl_no'])
						  ->set("pre_open_sec",$input_data['pre_open_sec'])
						  ->set("type",'door');
		$this->facility_db->insert("facility_list");
		return $this->facility_db->insert_id();
	}
	public function update_door($input_data)
	{
		$this->facility_db->set("parent_ID",$input_data['parent_ID'])
						  ->set("cht_name",$input_data['name'])
						  ->set("location_ID",$input_data['location_ID'])
						  ->set("tel_ext",$input_data['tel_ext'])
						  ->set("note",$input_data['note'])
						  ->set("ctrl_no",$input_data['ctrl_no'])
						  ->set("pre_open_sec",$input_data['pre_open_sec'])
						  ->set("type",'door');
		$this->facility_db->where("ID",$input_data['ID']);
		$this->facility_db->update("facility_list");
		return $this->facility_db->affected_rows();
	}
	
	//-------------------------------儀器類別------------------------------------
	public function get_facility_category_list()
	{
		return $this->facility_db->get("facility_category")->result_array();
	}
	//---------------------------儀器預約系統管理員權限-----------------------------
	public function get_admin_privilege_list($input_data = array())
	{
		$sTable = "cmnst_facility.facility_admin_privilege";
		$sJoinTable = array("user_profile"=>"cmnst_common.user_profile");
		$this->facility_db->select("{$sJoinTable['user_profile']}.name AS name,
									{$sJoinTable['user_profile']}.email AS email")
						  ->join($sJoinTable['user_profile'],"{$sJoinTable['user_profile']}.ID = {$sTable}.admin_ID","LEFT");
		if(!empty($input_data['admin_ID']))
			$this->facility_db->where("{$sTable}.admin_ID",$input_data['admin_ID']);
		if(!empty($input_data['privilege']))
			$this->facility_db->where("{$sTable}.privilege",$input_data['privilege']);
		
		return $this->facility_db->get("facility_admin_privilege");
	}
	//---------------------使用者權限------------------------------
	public function get_user_privilege_list_array($input_data)
	{
		$sTable = "cmnst_facility.facility_user_privilege";
		$sJoinTable = array("user"=>"cmnst_common.user_profile","facility"=>"cmnst_facility.facility_list");
		
	  	$aColumns = array( "$sTable.user_ID"=>"user_ID","{$sJoinTable['user']}.name"=>"user_name","{$sJoinTable['user']}.email"=>"user_email", "{$sJoinTable['facility']}.cht_name"=>"facility_cht_name","{$sJoinTable['facility']}.eng_name"=>"facility_eng_name","$sTable.privilege"=>"privilege","$sTable.expiration_date"=>"expiration_date","$sTable.serial_no"=>"SN");
		
		$sJoin = "LEFT OUTER JOIN {$sJoinTable['facility']} ON {$sJoinTable['facility']}.ID = {$sTable}.facility_ID 
				  LEFT OUTER JOIN {$sJoinTable['user']} ON {$sJoinTable['user']}.ID = {$sTable}.user_ID";
		
		$result = $this->get_jQ_DTs_array_with_join($this->facility_db,$sTable,$sJoin,$aColumns,$input_data);
		return $result;
	}
	public function get_user_privilege_list($input_data)
	{
		$sTable = "cmnst_facility.facility_user_privilege";
		$sJoinTable = array("user"=>"cmnst_common.user_profile","facility"=>"cmnst_facility.facility_list");
		
		$this->facility_db->select("$sTable.serial_no AS serial_no,
									$sTable.user_ID AS user_ID,
									$sTable.facility_ID AS facility_ID,
									$sTable.privilege AS privilege,
									$sTable.expiration_date AS expiration_date,
									$sTable.total_secs_used AS total_secs_used,
									$sTable.verification_time AS verification_time,
									$sTable.suspended AS suspended,
									{$sJoinTable['user']}.name AS user_name,
									{$sJoinTable['user']}.email AS user_email,
									{$sJoinTable['user']}.card_num AS user_card_num,
									{$sJoinTable['user']}.security_verified AS security_verified,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name,
									{$sJoinTable['facility']}.state AS facility_state,
									{$sJoinTable['facility']}.enable_booking AS enable_booking,
									{$sJoinTable['facility']}.note AS facility_note,
									{$sJoinTable['facility']}.extension_sec AS extension_sec
									")
		->from($sTable)
		->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = {$sTable}.user_ID","LEFT")
		->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sTable}.facility_ID","LEFT");
		
		if(isset($input_data['serial_no'])){
			$this->facility_db->where("$sTable.serial_no",$input_data['serial_no']);
		}
			
			
		if(isset($input_data['user_ID'])){
			$this->facility_db->where("$sTable.user_ID",$input_data['user_ID']);
		}
			
		if(isset($input_data['facility_ID'])){
			$this->facility_db->where_in("$sTable.facility_ID",$input_data['facility_ID']);
		}
			
		
		if(isset($input_data['privilege']))
		{
			$this->facility_db->where_in("$sTable.privilege",$input_data['privilege']);
		}
		if(isset($input_data['expiration_date_start']))
		{
			$this->facility_db->where("$sTable.expiration_date >=",$input_data['expiration_date_start']);
		}
		if(isset($input_data['expiration_date_end']))
		{
			$this->facility_db->where("$sTable.expiration_date <=",$input_data['expiration_date_end']);
		}
		
		return $this->facility_db->get();
	}
	public function add_user_privilege($input_data)
	{
		if(!is_array(current($input_data)))
			$input_data = array($input_data);
		foreach($input_data as $row)
		{
			$this->facility_db->set("user_ID",$row['user_ID']);
			$this->facility_db->set("facility_ID",$row['facility_ID']);
			$this->facility_db->set("privilege",$row['privilege']);
			if(!empty($row['expiration_date']))
				$this->facility_db->set("expiration_date",$row['expiration_date']);
			$this->facility_db->insert("facility_user_privilege");
		}
	}
	public function update_user_privilege($input_data)
	{
		if(!empty($input_data['privilege']))
			$this->facility_db->set("privilege",$input_data['privilege']);
		if(empty($input_data['expiration_date']))
			$this->facility_db->set("expiration_date",NULL);
		else
			$this->facility_db->set("expiration_date",$input_data['expiration_date']);
		if(isset($input_data['suspended']))
			$this->facility_db->set("suspended",$input_data['suspended']);
		if(isset($input_data['total_secs_used']))
			$this->facility_db->set("total_secs_used",$input_data['total_secs_used']);
		$this->facility_db->where("serial_no",$input_data['serial_no']);
		$this->facility_db->update("facility_user_privilege");
	}
	public function del_user_privilege($input_data)
	{
		if(!is_array(current($input_data)))
			$input_data = array($input_data);
		foreach($input_data as $row)
		{
			if(!empty($row['user_ID']))
				$this->facility_db->where("user_ID",$row['user_ID']);
			if(!empty($row['facility_ID']))
				$this->facility_db->where("facility_ID",$row['facility_ID']);
			if(!empty($row['privilege']))
				$this->facility_db->where("privilege",$row['privilege']);
			$this->facility_db->delete("facility_user_privilege");
		}
	}
	//------------------------------儀器預約與查詢-----------------------------------
	public function get_facility_booking_list($input_data)
	{
		$sTable = "cmnst_facility.facility_booking";
		$sJoinTable = array("facility"=>"cmnst_facility.facility_list","user"=>"cmnst_common.user_profile","nocharge"=>"cmnst_facility.facility_booking_nocharge");
		$this->facility_db->select("{$sTable}.serial_no,
									{$sTable}.user_ID,
									{$sTable}.purpose,
									{$sTable}.note,
									{$sTable}.booking_time,
									{$sTable}.start_time,
									{$sTable}.end_time,
									{$sTable}.update_time,
									{$sTable}.cancel_time,
									{$sJoinTable['user']}.name AS user_name,
									{$sJoinTable['user']}.email AS user_email,
									{$sJoinTable['facility']}.ID AS facility_ID,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name,
									{$sJoinTable['nocharge']}.booking_ID AS nocharge_serial_no,
									{$sJoinTable['nocharge']}.result AS nocharge_result")
						  ->from($sTable)
						  ->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = {$sTable}.user_ID","LEFT")
						  ->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sTable}.facility_ID","LEFT")
						  ->join($sJoinTable['nocharge'],"{$sJoinTable['nocharge']}.booking_ID = {$sTable}.serial_no","LEFT");
		if(isset($input_data['serial_no']))
			$this->facility_db->where_in("{$sTable}.serial_no",$input_data['serial_no']);
		
		if($this->session->userdata('status')=="admin" && isset($input_data['user_ID']) && isset($input_data['facility_ID']) && is_array($input_data['facility_ID']))
		{//special case
			$this->facility_db->where("({$sTable}.user_ID = '{$input_data['user_ID']}' OR {$sTable}.facility_ID IN ('".implode("','",$input_data['facility_ID'])."'))");
		}else{
			if(isset($input_data['user_ID']))
				$this->facility_db->where("{$sTable}.user_ID",$input_data['user_ID']);
			if(isset($input_data['facility_ID'])){
				if( is_array($input_data['facility_ID']) && count($input_data['facility_ID'])==0 )
				{//fix where in syntex error
					$input_data['facility_ID'] = array('');
				}
				$this->facility_db->where_in("{$sTable}.facility_ID",$input_data['facility_ID']);
			}
				
		}
		
		if(isset($input_data['purpose']))
			$this->facility_db->where("{$sTable}.purpose",$input_data['purpose']);
		if(isset($input_data['start_time']))
			$this->facility_db->where("{$sTable}.end_time >",$input_data['start_time']);
		if(isset($input_data['end_time']))
			$this->facility_db->where("{$sTable}.start_time <",$input_data['end_time']);
		if(isset($input_data['start_cancel_time']))
			$this->facility_db->where("{$sTable}.cancel_time >=",$input_data['start_cancel_time']);
		else
			$this->facility_db->where("{$sTable}.cancel_time",NULL);
		$this->facility_db->order_by("end_time","desc");
		$query = $this->facility_db->get();
		return $query;
	}
	
	public function add_facility_booking($input_data)
	{
		$this->facility_db->set("user_ID",$input_data['user_ID']);
		$this->facility_db->set("facility_ID",$input_data['facility_ID']);
		$this->facility_db->set("purpose",$input_data['purpose']);
		if(!empty($input_data['note']))
			$this->facility_db->set("note",$input_data['note']);
		$this->facility_db->set("start_time",$input_data['start_time']);
		$this->facility_db->set("end_time",$input_data['end_time']);
		$this->facility_db->insert("facility_booking");
		return $this->facility_db->insert_id();
	}
	public function update_facility_booking($input_data = array())
	{
		if(isset($input_data['cancel_time']))
			$this->facility_db->set('cancel_time',$input_data['cancel_time']);
		if(isset($input_data['update_time']))
			$this->facility_db->set('update_time',$input_data['update_time']);
		if(isset($input_data['user_ID']))
			$this->facility_db->set("user_ID",$input_data['user_ID']);
		if(isset($input_data['start_time']))
			$this->facility_db->set('start_time',$input_data['start_time']);
		if(isset($input_data['end_time']))
			$this->facility_db->set('end_time',$input_data['end_time']);
		$this->facility_db->where("serial_no",$input_data['serial_no']);
		$this->facility_db->update("facility_booking");
		return $this->facility_db->affected_rows();
	}
	//---------------------------儀器維護申請與查詢--------------------------------
	public function get_maintenance_list($input_data = array())
	{
		$sTable = "facility_maintenance";
		$sJoinTable = array("booking"=>"facility_booking","facility"=>"facility_list","user"=>"cmnst_common.user_profile");
		
		$this->facility_db->select("{$sTable}.*,
									{$sJoinTable['booking']}.start_time AS booking_start_time,
									{$sJoinTable['booking']}.end_time AS booking_end_time,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name,
									{$sJoinTable['user']}.name AS applicant_name")
						  ->from($sTable)
						  ->join($sJoinTable['booking'],"{$sJoinTable['booking']}.serial_no = {$sTable}.booking_ID","LEFT")
						  ->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sTable}.facility_ID","LEFT")
						  ->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = {$sTable}.applicant_ID","LEFT");
		
		if(!empty($input_data['serial_no']))
			$this->facility_db->where("{$sTable}.serial_no",$input_data['serial_no']);
		if(!empty($input_data['applicant_ID']))
			$this->facility_db->where("{$sTable}.applicant_ID",$input_data['applicant_ID']);
		if(!empty($input_data['facility_ID']))
			$this->facility_db->where_in("{$sTable}.facility_ID",$input_data['facility_ID']);
		return $this->facility_db->get();
	}
	public function add_maintenance($input_data = array())
	{
		$this->facility_db->set("applicant_ID",$input_data['applicant_ID']);
		$this->facility_db->set("facility_ID",$input_data['facility_ID']);
		$this->facility_db->set("subject",$input_data['subject']);
		
		if(!empty($input_data['booking_ID']))
			$this->facility_db->set("booking_ID",$input_data['booking_ID']);
		if(!empty($input_data['content']))
			$this->facility_db->set("content",$input_data['content']);
		if(!empty($input_data['result']))
			$this->facility_db->set("result",$input_data['result']);
		if(!empty($input_data['maintainer_name']))
			$this->facility_db->set("maintainer_name",$input_data['maintainer_name']);
		if(!empty($input_data['fees']))
			$this->facility_db->set("fees",$input_data['fees']);
		$this->facility_db->insert("facility_maintenance");
		
		return $this->facility_db->insert_id();
	}
	public function update_maintenance($input_data)
	{
		if(!empty($input_data['booking_ID']))
			$this->facility_db->set("booking_ID",$input_data['booking_ID']);
		if(!empty($input_data['subject']))
			$this->facility_db->set("subject",$input_data['subject']);
		if(!empty($input_data['content']))
			$this->facility_db->set("content",$input_data['content']);
		if(!empty($input_data['maintainer_name']))
			$this->facility_db->set("maintainer_name",$input_data['maintainer_name']);
		if(!empty($input_data['result']))
			$this->facility_db->set("result",$input_data['result']);
		if(!empty($input_data['manager_ID']))
			$this->facility_db->set("manager_ID",$input_data['manager_ID']);
		$this->facility_db->where("serial_no",$input_data['serial_no']);
		$this->facility_db->update("facility_maintenance");
	}
	//----------------------------------儀器預約不計費查詢-------------------------------------
	public function get_booking_nocharge_list($input_data = array())
	{
		$sTable = "cmnst_facility.facility_booking_nocharge";
		$sJoinTable = array("booking"=>"cmnst_facility.facility_booking",
							"facility"=>"cmnst_facility.facility_list",
							"user"=>"cmnst_common.user_profile",
							"admin"=>"cmnst_common.user_profile");
		$this->facility_db->select("{$sTable}.booking_ID,
									{$sTable}.apply_date,
									{$sTable}.reason,
									{$sTable}.result,
									{$sTable}.start_time AS nocharge_start_time,
									{$sTable}.end_time AS nocharge_end_time,
									{$sTable}.comment,
									{$sJoinTable['booking']}.user_ID,
									{$sJoinTable['user']}.name AS user_name,
									{$sJoinTable['user']}.email AS user_email,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name,
									{$sJoinTable['booking']}.start_time,
									{$sJoinTable['booking']}.end_time,
									admin.name AS admin_name")
						  ->from("facility_booking_nocharge")
						  ->join($sJoinTable['booking'],"{$sJoinTable['booking']}.serial_no = {$sTable}.booking_ID","LEFT")
						  ->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sJoinTable['booking']}.facility_ID")
						  ->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = {$sJoinTable['booking']}.user_ID")
						  ->join("{$sJoinTable['admin']} AS admin","admin.ID = {$sTable}.admin_ID","LEFT");
		if(!empty($input_data['booking_ID']))
			$this->facility_db->where("{$sTable}.booking_ID",$input_data['booking_ID']);
		if(!empty($input_data['facility_ID']))
			$this->facility_db->where_in("{$sJoinTable['facility']}.ID",(array)$input_data['facility_ID']);
		$query = $this->facility_db->get();
		return $query;
	}
	public function add_booking_nocharge($input_data)
	{
		$this->facility_db->set("booking_ID",$input_data['booking_ID']);
		$this->facility_db->set("reason",$input_data['reason']);
		$this->facility_db->insert("facility_booking_nocharge");
		return $this->facility_db->affected_rows();
	}
	public function update_booking_nocharge($input_data)
	{
		$this->facility_db->set("admin_ID",$input_data['admin_ID']);
		$this->facility_db->set("comment",$input_data['comment']);
		$this->facility_db->set("approval_date",date("Y-m-d H:i:s"));
		$this->facility_db->set("start_time",$input_data['start_time']);
		$this->facility_db->set("end_time",$input_data['end_time']);
		$this->facility_db->set("result",$input_data['result']);
		$this->facility_db->where("booking_ID",$input_data['booking_ID']);
		$this->facility_db->update("facility_booking_nocharge");
		return $this->facility_db->affected_rows();
	}
	public function del_booking_nocharge($ID)
	{
		$this->facility_db->where("booking_ID",$ID);
		$this->facility_db->delete("facility_booking_nocharge");
		return $this->facility_db->affected_rows();
	}
	//START---------------------------卡機相關查詢(刷卡動作、開關預約、連線資訊)------------------------------
	public function get_access_card_list_json($input_data)
	{
		$sTable = "cmnst_facility.Card";
		$sJoinTable = array("cmnst_facility.facility_list","cmnst_common.user_profile");
		
	  	$aColumns = array( "$sTable.FDate"=>"access_date","$sTable.FTime"=>"access_time","$sTable.CardNo"=>"card_num","$sJoinTable[0].cht_name"=>"facility_name", "$sJoinTable[1].name"=>"user_name","$sTable.Status"=>"status");
		
		$sJoin = "LEFT OUTER JOIN $sJoinTable[0] ON $sJoinTable[0].ctrl_no = $sTable.CtrlNo 
				  LEFT OUTER JOIN $sJoinTable[1] ON $sJoinTable[1].card_num = $sTable.CardNo";
				  
		$input_data['sWhere'] = "($sTable.Status = '00' OR $sTable.Status = '01' OR $sTable.Status = 'A0')";
		if(!empty($input_data['start_date']))
			$input_data['sWhere'] .= " AND TIMESTAMP($sTable.FDate,$sTable.FTime) >= '{$input_data['start_date']} {$input_data['start_time']}'";
		if(!empty($input_data['end_date']))
			$input_data['sWhere'] .= " AND TIMESTAMP($sTable.FDate,$sTable.FTime) <= '{$input_data['end_date']} {$input_data['end_time']}'";
		if(!empty($input_data['facility_ctrl_no']))
			$input_data['sWhere'] .= " AND $sTable.CtrlNo = '{$input_data['facility_ctrl_no']}'";
		
		$result = $this->get_jQ_DTs_json_with_join($this->facility_db,$sTable,$sJoin,$aColumns,$input_data);
		
		return $result;
	}
	public function sync_access_card_list()
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
	public function get_access_ctrl_list($input_data)
	{
		$sTable = "RunList";
		$sJoinTable = "";
		
		$this->mssql_old_db->select("$sTable.SerNo AS serial_no,
									 $sTable.FTime AS access_time,
									 $sTable.Fun AS action,
									 $sTable.CardNo AS card_num,
									 $sTable.CtrlNo AS ctrl_no,
									 $sTable.Flag AS flag,
									 $sTable.Status AS status")
						   ->from($sTable);
		if(isset($input_data['start_time']))
			$this->mssql_old_db->where("$sTable.FTime >=",date("Y/m/d H:i:s",strtotime($input_data['start_time'])));
		if(isset($input_data['end_time']))
			$this->mssql_old_db->where("$sTable.FTime <=",date("Y/m/d H:i:s",strtotime($input_data['end_time'])));
		if(isset($input_data['f_time']))
		{
			$this->mssql_old_db->where("$sTable.FTime",date("Y/m/d H:i:s",strtotime($input_data['f_time'])));
		}
		if(isset($input_data['facility_ctrl_no']))
			$this->mssql_old_db->where_in("$sTable.CtrlNo",$input_data['facility_ctrl_no']);
		if(isset($input_data['card_num']))
			$this->mssql_old_db->where("$sTable.CardNo",$input_data['card_num']);
		if(isset($input_data['fun']))
			$this->mssql_old_db->where("$sTable.Fun",$input_data['fun']);
		if(isset($input_data['state']))
			$this->mssql_old_db->where("$sTable.Status",$input_data['state']);
		if(isset($input_data['flag']))
			$this->mssql_old_db->where("$sTable.Flag",$input_data['flag']);
		
		//若只有設定開始時間，則排序由時間小到大，反之亦然
		if(isset($input_data['start_time'])&&!isset($input_data['end_time'])){
			$this->mssql_old_db->order_by("$sTable.FTime","ASC");
		}else if(isset($input_data['end_time'])&&!isset($input_data['start_time'])){
			$this->mssql_old_db->order_by("$sTable.FTime","DESC");
		}else{
			
		}
		return $this->mssql_old_db->get();
	}

	public function add_access_ctrl($input_data)
	{
		foreach($input_data as $row)
		{
			if(empty($row['ctrl_no']))
				continue;
			$this->mssql_old_db->set("FTime",date("Y/m/d H:i:s",strtotime($row['date_time'])));
			$this->mssql_old_db->set("Fun",$row['fun']);
			$this->mssql_old_db->set("CardNo",$row['card_num']);
			$this->mssql_old_db->set("CtrlNo",$row['ctrl_no']);
			$this->mssql_old_db->set("Flag","0");
			$this->mssql_old_db->insert("RunList");
		}
	}
	public function update_access_ctrl($input_data)
	{
		if(!empty($input_data['date_time']))
			$this->mssql_old_db->set("FTime",date("Y/m/d H:i:s",strtotime($input_data['date_time'])));
		if(!empty($input_data['fun']))
			$this->mssql_old_db->set("Fun",$input_data['fun']);
		if(!empty($input_data['card_num']))
			$this->mssql_old_db->set("CardNo",$input_data['card_num']);
		if(!empty($input_data['ctrl_no']))
			$this->mssql_old_db->set("CtrlNo",$input_data['ctrl_no']);
		$this->mssql_old_db->set("Flag","0");
		$this->mssql_old_db->set("Status",NULL);
		$this->mssql_old_db->where("Serno",$input_data['serial_no']);
		$this->mssql_old_db->update("RunList");
	}
	public function del_access_ctrl($input_data)
	{
		
		if(isset($input_data['date_time']))
			$this->mssql_old_db->where("FTime",date("Y/m/d H:i:s",strtotime($input_data['date_time'])));
		if(isset($input_data['fun']))
			$this->mssql_old_db->where("Fun",$input_data['fun']);
		if(isset($input_data['card_num']))
			$this->mssql_old_db->where("CardNo",$input_data['card_num']);
		if(isset($input_data['ctrl_no']))
			$this->mssql_old_db->where("CtrlNo",$input_data['ctrl_no']);
		$this->mssql_old_db->where("Serno",$input_data['serial_no']);
		$this->mssql_old_db->where("Flag","0");//若為1，表示已執行的，刪了也沒用，故留做紀錄
		$this->mssql_old_db->delete("RunList");
		
	}
	public function get_access_link_list($input_data = array())
	{
		if(!empty($input_data['CtrlNo']))
			$this->mssql_old_db->where("CtrlNo",$input_data['CtrlNo']);
		return $this->mssql_old_db->get("MLink");
	}
	public function add_access_link($input_data)
	{
		$this->mssql_old_db->set("CtrlNo",$input_data['CtrlNo']);
		$this->mssql_old_db->set("Tcpip",$input_data['Tcpip']);
		$this->mssql_old_db->set("MType",$input_data['MType']);
		$this->mssql_old_db->set("Addr",$input_data['CtrlNo']);
		$this->mssql_old_db->insert("MLink");
		return $this->mssql_old_db->affected_rows();
	}
	public function update_access_link($input_data)
	{
		$this->mssql_old_db->set("Tcpip",$input_data['Tcpip']);
		$this->mssql_old_db->set("MType",$input_data['MType']);
		$this->mssql_old_db->where("CtrlNo",$input_data['CtrlNo']);
		$this->mssql_old_db->update("MLink");
		return $this->mssql_old_db->affected_rows();
	}
	public function del_access_link($input_data)
	{
		$this->mssql_old_db->where("CtrlNo",$input_data['CtrlNo']);
		$this->mssql_old_db->delete("MLink");
	}
	//END---------------------------刷卡查詢------------------------------
	
	//-------------------------------磁卡查詢----------------------------------
	public function get_card_application_list($input_data = array())
	{
		$sTable = "cmnst_facility.facility_card_application";
		$sJoinTable = array("cmnst_common.user_profile","cmnst_common.user_profile");
		$this->facility_db->select("$sTable.serial_no,
									$sTable.type,
									$sTable.user_ID,
									$sTable.application_date,
									$sTable.comment,
									$sTable.card_num,
									$sTable.issuance_date,
									$sTable.checkpoint,
									$sJoinTable[0].name AS user_name,
									$sJoinTable[0].email,
									$sJoinTable[0].mobile,
									admin.name AS admin_name")
						  ->from($sTable)
						  ->join($sJoinTable[0],"$sJoinTable[0].ID = $sTable.user_ID","LEFT")
						  ->join("{$sJoinTable[1]} AS admin","admin.ID = $sTable.officer_ID","LEFT")
						  ->order_by("$sTable.serial_no","desc");
		
		if(!empty($input_data['type']))
			$this->facility_db->where("$sTable.type",$input_data['type']);
		if(!empty($input_data['user_ID']))
			$this->facility_db->where("$sTable.user_ID",$input_data['user_ID']);
		if(!empty($input_data['serial_no']))
			$this->facility_db->where("$sTable.serial_no",$input_data['serial_no']);
		
		return $this->facility_db->get();
	}

	public function add_card_application($input_data)
	{
		$this->facility_db->set("user_ID",$input_data['user_ID']);
		$this->facility_db->set("type",$input_data['type']);
		if(isset($input_data['comment']))
			$this->facility_db->set("comment",$input_data['comment']);
		$this->facility_db->insert("facility_card_application");
		return $this->facility_db->affected_rows();
	}
	public function update_card_application($input_data)
	{
		if(!empty($input_data['card_num']))
			$this->facility_db->set("card_num",$input_data['card_num']);
		if(!empty($input_data['officer_ID']))
		{
			$this->facility_db->set("issuance_date","NOW()",FALSE);
			$this->facility_db->set("officer_ID",$input_data['officer_ID']);
		}
		if(isset($input_data['canceled_by']))
		{
			$this->facility_db->set("canceled_by",$input_data['canceled_by']);
			$this->facility_db->set("cancellation_date",date("Y-m-d H:i:s"));
		}
		
		$this->facility_db->set("checkpoint",$input_data['checkpoint']);
		$this->facility_db->where("serial_no",$input_data['serial_no']);
		$this->facility_db->update("facility_card_application");
		return $this->facility_db->affected_rows();
	}

	//----------------------------通用-----------------------------
	public function is_facility_super_admin()
	{
		if($this->session->userdata('status')=="admin")
			return $this->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>"facility_super_admin"))->row_array();
		else
			return FALSE;
	}
	public function is_facility_admin($f_ID)
	{
		if($this->session->userdata('status')=="admin")
			return $this->get_user_privilege_list(array("user_ID"=>$this->session->userdata('ID'),"facility_ID"=>$f_ID,"privilege"=>"admin"))->row_array();
		else
			return FALSE;
	}
	public function get_facility_select_options($type = NULL)
	{
		
		$facilities = $this->get_facility_list(array("type"=>$type))->result_array();
		$select_options = array();
		foreach($facilities as $facility)
		{
			$select_options[$facility['ID']] = $facility['cht_name']." (".$facility['eng_name'].")";
		}
		return $select_options;
	}
	/**
	* 
	* @param $IDs 單一儀器ID或複數儀器IDs
	* @param $options [facility_only]只找儀器類(不要門),[no_child]不尋找小孩
	* @param $results 遞回傳入參數兼回傳之結果
	* 
	* @return 與輸入儀器同群組之id
	*/
	public function get_vertical_group_facilities($IDs,$options = array(),$results = array())
	{
		//設定初始參數
		if(!isset($options['facility_only']))
			$options['facility_only'] = FALSE;
		if(!isset($options['no_child']))
			$options['no_child'] = FALSE;
		if(!isset($options['door_only']))
			$options['door_only'] = FALSE;//facility_only 優先
			
		foreach((array)$IDs as $ID)
		{
			//如果已存在，不要重複找
			if(in_array($ID,$results))
			{
				continue;
			}
			//確認儀器或門存在
			$facility = $this->get_facility_list(array("ID"=>$ID))->row_array();
			if(!$facility)
			{
				continue;
			}
				
			//先確認是不是只要儀器
			if($options['facility_only'] && $facility['type'] != 'facility')
			{
				continue;
			}
			
			
			
			//存入陣列
			//確認是不是只要門禁
			if($options['door_only'] && $facility['type'] != 'door'){
				//do nothing
			}else{
				array_push($results,$ID);
			}
			
			
			//尋找父親
			if(!empty($facility['parent_ID']))
				$results = $this->get_vertical_group_facilities($facility['parent_ID'],$options,$results);	
			
			//尋找孩子(先確認有要找孩子且不是門)
			if($options['no_child'] || $facility['type'] == 'door')
			{
				continue;
			}
				
			
			$facilities = $this->get_facility_list(array("parent_ID"=>$ID,
																		 "type"=>"facility"))->result_array();
			foreach($facilities as $facility)
			{
				$results = $this->get_vertical_group_facilities($facility['ID'],$options,$results);	
			}
		}
		return $results;
	}
	
	public function get_horizontal_group_facilities($IDs)
	{
		$output = array();
		foreach((array)$IDs as $ID)
		{
			//確認儀器或門存在，且有水平群組
			$facility = $this->get_facility_list(array("ID"=>$ID))->row_array();
			if(!$facility || empty($facility['horizontal_group_ID']))
			{
				continue;
			}
			
			$facilities = $this->get_facility_list(array("horizontal_group_ID"=>$facility['horizontal_group_ID']))->result_array();
			$output = array_merge($output,sql_result_to_column($facilities,"ID"));
		}
		return array_unique($output);
	}

	
	public function get_max_pre_open_sec($f_ID = NULL)
	{
		$options = array("ID"=>$f_ID);
		$facilities = $this->get_facility_list($options)->result_array();
		if($facilities){
			$pre_open_sec = sql_result_to_column($facilities,"pre_open_sec");
			return max($pre_open_sec);
		}else{
			return 0;
		}
	}
}