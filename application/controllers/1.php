<?php
class User extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('user_model');
  
  }
  public function index()
  {
  	
  }
  
  public function form()
  {
      
	  $this->load->view('templates/header');
	  $this->load->view('templates/sidebar');
	  $this->load->view('user/form');
	  $this->load->view('templates/footer');
    
  }
  

}
?>
