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
		
		$data['receipt_initialized_by'] = $this->session->userdata('ID');
		$data['receipt_initialization_time'] = date("Y-m-d H:i:s");
		$data['receipt_account'] = $account_no;
		$data['receipt_checkpoint'] = "initialized";
		$receipt_no = $this->cash_model->add_receipt(elements(array(
			"receipt_type","receipt_ID","receipt_title","receipt_initialized_by","receipt_initialization_time","receipt_contact_name","receipt_contact_email","receipt_contact_tel","receipt_contact_address","receipt_note","receipt_delivery_way","receipt_account","receipt_checkpoint"
		),$data),NULL);
		
		return $account_no;
	}
//	public function update($data)
//	{
//		
//	}
	public function open($receipt_no,$receipt_ID){
		$this->cash_model->update_receipt(array(
			"receipt_no"=>$receipt_no,
			"receipt_ID"=>$receipt_ID,
			"receipt_opened_by"=>$this->session->userdata('ID'),
			"receipt_opening_time"=>date("Y-m-d H:i:s"),
			"receipt_checkpoint"=>'opened'
		));
		
		//發信
		$receipt = $this->cash_model->get_receipt_list(array("receipt_no"=>$receipt_no))->row_array();
		if($receipt['receipt_delivery_way']=='pickup')
		{
			//取得處理人員profile
			$this->load->model('admin_model');
			$admin_profile = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$this->session->userdata('ID')))->row_array();
			$this->email->to($receipt['receipt_contact_email']);
			$this->email->cc($admin_profile['email']);
			$this->email->subject("成大微奈米科技研究中心 -收據開立通知-");
			$this->email->message("
				{$receipt['receipt_contact_name']} 您好：<br>
				您於本中心繳費金額 {$receipt['receipt_amount']} 之收據編號 {$receipt['receipt_ID']} 已開立完畢，請前往中心領取，謝謝。
			");
			$this->email->send();
		}
		
	}
	public function delivery_by_collection($receipt_no){
		$this->cash_model->update_receipt(array(
			"receipt_no"=>$receipt_no,
			"receipt_checkpoint"=>'delivered',
			"receipt_delivered_by"=>$this->session->userdata('ID'),
			"receipt_delivery_time"=>date("Y-m-d H:i:s")
		));
	}
	public function delivery_by_post($receipt_no,$post_no){
		$this->cash_model->update_receipt(array(
			"receipt_no"=>$receipt_no,
			"receipt_checkpoint"=>'delivered',
			"receipt_delivered_by"=>$this->session->userdata('ID'),
			"receipt_delivery_time"=>date("Y-m-d H:i:s"),
			"receipt_remark"=>$post_no
		));
		
		//發信
		$receipt = $this->cash_model->get_receipt_list(array("receipt_no"=>$receipt_no))->row_array();
		$this->email->to($receipt['receipt_contact_email']);
		$this->email->subject("成大微奈米科技研究中心 -收據開立通知-");
		$this->email->message("
			{$receipt['receipt_contact_name']} 您好：<br>
			您於本中心繳費金額 {$receipt['receipt_amount']} 之收據編號 {$receipt['receipt_ID']} 已開立完畢並已掛號郵寄至 {$receipt['receipt_contact_address']}，掛號函件執據號為 $post_no ，謝謝。
		");
		$this->email->send();
	}
}
