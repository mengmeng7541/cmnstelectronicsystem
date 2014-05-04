<?php
class Reward_model extends MY_Model {
  
  protected $reward_db;
  
	public function __construct()
	{
		parent::__construct();
		$this->reward_db = $this->load->database("reward",TRUE);
	}
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
	public function get_application($input = array())
	{
		$this->reward_db->select("*")->from("Reward_application");
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
  
  public function get_plan()
  {
  	$sql = "SELECT * FROM Reward_plan";
	$query = $this->reward_db->query($sql);
	return $query->result_array();
  }
  public function get_plan_by_SN($SN)
  {
  	$sql = "SELECT * FROM Reward_plan WHERE serial_no = '{$SN}'";
	$query = $this->reward_db->query($sql);
	return $query->row_array();
  }
  
}