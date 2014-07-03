<?php
class App_model extends MY_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('nanomark_model');
	}
	
	public function del($ID = "")
	{
		if(!$this->nanomark_model->is_super_admin())
		{
			throw new Exception("權限不足",ERROR_CODE);
		}
		
		$app = $this->nanomark_model->get_application_list(array("serial_no"=>$ID))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		$this->nanomark_model->update_application(array(
			"serial_no"=>$app['serial_no'],
			"canceled_by"=>$this->session->userdata('ID'),
			"checkpoint"=>"Canceled"
		));
	}
}