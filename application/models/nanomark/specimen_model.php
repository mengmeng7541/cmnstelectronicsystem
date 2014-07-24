<?php
class Specimen_model extends MY_Model {
	protected $nanomark_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->nanomark_db = $this->load->database('nanomark',TRUE);
		$this->load->model('nanomark_model');
	}
	
	public function get_engineer_ID_select_options($test_item_SN)
	{
		$output = array();
		$test_item = $this->nanomark_model->get_test_item_list(array("serial_no"=>$test_item_SN))->row_array();
		if(!$test_item) return $output;
		$this->load->model('facility_model');
		$output = $this->facility_model->get_user_privilege_list(array(
			"facility_ID"=>$test_item['facility_ID'],
			"privilege"=>array("admin","super")
		))->result_array();
		return sql_column_to_key_value_array($output,"user_name","user_ID");
	}
	
	public function get_test_item_select_options()
	{
		$test_items = $this->nanomark_model->get_test_item_list()->result_array();
		$test_item_select_options = array(""=>"");
		foreach($test_items as $row)
		{
			$test_item_select_options[$row['serial_no']] = $row['name'];
		}
		return $test_item_select_options;
	}
}