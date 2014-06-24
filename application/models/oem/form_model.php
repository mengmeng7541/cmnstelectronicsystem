<?php
class Form_model extends Oem_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('oem_model');
	}
	
	
	//---------------------FORM-------------------------------
	public function add($data)
	{
		$form_SN = $this->oem_model->add_form($data);
		
		foreach((array)$data['facility_SN'] as $facility_SN)
		{
			$this->oem_model->add_form_facility_map(array(
				"facility_SN"=>$facility_SN,
				"form_SN"=>$form_SN
			));
		}
		
		return $form_SN;
	}
	public function update($data)
	{
		//取得要修改的表單
		$form = $this->oem_model->get_form_list(array("form_SN"=>$data['form_SN']))->row_array();
		if(!$form)
		{
			throw new Exception("無此表單",ERROR_CODE);
		}
		//確認權限
		if(!$this->oem_model->is_super_admin()&&$form['form_admin_ID']!=$this->session->userdata('ID'))
		{
			throw new Exception("權限不足",ERROR_CODE);
		}
		
		$this->oem_model->update_form($data);
		
		$this->oem_model->del_form_facility_map(array("form_SN"=>$data['form_SN']));
		foreach((array)$data['facility_SN'] as $facility_SN)
		{
			$this->oem_model->add_form_facility_map(array(
				"facility_SN"=>$facility_SN,
				"form_SN"=>$data['form_SN']
			));
		}
	}
	public function del($data)
	{
		
	}
}
