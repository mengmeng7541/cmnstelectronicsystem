<?php
class Access extends MY_Controller {
	
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('access_model');
		$this->load->model('access/access_card_temp_application_model');
	}
	public function index()
	{

	}
	//-----------------------SYSTEM MANAGEMENT-------------------
		//------------------ADMIN----------------
	public function edit_config()
	{
		try{
			$this->is_admin_login();
			
			$this->load->model('admin_model');
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			$privileges = $this->access_model->get_privilege_list(array(
				"privilege"=>"access_super_admin"
			))->result_array();
			if($privileges){
				$this->data['admin_ID'] = sql_column_to_key_value_array($privileges,"admin_ID");
			}
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/edit_config',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function update_config()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->access_model->is_super_admin())
			{
				throw new Exception("權限不足",ERROR_CODE);
			}
			
			$this->form_validation->set_rules("admin_ID[]","管理員","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			//先取
			$privileges = $this->access_model->get_privilege_list(array(
				"privilege"=>"access_super_admin"
			))->result_array();
			//再刪
			foreach($privileges as $privilege){
				$this->access_model->del_privilege(array(
					"serial_no"=>$privilege['serial_no']
				));
			}
			//後增
			$this->access_model->add_privilege(array(
				"admin_ID"=>$input_data['admin_ID']
			));
			
			echo $this->info_modal("設定成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
		//-----------------ACCESS CARD POOL----------
	public function list_access_card_pool()
	{
		try{
			$this->is_admin_login();
		}catch(Exception $e){
			
		}
	}
	public function query_access_card_pool()
	{
		try{
			$this->is_admin_login();
			
			$cards = $this->access_model->get_access_card_pool_list()->result_array();
			
			$output['aaData'] = array();
			foreach($cards as $card){
				$row = array();
				$row[] = $card['access_card_num'];
				$display = array();
				$display[] = form_checkbox("access_card_num[]",$card['access_card_num']);
				$row[] = implode(' ',$display);
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function form_access_card_pool()
	{
		
	}
	public function edit_access_card_pool()
	{
		
	}
	public function add_access_card_pool($action = NULL)
	{
		try{
			$this->is_admin_login();
			
			if(!$this->access_model->is_super_admin())
			{
				throw new Exception("沒有權限",ERROR_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			if($action=="batch"){
				$this->form_validation->set_rules("serial_no_start","啟始流水號","required|numeric|exact_length[8]");
				$this->form_validation->set_rules("serial_no_end","結束流水號","required|numeric|exact_length[8]");
				if(!$this->form_validation->run()){
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				if($input_data['serial_no_start'] > $input_data['serial_no_end'])
				{
					throw new Exception("啟始流水號不可大於結束流水號",WARNING_CODE);
				}
				for($i=$input_data['serial_no_start'];$i<=$input_data['serial_no_end'];$i++){
					$this->access_model->add_access_card_pool(array(
						"access_card_num"=>str_pad($i,8,"0",STR_PAD_LEFT)
					));
				}
			}else{
				
			}
			
			echo $this->info_modal("新增成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_access_card_pool()
	{
		
	}
	public function del_access_card_pool($SN = NULL)
	{
		try{
			$this->is_admin_login();
			
			if(!$this->access_model->is_super_admin()){
				throw new Exception("沒有權限",ERROR_CODE);
			}
			
			if(isset($SN)){
				
			}else{
				$this->form_validation->set_rules("access_card_num[]","卡號","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$input_data = $this->input->post(NULL,TRUE);
				$this->access_model->del_access_card_pool(array(
					"access_card_num"=>$input_data['access_card_num']
				));
			}
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//-----------------------TEMP APPLICATION--------------------
	public function list_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/list_card_temp_application',$this->data);
			$this->load->view('templates/footer');
			
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	
	public function query_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			$apps = $this->access_model->get_access_card_temp_application_list()->result_array();
			
			$output['aaData'] = array();
			foreach($apps as $app)
			{
				$row = array();
				$row[] = $app['application_type_name'];
				$row[] = $app['guest_purpose_name'];
				$row[] = $app['applicant_name'];
				$row[] = $app['guest_name'];
				$row[] = $app['guest_access_start_time'].'~'.$app['guest_access_end_time'];
				$row[] = $app['guest_access_card_num'];
				$row[] = $app['issuer_name'];
				$row[] = $app['refunder_name'];
				$display = array();
				if($app['application_checkpoint_ID']=='applied'){
					$display[] = anchor("access/card/application/temp/edit/".$app['serial_no'],"審查","class='btn btn-small btn-primary'");
					$display[] = form_button("reject","退件","class='btn btn-small btn-danger' value='{$app['serial_no']}'");
				}else if($app['application_checkpoint_ID']=='issued'){
					$display[] = form_button("refund","確認歸還","class='btn btn-small btn-warning' value='{$app['serial_no']}'");
				}else if($app['application_checkpoint_ID']=='refunded'){
					$display[] = form_label("已結案","",array("class"=>"label label-success"));
				}
				$row[] = implode(' ',$display);
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}

	public function form_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			$this->data['purposes'] = $this->access_card_temp_application_model->get_purpose_select_option_array();
			$this->load->model('admin_model');
			$this->data['used_by_array'] = $this->admin_model->get_admin_ID_select_options();
			
			$this->load->model('facility_model');
			$this->data['facility_SN_select_options'] = $this->facility_model->get_facility_select_options("door");
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/form_card_temp_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}

	public function edit_card_temp_application($SN)
	{
		try{
			$this->is_admin_login();
			
			if(!$this->access_model->is_super_admin())
			{
				throw new Exception();
			}
			
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_array();
			if(!$app)
			{
				throw new Exception();
			}
			
			$this->data = $app;
			
			$this->data['purposes'] = $this->access_card_temp_application_model->get_purpose_select_option_array();
			$this->load->model('admin_model');
			$this->data['used_by_array'] = $this->admin_model->get_admin_ID_select_options();
			
			if($app['application_checkpoint_ID']=='applied' && $this->access_model->is_super_admin()){
				$this->data['page'] = "issue";
			}
			
			$this->load->model('facility_model');
			$this->data['facility_SN_select_options'] = $this->facility_model->get_facility_select_options("door");
			$maps = $this->access_model->get_access_card_temp_app_facility_map_list(array("temp_app_SN"=>$app['serial_no']))->result_array();
			$this->data['facility_SN'] = sql_column_to_key_value_array($maps,"facility_SN");
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/form_card_temp_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	
//	public function get_access_card_temp_application($SN = ""){
//		try{
//			$this->is_admin_login();
//			
//			$SN = $this->security->xss_clean($SN);
//			
//			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_Array();
//			if(!$app){
//				throw new Exception();
//			}
//			
//			$output = array();
//			$output = $app;
//			echo json_encode($output);
//		}catch(Exception $e){
//			
//		}
//	}
	
	public function get_access_card_temp_application_type_purpose_json()
	{
		try{
			$this->is_user_login();
			$output = $this->access_card_temp_application_model->get_type_purpose_array();
			echo json_encode($output);
		}catch(Exception $e){
			
		}
	}

	public function add_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("application_type_ID","申請磁卡類別","required");
			$this->form_validation->set_rules("guest_purpose_ID","申請磁卡目的","required");
			if(!$this->form_validation->run()){
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			$input_data = $this->input->post(NULL,TRUE);
			if($input_data['application_type_ID']=="guest")
			{

				if(!$this->at_least_one($this->input->post("guest_name"))){
					throw new Exception("請至少填寫一位訪客姓名",WARNING_CODE);
				}
				
				$this->form_validation->set_rules("guest_access_start_date","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_start_time","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_end_date","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_end_time","磁卡使用時段","required");
				$this->form_validation->set_rules("facility_SN[]","需求門禁","required");
				if(!$this->form_validation->run()){
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$this->access_card_temp_application_model->apply($input_data);
			}else if($input_data['application_type_ID']=="user"){
				$this->form_validation->set_rules("used_by","借卡者","required");
				if(!$this->form_validation->run()){
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$this->access_card_temp_application_model->apply($input_data);
			}
			echo $this->info_modal("申請成功","/access/card/application/temp/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}

	public function update_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->access_model->is_super_admin())
			{
				throw new Exception("沒有權限",ERROR_CODE);
			}
			
			$this->form_validation->set_rules("serial_no","流水號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),ERROR_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$input_data['serial_no']))->row_array();
			if(!$app)
			{
				throw new Exception("無此筆資料",ERROR_CODE);
			}
			
			if($app['application_checkpoint_ID']=='rejected'){
				throw new Exception("此單已退件",ERROR_CODE);
			}else if($app['application_checkpoint_ID']=='applied'){
				if(isset($input_data['auto_issue'])&&$input_data['auto_issue']==1)
				{
					$card_num = $this->access_card_temp_application_model->issue($app['serial_no']);
					
					echo $this->info_modal("核發成功 卡號為：$card_num","/access/card/application/temp/list");
				}else{
					$this->form_validation->set_rules("guest_access_card_num","磁卡卡號","required");
					if(!$this->form_validation->run()){
						throw new Exception(validation_errors(),WARNING_CODE);
					}
					$this->access_card_temp_application_model->issue($app['serial_no'],$input_data['guest_access_card_num']);
					
					echo $this->info_modal("核發成功","/access/card/application/temp/list");
				}
			}else if($app['application_checkpoint_ID']=='issued'){
				$this->access_card_temp_application_model->refund($app['serial_no']);
				
				echo $this->info_modal("歸還成功");
			}else if($app['application_checkpoint_ID']=='refunded'){
				throw new Exception("此單已結案",ERROR_CODE);
			}else{
				throw new Exception("未知的動作",ERROR_CODE);
			}
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	
	public function del_card_temp_application($SN = "")
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_array();
			if(!$app)
			{
				throw new Exception("無此筆資料",ERROR_CODE);
			}
			
			//檢查是否為申請者本人或超級管理者
			if(	!$this->access_model->is_super_admin() &&
				$app['applied_by'] != $this->session->userdata('ID'))
			{
				throw new Exception("沒有權限",ERROR_CODE);
			}
			
			if($app['application_checkpoint_ID']!="applied"){
				throw new Exception("此單非在申請中，不可刪除",ERROR_CODE);
			}
			
			$this->access_model->del_access_card_temp_application(array("serial_no"=>$app['serial_no']));
			
			echo $this->info_modal("退件成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//------------------ACCOUNT VERIFY---------------------
	public function verify_card_application($SN = "")
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$this->load->model('access/access_card_application_model');
			$this->access_card_application_model->verify($SN);
			
			echo $this->info_modal("確認繳交成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
}
?>
