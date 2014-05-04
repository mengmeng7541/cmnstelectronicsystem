<?php
class Common_model extends MY_Model {
	protected $common_db;

	public function __construct()
	{
		parent::__construct();
		$this->common_db = $this->load->database("common",TRUE);
	}
	//------------位置----------------
	public function get_location_list($options = array())
	{
		if(isset($options['location_ID']))
			$this->common_db->where("location_ID",$options['location_ID']);
		return $this->common_db->get("location");
	}
	public function get_location_ID_select_options($column = 'location_ID')
	{
		$results = $this->get_location_list()->result_array();
		$output = array(""=>"");
		foreach($results as $result)
		{
			$output[$result[$column]] = $result['location_cht_name'];
		}
		return $output;
	}
	
}
