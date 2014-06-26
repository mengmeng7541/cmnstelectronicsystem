<?php
class User extends MY_Controller {
	private $data = array('org'=>'');
	public function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');

	}
	
	public function login()
	{
		$input_data = $this->input->post(NULL,TRUE);
		
		$this->form_validation->set_rules("ID","帳號","required");
		$this->form_validation->set_rules("passwd","密碼","required");
		if(!$this->form_validation->run())
		{
			$this->session->keep_flashdata('prev_url');
			redirect('/');
		}
	    if($this->user_model->get_user_profile_list(array("user_ID"=>$input_data['ID'],"passwd"=>$input_data['passwd'],"group"=>"normal"))->row_array())
	    {
			//登入成功，寫入session
			$user_data = $this->user_model->get_user_profile_by_ID($input_data['ID']);
			$this->session->set_userdata('ID',$user_data['ID']);
			$this->session->set_userdata("status","user");
			
			if(!$this->session->flashdata('prev_url'))
			{
				redirect('user/edit');
			}else{
				redirect($this->session->flashdata('prev_url'));
			}
			
	    }else{
			$this->session->keep_flashdata('prev_url');
			redirect('/');
		}
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
	public function forget_passwd()
	{
		$user_email = $this->input->post("email",TRUE);
		
		$this->form_validation->set_rules("email","Email","required|valid_email");
		if(!$this->form_validation->run()){
			echo preg_replace("/\<(.*?)\>/","",validation_errors());//濾掉所有用"<"與">"包起來的東西
			return;
		}

		
		$user_profile = $this->user_model->get_user_profile_by_email($user_email);
		
		if(!$user_profile){
			echo "無此email";
			return FALSE;
		}
		
		$this->email->to($user_email); 

		$this->email->subject('微奈米中心使用者帳號重設訊息');
		$this->email->message("
                        <p>您好： </p>
						<p>您的帳號是：{$user_profile['ID']}</p>
                        <p>您的密碼是：{$user_profile['passwd']}</p>
                        <p></p>"); 
		$this->email->send();
		
		echo "密碼已寄出";
	}
	
	public function form_account()
	{
		//已經登入就重導到帳號編輯頁
		if($this->is_user_login(FALSE))
		{
			$this->edit_account();
			return;
		}
		
	  	$this->data['org_select_options'] = $this->user_model->get_org_ID_select_options();
	  	$this->data['status_select_options'] = $this->user_model->get_user_status_select_options();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/form',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_account($user_ID = "")
	{
		$this->is_user_login();
		
		//檢查是不是自己的帳號
		if(empty($user_ID)) $user_ID = $this->session->userdata('ID');
		if(!$this->_get_account_privilege($user_ID))
		{
			$this->show_error_page(403);
			return;
		}
		
		$user_profile = $this->user_model->get_user_profile_by_ID($user_ID);
		$boss_profile = $this->user_model->get_boss_by_SN($user_profile['boss_no']);
		
		$this->data = $user_profile;
		$this->data['boss'] = $boss_profile;
		
		$this->data['org_select_options'] = $this->user_model->get_org_ID_select_options();
		$this->data['status_select_options'] = $this->user_model->get_user_status_select_options();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/edit',$this->data);
		$this->load->view('templates/footer');
	}
	public function list_account()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/list',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_account()
	{
		try{
			$this->is_user_login();
			
			$output['aaData'] = array();
			
			$input_data = $this->input->get(NULL,TRUE);
			if(!$this->is_admin_login(FALSE)){
				//一般使用者只能看到自己的資料
				$input_data['user_ID'] = $this->session->userdata('ID');
			}
			
			$user_profile = $this->user_model->get_user_profile_list($input_data)->result_array();
			$output['aaData'] = $user_profile;
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function list_account_query()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$result = $this->user_model->get_user_list_JSON($input_data);		
		
		echo $result;
		
	}
	public function add_account()
	{
		$input_data = $this->input->post(NULL,TRUE);
		
		$this->form_validation->set_rules("ID","身分證字號","required|alpha_numeric|callback_user_ID_not_existed");
		$this->form_validation->set_rules("passwd","自訂密碼","required");
		$this->form_validation->set_rules("passwd2","密碼確認","required");
		$this->form_validation->set_rules("name","姓名","required");
		$this->form_validation->set_rules("sex","性別","required");
		$this->form_validation->set_rules("organization","服務單位","required");
		$this->form_validation->set_rules("department","系所或部門","required");
		$this->form_validation->set_rules("tel","聯絡電話","required");
		$this->form_validation->set_rules("mobile","行動電話","required");
		$this->form_validation->set_rules("email","E-Mail","required|valid_email");
		$this->form_validation->set_rules("status","身份別","required");
		$this->form_validation->set_rules("boss_name","指導老師姓名","required");
		$this->form_validation->set_rules("boss_department","指導老師服務單位","required");
		$this->form_validation->set_rules("boss_email","指導老師Email","required|valid_email");
		$this->form_validation->set_rules("boss_tel","指導老師聯絡電話","required");
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","warning");
			return;
		}
		
		//for old db-------------------------------------
		$organization = $this->user_model->get_org_by_SN($input_data['organization']);
		//------------------------------------
		
		//查詢老闆編號
		$boss_profile = $this->user_model->get_boss_no_by_name($input_data['boss_name']);
		if(empty($boss_profile))
		{
			$input_data['boss_no'] = $this->user_model->add_boss($input_data);
			//for old db-------------------------------------
			$input_data['boss_organization'] = $organization['name'];
			$input_data_big5 = $this->utf8_to_big5($input_data);
			$this->user_model->add_boss_for_old_db($input_data_big5);
			//------------------------------------
		}else{
			$input_data['boss_no'] = $boss_profile['serial_no'];
		}
		
		
		//新增使用者 
		$input_data['group'] = "normal";
		$this->user_model->add_user($input_data);
		//for old db------------------------------------
		$input_data['organization'] = $organization['name'];
		$input_data_big5 = $this->utf8_to_big5($input_data);
		$this->user_model->add_user_for_old_db($input_data_big5);
		//------------------------------------
		echo $this->info_modal("註冊成功");
		return;
		
	}
	public function update_account()
	{
		$this->is_user_login();
		
		//表單驗證
		$this->form_validation->set_rules("ID","身分證字號","required");
		$this->form_validation->set_rules("name","姓名","required");
		$this->form_validation->set_rules("sex","性別","required");
		$this->form_validation->set_rules("organization","服務單位","required");
		$this->form_validation->set_rules("department","系所或部門","required");
		$this->form_validation->set_rules("tel","聯絡電話","required");
		$this->form_validation->set_rules("mobile","行動電話","required");
		$this->form_validation->set_rules("email","E-Mail","required|valid_email");
		$this->form_validation->set_rules("status","身份別","required");
	
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","warning");
			return;
		}
		
		$input_data = $this->input->post(NULL,TRUE);
		
		//檢查是不是帳號擁有者
		if(!$this->_get_account_privilege($input_data['ID']))
		{
			echo $this->info_modal("沒有權限","","error");
			return;
		}
		
		
		
		//for old db-------------------------------------
		$organization = $this->user_model->get_org_by_SN($input_data['organization']);
		//------------------------------------
		
		//查詢老闆編號
		$boss_profile = $this->user_model->get_boss_no_by_name($input_data['boss_name']);
		if(empty($boss_profile))
		{
			$input_data['boss_no'] = $this->user_model->add_boss($input_data);
			//for old db-------------------------------------
			$input_data['boss_organization'] = $organization['name'];
			$input_data_big5 = $this->utf8_to_big5($input_data);
			$this->user_model->add_boss_for_old_db($input_data_big5);
			//------------------------------------
		}else{
			$input_data['boss_no'] = $boss_profile['serial_no'];
		}
		
		//更新使用者 
		$this->user_model->update_user_profile($input_data);
		//for old db------------------------------------
		$input_data['organization'] = $organization['name'];
		$this->user_model->update_user_for_old_db($input_data);
		//------------------------------------
		
		echo $this->info_modal("更新成功");
	}
	
	//------------------------ORG---------------------------
	public function list_org()
	{
		try{
			$this->is_admin_login();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('user/list_org',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function query_org()
	{
		try{
			$output['aaData'] = array();
			
			$orgs = $this->user_model->get_org_list()->result_array();
			
			foreach($orgs as $org)
			{
				$row = array();
				$row[] = $org['name'];
				$row[] = $org['VAT'];
				$row[] = $org['address'];
				$row[] = $org['tel'];
				$row[] = $org['status_name'];
				$row[] = $org['aliance_name'];
				$display = array();
				$display[] = anchor("/org/edit/".$org['serial_no'],"編輯","class='btn btn-warning btn-small'");
				$display[] = form_button("del","刪除","class='btn btn-danger btn-small' value='{$org['serial_no']}'");
				$row[] = implode(' ',$display);
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function form_org()
	{
		try{
			$this->is_admin_login();
			
			$this->data['status_ID_select_options'] = $this->user_model->get_org_status_ID_select_options();
			
			$this->data['aliance_no_select_options'] = $this->user_model->get_aliance_no_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('user/edit_org',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_org($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			$org = $this->user_model->get_org_list(array("serial_no"=>$SN))->row_array();
			if(!$org)
			{
				throw new Exception();
			}
			$this->data = $org;
			
			$this->data['status_ID_select_options'] = $this->user_model->get_org_status_ID_select_options();
			
			$this->data['aliance_no_select_options'] = $this->user_model->get_aliance_no_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('user/edit_org',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_org()
	{
		try{
			$org_name = $this->input->post("org",TRUE);
			//判斷是否已存在
			$org = $this->user_model->get_org_list(array("name"=>$org_name))->row_array();
			if($org)
			{
				throw new Exception("該組織單位已存在",ERROR_CODE);
			}
			
			$insert_id = $this->user_model->add_org(array("name"=>$org_name));
			//for old db-----
			$org_name = $this->utf8_to_big5($org_name);
			$result = $this->user_model->add_org_for_old_db($insert_id,$org_name);
			//---------------
			
			if($result)
			{
				echo $this->info_modal("新增成功","/user/form");
			}else{
				echo $this->info_modal("內部錯誤","","error");
			}
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function update_org()
	{
		try{
			$this->is_admin_login();

			$this->form_validation->set_rules("name","名稱","required");
			$this->form_validation->set_rules("status_ID","地位","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			if(empty($input_data['serial_no']))
			{
				//ADD
				
				$duplicated = $this->user_model->get_org_list(array("name"=>$input_data['name']))->num_rows();
				if($duplicated)
				{
					throw new Exception("該組織單位已存在",ERROR_CODE);
				}
				
				$this->user_model->add_org($input_data);
				echo $this->info_modal("新增成功","/org/list");
			}else{
				//UPDATE
				$org = $this->user_model->get_org_list(array("serial_no"=>$input_data['serial_no']))->row_array();
				if(!$org)
				{
					throw new Exception("無此筆資料",ERROR_CODE);
				}
				
				$this->user_model->update_org($input_data);
				echo $this->info_modal("變更成功","/org/list");
			}
			
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_org($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$org = $this->user_model->get_org_list(array("serial_no"=>$SN))->row_array();
			if(!$org)
			{
				throw new Exception("無此筆資料",ERROR_CODE);
			}
			$user_profile_nums = $this->user_model->get_user_profile_list(array("organization"=>$org['serial_no']))->num_rows();
			if($user_profile_nums)
			{
				throw new Exception("該組織已有使用者綁定，不可刪除",ERROR_CODE);
			}
			
			$this->user_model->del_org(array("serial_no"=>$org['serial_no']));
			
			echo $this->info_modal("刪除成功");
						
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//---------------------BOSS------------------------------
	public function list_boss()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
	    $this->load->view('templates/sidebar');
	    $this->load->view('user/list_boss',$this->data);
	    $this->load->view('templates/footer');
	}
	public function query_boss()
	{
		try{
			$this->is_admin_login();
			
			$bosses = $this->user_model->get_boss_list()->result_array();
			
			$output['aaData'] = array();
			foreach($bosses as $boss)
			{
				$row = array();
				
				$row[] = $boss['name'];
				$row[] = $boss['org_name'];
				$row[] = $boss['department'];
				$row[] = $boss['tel'];
				$row[] = $boss['email'];
				$row[] = $boss['new_expiration_time'];
				$display = array();
				$display[] = anchor('boss/edit/'.$boss['serial_no'],"編輯","class='btn btn-small btn-warning'");
				$display[] = form_button("del","刪除","class='btn btn-small btn-danger' value='{$boss['serial_no']}'");
				$row[] = implode(' ',$display);
				$output['aaData'][] = $row;	
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function form_boss()
	{
		try{
			$this->is_admin_login();
			
			//取得ORG列表
			$this->data['org_ID_select_options'] = $this->user_model->get_org_ID_select_options();
			
			$this->load->view('templates/header');
		    $this->load->view('templates/sidebar');
		    $this->load->view('user/edit_boss',$this->data);
		    $this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_boss($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$boss = $this->user_model->get_boss_list(array("serial_no"=>$SN))->row_array();
			if(!$boss)
			{
				throw new Exception();
			}
			
			$this->data = $boss;
			
			//取得ORG列表
			$this->data['org_ID_select_options'] = $this->user_model->get_org_ID_select_options();
			
			$this->load->view('templates/header');
		    $this->load->view('templates/sidebar');
		    $this->load->view('user/edit_boss',$this->data);
		    $this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_boss()
	{
		$this->update_boss();
	}
	public function update_boss()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("organization","組織","required");
			$this->form_validation->set_rules("email","Email","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			if(empty($input_data['serial_no']))
			{
				//ADD
				$this->user_model->add_boss($input_data);
				echo $this->info_modal("新增成功",$this->whence->pop());
			}else{
				//UPDATE
				$this->user_model->update_boss($input_data);
				echo $this->info_modal("更新成功",$this->whence->pop());
			}
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_boss($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$boss = $this->user_model->get_boss_list(array("serial_no"=>$SN))->row_array();
			if(!$boss)
			{
				throw new Exception("無此筆資料",ERROR_CODE);
			}
			$user_profile_nums = $this->user_model->get_user_profile_list(array("boss_no"=>$boss['serial_no']))->num_rows();
			if($user_profile_nums)
			{
				throw new Exception("此位老師/主管有使用者掛名，不可刪除",ERROR_CODE);
			}
			
			$this->user_model->del_boss(array("serial_no"=>$boss['serial_no']));
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//------------------學生打卡----------------
	public function list_clock($location_ID = NULL)
	{
		$location_ID = $this->security->xss_clean($location_ID);
		$this->data['location_ID'] = $location_ID;
		$this->load->model('common_model');
		$this->data['location_ID_select_options'] = $this->common_model->get_location_ID_select_options();
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/list_clock',$this->data);
		$this->load->view('templates/footer');
	}
	//for advertisement
	public function list_advertisement($location_ID = NULL)
	{
		$location_ID = $this->security->xss_clean($location_ID);
		$this->data['location_ID'] = $location_ID;
		$this->load->model('common_model');
		$this->data['location_ID_select_options'] = $this->common_model->get_location_ID_select_options();
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('user/advertisement',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_clock()
	{
		$input_data = $this->input->get(NULL,TRUE);
		
		$data = array("location_ID"=>empty($input_data['location_ID'])?NULL:$input_data['location_ID']);
		
		$results = $this->user_model->get_clock_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($results as $result)
		{
			//如果在門口就進不去(A0)或刷出(01)，就忽略
			if($result['access_status']=="01" || $result['access_status']=="A0")
			{
				//先判斷其父親的location是否跟目前刷卡的位置一樣
				//若是，代表並非最外面的門，不可忽略
				//若否，代表人員在最外面的門就被擋住了或已刷出最後一道門，即可忽略
				//好像可以用另一種方法就不用query
				//理論上location_ID比較有直覺性，但是要多一道query去判斷是否為最後一扇門就是了...
				//因此我不想用facility_ID當作最一開始的input...
				$this->load->model('facility_model');
				$facility = $this->facility_model->get_facility_list(array("ID"=>$result['facility_parent_ID']))->row_array();
				if(!$facility) continue;
				if($facility['location_ID']!=$result['location_ID']) continue;
			}
			$row = array();
			if(empty($result['user_name'])){
				if(empty($result['guest_name'])){
					$row[] = "未知人員";
				}else{
					$row[] = $result['guest_name'];
				}
			}else{
				$row[] = $result['user_name'];
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	public function add_clock()
	{
		
	}
	
	/**
	* 
	* @param string $ID
	* 
	* @return boolean
	*/
	private function _get_account_privilege($user_ID)
	{
		if(empty($user_ID)) $user_ID = $this->session->userdata('ID');
		if($this->session->userdata('ID')!=$user_ID && !$this->is_admin_login(FALSE))
			return FALSE;
		else
			return TRUE;
	}
}