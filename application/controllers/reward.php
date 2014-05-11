<?php
class Reward extends MY_Controller {
	
	public $data;
	

	public function __construct()
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("reward_model");
		$this->load->model("admin_model");
	}

	public function form()
	{
		
		$this->data['awardees_select_options'] = $this->user_model->get_boss_ID_select_options();
	    
		
		//載入可以用的選項
		$this->data['plan_select_options'] = $this->reward_model->get_plan_ID_select_options();
		
		$this->data['action_btn'][] = form_submit("","送出","class='btn btn-success'");
		$this->data['action_btn'][] = form_reset("","重設","class='btn'");
			
		$this->load->view('templates/header');
	    $this->load->view('templates/sidebar');
	  	$this->load->view('reward/form', $this->data);
	  	$this->load->view('templates/footer');
	}
  public function add()
  {
    $input_data = $this->input->post(NULL,TRUE);
	$input_data['research_field'] = implode(",",$input_data['research_field']);
				
     //先檢查是否重複申請同一篇
	if(empty($input_data['paper_title']))
	{
	  echo $this->info_modal("論文名稱不可為空","","warning");
      return;
	}
    if($this->reward_model->get_application_by_paper_title($input_data['paper_title']) != FALSE)
    {
      echo $this->info_modal("您的論文已存在，請勿重複申請，謝謝！","","warning");
      return;
    }
  	$config['upload_path'] = './document/';
	$config['allowed_types'] = 'pdf';
	$config['max_size']	= '64000';

	$this->load->library('upload',$config);

	if ( ! $this->upload->do_upload())
	{
    	//上傳失敗
		$error = $this->upload->display_errors();

		echo $this->info_modal($error,"","warning");
    
	}
	else
	{  
		$upload_data = $this->upload->data();
    	//插入資料庫
		$this->reward_model->add_application($input_data,$upload_data);
           
    	
		
		//send email
		$reviewer = $this->reward_model->get_admin_by_privilege("reward_reviewer");
		$reviewer = $this->admin_model->get_admin_profile_by_ID($reviewer['admin_ID']);
		
  
		$this->email->to($reviewer['email']); 

		$this->email->subject('成大微奈米科技研究中心 -論文獎勵系統通知-');
		$this->email->message("
                        <p>您好： </p>
                        <p>有人投稿中心的論文</p>
                        <p>請點選此<a href='http://140.116.176.44/index.php'>連結</a>登入審核</p>
                        <p>帳號{$reviewer['ID']}</p>"); 
		$this->email->send();
  
		//顯示申請完成頁面
		echo $this->info_modal("您已申請完成，請待本中心審核後通知結果，謝謝！");
	}

  }
  
  public function list_application()
  {
  	$this->is_admin_login();
  	
  	//檢查有否權限
    if(!$this->reward_model->get_privilege('reward_reviewer'))
    {
      redirect('/admin/main');
      return;
    }
    
    //display
	$this->load->view('templates/header');
	$this->load->view('templates/sidebar');
    $this->load->view('reward/list',$this->data);
    $this->load->view('templates/footer');
  }
  
  public function query_application()
  {
  	$this->is_admin_login();
  	
  	if(!$this->reward_model->get_privilege('reward_reviewer'))
    {
      redirect('/admin/main');
      return;
    }
    
  	//取得申請的基本資料列表
	$applications = $this->reward_model->get_application_list()->result_array();
	
	$output['aaData'] = array();
	foreach($applications as $app)
	{
		$row = array();
		
		if($app['is_review'])
			$row[] = form_button("","刪除","class='btn btn-small disabled'");
		else
			$row[] = form_button("del_row","刪除","class='btn btn-small btn-danger' value='{$app['serial_no']}'");
		$row[] = $app['serial_no'];
		$row[] = $app['applicant_name'];
		$row[] = $app['paper_title'];
		if($app['is_review'])
			$row[] = anchor("reward/view/{$app['serial_no']}","結案","class='btn btn-small btn-inverse'");
		else
			$row[] = anchor("reward/edit/{$app['serial_no']}","審核","class='btn btn-small btn-success'");
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
  }
  
  public function edit($serial_no)
  {
  	try{
		$this->is_admin_login();
	    //檢查有否權限
	    if(!$this->reward_model->get_privilege('reward_reviewer'))
	    {
			throw new Exception();
	    }
	    //取得申請者資料
	    $application = $this->reward_model->get_application_list(array("serial_no"=>$serial_no))->row_array();
		if(!$application)
		{
			throw new Exception();
		}
		$this->data = $application;
		$this->data['readonly'] = TRUE;
		
		$this->data['awardees_select_options'] = $this->user_model->get_boss_ID_select_options();
		
		$this->data['plan_select_options'] = $this->reward_model->get_plan_ID_select_options(FALSE);
		
		$this->data['action_btn'][] = form_button("update","送出","class='btn btn-success'");
		$this->data['action_btn'][] = anchor("reward/list","回前頁","class='btn btn-inverse'");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
	    $this->load->view('reward/form',$this->data);
	  	$this->load->view('templates/footer');
		
	}catch(Exception $e){
		$this->show_error_page();
	}
	
  }
  public function view($SN)
  {
  	try{
		$this->is_admin_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$application = $this->reward_model->get_application_list(array("serial_no"=>$SN))->row_array();
		if(!$application)
		{
			throw new Exception();
		}
		$this->data = $application;
		
		$this->data['readonly'] = TRUE;
		
		$this->data['awardees_select_options'] = $this->user_model->get_boss_ID_select_options();
		
		$this->data['plan_select_options'] = $this->reward_model->get_plan_ID_select_options(FALSE);
		
		$this->data['action_btn'] = anchor("reward/list","回前頁","class='btn btn-inverse'");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
	    $this->load->view('reward/form',$this->data);
	  	$this->load->view('templates/footer');
	}catch(Exception $e){
		$this->show_error_page();
	}
  	
  }
  
  public function update()
  {
  	try{
		$this->is_admin_login();
		
	  	//檢查有否權限
	    if(!$this->reward_model->is_super_admin())
	    {
			throw new Exception("沒有權限",ERROR_CODE);
	    }
	    
	    $this->form_validation->set_rules("result","審查結果","required");
	    if(!$this->form_validation->run())
	    {
			throw new Exception(validation_errors(),WARNING_CODE);
		}
	    
		$input_data = $this->input->post(NULL,TRUE);
		
		$reviewer = $this->admin_model->get_admin_profile_by_ID($this->session->userdata('ID'));
		$input_data['reviewer_name'] = $reviewer['name'];
	  
		//送出前先處理
		if($input_data['result'])
			$input_data['deny_reason'] = '';
		else
			$input_data['accept_plan_no'] = '';
	  
	  
		//更新資料
		$this->reward_model->update_application($input_data);
		
		//email
	    $reward_data = $this->reward_model->get_application_list(array("serial_no",$input_data['serial_no']))->row_array();
	    
	    $this->email->from($reviewer['email'], $reviewer['name']);
		$this->email->to($reward_data['email']); 

		$this->email->subject('成大微奈米中心論文獎勵系統審核結果通知');
		$plan = $this->reward_model->get_plan_by_SN($reward_data['accept_plan_no']);
		if(empty($plan['name']))
			$plan['name'] = "";
		if($reward_data['result'])
			$reward_data['result'] = '符合';
		else
			$reward_data['result'] = '不符合';
		$this->email->message("
	                        <p>{$reward_data['applicant_name']} 老師您好： 
	                        <p>感謝您參加成功大學微奈米科技研究中心的論文獎勵活動，您的論文 {$reward_data['paper_title']} 已審核完畢</p>
	                        <p>審核結果為：{$reward_data['result']} </p>
	                        <p>{$plan['name']}{$reward_data['deny_reason']}</p>
	                        <p>有任何問題歡迎再與本中心聯絡，謝謝！ </p>"); 
		$this->email->send();
	  
		echo $this->info_modal("審核成功","/reward/list");
	}catch(Exception $e){
		echo $this->info_modal($e->getMessage(),"",$e->getCode());
	}
  	
	
  }
  
  public function delete($SN)
  {
  	try{
		$this->is_admin_login();
		
		if($this->reward_model->is_super_admin())
		{
			throw new Exception("沒有權限",ERROR_CODE);
		}
		
		$SN = $this->security->xss_clean($SN);
		
		$this->reward_model->delete_application($SN);
		
		echo $this->info_modal("刪除成功");
	}catch(Exception $e){
		echo $this->info_modal($e->getMessage(),"",$e->getCode());
	}
  }
  //------------CONFIG----------------
  public function edit_config()
  {
  	try{
		$this->is_admin_login();
		
		$this->load->model('admin_model');
		$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
		
		//取得管理員名單
		$admin_list = $this->reward_model->get_admin_privilege_list(array("privilege"=>"reward_super_admin"))->result_array();
		if($admin_list)
		{
			$this->data['admin_ID'] =  sql_result_to_column($admin_list,"admin_ID");
		}
		
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
	    $this->load->view('reward/config',$this->data);
	  	$this->load->view('templates/footer');
	}catch(Exception $e){
		$this->show_error_page();
	}
  }
  public function update_config()
  {
  	try{
		$this->is_admin_login();
		
		if(!$this->reward_model->is_super_admin())
		{
			throw new Exception("權限不足",ERROR_CODE);
		}
		
		$this->form_validation->set_rules("admin_ID[]","管理員","required");
		if(!$this->form_validation->run())
		{
			throw new Exception(validation_errors(),WARNING_CODE);
		}
		
		$input_data = $this->input->post(NULL,TRUE);
		
		//先刪掉
		$privileges = $this->reward_model->get_admin_privilege_list(array("privilege"=>"reward_super_admin"))->result_array();
		foreach($privileges as $privilege)
		{
			$this->reward_model->del_admin_privilege(array("serial_no"=>$privilege['serial_no']));
		}
		//再新增
		$this->reward_model->add_admin_privilege(array(
			"admin_ID"=>$input_data['admin_ID'],
			"privilege"=>"reward_super_admin"
		));
		
		echo $this->info_modal("變更成功");
	}catch(Exception $e){
		echo $this->info_modal($e->getMessage(),"",$e->getCode());
	}
  }
  //--------------PLAN----------------
  public function query_plan()
  {
  	try{
  		$this->is_admin_login();
  		
  		$plans = $this->reward_model->get_plan_list()->result_array();
  		
		$output['aaData'] = array();
		foreach($plans as $plan)
		{
			$row = array();
			$row[] = $plan['name'];
			$row[] = $plan['points'];
			$row[] = $plan['available']?"是":"否";
			$display = array();
			$display[] = anchor("reward/plan/edit/".$plan['serial_no'],"編輯","class='btn btn-small btn-warning'");
			$display[] = form_button("del","刪除","class='btn btn-small btn-danger' value='{$plan['serial_no']}'");
			$row[] = implode(' ',$display);
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}catch(Exception $e){
		echo json_encode($output);
	}
  }
  public function form_plan()
  {
  	try{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
	    $this->load->view('reward/edit_plan',$this->data);
	  	$this->load->view('templates/footer');
	}catch(Exception $e){
		$this->show_error_page();
	}
  }
  public function edit_plan($SN = "")
  {
  	try{
		$this->is_admin_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$plan = $this->reward_model->get_plan_list(array("serial_no"=>$SN))->row_array();
		if(!$plan){
			throw new Exception();
		}
		
		$this->data = $plan;
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
	    $this->load->view('reward/edit_plan',$this->data);
	  	$this->load->view('templates/footer');
	}catch(Exception $e){
		$this->show_error_page();
	}
  }
  public function add_plan()
  {
  	$this->update_plan();
  }
  public function update_plan()
  {
  	try{
		$this->is_admin_login();
		
		$this->form_validation->set_rules("name","方案名稱","required");
		$this->form_validation->set_rules("points","獎勵金額","required");
//		$this->form_validation->set_rules("available","是否開放","required");
		if(!$this->form_validation->run())
		{
			throw new Exception(validation_errors(),WARNING_CODE);
		}
		
		$input_data = $this->input->post(NULL,TRUE);
		$input_data['available'] = empty($input_data['available'])?0:1;
		
		if(!isset($input_data['serial_no'])){
			//ADD
			$this->reward_model->add_plan($input_data);
			echo $this->info_modal("新增成功","/reward/config/edit");
		}else{
			//UPDATE
			$this->reward_model->update_plan($input_data);
			echo $this->info_modal("變更成功","/reward/config/edit");
		}
	}catch(Exception $e){
		echo $this->info_modal($e->getMessage(),"",$e->getCode());
	}
  }
  public function del_plan($SN = "")
  {
  	try{
		$this->is_admin_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$plan = $this->reward_model->get_plan_list(array("serial_no"=>$SN))->row_array();
		if(!$plan){
			throw new Exception("無此筆資料",ERROR_CODE);
		}
		
		$application_nums = $this->reward_model->get_application_list(array("apply_plan_no"=>$plan['serial_no']))->num_rows();
		if($application_nums)
		{
			throw new Exception("已有申請單使用此方案，不可刪除",ERROR_CODE);
		}
		$this->reward_model->del_plan(array("serial_no"=>$plan['serial_no']));
		
		echo $this->info_modal("刪除成功");
	}catch(Exception $e){
		echo $this->info_modal($e->getMessage(),"",$e->getCode());
	}
  }
}