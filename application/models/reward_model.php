<?php
class Reward_model extends MY_Model {
  
  protected $reward_db;
  
	public function __construct()
	{
		parent::__construct();
		$this->reward_db = $this->load->database("reward",TRUE);
	}
	
  
  public function get_application_by_paper_title($title)
  {
    $query = $this->reward_db->query("SELECT * FROM Reward_application WHERE paper_title='{$title}' ");
    if($query->num_rows())
	{
		return $query->row();
	}else{
		return FALSE;
	}
	 
  }
  
  public function add_application($input_data,$upload_data)
  {
  	
	
    $sql = "INSERT INTO Reward_application (application_date,applicant_name,department,tel,email,research_field,paper_title,journal,journal_year,awardees_no,apply_plan_no,upload_file) VALUES (CURDATE(),?,?,?,?,?,?,?,?,?,?,?) ";
    $val = array($input_data['applicant_name'],$input_data['department'],$input_data['tel'],$input_data['email'],$input_data['research_field'],$input_data['paper_title'],$input_data['journal'],$input_data['journal_year'],$input_data['awardees_no'],$input_data['apply_plan_no'],$upload_data['file_name']);
    $query = $this->reward_db->query($sql,$val);
	return true;
  }
  
  
	/**
	* @serial_no the start serial number
	* @row_num how many rows are requested      
	*/  
	public function get_application_list($input = array())
	{
		$this->reward_db->select("*")->from("Reward_application");
		
		if(isset($input['apply_plan_no']))
		{
			$this->reward_db->where("apply_plan_no",$input['apply_plan_no']);
		}
		
		if(isset($input['serial_no']))
			$this->reward_db->where("serial_no",$input['serial_no']);
		else
			$this->reward_db->order_by("serial_no","DESC");
		
		$query = $this->reward_db->get();
		
		return $query;
	}
  
	public function update_application($input_data)
	{
		$sql = "UPDATE Reward_application SET review_date = CURDATE() ,reviewer_name = ? ,is_review = '1' ,deny_reason = ? ,accept_plan_no = ? ,result = ? WHERE serial_no = ?";
		$val = array($input_data['reviewer_name'],$input_data['deny_reason'],$input_data['accept_plan_no'],$input_data['result'],$input_data['serial_no']);
		$query = $this->reward_db->query($sql,$val);
		return true;
	}
	public function delete_application($SN)
	{
		$sql = "DELETE FROM Reward_application WHERE serial_no = {$SN}";
		$query = $this->reward_db->query($sql);
		return;
	}
  
  //-------------------CONFIG----------------------------
  public function get_admin_by_privilege($privilege)
	{
		$sql = "SELECT * FROM Reward_admin_privilege WHERE privilege = '{$privilege}'";
		$query = $this->reward_db->query($sql);
		
		return $query->row_array();
	}
	public function get_privilege($privilege,$admin_ID = "")
	{
		if(empty($ID))	$ID = $this->session->userdata('ID');
  	
	  	$sql = "SELECT * FROM Reward_admin_privilege WHERE admin_ID = '{$ID}' AND privilege = '{$privilege}'";
		$query = $this->reward_db->query($sql);
		return $query->num_rows();
	}
	
	public function get_admin_privilege_list($options = array())
	{
		if(isset($options['privilege']))
		{
			$this->reward_db->where("privilege",$options['privilege']);
		}
		if(isset($options['admin_ID']))
		{
			$this->reward_db->where("admin_ID",$options['admin_ID']);
		}
		return $this->reward_db->get("Reward_admin_privilege");
	}
	public function is_super_admin($user_ID = NULL)
	{
		if(!isset($user_ID))
		{
			$user_ID = $this->session->userdata('ID');
		}
		return $this->get_admin_privilege_list(array(
			"privilege"=>"reward_super_admin",
			"admin_ID"=>$user_ID
		))->num_rows();
	}
  public function add_admin_privilege($data)
  {
  	foreach((array)$data['admin_ID'] as $admin_ID)
  	{
		$this->reward_db->set("admin_ID",$admin_ID);
	  	$this->reward_db->set("privilege",$data['privilege']);
	  	$this->reward_db->insert("Reward_admin_privilege");
	}
  	
  }
  public function del_admin_privilege($data)
  {
  	$this->reward_db->where("serial_no",$data['serial_no']);
  	$this->reward_db->delete("Reward_admin_privilege");
  }
  
  //--------------------PLAN----------------------------
  public function get_plan_list($options = array())
  {
  	$this->reward_db->select("*");
  	if(isset($options['available']))
  	{
		$this->reward_db->where("available",$options['available']);
	}
	if(isset($options['serial_no']))
	{
		$this->reward_db->where("serial_no",$options['serial_no']);
	}
  	return $this->reward_db->get("Reward_plan");
  }
  public function get_plan_ID_select_options($only_available = 1)
  {
  	$plans = $this->get_plan_list(array("available"=>$only_available?$only_available:NULL))->result_array();
  	$output = array();
  	foreach($plans as $plan)
  	{
		$output[$plan['serial_no']] = $plan['name'];
	}
	return $output;
  }
  public function get_plan_by_SN($SN)
  {
  	$sql = "SELECT * FROM Reward_plan WHERE serial_no = '{$SN}'";
	$query = $this->reward_db->query($sql);
	return $query->row_array();
  }
  public function add_plan($data)
  {
  	return $this->update_plan($data);
  }
  public function update_plan($data)
  {
  	$this->reward_db->set("name",$data['name'])
  					->set("points",$data['points'])
  					->set("available",$data['available']);
  	if(!isset($data['serial_no']))
  	{
		//add
		$this->reward_db->insert("Reward_plan");
		return $this->reward_db->insert_id();
	}else{
		//update
		$this->reward_db->where("serial_no",$data['serial_no']);
		$this->reward_db->update("Reward_plan");
	}
  }
  public function del_plan($data)
  {
  	$this->reward_db->where("serial_no",$data['serial_no']);
  	$this->reward_db->delete("Reward_plan");
  }
}