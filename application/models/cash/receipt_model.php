<?php
class Receipt_model extends MY_Model {
	protected $cash_db;

	public function __construct()
	{
		parent::__construct();
//		$this->cash_db = $this->load->database("cash",TRUE);
		$this->load->model('cash_model');
	}
	
	//--------------------RECEIPT-------------------------
	public function add($data)
	{
		$data['account_type'] = 10;//temporary
		$data['account_opened_by'] = $this->session->userdata('ID');
		$data['account_opening_time'] = date("Y-m-d H:i:s");
		$data['account_start_time'] = date("Y-m-d H:i:s");
		$account_no = $this->cash_model->add_account(elements(array(
			"account_boss","account_type","account_amount","account_opened_by","account_opening_time","account_start_time",
		),$data),NULL);
		
		$data['receipt_opened_by'] = $this->session->userdata('ID');
		$data['receipt_opening_time'] = date("Y-m-d H:i:s");
		$data['receipt_account'] = $account_no;
		$data['receipt_checkpoint'] = "initialized";
		$receipt_no = $this->cash_model->add_receipt(elements(array(
			"receipt_type","receipt_ID","receipt_title","receipt_opened_by","receipt_opening_time",
			"receipt_contact_name","receipt_contact_email","receipt_contact_tel","receipt_contact_address","receipt_delivery_way","receipt_account","receipt_checkpoint"
		),$data),NULL);
		
		return $account_no;
	}
	public function update($data)
	{
		
	}
	
}
