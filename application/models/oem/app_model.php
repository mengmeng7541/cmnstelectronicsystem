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
		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		$this->oem_model->update_app(array(
			"app_description"=>$description,
			"app_checkpoint"=>"",
			"app_SN"=>$app['app_SN']
		));
	}
	public function submit($app_SN,$description)
	{
		//for user
		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		$this->oem_model->update_app(array(
			"app_description"=>$description,
			"app_checkpoint"=>"facility_admin_init",
			"app_SN"=>$app['app_SN']
		));
	}
	public function confirm($app_SN,$admin_ID,$comment,$action)
	{
		//for Admin
		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		//確認權限
		switch($app['app_checkpoint'])
		{
			case 'facility_admin_init':
				//代工單管理員或其技術長
				if($app['form_admin_ID']!=$admin_ID){
					throw new Exception("權限不足",ERROR_CODE);
				}
				break;
			case 'common_lab_deputy_section_chief':
				$this->load->model('admin_model');
				$privilege = $this->admin_model->get_org_chart_list(array(
					"admin_ID"=>$admin_ID,
					"team_ID"=>"common_lab",
					"status_ID"=>"deputy_section_chief"
				))->row_array();
				if(!$privilege){
					throw new Exception("權限不足",ERROR_CODE);
				}
				break;
			case 'user_boss':
				//由controller控制
				break;
			default:
				throw new Exception("未知的狀態",ERROR_CODE);
		}
		
		$this->oem_model->add_app_checkpoint(array(
			"app_SN"=>$app['app_SN'],
			"checkpoint_ID"=>$app['app_checkpoint'],
			"checkpoint_admin_ID"=>$admin_ID,
			"checkpoint_comment"=>$comment
		));
		
		if($action=="accept")
		{
			$next_cp = array(
				"facility_admin_init"=>"common_lab_deputy_section_chief",
				"common_lab_deputy_section_chief"=>"user_boss",
				"user_boss"=>"facility_admin_final",
//				"facility_admin_final"=>"customer_final",
			);
			$this->oem_model->update_app(array(
				"app_checkpoint"=>$next_cp[$app['app_checkpoint']],
				"app_SN"=>$app['app_SN']
			));
		}
		else if($action=="reject")
		{
			$this->oem_model->update_app(array(
				"app_checkpoint"=>"rejected",
				"app_SN"=>$app['app_SN']
			));
		}
	}
	public function del($data)
	{
		
	}
}
