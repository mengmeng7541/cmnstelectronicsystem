<?php      
class Admin extends MY_Controller {
  private $data = array();
	
  public function __construct()
  {
    parent::__construct();
    $this->load->model('common_model');
    $this->load->model('admin_model');
    $this->load->model('user_model');
    
    $this->load->model('clock/admin_clock_model');
  }
  
  public function index()
  {
    $this->session->sess_destroy();
    $this->load->view('/login.php');
    
  }
  
  public function login()
  {
  	$input_data = $this->input->post(NULL,TRUE);
	
    //login verify
    if($this->admin_model->login_access($input_data['ID'],$this->admin_model->hash_passwd($input_data['passwd'])))
    {//login success
      //register session
      $admin_profile = $this->admin_model->get_admin_profile_by_ID($input_data['ID']);
	  
	  //註冊admin session
	  $this->session->set_userdata("ID",$admin_profile['ID']);
      $this->session->set_userdata("status","admin");
	  
      //temporary
      redirect('/admin/clock/list');
    }
    else
    {
      //login false
      redirect('/admin');
    }
  }
  
  public function main()
  {
    $this->is_admin_login();
    
    $this->load->view('templates/header');
    $this->load->view('templates/sidebar');
    $this->load->view('admin/main');
    $this->load->view('templates/footer');
  }
  
