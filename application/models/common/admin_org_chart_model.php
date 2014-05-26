<?php
class Admin_org_chart_model extends MY_Model {
  
  protected $common_db;
 
  public function __construct()
  {
	$this->common_db = $this->load->database('common',TRUE);
	$this->load->model('admin_model');
  }
  public function get_group_team_status_array()
  {
  	$groups = $this->admin_model->get_enum_org_chart_group_list()->result_array();
			
	$output = array();
	foreach($groups as $group){
		$group_row = array();
		$group_row['group_no'] = $group['group_no'];
		$group_row['group_ID'] = $group['group_no'];
		$group_row['group_name'] = $group['group_name'];
		$teams = $this->admin_model->get_enum_org_chart_team_list(array("group_no"=>$group['group_no']))->result_array();
		
		$group_row['team'] = array();
		foreach($teams as $team)
		{
			$team_row = array();
			$team_row['team_no'] = $team['team_no'];
			$team_row['team_ID'] = $team['team_ID'];
			$team_row['team_name'] = $team['team_name'];
			$group_row['team'][] = $team_row;
		}
		
		
		$statuses = $this->admin_model->get_enum_org_chart_status_list(array("group_no"=>$group['group_no']))->result_array();
		
		$group_row['status'] = array();
		foreach($statuses as $status)
		{
			$status_row = array();
			$status_row['status_no'] = $status['status_no'];
			$status_row['status_ID'] = $status['status_ID'];
			$status_row['status_name'] = $status['status_name'];
			$group_row['status'][] = $status_row;
		}
		
		$output[$group_row['group_no']] = $group_row;
	}
	
	return $output;
  }
}