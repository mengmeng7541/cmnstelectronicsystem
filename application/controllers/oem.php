<?php
class Oem extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->model('oem_model');

	}
	//----------------------CONFIG---------------------------------
	public function edit_config()
	{
		try{
			$this->is_admin_login();
			
			$admins = $this->oem_model->get_admin_privilege_list(array("privilege"=>"oem_super_admin"))->result_array();
			$this->data['admin_ID'] = sql_column_to_key_value_array($admins,"admin_ID");
			
			$this->load->model('admin_model');
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/edit_config',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function update_config()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->oem_model->is_super_admin())
			{
				throw new Exception("權限不足",ERROR_CODE);
			}
			
			$this->form_validation->set_rules("admin_ID[]","超級管理者","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$privileges = $this->oem_model->get_admin_privilege_list(array("privilege"=>"oem_super_admin"))->result_array();
			foreach($privileges as $privilege)
			{
				$this->oem_model->del_admin_privilege(array("serial_no"=>$privilege['serial_no']));
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			foreach($input_data['admin_ID'] as $admin_ID)
			{
				$this->oem_model->add_admin_privilege(array(
					"admin_ID"=>$admin_ID,
					"privilege"=>"oem_super_admin"
				));
			}
			
			echo $this->info_modal("更新成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}	
	}
	//----------------------APPLICATION----------------------------
	public function list_app()
	{
		try{
			$this->is_user_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/list_app');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function query_app()
	{
		
	}
	public function new_app($form_SN = NULL)
	{
		try{
			$this->is_user_login();
			
			if(isset($form_SN))
			{
				//新的申請單
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('oem/list_form');
				$this->load->view('templates/footer');
			}else{
				//表單列表
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('oem/list_form');
				$this->load->view('templates/footer');
			}
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_app()
	{
		
	}
	public function add_app()
	{
		
	}
	public function update_app()
	{
		
	}
	public function del_app()
	{
		
	}
	//----------------------FORM------------------------
	public function list_form()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/list_form');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function query_form()
	{
		try{
			$this->is_user_login();
			
			$output['aaData'] = array();
			
			$forms = $this->oem_model->get_form_list()->result_array();
			$output['aaData'] = $forms;
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function new_form()
	{
		try{
			$this->is_admin_login();
			
			$this->load->model('facility_model');
			$this->data['facility_SN_select_options'] = $this->facility_model->get_facility_select_options('facility');
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/new_form',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_form($SN = "")
	{
		try{
			$this->is_admin_login();
			
			$form = $this->oem_model->get_form_list(array("form_SN"=>$SN))->row_array();
			if(!$form)
			{
				throw new Exception();
			}
			$this->data = $form;
			
			$maps = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$SN))->result_array();
			$this->data['facility_SN'] = sql_column_to_key_value_array($maps,"facility_SN");
			
			$this->load->model('facility_model');
			$this->data['facility_SN_select_options'] = $this->facility_model->get_facility_select_options('facility');
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/new_form',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_form()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("form_cht_name","代工單中文名稱","required");
			$this->form_validation->set_rules("form_eng_name","代工單英文名稱","required");
			$this->form_validation->set_rules("facility_SN[]","代工單對應儀器","required");
			$this->form_validation->set_rules("form_note","注意事項","required");
			$this->form_validation->set_rules("form_description","預設描述(客戶填寫)","required");
			$this->form_validation->set_rules("form_enable","是否開放代工","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->load->model('oem/form_model');
			$this->form_model->add($input_data);
			
			echo $this->info_modal("新增成功","oem/form/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_form()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("form_SN","表單編號","required");
			$this->form_validation->set_rules("form_cht_name","代工單中文名稱","required");
			$this->form_validation->set_rules("form_eng_name","代工單英文名稱","required");
			$this->form_validation->set_rules("facility_SN[]","代工單對應儀器","required");
			$this->form_validation->set_rules("form_note","注意事項","required");
			$this->form_validation->set_rules("form_description","預設描述(客戶填寫)","required");
			$this->form_validation->set_rules("form_enable","是否開放代工","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->load->model('oem/form_model');
			$this->form_model->update($input_data);
			
			echo $this->info_modal("更新成功","oem/form/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_form()
	{
		
	}
}
?>
