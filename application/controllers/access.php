<?php
class Access extends MY_Controller {
	
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('access_model');
		$this->load->model('access_card_temp_application_model');
	}
	public function index()
	{

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
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/form_card_temp_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
		

	}

	public function add_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("application_type","申請磁卡類別","required");
			$this->form_validation->set_rules("guest_purpose","申請磁卡目的","required");
			$this->form_validation->set_rules("guest_name","來賓姓名","required");
			$this->form_validation->set_rules("guest_mobile","來賓聯絡手機","required");
			$this->form_validation->set_rules("guest_access_start_date","磁卡使用時段","required");
			$this->form_validation->set_rules("guest_access_start_time","磁卡使用時段","required");
			$this->form_validation->set_rules("guest_access_end_date","磁卡使用時段","required");
			$this->form_validation->set_rules("guest_access_end_time","磁卡使用時段","required");
			if(!$this->form_validation->run()){
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->access_card_temp_application_model->apply($input_data);
			
			echo $this->info_modal("申請成功","/access/card/application/temp/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}

	public function update_card_temp_application()
	{
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/form');
		$this->load->view('templates/footer');

	}
	
	public function del_card_temp_application()
	{
		
	}

}
?>
