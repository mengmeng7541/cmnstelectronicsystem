<?php

class Pages extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
  
  }
  public function index()
  {
  	//keep the prev_url
	$this->session->keep_flashdata('prev_url');
	$this->load->view("login.php");
  }
}
?>