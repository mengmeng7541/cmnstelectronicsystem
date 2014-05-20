<?php
class Unittest extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
    
    
  
  }
  public function index()
  {
		send_privilege_expiration_notification();
		
		echo "DONE";
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
