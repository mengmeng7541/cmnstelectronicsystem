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
			
			$output['aaData'] = array();
			
			$input_data = $this->input->get(NULL,TRUE);
			
			if(isset($input_data['app_token']))
			{
				$this->query_app_no_login();
				return;
			}
			
			$this->is_user_login();
			
			if(!$this->is_admin_login(FALSE)){
				//只能看自己的
				$input_data['app_user_ID'] = $this->session->userdata('ID');
			}
			
			$apps = $this->oem_model->get_app_list($input_data)->result_array();
			
			
			//get column
			foreach($apps as $key => $app){
				$cols = $this->oem_model->get_app_col_list(array("app_SN"=>$app['app_SN']))->result_array();
				$apps[$key]['app_cols'] = $cols;
			}
			
			//get checkpoint
			foreach($apps as $key => $app){
				$checkpoints = $this->oem_model->get_app_checkpoint_list(array("app_SN"=>$app['app_SN']))->result_array();
				$apps[$key]['app_checkpoints'] = $checkpoints;
			}
			
			$output['aaData'] = $apps;
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function query_app_no_login()
	{
		try{
			$output['aaData'] = array();
			
			$input_data = $this->input->get(NULL,TRUE);
			
			if(!isset($input_data['app_SN']))
			{
				return;
			}
			if(!isset($input_data['app_token']))
			{
				return;
			}
			
			$app = $this->oem_model->get_app_list($input_data)->row_array();
			if(!$app)
			{
				return;
			}
			if($app['app_token']!=$input_data['app_token'])
			{
				return;
			}
			$this->load->model('oem/form_model');
			$forms = $this->form_model->get_vertical_group_forms($app['form_SN']);
			$this->load->model('user_model');
			$user = $this->user_model->get_user_profile_list(array("user_ID"=>$app['app_user_ID']))->row_array();
			//get column
			$cols = $this->oem_model->get_app_col_list(array("app_SN"=>$app['app_SN']))->result_array();
			$app['app_cols'] = $cols;
			
			//get checkpoint
			$checkpoints = $this->oem_model->get_app_checkpoint_list(array("app_SN"=>$app['app_SN']))->result_array();
			$app['app_checkpoints'] = $checkpoints;
			
			$output['aaData'] = array(
				"app"=>$app,
				"forms"=>$forms,
				"user"=>$user
			);
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
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->oem_model->get_app_list(array("app_SN"=>$SN))->row_array();
			if(!$app)
			{
				throw new Exception();
			}
			if(!$this->is_user_login(FALSE))
			{
				$token = $this->input->get("app_token",TRUE);
				if($app['app_token']!=$token)
				{
					throw new Exception();
				}
			}else{
				unset($app['app_token']);
			}
			$this->data = $app;
			
			//取得儀器列表
			$this->load->model('facility_model');
			$this->data['facility_SN_select_options'] = $this->facility_model->get_facility_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('oem/new_app',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function view_app($SN = "")
	{
		try{
			$this->is_user_login();
			
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_app()
	{
		try{
			$this->is_user_login();
			
			$input = file_get_contents('php://input');
			$input = json_decode($input,TRUE);
			
			if(!isset($input['action']))
			{
				throw new Exception("未知的動作",ERROR_CODE);
			}
			
			$_POST = $input['data'];
			$this->form_validation->set_rules("form_SN","表單編號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$this->load->model('oem/app_model');
			$app_SN = $this->app_model->add($input['data']['form_SN'],$input['data']['app_description'],$input['data']['app_cols'],$input['data']['app_type']);
			if($input['action']=="submit")
			{
				$this->oem_model->update_app(array("app_checkpoint"=>"facility_admin_init","app_SN"=>$app_SN));
				$this->app_model->send_email($app_SN);
				echo json_encode($this->get_info_modal_array("申請成功","oem/app/list"));
			}else{
				echo json_encode($this->get_info_modal_array("儲存成功","oem/app/list"));
			}
		}catch(Exception $e){
			echo json_encode($this->get_info_modal_array($e->getMessage(),"",$e->getCode()));
		}
	}
	public function update_app()
	{
		try{
			
			$input = file_get_contents('php://input');
			$input = json_decode($input,TRUE);
			
			if(!isset($input['action']))
			{
				throw new Exception("未知的動作",ERROR_CODE);
			}
			$_POST = $input['data'];
			$this->form_validation->set_rules("app_SN","代工單號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),ERROR_CODE);
			}
			$app = $this->oem_model->get_app_list(array("app_SN"=>$input['data']['app_SN']))->row_array();
			if(!$app)
			{
				throw new Exception("無此代工單",ERROR_CODE);
			}
			
			$this->load->model('oem/app_model');
			switch($input['action'])
			{
				case 'accept':
				case 'reject':
					if(!isset($input['data']['app_checkpoints']) && !is_array($input['data']['app_checkpoints']))
					{
						throw new Exception("未知的錯誤",ERROR_CODE);
					}
					$app_checkpoint = end($input['data']['app_checkpoints']);
					if(!$app_checkpoint || !isset($app_checkpoint['checkpoint_comment']))
					{
						throw new Exception("未知的錯誤",ERROR_CODE);
					}
					if($app['app_checkpoint']=="user_boss")
					{
						//special case
						if($app['app_token']!=$input['data']['app_token'])
						{
							throw new Exception("權限不足",ERROR_CODE);
						}
						$this->app_model->confirm($input['data']['app_SN'],NULL,$app_checkpoint['checkpoint_comment'],$input['action']);
					}else{
						$this->is_user_login();
						$this->app_model->confirm($input['data']['app_SN'],$this->session->userdata('ID'),$app_checkpoint['checkpoint_comment'],$input['action'],$input['data']['app_estimated_hour']);
					}
					break;
				case 'submit':
					break;
				default:
					throw new Exception("未知的動作",ERROR_CODE);
			}
			
			
			echo json_encode($this->get_info_modal_array("審核成功","oem/app/list"));
		}catch(Exception $e){
			echo json_encode($this->get_info_modal_array($e->getMessage(),"",$e->getCode()));
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
			
			//下參數
			if(isset($input_data['only_parent'])&&$input_data['only_parent']==TRUE)
			{
				//只留下父代
				foreach($forms as $key => $form)
				{
					if($form['form_parent_SN']!==NULL)
					{
						unset($forms[$key]);
					}
				}
				$forms = array_values($forms);
			}
			
			//取得對應的儀器代碼
			foreach($forms as $key => $form)
			{
				$facilities = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$form['form_SN']))->result_array();
				$forms[$key]['form_facility_SN'] = sql_column_to_key_value_array($facilities,"facility_SN");
				
				$forms[$key]['form_facilities'] = $facilities;
				$this->load->model('facility_model');
				foreach($facilities as $idx => $facility)
				{
					$user_privileges = $this->facility_model->get_user_privilege_list(array(
						"facility_ID"=>$facility['facility_SN'],
						"privilege"=>array('super','admin')
					))->result_array();
					$forms[$key]['form_facilities'][$idx]['engineers'] = $user_privileges;
				}
			}
			
			//取得對應的欄位資訊
			foreach($forms as $key => $form)
			{
				$cols = $this->oem_model->get_form_col_list(array("form_SN"=>$form['form_SN']))->result_array();
				$forms[$key]['form_cols'] = $cols;
			}
			
			//取得對應的工程師
//			$this->load->model('facility_model');
//			foreach($forms as $key=>$form)
//			{
//				$user_privileges = $this->facility_model->get_user_privilege_list(array(
//					"facility_ID"=>$form['form_facility_SN'],
//					"privilege"=>array("admin","super")
//				))->result_array();
//				$forms[$key]['facility_super_user_SN'] = sql_column_to_key_value_array($user_privileges,"user_ID");	
//			}
			
			
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
//				foreach($form['form_cols'] as $col)
//				{
//					$_POST = $col;
//					$this->form_validation->set_rules("col_cht_name","欄位中文名稱","required");
//					$this->form_validation->set_rules("col_eng_name","欄位英文名稱","required");
//					$this->form_validation->set_rules("col_rule","欄位規則","required");
//					$this->form_validation->set_rules("col_enable","欄位是否啟用","required");
//					if(!$this->form_validation->run())
//					{
//						throw new Exception(validation_errors(),WARNING_CODE);
//					}
//				}
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
//				foreach($form['form_cols'] as $col)
//				{
//					$_POST = $col;
//					$this->form_validation->set_rules("col_cht_name","欄位中文名稱","required");
//					$this->form_validation->set_rules("col_eng_name","欄位英文名稱","required");
//					$this->form_validation->set_rules("col_rule","欄位規則","required");
//					$this->form_validation->set_rules("col_enable","欄位是否啟用","required");
//					if(!$this->form_validation->run())
//					{
//						throw new Exception(validation_errors(),WARNING_CODE);
//					}
//				}
			}
			$this->load->model('oem/form_model');
			$this->form_model->update($forms);
			
			echo json_encode($this->get_info_modal_array("更新成功","oem/form/list"));
		}catch(Exception $e){
			echo json_encode($this->get_info_modal_array($e->getMessage(),"",$e->getCode()));
		}
	}
	public function del_form()
	{
		
	}
	//------------------------BOOKING-------------------------------
	public function add_booking()
	{
		try{
			$this->is_admin_login();
			
			$input = file_get_contents('php://input');
			$booking = json_decode($input,TRUE);
			
			$result = $this->get_info_modal_array("預約成功");
			
			ob_start();
			var_dump($booking);
			$result['body']['message'] = ob_get_clean();
			
			echo json_encode($result);
		}catch(Exception $e){
			echo json_encode($this->get_info_modal_array($e->getMessage(),"",$e->getCode()));
		}
	}
	public function update_booking()
	{
		
	}
	public function del_booking()
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
