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
  	$this->common_db->set("name",$input_data['name']);
  	$this->common_db->set("email",$input_data['email']);
  	$this->common_db->set("mobile",$input_data['mobile']);
  	$this->common_db->insert("admin_profile");
  }
  public function update_account($input_data)
  {
  	if(!empty($input_data['passwd']))
	{
		$this->common_db->set("User_Password",$input_data['passwd']);
		$this->common_db->where("User_Account",$input_data['ID']);
		$this->common_db->update("admin_access");
	}
	
	$this->common_db->set("name",$input_data['name']);
  	$this->common_db->set("email",$input_data['email']);
  	$this->common_db->set("mobile",$input_data['mobile']);
  	if(!empty($input_data['stamp']))
  		$this->common_db->set("stamp",$input_data['stamp']);
  	$this->common_db->where("ID",$input_data['ID']);
	$this->common_db->update("admin_profile");
	return $this->common_db->affected_rows();
  }
  //-------------------PASSWORD----------------------
  public function hash_passwd($passwd)
  {
  	$code = crypt($passwd, sprintf('pw$%s-$@', crypt($passwd)));
	return md5($code);
  }
  //--------------------人員位置------------------------------
	public function get_auto_clock_list()
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
		$query = $this->facility_db->get();
		return $query;
	}
	public function get_manual_clock_list($option)
	{
		if(isset($option['clock_user_ID']))
			$this->clock_db->where("clock_user_ID",$option['clock_user_ID']);
		if(isset($option['clock_start_time'])){
			$this->clock_db->where("((ISNULL(clock_end_time) AND clock_start_time >='".date("Y-m-d H:i:s",strtotime("-30minutes"))."') OR clock_end_time >= '{$option['clock_start_time']}')");
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
	public function del_clock($data)
	{
		$this->clock_db->where("clock_ID",$data['clock_ID']);
		$this->clock_db->delete("clock_admin_manual");
	}
	//-------------------------BOSS---------------------------------
	public function get_boss_list($options = array())
	{
		$sTable = "boss_profile";
		$sJoinTable = array("org"=>"organization");
		$this->common_db->select("$sTable.*,{$sJoinTable['org']}.name AS org_name");
		$this->common_db->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = $sTable.organization","LEFT");
		if(isset($options['serial_no']))
		{
			$this->common_db->where("$sTable.serial_no",$options['serial_no']);
		}
		
		return $this->common_db->get($sTable);	
	}
	public function add_boss($data)
	{
		$this->update_boss($data);
	}
	public function update_boss($data)
	{
		$this->common_db->set("name",$data['name']);
		$this->common_db->set("organization",$data['organization']);
		$this->common_db->set("email",$data['email']);
		if(isset($data['department']))
		{
			$this->common_db->set("department",$data['department']);
		}
		if(isset($data['tel']))
		{
			$this->common_db->set("tel",$data['tel']);
		}
		if(!isset($data['serial_no']))
		{
			$this->common_db->insert("boss_profile");
			return $this->common_db->insert_id();
		}else{
			$this->common_db->where("serial_no",$data['serial_no']);
			$this->common_db->update("boss_profile");
		}
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