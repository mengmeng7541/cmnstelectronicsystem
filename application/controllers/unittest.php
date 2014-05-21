<?php
class Unittest extends MY_Controller {
	public function __construct()
	{
		parent::__construct();



	}
	public function index()
	{
		$this->output->enable_profiler(TRUE);
  		
		$this->load->model('facility_model');
		$this->load->model('facility/booking_model');
		$this->benchmark->mark('code_start');
		$result = $this->facility_model->get_vertical_group_facilities("1203");
		$this->benchmark->mark('code_end');
		var_dump($result);
		
		
		echo $this->benchmark->elapsed_time('code_start','code_end');
	}
  
//  public function form()
//  {
//      
//	  $this->load->view('templates/header');
//	  $this->load->view('templates/sidebar');
//	  $this->load->view('user/form');
//	  $this->load->view('templates/footer');
//    
//  }
  

}
?>