  public function list_account()
  {
  	$this->is_admin_login();
  	
  	
	
	$this->load->view('templates/header');
    $this->load->view('templates/sidebar');
    $this->load->view('admin/list');
    $this->load->view('templates/footer');
  }
  public function list_account_query()
  {
  	try{
  		$this->is_admin_login();
  		
		$output['aaData'] = array();
		
		$admin_profiles = $this->admin_model->get_admin_profile_list()->result_array();
		foreach($admin_profiles as $admin_profile)
		{
			$row = array();
			$row[] = $admin_profile['ID'];
			$row[] = $admin_profile['name'];
			$row[] = $admin_profile['email'];
			$row[] = $admin_profile['mobile'];
			$row[] = $admin_profile['card_num'];
			$row[] = empty($admin_profile['stamp'])?"":img($admin_profile['stamp']);
			$row[] = $admin_profile['suspended']?"鎖定":"正常";
			$row[] = anchor("/admin/edit/".$admin_profile['ID'],"編輯","class='btn btn-warning btn-small'");
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}catch(Exception $e){
		echo json_encode($output);
	}
  }
  public function edit_account($ID = "")
  {
	$this->is_admin_login();
	
	if(empty($ID))
	{
		$this->data['action'] = site_url()."/admin/add";
	}else{
		$admin_profile = $this->admin_model->get_admin_profile_by_ID($ID);
		
		$this->data = $admin_profile;
		$this->data['action'] = site_url()."/admin/update";
	}
	
	$this->data['org_chart_group_no_name_array'] = $this->admin_model->get_org_chart_group_array();
	$this->data['org_chart_team_no_name_array'] = $this->admin_model->get_org_chart_team_array();
	$this->data['org_chart_status_no_name_array'] = $this->admin_model->get_org_chart_status_array();
	
	$this->load->view('templates/header');
    $this->load->view('templates/sidebar');
    $this->load->view('admin/edit',$this->data);
    $this->load->view('templates/footer');
  }
  public function add_account()
  {
  	$this->is_admin_login();
	
	$input_data = $this->input->post(NULL,TRUE);
	
	//先新增使用者
	$data = array(	"ID"=>$input_data['ID'],
					"passwd"=>$input_data['passwd'],
					"name"=>$input_data['name'],
					"mobile"=>$input_data['mobile'],
					"email"=>$input_data['email'],
					"group"=>"admin");
	$this->user_model->add_user($data);
	
	$input_data['passwd'] = $this->admin_model->hash_passwd($input_data['passwd']);
	
	
	$this->admin_model->add_account($input_data);
	
	
	echo $this->info_modal("新增成功","/admin/list");
  }
  public function update_account()
  {
  	$this->is_admin_login();
	
	$input_data = $this->input->post(NULL,TRUE);
	if(!empty($input_data['passwd']))
		$input_data['passwd'] = $this->admin_model->hash_passwd($input_data['passwd']);
	
	$config['upload_path'] = 'stamp/';
	$config['allowed_types'] = 'png|jpg|gif';
	$config['max_size']	= '1000';
	$this->load->library('upload',$config);
	
	if(empty($_FILES['userfile']))
	{
		$this->admin_model->update_account($input_data);
		$this->user_model->update_user_profile($input_data);
		$this->user_model->update_user_card_num($input_data['ID'],$input_data['card_num']);
	}else{
		if ( !$this->upload->do_upload())
		{
	    	//上傳失敗
			$error = $this->upload->display_errors();
			echo $this->info_modal($error,"","warning");
			return;
		}
		$upload_data = $this->upload->data();
    	//插入資料庫
		$input_data['stamp'] = $config['upload_path'].$upload_data['file_name'];
		$this->admin_model->update_account($input_data);
		$this->user_model->update_user_profile($input_data);
		$this->user_model->update_user_card_num($input_data['ID'],$input_data['card_num']);
	}
	echo $this->info_modal("更新成功","/admin/list");
  }
  //----------------人員打卡-----------------------
	public function list_clock($screen = "")
	{
		$screen = $this->security->xss_clean($screen);
		
		$this->data['screen'] = $screen;
		
		if($screen == "full"){
			$this->load->view('admin/list_clock',$this->data);
		}else{
			
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('admin/list_clock',$this->data);
			$this->load->view('templates/footer');
		}
	}
	public function query_auto_clock()
	{
		$this->load->model('facility_model');
		
		$results = $this->admin_model->get_auto_clock_list()->result_array();
		//取得預設位置
		$location = $this->common_model->get_location_list()->row_array();
		$output['aaData'] = array();
		foreach($results as $r)
		{
			$row = array();
			
			$row[] = date("Y-m-d",strtotime($r['access_last_date']));
			$row[] = $r['access_last_time'];
			$row[] = $r['access_state'];
			$row[] = $r['user_name'];
			$row[] = $r['user_status_ID'];
			$row[] = $r['admin_tel_ext'];
			$row[] = $r['user_mobile'];
			$row[] = $r['facility_tel_ext'];
			$row[] = $r['location_cht_name'];
			$row[] = $r['location_tel'];
			$row[] = $r['clock_start_time'];
			$row[] = $r['clock_end_time'];
			$row[] = $r['clock_location'];
			$row[] = $r['clock_reason'];
			$row[] = $r['clock_remark'];
			
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	public function query_manual_clock()
	{
		$this->is_admin_login();
		
		if($this->admin_clock_model->is_super_admin())
		{
			$data = array("clock_start_time_start_time"=>date("Y-m-d H:i:s",strtotime("-30minutes")));
		}else{
			$data = array(
				"clock_user_ID"=>$this->session->userdata('ID'),
				"clock_start_time_start_time"=>date("Y-m-d H:i:s",strtotime("-30minutes"))
			);
		}
		
		$clocks = $this->admin_model->get_manual_clock_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($clocks as $clock)
		{
			$row = array();
			$row[] = $clock['clock_user_name'];
			$row[] = $clock['clock_reason'].'@'.$clock['clock_location'];
			$row[] = $clock['clock_start_time'];
			$row[] = $clock['clock_end_time'];
			$row[] = form_button("del","刪除","class='btn btn-warning btn-small' value='{$clock['clock_ID']}'");
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	public function add_clock()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("clock_start_date","起始日期","required");
			$this->form_validation->set_rules("clock_start_time","起始時間","required");
			$this->form_validation->set_rules("clock_location","地點","required");
			$this->form_validation->set_rules("clock_reason","事由","required");
			if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			
			if(!empty($input_data['clock_end_date'])&&!empty($input_data['clock_end_time']))
			{
				//檢查起始時間必須小於結束時間
				if(strtotime($input_data['clock_end_date'].' '.$input_data['clock_end_time'])<=strtotime($input_data['clock_start_date'].' '.$input_data['clock_start_time']))
				{
					throw new Exception("起始時間不可大於結束時間",WARNING_CODE);
				}
			}		
	
			$data = array(
				"clock_user_ID"=>isset($input_data['clock_user_ID'])&&$this->admin_clock_model->is_super_admin()?$input_data['clock_user_ID']:$this->session->userdata('ID'),
				"clock_start_time"=>date("Y-m-d H:i:s",strtotime($input_data['clock_start_date'].' '.$input_data['clock_start_time'])),
				"clock_end_time"=>!empty($input_data['clock_end_date'])&&!empty($input_data['clock_end_time'])?date("Y-m-d H:i:s",strtotime($input_data['clock_end_date'].' '.$input_data['clock_end_time'])):NULL,
				"clock_location"=>$input_data['clock_location'],
				"clock_reason"=>$input_data['clock_reason']
			);
			
			$this->admin_model->add_clock($data);
			
			//寄信給組長
			$org_charts = $this->admin_model->get_org_chart_list(array("admin_ID"=>$data['clock_user_ID']))->result_array();
			foreach($org_charts as $org_chart){
				$managers = $this->admin_model->get_org_chart_list(array("team_no"=>$org_chart['team_no'],"status_ID"=>"section_chief"))->result_array();
				foreach($managers as $manager){
					$this->email->to($manager['admin_email']);
					$this->email->subject("成大微奈米科技研究中心 -中心人員外出通知-");
					$message = "
						{$manager['team_name']} {$manager['status_name']} {$manager['admin_name']} 您好：<br>
						{$org_chart['team_name']} {$org_chart['status_name']} {$org_chart['admin_name']} 將於 {$data['clock_start_time']} 因 {$data['clock_reason']} 外出至 {$data['clock_location']}，系統特此通知，謝謝。
					";
					if(isset($data['clock_end_time'])){
						$message .= "預計直到 {$data['clock_end_time']}";
					}
					$this->email->message($message);
					$this->email->send();
				}
			}
			
			echo $this->info_modal("新增成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function del_clock($clock_ID)
	{
		try{
			$this->is_admin_login();
			
			$clock_ID = $this->security->xss_clean($clock_ID);
			
			$this->admin_clock_model->del($clock_ID);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//------------------------人員組織架構----------------------
	public function get_org_chart_group_team_status_json()
	{
		try{
			$this->is_admin_login();
			
			$this->load->model('common/admin_org_chart_model');
			$output = $this->admin_org_chart_model->get_group_team_status_array();
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode(array());
		}
	}
	public function query_org_chart()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->get(NULL,TRUE);
			
			$charts = $this->admin_model->get_org_chart_list(array(
				"admin_ID"=>$input_data['admin_ID']
			))->result_array();
			
			$output['aaData'] = array();
			foreach($charts as $chart){
				$row = array();
				$row[] = $chart['group_name'];
				$row[] = $chart['team_name'];
				$row[] = $chart['status_name'];
				$row[] = form_button("del","刪除","class='btn btn-danger btn-small' value='{$chart['serial_no']}'");
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function add_org_chart()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("admin_ID","人員","required");
			$this->form_validation->set_rules("group_no","群組","required");
			$this->form_validation->set_rules("team_no","團隊","required");
			$this->form_validation->set_rules("status_no","身分","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			$this->admin_model->add_org_chart(elements(array("admin_ID","group_no","team_no","status_no"),$input_data));
			
			echo $this->info_modal("新增成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_org_chart()
	{
		
	}
	public function del_org_chart($SN="")
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			$this->admin_model->del_org_chart(array("serial_no"=>$SN));
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
  //-----------------------------------------------------------------------------
  public function clock(){
	$this->load->view('admin/clock',$this->data);
  }
}
