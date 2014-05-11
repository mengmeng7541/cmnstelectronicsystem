<?php
class Access extends MY_Controller {
	public function __construct()
	{
	parent::__construct();

	$this->load->model('access_model');

	}
	public function index()
	{

	}

	public function form_card_temp_application()
	{
		try{
			$this->is_admin_login();
			
			
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('access/form_card_temp_application');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}

	public function edit_card_temp_application()
	{
	  
	  $this->load->view('templates/header');
	  $this->load->view('templates/sidebar');
	  $this->load->view('user/form');
	  $this->load->view('templates/footer');

	}

	public function form_card_temp_application()
	{
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/form');
		$this->load->view('templates/footer');

	}

	public function form_card_temp_application()
	{
	  
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/form');
		$this->load->view('templates/footer');

	}


}
?>
