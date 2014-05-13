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
				$display = array();
				if($app['application_checkpoint_ID']=='applied'){
					$display[] = anchor("access/card/application/temp/edit/".$app['serial_no'],"審查","class='btn btn-small btn-primary'");
					$display[] = form_button("reject","退件","class='btn btn-small btn-danger' value='{$app['serial_no']}'");
				}else if($app['application_checkpoint_ID']=='issued'){
					$display[] = form_button("refund","確認歸還","class='btn btn-small btn-warning'");
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
			
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_array();
			if(!$app)
			{
				throw new Exception();
			}
			
			$this->data = $app;
			
			$this->data['purposes'] = $this->access_card_temp_application_model->get_purpose_select_option_array();
			
			if($app['application_checkpoint_ID']=='applied' && $this->access_model->is_super_admin()){
				$this->data['page'] = "issue";
			}
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/form_card_temp_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	
	public function get_access_card_temp_application($SN = ""){
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$app = $this->access_model->get_access_card_temp_application_list(array("serial_no"=>$SN))->row_Array();
			if(!$app){
				throw new Exception();
			}
			
			$output = array();
			$output = $app;
			echo json_encode($output);
		}catch(Exception $e){
			
		}
	}
	
	public function get_access_card_temp_application_type_purpose_json()
	{
		try{
			
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
			if($input_data['application_type']=="guest")
			{
				$this->form_validation->set_rules("guest_name","來賓姓名","required");
				$this->form_validation->set_rules("guest_access_start_date","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_start_time","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_end_date","磁卡使用時段","required");
				$this->form_validation->set_rules("guest_access_end_time","磁卡使用時段","required");
				if(!$this->form_validation->run()){
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$this->access_card_temp_application_model->apply_guest($input_data);
			}else if($input_data['application_type']=="user"){
				$this->access_card_temp_application_model->apply_user($input_data);
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
			
			if($app['application_checkpoint_ID']!="applied"){
				throw new Exception("此單非在申請中，不可刪除",ERROR_CODE);
			}
			
			$this->access_model->del_access_card_temp_application(array("serial_no"=>$app['serial_no']));
			
			echo $this->info_modal("退件成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}

}
?>
