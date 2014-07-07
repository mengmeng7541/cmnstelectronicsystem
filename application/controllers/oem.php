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
		try{
			$this->is_user_login();
			
			$output['aaData'] = array();
			
			$input_data = $this->input->get(NULL,TRUE);
			
			
			$apps = $this->oem_model->get_app_list($input_data)->result_array();
			$output['aaData'] = $apps;
//			foreach($apps as $app)
//			{
//				$row = array();
//				$row[] = $app['app_SN'];
//				$row[] = "{$app['form_cht_name']} ({$app['form_eng_name']})";
//				$row[] = $app['user_name'];
//				$row[] = $app['org_name'];
//				$row[] = $app['app_SN'];
//				$display = array();
//				$display[] = anchor("oem/app/edit/{$app['app_SN']}","審核","class='btn btn-primary'");
//				$row[] = implode(' ',$display);
//				$output['aaData'][] = $row;
//			}
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function new_app($form_SN = NULL)
	{
		try{
			$this->is_user_login();
			
			$form_SN = $this->security->xss_clean($form_SN);
			
			if(!empty($form_SN))
			{
				//新的申請單
				$form = $this->oem_model->get_form_list(array("form_SN"=>$form_SN))->row_array();
				
				if(!$form){
					throw new Exception();
				}
				
				$this->data = $form;
				
				//取得申請人資料
				$this->load->model('user_model');
				$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$this->session->userdata('ID')))->row_array();
				$this->data['user_name'] = $user_profile['name'];
				$this->data['user_email'] = $user_profile['email'];
				$this->data['user_mobile'] = $user_profile['mobile'];
				$this->data['user_department'] = $user_profile['department'];
				$this->data['org_name'] = $user_profile['org_name'];
				$this->data['boss_name'] = $user_profile['boss_name'];
				
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('oem/new_app',$this->data);
				$this->load->view('templates/footer');
			}else{
				$this->data['mode'] = "app";
				
				//申請單列表
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('oem/list_form',$this->data);
				$this->load->view('templates/footer');
			}
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_app($SN = "")
	{
		try{
			$this->is_user_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->oem_model->get_app_list(array("app_SN"=>$SN))->row_array();
			if(!$app){
				throw new Exception();
			}
			$this->data = $app;
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/new_app',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_app()
	{
		try{
			$this->is_user_login();
			
			$this->form_validation->set_rules("form_SN","表單編號","required");
			$this->form_validation->set_rules("app_description","代工需求","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->load->model('oem/app_model');
			$this->app_model->add($input_data['form_SN'],$input_data['app_description']);
			
			echo $this->info_modal("申請成功","oem/app/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_app()
	{
		try{
			$this->is_user_login();
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("app_SN","代工單號","required");
			$this->form_validation->set_rules("action_btn","動作","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),ERROR_CODE);
			}
			$app = $this->oem_model->get_app_list(array("app_SN"))->row_array();
			if(!$app)
			{
				throw new Exception("無此代工單",ERROR_CODE);
			}
			
			$this->load->model('oem/app_model');
			switch($app['app_checkpoint'])
			{
				case 'user_init':
					if($input_data['action_btn']=='save')
					{
						$this->app_model->save($app['app_SN'],$input_data['app_description']);
					}else if($input_data['action_btn']=='submit'){
						$this->app_model->submit($app['app_SN'],$input_data['app_description']);
					}
					break;
				case 'facility_admin_init':
				case 'common_lab_section_chief':
				case 'user_boss':
					$this->app_model->confirm($app['app_SN'],$this->session->userdata('ID'),$input_data['checkpoint_comment'],$input_data['action_btn']);
					break;
				case 'common_lab_deputy_section_chief':
					break;
				case 'facility_admin_final':
					break;
				case 'user_final':
					break;
				case 'completed':
					break;
				default:
					throw new Exception("未知的狀態",ERROR_CODE);
			}
			
			echo $this->info_modal("審核成功","oem/app/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
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
			
			$input_data = $this->input->get(NULL,TRUE);
			$forms = $this->oem_model->get_form_list($input_data)->result_array();
			
			//TEST
			foreach($forms as $key => $form)
			{
				$facilities = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$form['form_SN']))->result_array();
				$forms[$key]['form_facility_SN'] = sql_column_to_key_value_array($facilities,"facility_SN");
			}
			
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
			
			$this->load->model('admin_model');
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			
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
			
			$this->load->model('admin_model');
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			
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
			
			$input = file_get_contents('php://input');
			$forms = json_decode($input,TRUE);

			foreach($forms as $form)
			{
				$_POST = $form;//HACK for CI form_validation
				$this->form_validation->set_rules("form_cht_name","代工單中文名稱","required");
				$this->form_validation->set_rules("form_eng_name","代工單英文名稱","required");
				$this->form_validation->set_rules("form_facility_SN[]","代工單對應儀器","required");
				$this->form_validation->set_rules("form_note","注意事項","required");
				$this->form_validation->set_rules("form_description","預設描述(客戶填寫)","required");
				$this->form_validation->set_rules("form_enable","是否開放代工","required");
				$this->form_validation->set_rules("form_admin_ID","代工單管理員","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
			}
			$this->load->model('oem/form_model');
			$this->form_model->add($forms);
			
			echo json_encode($this->get_info_modal_array("新增成功","oem/form/list"));
		}catch(Exception $e){
			echo json_encode($this->get_info_modal_array($e->getMessage(),"",$e->getCode()));
		}
	}
	public function update_form()
	{
		try{
			$this->is_admin_login();
			
			$input = file_get_contents('php://input');
			$forms = json_decode($input,TRUE);
			
			foreach($forms as $form)
			{
				$_POST = $form;//HACK for CI form_validation
				$this->form_validation->set_rules("form_SN","表單編號","required");
				$this->form_validation->set_rules("form_cht_name","代工單中文名稱","required");
				$this->form_validation->set_rules("form_eng_name","代工單英文名稱","required");
				$this->form_validation->set_rules("facility_SN[]","代工單對應儀器","required");
				$this->form_validation->set_rules("form_note","注意事項","required");
				$this->form_validation->set_rules("form_description","預設描述(客戶填寫)","required");
				$this->form_validation->set_rules("form_enable","是否開放代工","required");
				$this->form_validation->set_rules("form_admin_ID","代工單管理員","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
			}
			$this->load->model('oem/form_model');
			$this->form_model->update($input_data);
			
			echo json_encode($this->info_modal("更新成功","oem/form/list"));
		}catch(Exception $e){
			echo json_encode($this->info_modal($e->getMessage(),"",$e->getCode()));
		}
	}
	public function del_form()
	{
		
	}
	//--------------------FORM FACILITY MAP-------------------------
//	public function query_form_facility_map()
//	{
//		try{
//			$this->is_admin_login();
//			$output = array();
//			
//			$maps = $this->oem_model->get_form_facility_map_list()->result_array();
//			$output = $maps;
//			
//			echo json_encode($output);
//		}catch(Exception $e){
//			echo json_encode($output);
//		}
//	}
}
?>
