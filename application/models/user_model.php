<?php
class User_model extends MY_Model {
  protected $common_db;
  protected $order_class;//mssql
  protected $clock_db;
  protected $facility_db;
  public function __construct()
  {
    parent::__construct();
	$this->common_db = $this->load->database("common",TRUE);
	$this->order_class = $this->load->database("order_class",TRUE);
	$this->clock_db =  $this->load->database("clock",TRUE);
	$this->facility_db = $this->load->database("facility",TRUE);
  }
  //for old db----------------------------------
  public function add_user_for_old_db($input_data)
  {
	$sql = "INSERT INTO Iuser VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; 
	$arr = array($input_data['ID'],$input_data['name'],$input_data['sex'],$input_data['tel'],$input_data['mobile'],$input_data['address'],$input_data['email'],$input_data['status'],$input_data['boss_name'],$input_data['department'],$input_data['passwd'],'N',$input_data['organization'],'no','N',NULL,1,NULL,NULL,NULL,NULL);
	$query = $this->order_class->query($sql,$arr);
	return $this->order_class->affected_rows();
  }
  public function update_user_for_old_db($input_data)
  {
  	$sql = "UPDATE Iuser 
			SET 姓名 = '{$input_data['name']}',
				電話 = '{$input_data['tel']}',
				行動電話 = '{$input_data['mobile']}',
				通訊地址 = '{$input_data['address']}',
				電子郵件地址 = '{$input_data['email']}',
				性別 = '{$input_data['sex']}',
				身份別 = '{$input_data['status']}',
				指導老師姓名 = '{$input_data['boss_name']}',
				校別 = '{$input_data['organization']}',
				服務單位 = '{$input_data['department']}' 
			WHERE 身分證字號 = '{$input_data['ID']}'"; 
	$sql = @ICONV("UTF-8","BIG5//IGNORE",$sql);
	$query = $this->order_class->query($sql);
	return $this->order_class->affected_rows();
  }
  public function add_boss_for_old_db($input_data)
  {
  	$sql = "SET IDENTITY_INSERT teacher ON
			INSERT INTO teacher (teach_mail,teach_name,teach_school,teach_apartment,teach_tel,flownum) VALUES (?,?,?,?,?,?)
			SET IDENTITY_INSERT teacher OFF";
	$arr = array($input_data['email'],$input_data['boss_name'],$input_data['boss_organization'],$input_data['department'],$input_data['tel'],$input_data['boss_no']);
	$query = $this->order_class->query($sql,$arr);
	
  }
  public function add_org_for_old_db($SN,$org)
  {
  	$sql = "SET IDENTITY_INSERT school ON
			INSERT INTO school (school_no,school_name,school_area)
			VALUES (?,?,?)
			SET IDENTITY_INSERT school OFF";
			
	$arr = array($SN,$org,NULL);
	$query = $this->order_class->query($sql,$arr);
	return $this->order_class->affected_rows();
  }
  
  //--------------------------------------------
  
