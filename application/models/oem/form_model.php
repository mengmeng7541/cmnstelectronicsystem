<?php
class Form_model extends Oem_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('oem_model');
	}
	
	
	//---------------------FORM-------------------------------
	public function add($inputs)
	{
		if(isset($inputs['form_SN']))
		{
			$inputs = array($inputs);
		}
		foreach($inputs as $idx => $data)
		{
			if($idx==0)
			{
				$form_SN = $this->oem_model->add_form($data);
				$form_parent_SN = $form_SN;
			}else{
				$data['form_parent_SN'] = $form_parent_SN;
				$form_SN = $this->oem_model->add_form($data);
			}
			
			foreach((array)$data['form_facility_SN'] as $facility_SN)
			{
				$this->oem_model->add_form_facility_map(array(
					"facility_SN"=>$facility_SN,
					"form_SN"=>$form_SN
				));
			}
		}
		return $form_parent_SN;
	}
	public function update($inputs)
	{
		if(isset($inputs['form_SN']))
		{
			$inputs = array($inputs);
		}
		//檢查主服務是否存在
		$main_form = $this->oem_model->get_form_list(array("form_SN"=>isset($inputs[0]['form_SN'])?$inputs[0]['form_SN']:""))->row_array();
		if(!$main_form || $main_form['form_parent_SN'] != NULL)
		{
			throw new Exception("不可刪除主服務，若需新增主服務，請重新建立新代工單。",ERROR_CODE);
		}
		
		foreach($inputs as $idx => $data)
		{
			//取得要修改的表單
			$form = $this->oem_model->get_form_list(array("form_SN"=>isset($data['form_SN'])?$data['form_SN']:""))->row_array();
			if(!$form)
			{
				var_dump(array("form_SN"=>isset($data['form_SN'])?$data['form_SN']:""));return;
				//代表新增的，非修改
				$data['form_parent_SN'] = $inputs[0]['form_SN'];
				$this->oem_model->add_form($data);
				
				foreach((array)$data['form_facility_SN'] as $facility_SN)
				{
					$this->oem_model->add_form_facility_map(array(
						"facility_SN"=>$facility_SN,
						"form_SN"=>$form_SN
					));
				}
			}else{
				//確認權限
				if(!$this->oem_model->is_super_admin()&&$form['form_admin_ID']!=$this->session->userdata('ID'))
				{
					throw new Exception("權限不足",ERROR_CODE);
				}
				
				$this->oem_model->update_form($data);
			
				$this->oem_model->del_form_facility_map(array("form_SN"=>$data['form_SN']));
				foreach((array)$data['form_facility_SN'] as $facility_SN)
				{
					$this->oem_model->add_form_facility_map(array(
						"facility_SN"=>$facility_SN,
						"form_SN"=>$data['form_SN']
					));
				}
			}
			
		}
	}
	public function del($data)
	{
		
	}
}