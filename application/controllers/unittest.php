<?php
class Unittest extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
    
    
  
  }
  public function index()
  {
//  	$this->load->model('facility/access_ctrl_model');
//  	$this->access_ctrl_model->update('I100189036');
	$this->load->model('facility_model');
	echo $this->facility_model->get_max_pre_open_sec();
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
