<?php
class Error extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
  }
  public function show_404_page()
  {
	  $this->show_error_page(404);
  }
//  public function show($code = "404")
//  {
//	  $this->load->view('templates/header');
//	  $this->load->view('templates/sidebar');
//	  $this->load->view("error/{$code}");
//	  $this->load->view('templates/footer');
//  }
  

}