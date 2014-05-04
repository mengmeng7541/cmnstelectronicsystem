<?php
class Test_item_model extends MY_Model {
	protected $nanomark_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->nanomark_db = $this->load->database('nanomark',TRUE);
		$this->load->model('nanomark_model');
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