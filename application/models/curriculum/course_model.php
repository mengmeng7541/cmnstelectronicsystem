<?php
class Course_model extends MY_Model {
  
	protected $curriculum_db;
	protected $common_db;

	public function __construct()
	{
		parent::__construct();
		$this->curriculum_db = $this->load->database('curriculum',TRUE);
		$this->common_db = $this->load->database('common',TRUE);
		
		$this->load->model('curriculum_model');
	}
	//course
	public function get_course_list_table($options = array())
	{
		$sTable = "course_list";
		$sJoinTable = array("cf_map"=>"course_facility_map","facility"=>"cmnst_facility.facility_list");
		$this->curriculum_db->select(	"$sTable.course_ID,
										 $sTable.course_cht_name AS course_cht_name,
										 $sTable.course_eng_name AS course_eng_name,
										 GROUP_CONCAT({$sJoinTable['facility']}.cht_name SEPARATOR ',' ) AS facility_cht_name,
										 GROUP_CONCAT({$sJoinTable['facility']}.eng_name SEPARATOR ',' ) AS facility_eng_name,
										 IFNULL({$sJoinTable['cf_map']}.course_ID,$sTable.course_ID) AS uni_course_ID",FALSE)
							->join($sJoinTable['cf_map'],"{$sJoinTable['cf_map']}.course_ID = $sTable.course_ID","LEFT")
							->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sJoinTable['cf_map']}.facility_ID","LEFT")
							->group_by("uni_course_ID");
		return $this->curriculum_db->get($sTable);
	}
	
	public function get_course_ID_select_options()
	{
		$courses = $this->curriculum_model->get_course_list()->result_array();
		$select_options = array(""=>"");
		foreach($courses as $c)
		{
			$select_options[$c['course_ID']] = $c['course_cht_name']." (".$c['course_eng_name'].")";
		}
		return $select_options;
	}
	//取得課程對應之儀器ID
	public function get_course_map_facility_ID($course_ID)
	{
		$this->curriculum_db->select("course_facility_map.facility_ID");
		$this->curriculum_db->join("course_facility_map","course_facility_map.course_ID = course_list.course_ID");
		$this->curriculum_db->where("course_list.course_ID",$course_ID);
		$results = $this->curriculum_db->get("course_list")->result_array();
		if($results){
			return sql_result_to_column($results,"facility_ID");
		}else{
			return array();
		}
	}
	//取得課程對應之儀器列表[facility_ID]=>name
	public function get_facility_ID_select_options($course_ID)
	{
		
		if($course_ID == 1)//安講
		{
			return array();
		}
		$facilities_ID = $this->get_course_map_facility_ID($course_ID);
		$this->load->model('facility_model');
		$facilities = $this->facility_model->get_facility_list(array("ID"=>$facilities_ID))->result_array();		
		$select_optnios = array();
		foreach($facilities as $facility)
		{
			$select_optnios[$facility['ID']] = $facility['cht_name']." (".$facility['eng_name'].")";
		}
		return $select_optnios;
	}
	//取得該課程可授課教授名單[user_ID]=>name
	public function get_professor_ID_select_options($course_ID)
	{

			$facility_ID = $this->get_course_map_facility_ID($course_ID);
			if(empty($facility_ID)){
				$this->load->model('admin_model');
				return $this->admin_model->get_admin_ID_select_options();
			}else{
				$privilege = array("admin","super");
				$this->load->model('facility/user_privilege_model');
				return $this->user_privilege_model->get_available_users($facility_ID,$privilege);
			}
		
	}
}