  //USER
  public function get_user_profile_list($input_data = array())
  {
  	$sTable = "user_profile";
  	$sJoinTable = array("org"=>"organization","boss"=>"boss_profile");
  	$this->common_db->select("{$sTable}.*,
  							  {$sJoinTable['org']}.name AS org_name,
  							  {$sJoinTable['boss']}.name AS boss_name,
  							  {$sJoinTable['boss']}.email AS boss_email,")
  					->from($sTable)
  					->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = {$sTable}.organization","LEFT")
  					->join($sJoinTable['boss'],"{$sJoinTable['boss']}.serial_no = {$sTable}.boss_no","LEFT");
  	if(!empty($input_data['user_ID']))
		$this->common_db->where_in("{$sTable}.ID",$input_data['user_ID']);
	if(!empty($input_data['passwd']))
		$this->common_db->where("{$sTable}.passwd",$input_data['passwd']);
	if(!empty($input_data['group']))
		$this->common_db->where("{$sTable}.group",$input_data['group']);
	if(!empty($input_data['user_email']))
		$this->common_db->where("{$sTable}.email",$input_data['user_email']);
	if(!empty($input_data['user_card_num']))
		$this->common_db->where("{$sTable}.card_num",$input_data['user_card_num']);
	if(isset($input_data['boss_no']))
	{
		$this->common_db->where("$sTable.boss_no",$input_data['boss_no']);
	}
	if(isset($input_data['organization']))
	{
		$this->common_db->where("$sTable.organization",$input_data['organization']);
	}
  	return $this->common_db->get();
  }
  public function get_user_status_select_options()
  {
  	$results = $this->common_db->get("constant_user_status")->result_array();
  	$output = array(""=>"");
  	foreach($results as $result)
  	{
		$output[$result['status_no']] = $result['status_name'];
	}
	return $output;
  }
  public function get_user_profile_by_ID($ID)
  {
    $sql = "SELECT * FROM user_profile WHERE ID = '{$ID}'";
    $query = $this->common_db->query($sql);
    return $query->row_array();
  }
  public function get_user_profile_by_email($user_email)
  {
  	$sql = "SELECT * FROM user_profile WHERE email = '{$user_email}'";
	$query = $this->common_db->query($sql);
	return $query->row_array();
  }
  public function get_user_profile_by_card_num($card_num)
  {
  	$sql = "SELECT * FROM user_profile WHERE card_num = '{$card_num}'";
	$query = $this->common_db->query($sql);
	return $query->row_array();
  }
  public function get_user_ID_select_options($options = array())
  {
  	$user_profiles = $this->get_user_profile_list()->result_array();
  	$output = array(""=>"");
  	foreach($user_profiles as $user_profile)
  	{
  		$output[$user_profile['ID']] = "{$user_profile['name']} ({$user_profile['ID']})";
	}
	return $output;
  }
  public function get_user_list_JSON($input_data)
  {
  	$sTable = "user_profile";
  	$sJoinTable = array("org"=>"organization","boss"=>"boss_profile");
	
  	$aColumns = array(  "$sTable.ID"=>"ID", 
  						"$sTable.name"=>"name",
  						"$sTable.mobile"=>"mobile",
  						"$sTable.email"=>"email",
  						"$sTable.card_num"=>"card_num",
  						"{$sJoinTable['org']}.name"=>"org_name",
  						"{$sJoinTable['boss']}.name"=>"boss_name");
	
	$sJoin = "LEFT OUTER JOIN {$sJoinTable['org']} ON $sTable.organization = {$sJoinTable['org']}.serial_no
			  LEFT OUTER JOIN {$sJoinTable['boss']} ON $sTable.boss_no = {$sJoinTable['boss']}.serial_no";
	
	$result = $this->get_jQ_DTs_json_with_join($this->common_db,$sTable,$sJoin,$aColumns,$input_data);
	return $result;
  }
  public function add_user($input_data)
  {
  	$this->common_db->set("ID",$input_data['ID']);
  	$this->common_db->set("passwd",$input_data['passwd']);
	$this->common_db->set("name",$input_data['name']);
	$this->common_db->set("mobile",$input_data['mobile']);
	$this->common_db->set("email",$input_data['email']);
	if(isset($input_data['tel']))
		$this->common_db->set("tel",$input_data['tel']);
	if(isset($input_data['address']))
		$this->common_db->set("address",$input_data['address']);
	if(isset($input_data['sex']))
		$this->common_db->set("sex",$input_data['sex']);
	if(isset($input_data['status']))
		$this->common_db->set("status",$input_data['status']);
	if(isset($input_data['boss_no']))
		$this->common_db->set("boss_no",$input_data['boss_no']);
	if(isset($input_data['organization']))
		$this->common_db->set("organization",$input_data['organization']);
	if(isset($input_data['department']))
		$this->common_db->set("department",$input_data['department']);
	$this->common_db->set("enable_date","CURDATE()",FALSE);
	$this->common_db->set("group",empty($input_data['group'])?"normal":$input_data['group']);
	$this->common_db->insert("user_profile");
	return $this->common_db->affected_rows();
  }
  public function delete_user($ID)
  {
  	
  }
	public function update_user_profile($input_data)
	{
		$this->common_db->set("name",$input_data['name']);
		$this->common_db->set("tel",$input_data['tel']);
		$this->common_db->set("mobile",$input_data['mobile']);
		$this->common_db->set("address",$input_data['address']);
		$this->common_db->set("email",$input_data['email']);
		$this->common_db->set("sex",$input_data['sex']);
		$this->common_db->set("status",$input_data['status']);
		if(isset($input_data['card_num']))
		{
			$this->common_db->set("card_num",$input_data['card_num']);
		}
		$this->common_db->set("boss_no",$input_data['boss_no']);
		$this->common_db->set("organization",$input_data['organization']);
		$this->common_db->set("department",$input_data['department']);
		$this->common_db->where("ID",$input_data['ID']);
		$this->common_db->update("user_profile");
		return $this->common_db->affected_rows();
	}
	public function update_user_card_num($ID,$card_num = NULL)
	{
		$this->common_db->set("card_num",$card_num)
						->where("ID",$ID)
						->update("user_profile");
	}
	public function update_user_security_verification($user_ID,$varification = 1)
	{
		$this->common_db->set("security_verified",$varification)
						->where("ID",$user_ID)
						->update("user_profile");
		return $this->common_db->affected_rows();
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
	public function get_boss_ID_select_options()
  {
  	$bosses = $this->get_boss_list()->result_array();
  	$output = array();
  	foreach($bosses as $boss)
  	{
		$output[$boss['serial_no']] = $boss['name'];
	}
	echo $output;
  }
  public function get_boss_by_SN($SN)
  {
  	$sql = "SELECT * FROM boss_profile WHERE serial_no = '{$SN}'";
	$query = $this->common_db->query($sql);
	return $query->row_array();
  }
  public function get_boss_no_by_name($name)
  {
  	$sql = "SELECT * FROM boss_profile WHERE name = '{$name}'";
	$query = $this->common_db->query($sql);
	if($query->num_rows()){
		return $result = $query->row_array();
	}else{
		return FALSE;
	}
  }
	public function add_boss($data)
	{
		return $this->update_boss($data);
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
	public function del_boss($data)
	{
		$this->common_db->where("serial_no",$data['serial_no']);
		$this->common_db->delete("boss_profile");
	}
  
  //ORGINIZATION
  public function add_org($data)
  {
  	return $this->update_org($data);
  }
  public function update_org($data)
  {
  	$this->common_db->set("name",$data['name']);
  	if(isset($data['VAT']))
  	{
		$this->common_db->set("VAT",$data['VAT']);
	}
  	if(isset($data['address']))
  	{
		$this->common_db->set("address",$data['address']);
	}
  	if(isset($data['tel']))
  	{
		$this->common_db->set("tel",$data['tel']);
	}
	if(isset($data['status_ID']))
	{
		$this->common_db->set("status_ID",$data['status_ID']);
	}
	
  	if(isset($data['aliance_no']))
  	{
		$this->common_db->set("aliance_no",$data['aliance_no']);
	}
  	if(!isset($data['serial_no']))
  	{
		$this->common_db->insert("organization");
  		return $this->common_db->insert_id();
	}else{
		$this->common_db->where("serial_no",$data['serial_no']);
  		$this->common_db->update("organization");
	}
  	
  }
  public function del_org($data)
  {
  	$this->common_db->where("serial_no",$data['serial_no']);
  	$this->common_db->delete("organization");
  }
  public function get_org_list($options = array())
  {
  	$sTable = "organization";
  	$sJoinTable = array("constant_accounting"=>"cmnst_database_constants.cmnst_accounting","constant_org"=>"constant_org_status");
  	$this->common_db->select("
  		$sTable.*,
  		{$sJoinTable['constant_org']}.status_name AS status_name,
  		{$sJoinTable['constant_accounting']}.comment AS aliance_name
  	");
  	$this->common_db->from($sTable);
  	$this->common_db->join($sJoinTable['constant_org'],"$sTable.status_ID = {$sJoinTable['constant_org']}.status_ID","LEFT");
  	$this->common_db->join($sJoinTable['constant_accounting'],"$sTable.aliance_no = {$sJoinTable['constant_accounting']}.constant AND {$sJoinTable['constant_accounting']}.table = 'aliance_discount' AND {$sJoinTable['constant_accounting']}.column = 'aliance_type'","LEFT");
  	if(isset($options['serial_no']))
  	{
		$this->common_db->where("serial_no",$options['serial_no']);
	}
	if(isset($options['name']))
	{
		$this->common_db->where("name",$options['name']);
	}
	$query = $this->common_db->get();
	return $query;
  }
  public function get_org_ID_select_options()
  {
  	$orgs = $this->get_org_list()->result_array();
	$org_select_options = array(""=>"");
	foreach($orgs as $org)
	{
		$org_select_options[$org['serial_no']] = $org['name'];
	}
	return $org_select_options;
  }
  public function get_org_by_SN($SN)
  {
  	$sql = "SELECT * FROM organization WHERE serial_no = '{$SN}'";
	$query = $this->common_db->query($sql);
	return $query->row_array();
  }
  public function get_org_by_name($name)
  {
  	$sql = "SELECT * FROM organization WHERE name = '{$name}'";
	$query = $this->common_db->query($sql);
	return $query->row_array();
  }
  public function get_org_name()
  {
  	$sql = "SELECT name FROM organization";
	$query = $this->common_db->query($sql);
	return $query->result_array();
  }
  public function get_org_status_ID_select_options()
  {
  	$results = $this->common_db->get("constant_org_status")->result_array();
  	$output = array();
  	foreach($results as $result)
  	{
		$output[$result['status_ID']] = $result['status_name'];
	}
	return $output;
  }
	
  //---------------------aliance------------------------
  public function get_aliance_no_select_options()
  {
  	$sTable = "cmnst_database_constants.cmnst_accounting";
  	$this->common_db->select("$sTable.constant,$sTable.comment")
  					->from($sTable);
  	$this->common_db->where("$sTable.table","aliance_discount");
  	$this->common_db->where("$sTable.column","aliance_type");
  	$results = $this->common_db->get()->result_array();
  	$output = array(""=>"無");
  	foreach($results as $result)
  	{
		$output[$result['constant']] = $result['comment'];
	}
	return $output;
  }
	//---------------------自動打卡------------------------
	public function get_clock_list($options = array())
	{
		$sTable = "cmnst_facility.Card";
		$sJoinTable = array("user"=>"cmnst_common.user_profile","facility"=>"cmnst_facility.facility_list","location"=>"cmnst_common.location");
		$this->clock_db->select("
			card.Status AS access_status,
			MAX(TIMESTAMP(card.FDate,card.FTime)) AS access_last_datetime,
			MIN(TIMESTAMP(card.FDate,card.FTime)) AS access_first_datetime,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['user']}.mobile AS user_mobile,
			{$sJoinTable['user']}.card_num AS user_card_num,
			{$sJoinTable['facility']}.parent_ID AS facility_parent_ID,
			{$sJoinTable['facility']}.location_ID AS location_ID,
			{$sJoinTable['location']}.location_cht_name AS location_cht_name
			FROM
			(SELECT * FROM $sTable WHERE TIMESTAMP(FDate,FTime) > NOW() - INTERVAL 7 DAY ORDER BY FDate DESC, FTime DESC) card
		",FALSE)
					   ->join($sJoinTable['user'],"card.CardNo = {$sJoinTable['user']}.card_num")
					   ->join($sJoinTable['facility'],"card.CtrlNo = {$sJoinTable['facility']}.ctrl_no","LEFT")
					   ->join($sJoinTable['location'],"{$sJoinTable['facility']}.location_ID = {$sJoinTable['location']}.location_ID","LEFT")
					   ->join("(SELECT MAX(TIMESTAMP(FDate,FTime)) AS access_out_last_datetime,CardNo FROM $sTable WHERE Status='01' GROUP BY CardNo) temp_card","temp_card.CardNo = card.CardNo","LEFT")
					   ->group_by("card.CardNo");
		$this->clock_db->where("TIMESTAMP(card.FDate,card.FTime) >","temp_card.access_out_last_datetime",FALSE);
		if(isset($options['location_ID']))
			$this->clock_db->having("location_ID",$options['location_ID']);
//					   ->having("card.Status","00");
		
		$query = $this->clock_db->get();
		echo $this->clock_db->last_query();
		return $query;
		//$this->clock_db->get("clock_user_manual");
	}
}
