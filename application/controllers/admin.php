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
		$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$ID))->row_array();
		
		$this->data['ID'] = $admin_profile['ID'];
		$this->data['name'] = $admin_profile['name'];
		$this->data['email'] = $admin_profile['email'];
		$this->data['mobile'] = $admin_profile['mobile'];
		$this->data['card_num'] = $user_profile['card_num'];
		$this->data['stamp'] = $admin_profile['stamp'];
		$this->data['action'] = site_url()."/admin/update";
	}
	
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
			$row[] = $r['user_tel'];
			$row[] = $r['user_mobile'];
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
		
		$data = array("clock_user_ID"=>$this->session->userdata('ID'),
					  "clock_start_time"=>date("Y-m-d H:i:s"));
		$clocks = $this->admin_model->get_manual_clock_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($clocks as $clock)
		{
			$row = array();
			$row[] = $clock['clock_time'];
			$row[] = $clock['clock_remark'];
			$row[] = $clock['clock_start_time'];
			$row[] = $clock['clock_end_time'];
			$row[] = form_button("del","刪除","class='btn btn-warning' value='{$clock['clock_ID']}'");
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
		
			$data = array(
				"clock_user_ID"=>isset($input_data['clock_user_ID'])&&$this->admin_clock_model->is_super_admin()?$input_data['clock_user_ID']:$this->session->userdata('ID'),
				"clock_start_time"=>date("Y-m-d H:i:s",strtotime($input_data['clock_start_date'].' '.$input_data['clock_start_time'])),
				"clock_end_time"=>isset($input_data['clock_end_date'])&&isset($input_data['clock_end_time'])?date("Y-m-d H:i:s",strtotime($input_data['clock_end_date'].' '.$input_data['clock_end_time'])):NULL,
				"clock_location"=>$input_data['clock_location'],
				"clock_reason"=>$input_data['clock_reason']
			);
			
			$this->admin_model->add_clock($data);
			
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
			
			$this->admin_model->del_clock(array("clock_ID"=>$clock_ID));
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
  //-------------------------------------------------------------------------------------------------------------
  public function clock(){
  	
	$this->load->view('admin/clock',$this->data);
  }
}
