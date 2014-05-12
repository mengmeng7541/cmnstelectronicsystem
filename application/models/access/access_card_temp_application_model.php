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
	public function get_type_purpose_array()
	{
		$output = array();
		$types = $this->_get_type_list()->result_array();
		foreach($types as $type){
			$obj = array();
			$obj = $type;
			$purposes = $this->_get_purpose_list($type['type_no'])->result_array();
			foreach($purposes as $purpose){
				$obj['purpose'][] = $purpose;
			}			
			$output[] = $obj;
		}
		return $output;
	}
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
		//自動ASSIGN卡號
		if(!isset($card_num))
		{
			$card = $this->access_model->get_access_card_pool_list(array("occupied"=>0))->row_array();
			if(!$card){
				throw new Exception("已無空卡可用",ERROR_CODE);
			}
			$card_num = $card['access_card_num'];
		}
		
		//取得CP編號
		$cp = $this->access_model->get_enum_access_card_temp_application_checkpoint_list(array("checkpoint_ID"=>"issued"))->row_array();
		//寫入單子
		$this->access_model->update_access_card_temp_application(array(
			"guest_access_card_num"=>$card_num,
			"issued_by"=>isset($issued_by)?$issued_by:$this->session->userdata('ID'),
			"application_checkpoint"=>$cp['checkpoint_no'],
			"serial_no"=>$SN
		));
		//取得單子資料
		$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_array();
		if(!$app){
			throw new Exception("無此單資料");
		}
		//標記已用
		$this->access_model->update_access_card_pool(array("occupied"=>1,"access_card_num"=>$app['card_num']));
		//開啟權限
		$this->load->model('facility/access_ctrl_model');
		$this->access_ctrl_model->open_all_door_by_num($app['guest_access_card_num'],$app['guest_access_start_time'],$app['guest_access_end_time']);
		//回傳開啟的卡號
		return $app['guest_access_card_num'];
	}
	
	public function refund($SN,$refund_by = NULL)
	{
		$cp = $this->access_model->get_enum_access_card_temp_application_checkpoint_list(array("checkpoint_ID"=>"refunded"))->row_array();
		$this->access_model->update_access_card_temp_application(array(
			"refund_by"=>isset($refund_by)?$refund_by:$this->session->userdata('ID'),
			"application_checkpoint"=>$cp['checkpoint_no'],
			"serial_no"=>$SN
		));
		//取得單子資料
		$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_array();
		if(!$app){
			throw new Exception("無此單資料");
		}
		//標記已還
		$this->access_model->update_access_card_pool(array("occupied"=>0,"access_card_num"=>$app['card_num']));
	}
}
