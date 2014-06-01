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
	public function get_bill_list()
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
				$curriculum_bills = $this->cash_model->get_curriculum_list(array("reg_ID"=>isset($input_data['bill_ID'])?$input_data['bill_ID']:""))->result_array();
				$output['aaData'] = array();
				foreach($curriculum_bills as $bill)
				{
					$row = array();
					$row[] = "儀器訓練課程";
					$row[] = $bill['reg_ID'];
					$row[] = $bill['bill_fee'];
					$row[] = $bill['bill_discount_percent'];
					$row[] = $bill['bill_fee']*$bill['bill_discount_percent'];
					$row[] = form_input("bill_fee[]",$bill['bill_fee']*$bill['bill_discount_percent'],"");
					$output['aaData'][] = $row;
				}
				echo json_encode($output);
			}
			
		}catch(Exception $e){
			
			echo json_encode($e);
		}
	}
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
			$curriculum_bills = $this->cash_model->get_curriculum_list($input_data)->result_array();
			
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
				
				if(empty($curriculum_bill['reg_confirmed_by']))//未到
				{
					if(isset($curriculum_bill['bill_discount_percent']))
					{
						$row[] = $curriculum_bill['bill_fee']." * 0.5 * ".($curriculum_bill['bill_discount_percent'])." = ".round($curriculum_bill['bill_fee']*0.5*$curriculum_bill['bill_discount_percent']);
					}else{
						$row[] = $curriculum_bill['bill_fee']." * 0.5 = ".round($curriculum_bill['bill_fee']*0.5);
					}
				}else{
					if(isset($curriculum_bill['bill_discount_percent']))
					{
						$row[] = $curriculum_bill['bill_fee']." * ".($curriculum_bill['bill_discount_percent'])." = ".round($curriculum_bill['bill_fee']*$curriculum_bill['bill_discount_percent']);
					}else{
						$row[] = $curriculum_bill['bill_fee'];
					}
				}
				
				
				$row[] = form_checkbox("bill_ID[]",$curriculum_bill['reg_ID'],"","");
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
