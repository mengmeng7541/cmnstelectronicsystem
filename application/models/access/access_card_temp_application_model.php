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
		//寫入資料
		$insert_id = $this->access_model->add_access_card_temp_application(array(
			"applied_by"=>$this->session->userdata('ID'),
			"application_type"=>$data['application_type'],
			"guest_name"=>$data['guest_name'],
			"guest_mobile"=>$data['guest_mobile'],
			"guest_purpose"=>$data['guest_purpose'],
			"guest_access_start_time"=>$data['guest_access_start_date'].' '.$data['guest_access_start_time'],
			"guest_access_end_time"=>$data['guest_access_end_date'].' '.$data['guest_access_end_time']
		));
		
		//發信通知
	}
	
	public function issue($SN,$card_num = NULL,$issued_by = NULL)
	{
		
	}
	
	public function refund($SN,$refund_by = NULL)
	{
		
	}
}
