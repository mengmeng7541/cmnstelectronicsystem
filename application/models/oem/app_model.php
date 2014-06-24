<?php
class App_model extends Oem_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('oem_model');
	}
	
	
	//---------------------APP-------------------------------
	public function add($form_SN,$description,$app_type = 'normal')
	{
		$form = $this->oem_model->get_form_list(array("form_SN"=>$form_SN))->row_array();
		if(!$form)
		{
			throw new Exception("無此表單",ERROR_CODE);
		}
		
		return $this->oem_model->add_app(array(
			"form_SN"=>$form_SN,
			"app_type"=>$app_type,
			"app_description"=>$description
			
		));
	}
	public function save($app_SN,$description)
	{
		//for user
	}
	public function update($data)
	{
		
	}
	public function del($data)
	{
		
	}
}
