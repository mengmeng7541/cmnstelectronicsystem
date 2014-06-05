<?php
class Cash extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cash_model');
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
	//---------------------RECEIPT-------------------
	public function list_receipt()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('cash/list_receipt');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			
		}
	}
	public function query_receipt()
	{
		try{
			$this->is_admin_login();
			
			$receipts = $this->cash_model->get_receipt_list()->result_array();
			
			$output['aaData'] = $receipts;
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function add_receipt()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->cash_model->is_super_admin())
			{
				throw new Exception("權限不足",ERROR_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("bill_type[]","帳單類別","required");
			$this->form_validation->set_rules("bill_ID[]","帳單編號","required");
			$this->form_validation->set_rules("bill_amount_received[]","帳單實收金額","required");
			$this->form_validation->set_rules("account_boss","帳戶擁有者編號","required");
			$this->form_validation->set_rules("receipt_type","收據類別","required");
			$this->form_validation->set_rules("receipt_title","收據抬頭","required");
			$this->form_validation->set_rules("account_amount","收據金額","required");
			$this->form_validation->set_rules("receipt_delivery_way","收據作業","required");
			if(isset($input_data['receipt_delivery_way'])&&$input_data['receipt_delivery_way']=='pickup')
			{
				$this->form_validation->set_rules("receipt_contact_email","電子郵件","required");
			}else{
				$this->form_validation->set_rules("receipt_contact_address","郵寄地址","required");
			}
			$this->form_validation->set_rules("receipt_contact_name","連絡人姓名","required");
			$this->form_validation->set_rules("receipt_contact_tel","連絡電話","required");

			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			//確認帳單實收總額<=收據金額
			if(array_sum($input_data['bill_amount_received'])>$input_data['account_amount']){
				throw new Exception("帳單實收總額不可大於收據金額",WARNING_CODE);
			}
			
			$this->load->model('cash/receipt_model');
			//寫入收據資訊並開立帳戶
			$account_no = $this->receipt_model->add($input_data);
			//取得帳單資訊，然後寫入帳戶抵扣帳單資訊
			foreach($input_data['bill_type'] as $key => $bill_type)
			{
				if($bill_type=='curriculum')
				{
					$bill = $this->cash_model->get_curriculum_bill_list(array("reg_ID"=>$input_data['bill_ID'][$key]))->row_array();
					$bill_no = $this->cash_model->add_bill(array(
						"bill_type"=>$bill_type,
						"bill_ID"=>$input_data['bill_ID'][$key],
						"bill_org"=>$bill['user_org_no'],
						"bill_boss"=>$bill['user_boss_no'],
						"bill_amount_original"=>$bill['bill_amount'],
						"bill_discount_percent"=>$bill['bill_discount_percent'],
						"bill_amount_receivable"=>$bill['bill_amount']*$bill['bill_discount_percent'],
						"bill_time"=>date("Y-m-d H:i:s")
					));
					
					
				}else if($bill_type=='nanomark'){
					$bill = $this->cash_model->get_nanomark_bill_list(array("application_SN"=>$input_data['bill_ID'][$key]))->row_array();
					$bill_no = $this->cash_model->add_bill(array(
						"bill_type"=>$bill_type,
						"bill_ID"=>$input_data['bill_ID'][$key],
						"bill_org"=>$bill['user_org_no'],
						"bill_boss"=>$bill['user_boss_no'],
						"bill_amount_original"=>$bill['total_fees'],
						"bill_discount_percent"=>$bill['bill_discount_percent'],
						"bill_amount_receivable"=>$bill['total_fees']*$bill['bill_discount_percent'],
						"bill_time"=>date("Y-m-d H:i:s")
					));
					
				}
				
				$this->cash_model->add_account_bill_map(array(
					"account_no"=>$account_no,
					"bill_no"=>$bill_no,
					"amount_transacted"=>$input_data['bill_amount_received'][$key],
					"transacted_by"=>$this->session->userdata('ID'),
					"transaction_time"=>date("Y-m-d H:i:s")
				));
			}
			
			echo $this->info_modal("開立完成");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_receipt()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->cash_model->is_super_admin())
			{
				throw new Exception("權限不足",ERROR_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("receipt_no","收據流水號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$receipt = $this->cash_model->get_receipt_list(array("receipt_no"=>$input_data['receipt_no']))->row_array();
			if(!$receipt){
				throw new Exception("無此收據",ERROR_CODE);
			}
			
			$this->load->model('cash/receipt_model');
			if($receipt['receipt_checkpoint']=='initialized')
			{
				$this->form_validation->set_rules("receipt_ID","收據編號","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				
				//寄信通知已開立
				$this->receipt_model->open($input_data['receipt_no'],$input_data['receipt_ID']);
			}
			else if($receipt['receipt_checkpoint']=='opened')
			{
				if($receipt['receipt_delivery_way']=='pickup')
				{
					//紀錄已領時間
					$this->receipt_model->delivery_by_collection($input_data['receipt_no']);
				}else if($receipt['receipt_delivery_way']=='post' && $receipt['receipt_type']=='receipt')//開收據才會由我們寄出
				{
					//通知已郵寄，並填入郵寄代碼
					$this->form_validation->set_rules("receipt_remark","郵寄代碼","required");
					if(!$this->form_validation->run())
					{
						throw new Exception(validation_errors(),WARNING_CODE);
					}
					$this->receipt_model->delivery_by_post($input_data['receipt_no'],$input_data['receipt_remark']);
				}
			}
			
			echo $this->info_modal("更新成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_receipt($SN = "")
	{
		
	}
	//-----------------BILL--------------------
	public function query_bill()
	{
		try{
			$this->is_admin_login();
			
//			$this->form_validation->set_rules("bill_type","帳單型態","required");
//			$this->form_validation->set_rules("bill_ID[]","帳單編號","required");
//			if(!$this->form_validation->run())
//			{
//				throw new Exception(validation_errors(),WARNING_CODE);
//			}
			
			$input_data = $this->input->get(NULL,TRUE);
			
			if($input_data['bill_type']=="curriculum")
			{
				$curriculum_bills = $this->cash_model->get_curriculum_bill_list(array("reg_ID"=>isset($input_data['bill_ID'])?$input_data['bill_ID']:""))->result_array();
				$output['aaData'] = $curriculum_bills;
				echo json_encode($output);
			}else if($input_data['bill_type']=="nanomark")
			{
				$nanomark_bills = $this->cash_model->get_nanomark_bill_list(array("application_SN"=>isset($input_data['bill_ID'])?$input_data['bill_ID']:""))->result_array();
				$output['aaData'] = $nanomark_bills;
				echo json_encode($output);
			}
			
		}catch(Exception $e){
			
			echo json_encode($e);
		}
	}
//	public function add_bill()
//	{
//		try{
//			$this->is_admin_login();
//			
//			$input_data = $this->input->post(NULL,TRUE);
//			
//			
//			
//		}catch(Exception $e){
//			echo $this->info_modal($e->getMessage(),"",$e->getCode());
//		}
//	}
	//------------------LIST CURRICULUM BILL--------------
	public function list_curriculum()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('cash/list_curriculum');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function query_curriculum()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->get(NULL,TRUE);
			$curriculum_bills = $this->cash_model->get_curriculum_bill_list($input_data)->result_array();
			
			$output['aaData'] = array();
			$this->load->model('curriculum_model');
			foreach($curriculum_bills as $curriculum_bill)
			{
				//如果超過上限，才不用SHOW，但還要再判斷若超過上限也有確認，還是要付錢
				if(
					$curriculum_bill['reg_rank'] > $curriculum_bill['class_max_participants'] &&
					empty($curriculum_bill['reg_confirmed_by'])
				)
				{
					continue;
				}
				
				$row = array();
				$row[] = $curriculum_bill['course_cht_name'];
				$row[] = $curriculum_bill['class_code'];
				$row[] = $this->curriculum_model->get_class_type_str($curriculum_bill['class_type']);
				$row[] = $curriculum_bill['user_name'];
				$row[] = $curriculum_bill['org_name'];
				
				if(isset($curriculum_bill['bill_discount_percent']))
				{
					$row[] = $curriculum_bill['bill_amount']." * ".($curriculum_bill['bill_discount_percent'])." = ".round($curriculum_bill['bill_amount']*$curriculum_bill['bill_discount_percent']);
				}else{
					$row[] = $curriculum_bill['bill_amount'];
				}
				
				$display = array();
				if($curriculum_bill['bill_amount']*$curriculum_bill['bill_discount_percent']>$curriculum_bill['bill_amount_received'])//應收大於已收
				{
					$display[] = form_checkbox("bill_ID[]",$curriculum_bill['reg_ID'],"","");
				}
				if(!empty($curriculum_bill['bill_amount_received']))
				{
					$display[] = $curriculum_bill['receipt_ID'];
					$display[] = "($".$curriculum_bill['bill_amount_received'].")";
				}
				$row[] = implode(' ',$display);
				
				$row[] = "";
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	//------------------------NANOMARK BILL-----------------------
	public function list_nanomark()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('cash/list_nanomark');
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function query_nanomark()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->get(NULL,TRUE);
			$nanomark_bills = $this->cash_model->get_nanomark_bill_list($input_data)->result_array();
			
			$output['aaData'] = array();
			$this->load->model('nanomark_model');
			foreach($nanomark_bills as $nanomark_bill)
			{
				$row = array();
				$row[] = $nanomark_bill['report_title'];
				$row[] = $nanomark_bill['contact_name'];
				$row[] = $nanomark_bill['specimen_name'];
				$row[] = $nanomark_bill['application_date'];
				$row[] = $nanomark_bill['ID'];
				$row[] = $nanomark_bill['total_fees']."*".$nanomark_bill['bill_discount_percent']."=".$nanomark_bill['total_fees']*$nanomark_bill['bill_discount_percent'];
				$display = array();
				if($nanomark_bill['total_fees']*$nanomark_bill['bill_discount_percent']>$nanomark_bill['bill_amount_received'])//應收大於已收
				{
					$display[] = form_checkbox("bill_ID[]",$nanomark_bill['serial_no'],"","");
				}
				if(!empty($nanomark_bill['bill_amount_received']))
				{
					$display[] = $nanomark_bill['receipt_ID'];
					$display[] = "($".$nanomark_bill['bill_amount_received'].")";
				}
				$row[] = implode(' ',$display);
				$row[] = "";
				$row[] = "";
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
}
?>
