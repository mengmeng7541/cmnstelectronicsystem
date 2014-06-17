<?php
class Access_card_application_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('access_model');
		$this->load->model('facility_model');
	}
	
	//-------------------VERIFY----------------------
	public function verify($SN)
	{
		$this->load->model('access_model');
		if(!$this->access_model->is_super_admin())
		{
			throw new Exception("沒有權限",ERROR_CODE);
		}
		
		$app = $this->facility_model->get_card_application_list(array("serial_no"=>$SN))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		if(!empty($app['AB_form_verified_by']))
		{
			throw new Exception("此使用者已確認過AB表繳交",ERROR_CODE);
		}
		
		$this->access_model->update_card_application(array(
			"AB_form_verified_by"=>$this->session->userdata('ID'),
			"serial_no"=>$app['serial_no']
		));
	}
	
	
}
