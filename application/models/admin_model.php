<?php
class Admin_model extends MY_Model {
  
  protected $common_db;
  protected $facility_db;
  protected $clock_db;
  public function __construct()
  {
	$this->common_db = $this->load->database('common',TRUE);
	$this->facility_db = $this->load->database('facility',TRUE);
	$this->clock_db = $this->load->database('clock',TRUE);
  }
  //---------------------------權限-------------------------------
  public function get_admin_profile_list($options = array())
  {
  	$sTable = "admin_profile";
  	$sJoinTable = array("user"=>"user_profile");
  	$this->common_db->select("$sTable.stamp AS stamp,
  							  $sTable.suspended AS suspended,
  							  {$sJoinTable['user']}.*")
  					->from($sTable)
  					->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.ID","LEFT");
  	
  	if(isset($options['admin_ID']))
  	{
		$this->common_db->where_in("$sTable.ID",$options['admin_ID']);
	}
  		
  	if(isset($options['suspended']))
  	{
		$this->common_db->where("suspended",$options['suspended']);
	}
  	return $this->common_db->get();
  }
  public function get_admin_profile_by_ID($ID)
  {
  	return $this->get_admin_profile_list(array("admin_ID"=>$ID))->row_array();
  }
  public function get_admin_privilege($privilege,$ID = "")
  {
  	if(empty($ID))	$ID = $this->session->userdata('ID');
  	
  	$sql = "SELECT * FROM admin_privilege WHERE ID = '{$ID}' AND privilege = '{$privilege}'";
	$query = $this->common_db->query($sql);
	return $query->num_rows();
  }
  
  
  public function login_access($ID,$passwd)
  {
  	$sql = "SELECT * FROM admin_access WHERE User_Account = '{$ID}' AND User_Password = '{$passwd}'";
	$query = $this->common_db->query($sql);
    if(!$query->num_rows()) return FALSE;
    $this->common_db->where("ID",$ID);
    $admin_profile = $this->common_db->get("admin_profile")->row_array();
    if(!$admin_profile) return FALSE;
    if($admin_profile['suspended']) return FALSE;
    return TRUE;
  }
  //-----------------------------帳號--------------------------------
  public function add_account($input_data)
  {
  	$this->common_db->set("User_Account",$input_data['ID']);
  	$this->common_db->set("User_Password",$input_data['passwd']);
  	$this->common_db->insert("admin_access");

	$this->common_db->set("ID",$input_data['ID']);
  	$this->common_db->insert("admin_profile");
  	
  	$this->common_db->set("ID",$input_data['ID']);
  	$this->common_db->set("name",$input_data['name']);
  	$this->common_db->set("email",$input_data['email']);
  	$this->common_db->set("mobile",$input_data['mobile']);
  	$this->common_db->insert("user_profile");
  }
  public function update_account($input_data)
  {
  	if(!empty($input_data['passwd']))
	{
		$this->common_db->set("User_Password",$input_data['passwd']);
		$this->common_db->where("User_Account",$input_data['ID']);
		$this->common_db->update("admin_access");
	}
	
	if(!empty($input_data['stamp'])){
		$this->common_db->set("stamp",$input_data['stamp']);
		$this->common_db->where("ID",$input_data['ID']);
		$this->common_db->update("admin_profile");
	}
	
	$this->common_db->set("name",$input_data['name']);
  	$this->common_db->set("email",$input_data['email']);
  	$this->common_db->set("mobile",$input_data['mobile']);
  	$this->common_db->where("ID",$input_data['ID']);
	$this->common_db->update("user_profile");
	return $this->common_db->affected_rows();
  }
  //-------------------PASSWORD----------------------
  public function hash_passwd($passwd)
  {
  	$code = crypt($passwd, sprintf('pw$%s-$@', crypt($passwd)));
	return md5($code);
  }
  //--------------------人員位置------------------------------
	public function get_auto_clock_list($options = array())
	{
		$sTable = "cmnst_common.user_profile";
		$sJoinTable = array("card"=>"cmnst_facility.Card","facility"=>"cmnst_facility.facility_list","location"=>"cmnst_common.location","clock"=>"cmnst_clock.clock_admin_manual","constant_user_status"=>"cmnst_common.constant_user_status");
		$this->facility_db->select("$sTable.name AS user_name,
									$sTable.tel AS user_tel,
									$sTable.mobile AS user_mobile,
									$sTable.card_num AS user_card_num,
									IFNULL(card.CardNo,$sTable.serial_no) AS uni_card_num,
									card.FDate AS access_last_date,
									card.FTime AS access_last_time,
									card.FDateTime AS access_last_datetime,
									card.Status AS access_state,
									{$sJoinTable['facility']}.type AS facility_type,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name,
									{$sJoinTable['location']}.location_cht_name,
									{$sJoinTable['location']}.location_eng_name,
									{$sJoinTable['location']}.location_tel,
									{$sJoinTable['constant_user_status']}.status_ID AS user_status_ID,
									clock.clock_remark AS clock_remark,
									clock.clock_reason AS clock_reason,
									clock.clock_location AS clock_location,
									clock.clock_start_time AS clock_start_time,
									clock.clock_end_time AS clock_end_time",FALSE);
		$this->facility_db->from($sTable);
		$this->facility_db->join("(SELECT * FROM {$sJoinTable['card']} WHERE FDate = '".date("Y-m-d")."' ORDER BY FDate DESC, FTime DESC) card","$sTable.card_num = card.CardNo","LEFT");
		$this->facility_db->group_by("uni_card_num");
		$this->facility_db->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ctrl_no = card.CtrlNo","LEFT");
		$this->facility_db->join($sJoinTable['location'],"{$sJoinTable['facility']}.location_ID = {$sJoinTable['location']}.location_ID","LEFT");
		$this->facility_db->join("(SELECT * FROM {$sJoinTable['clock']} WHERE (NOW() BETWEEN clock_start_time AND clock_end_time) OR (NOW() > clock_start_time AND DATE(clock_start_time) = CURDATE()) ORDER BY clock_time DESC) clock","clock.clock_user_ID = $sTable.ID","LEFT");
		$this->facility_db->join($sJoinTable['constant_user_status'],"$sTable.status = {$sJoinTable['constant_user_status']}.status_no","LEFT");
		$this->facility_db->where("$sTable.group","admin");
		if(isset($options['admin_ID']))
		{
			$this->facility_db->where("$sTable.ID",$options['admin_ID']);
		}
		$query = $this->facility_db->get();
		return $query;
	}
	public function get_manual_clock_list($option = array())
	{
		$sTable = "clock_admin_manual";
		$sJoinTable = array("user"=>"cmnst_common.user_profile");
		$this->clock_db->select("
			$sTable.*,
			{$sJoinTable['user']}.name AS clock_user_name
		");
		$this->clock_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.clock_user_ID","LEFT");
		if(isset($option['clock_user_ID']))
			$this->clock_db->where("clock_user_ID",$option['clock_user_ID']);
		if(isset($option['clock_start_time']))
		{
			$this->clock_db->where("ISNULL(clock_end_time) OR clock_end_time >= '{$option['clock_start_time']}'");
		}
		if(isset($option['clock_end_time']))
		{
			$this->clock_db->where("clock_start_time <=",$option['clock_end_time']);
		}
		if(isset($option['clock_start_time_start_time']))
		{
			$this->clock_db->where("clock_start_time >=",$option['clock_start_time_start_time']);
		}
		if(isset($option['clock_start_time_end_time']))
		{
			$this->clock_db->where("clock_start_time <=",$option['clock_start_time_end_time']);
		}
		if(isset($option['clock_end_time_start_time']))
		{
			$this->clock_db->where("ISNULL(clock_end_time) OR clock_end_time >= '{$option['clock_end_time_start_time']}'");
		}
		if(isset($option['clock_end_time_end_time']))
		{
			$this->clock_db->where("clock_end_time <=",$option['clock_end_time_end_time']);
		}
		if(isset($option['clock_time_start_time']))
		{
			$this->clock_db->where("clock_time >=",$option['clock_time_start_time']);
		}
		if(isset($option['clock_time_end_time']))
		{
			$this->clock_db->where("clock_time <=",$option['clock_time_end_time']);
		}
		if(isset($option['clock_checkpoint']))
		{
			$this->clock_db->where("clock_checkpoint",$option['clock_checkpoint']);
		}	
		return $this->clock_db->get("clock_admin_manual");
	}
	public function add_clock($input_data)
	{
		$this->clock_db->set("clock_user_ID",$input_data['clock_user_ID'])
					   ->set("clock_start_time",$input_data['clock_start_time'])
					   ->set("clock_location",$input_data['clock_location'])
					   ->set("clock_reason",$input_data['clock_reason']);
		if(isset($input_data['clock_remark']))
		{
			$this->clock_db->set("clock_remark",$input_data['clock_remark']);
		}
		if(isset($input_data['clock_end_time'])){
			$this->clock_db->set("clock_end_time",$input_data['clock_end_time']);
		}
		$this->clock_db->insert("clock_admin_manual");
	}
	public function update_clock($data)
	{
		$this->clock_db->set("clock_checkpoint",$data['clock_checkpoint']);
		$this->clock_db->where("clock_ID",$data['clock_ID']);
		$this->clock_db->update("clock_admin_manual");
	}
	public function del_clock($data)
	{
		$this->clock_db->where("clock_ID",$data['clock_ID']);
		$this->clock_db->delete("clock_admin_manual");
	}
	
	//--------------------------人員組織-------------
	public function get_org_chart_list($options = array())
	{
		$sTable = "admin_org_chart";
		$sJoinTable = array("group"=>"enum_admin_org_group","team"=>"enum_admin_org_team","status"=>"enum_admin_org_status","user"=>"cmnst_common.user_profile");
		$this->common_db->select("
			$sTable.*,
			{$sJoinTable['group']}.group_ID AS group_ID,
			{$sJoinTable['group']}.group_name AS group_name,
			{$sJoinTable['team']}.team_ID AS team_ID,
			{$sJoinTable['team']}.team_name AS team_name,
			{$sJoinTable['status']}.status_ID AS status_ID,
			{$sJoinTable['status']}.status_name AS status_name,
			{$sJoinTable['user']}.name AS admin_name,
			{$sJoinTable['user']}.email AS admin_email
		");
		$this->common_db->join($sJoinTable['group'],"{$sJoinTable['group']}.group_no = $sTable.group_no","LEFT")
						->join($sJoinTable['team'],"{$sJoinTable['team']}.team_no = $sTable.team_no","LEFT")
						->join($sJoinTable['status'],"{$sJoinTable['status']}.status_no = $sTable.status_no","LEFT")
						->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.admin_ID","LEFT");
		if(isset($options['admin_ID']))
		{
			$this->common_db->where("$sTable.admin_ID",$options['admin_ID']);
		}
		if(isset($options['group_no']))
		{
			$this->common_db->where("$sTable.group_no",$options['group_no']);
		}
		if(isset($options['group_ID']))
		{
			$this->common_db->where("{$sJoinTable['group']}.group_ID",$options['group_ID']);
		}
		if(isset($options['team_no']))
		{
			$this->common_db->where("$sTable.team_no",$options['team_no']);
		}
		if(isset($options['team_ID']))
		{
			$this->common_db->where("{$sJoinTable['team']}.team_ID",$options['team_ID']);
		}
		if(isset($options['status_no']))
		{
			$this->common_db->where("$sTable.status_no",$options['status_no']);
		}
		if(isset($options['status_ID']))
		{
			$this->common_db->where_in("{$sJoinTable['status']}.status_ID",$options['status_ID']);
		}
		return $this->common_db->get($sTable);
	}
	public function add_org_chart($data)
	{
		$this->common_db->insert("admin_org_chart",$data);
	}
	public function update_org_chart($data)
	{
		
	}
	public function del_org_chart($data)
	{
		$this->common_db->where("serial_no",$data['serial_no']);
		$this->common_db->delete("admin_org_chart");
	}
	public function get_enum_org_chart_group_list($options = array())
	{
		$sTable = "enum_admin_org_group";
		return $this->common_db->get($sTable);
	}
	public function get_org_chart_group_array()
	{
		$groups = $this->get_enum_org_chart_group_list()->result_array();
		return sql_column_to_key_value_array($groups,"group_name","group_no");
	}
	public function get_enum_org_chart_team_list($options = array())
	{
		$sTable = "enum_admin_org_team";
		return $this->common_db->get($sTable);
	}
	public function get_org_chart_team_array()
	{
		$teams = $this->get_enum_org_chart_team_list()->result_array();
		return sql_column_to_key_value_array($teams,"team_name","team_no");
	}
	public function get_enum_org_chart_status_list($options = array())
	{
		$sTable = "enum_admin_org_status";
		return $this->common_db->get($sTable);
	}
	public function get_org_chart_status_array()
	{
		$statuss = $this->get_enum_org_chart_status_list()->result_array();
		return sql_column_to_key_value_array($statuss,"status_name","status_no");
	}
	//-----------------通用------------------
	public function get_admin_ID_select_options()
	{
		$admin_profiles = $this->get_admin_profile_list(array("suspended"=>0))->result_array();
		$output = array();
		foreach($admin_profiles as $admin_profile){
			$output[$admin_profile['ID']] = $admin_profile['name'];
		}
		return $output;
	}
}