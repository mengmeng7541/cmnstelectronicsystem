<?php
class Access_card_temp_application_model extends MY_Model {
	protected $access_db;

	public function __construct()
	{
		parent::__construct();
		$this->access_db = $this->load->database("access",TRUE);
		$this->load->model('access_model');
	}
	
	private function _get_type_list($type_no = NULL)
	{
		if(isset($type_no))
		{
			$this->access_db->where("type_no",$type_no);
		}
		return $this->access_db->get("enum_access_card_temp_application_type");
	}
	
	private function _get_purpose_list($type_no = NULL)
	{
		if(isset($type_no))
		{
			$this->access_db->where("type_no",$type_no);
		}
		return $this->access_db->get("enum_access_card_temp_application_purpose");
	}
	
	//select options
	public function get_purpose_select_option_array($type_no = NULL)
	{
		$types = $this->_get_type_list($type_no)->result_array();
		
		$output = array();
		foreach($types as $type){
			$purposes = $this->_get_purpose_list($type['type_no'])->result_array();
			foreach($purposes as $purpose)
			{
				$output[$type['type_name']][$purpose['purpose_no']] = $purpose['purpose_name'];
			}
		}
		
		return $output;
	}
	
	//
	public function apply($data)
	{
		
		$insert_id = $this->access_model->add_access_card_temp_application(array(
			
		));
	}
	
	public function issue($SN,$card_num = NULL,$issued_by = NULL)
	{
		
	}
	
	public function refund($SN,$refund_by = NULL)
	{
		
	}
}
