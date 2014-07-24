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
			
			foreach((array)$data['form_cols'] as $col)
			{
				$col['form_SN'] = $form_SN;
				$this->oem_model->add_form_col($col);
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
				//代表新增的，非修改
				$data['form_parent_SN'] = $inputs[0]['form_SN'];
				$form_SN = $this->oem_model->add_form($data);
				
				foreach((array)$data['form_facility_SN'] as $facility_SN)
				{
					$this->oem_model->add_form_facility_map(array(
						"facility_SN"=>$facility_SN,
						"form_SN"=>$form_SN
					));
				}
				
				foreach((array)$data['form_cols'] as $col)
				{
					$col['form_SN'] = $form_SN;
					$this->oem_model->add_form_col($col);
				}
			}else{
				//確認權限
				if(!$this->oem_model->is_super_admin()&&$form['form_admin_ID']!=$this->session->userdata('ID'))
				{
					throw new Exception("權限不足",ERROR_CODE);
				}
				
				$this->oem_model->update_form($data);
			
				$this->oem_model->del_form_facility_map(array("form_SN"=>$form['form_SN']));
				foreach((array)$data['form_facility_SN'] as $facility_SN)
				{
					$this->oem_model->add_form_facility_map(array(
						"facility_SN"=>$facility_SN,
						"form_SN"=>$form['form_SN']
					));
				}
				
				foreach((array)$data['form_cols'] as $col)
				{
					if(empty($col['form_col_SN']))
					{
						//新增的欄位
						$col['form_SN'] = $form['form_SN'];
						$this->oem_model->add_form_col($col);
					}else{
						//變更的欄位
						$this->oem_model->update_form_col($col);
					}
				}
			}
		}
	}
	public function del($data)
	{
		
	}
	//--------------------GET-----------------------
	public function get_vertical_group_forms($form_SN)
	{
		$form = $this->oem_model->get_form_list(array("form_SN"=>$form_SN))->row_array();
		if(!$form)
		{
			return array();
		}
		if($form['form_parent_SN'])
		{
			$form = $this->oem_model->get_form_list(array("form_SN"=>$form['form_parent_SN']))->result_array();
			$forms = $this->oem_model->get_form_list(array("form_parent_SN"=>$form['form_parent_SN']))->result_array();
			return array_merge($form,$forms);
		}else{
			$forms = $this->oem_model->get_form_list(array("form_parent_SN"=>$form['form_SN']))->result_array();
			return $forms?array_merge(array($form),$forms):array($form);
		}
	}
	public function get_facility_admin_profile($form_SN)
	{
		$form = $this->oem_model->get_form_list(array("form_SN"=>$form_SN))->row_array();
		
		if(isset($form['form_parent_SN']))
		{
			$parent_form_facility_map = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$form['form_parent_SN']))->result_array();
		}
		$form_facility_map = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$form['form_SN']))->result_array();
		if(isset($parent_form_facility_map))
		{
			$form_facility_map = array_merge($form_facility_map,$parent_form_facility_map);
		}
		$this->load->model('facility_model');
		$admin_privileges = $this->facility_model->get_user_privilege_list(array(
			"facility_ID"=>array_unique(sql_column_to_key_value_array($form_facility_map,"facility_SN"))
		))->result_array();
		return $admin_privileges;
	}
}
