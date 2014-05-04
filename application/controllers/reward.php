<?php
class Reward extends MY_Controller {
	
	public $data;
	

	public function __construct()
	{
		parent::__construct();
    
	
		$this->load->model("user_model","user_model");
		$this->load->model("reward_model","reward_model");
		$this->load->model("admin_model","admin_model");
		
		
	}

	public function form()
	{
		$this->data['serial_no'] = form_input("serial_no","","class='span6' readonly='readonly'");
		
		$this->data['applicant_name'] = form_input("applicant_name","","class='span6'");
		
		$this->data['department'] = form_input("department","","class='span6'");
		
		$this->data['tel'] = form_input("tel","","class='span6'");
		
		$this->data['email'] = form_input("email","","class='span6'");
		
		$this->data['research_field'] = "<label class='checkbox'>".form_checkbox("research_field[]","奈米材料","")."奈米材料</label>
                                 		 <label class='checkbox'>".form_checkbox("research_field[]","奈米檢測","")."奈米檢測</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","奈米精密加工","")."奈米精密加工</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","分子診斷與治療","")."分子診斷與治療</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","其它","")."其它</label>";
		
		$this->data['paper_title'] = form_input("paper_title","","class='span6'");
		
		$this->data['journal'] = form_input("journal","","class='span6'");
		
		$book_year_select_options = array('2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015');
		$this->data['journal_year'] = form_dropdown("journal_year",$book_year_select_options,"","class='span6'");
		
		$boss = $this->user_model->get_boss();
		$awardees_select_options = array(""=>"");
	    foreach($boss as $row){
			$awardees_select_options[$row['serial_no']] = $row['name'];
		}
		$this->data['awardees'] = form_dropdown("awardees_no",$awardees_select_options,"","class = 'span6 chosen'");
		
		$plan = $this->reward_model->get_plan();
		foreach($plan as $row)
		{
			$plan_select_options[$row['serial_no']] = $row['name'];
		}
		$this->data['apply_plan'] = form_dropdown("apply_plan_no",$plan_select_options,"","class='span6'");
		
		$this->data['file_name'] = form_upload("userfile","","");
		
		$this->data['review_date'] = "";
		
		$this->data['reviewer'] = "";
		
		$this->data['result'] = "";
		
		$this->data['action_btn'] = form_submit("","送出","class='btn btn-success'").form_reset("","重設","class='btn'");
			
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
	$applications = $this->reward_model->get_application();
	
	$output['aaData'] = array();
	foreach($applications->result_array() as $app)
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
			$row[] = anchor("reward/edit/{$app['serial_no']}","結案","class='btn btn-small btn-inverse'");
		else
			$row[] = anchor("reward/edit/{$app['serial_no']}","審核","class='btn btn-small btn-success'");
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode($output);
  }
  
  public function edit($serial_no)
  {
  	if($this->session->userdata('status')!="admin")
    {
      redirect('/admin');
      return;
    }
    //檢查有否權限
    if(!$this->reward_model->get_privilege('reward_reviewer'))
    {
      redirect('/admin/main');
      return;
    }
    
    //取得申請者資料
    $application = $this->reward_model->get_application(array("serial_no"=>$serial_no));
	$application = $application->row_array();
	if(!$application)
	{
		$this->show_error_page();
		return;
	}
	$this->data['serial_no'] = form_input("serial_no",$application['serial_no'],"class='span6' readonly='readonly'");
	$this->data['application_date'] = $application['application_date'];
	$this->data['applicant_name'] = form_input("applicant_name",$application['applicant_name'],"class='span6' readonly='readonly'");
	$this->data['department'] = form_input("department",$application['department'],"class='span6' readonly='readonly'");
	$this->data['tel'] = form_input("tel",$application['tel'],"class='span6' readonly='readonly'");
	$this->data['email'] = form_input("email",$application['email'],"class='span6' readonly='readonly'");
	$this->data['research_field'] = form_input("research_field",$application['research_field'],"class='span6' readonly='readonly'");
	$this->data['paper_title'] = form_input("paper_title",$application['paper_title'],"class='span6' readonly='readonly'");
	$this->data['journal'] = form_input("journal",$application['journal'],"class='span6' readonly='readonly'");
	$this->data['journal_year'] = form_input("journal_year",$application['journal_year'],"class='span6' readonly='readonly'");
	$boss = $this->user_model->get_boss_by_SN($application['awardees_no']);
	$this->data['awardees'] = form_input("awardees_no",isset($boss['name'])?$boss['name']:"","class='span6' readonly='readonly'");
	$apply_plan = $this->reward_model->get_plan_by_SN($application['apply_plan_no']);
	$this->data['apply_plan'] = form_input("apply_plan_no",$apply_plan['name'],"class='span6' readonly='readonly'");
	$this->data['file_name'] = anchor(base_url("document/{$application['upload_file']}"),$application['upload_file']);
	if($application['is_review'])
	{
		$this->data['review_date'] = form_input("review_date",$application['review_date'],"class='span6' readonly='readonly'");
		$this->data['reviewer'] = form_input("reviewer_name",$application['reviewer_name'],"class='span6' readonly='readonly'");
		$plan = $this->reward_model->get_plan_by_SN($application['accept_plan_no']);
		$this->data['result'] = "<label class='radio'>".form_radio("result","1",$application['result']," disabled='disabled'")."符合</label>".form_input("accept_plan_no",$plan['name'],"class='span5' readonly='readonly'")." <label class='radio'>".form_radio("result","0",!$application['result'],"disabled='disabled'")."不符合</label>".form_input("deny_reason",$application['deny_reason'],"class='span4' readonly='readonly'");
		$this->data['action_btn'] = anchor("reward/list","回前頁","class='btn btn-inverse'");
	}else{
		$this->data['review_date'] = "";
		$this->data['reviewer'] = "";
		$plan = $this->reward_model->get_plan();
		foreach($plan as $row)
		{
			$plan_select_options[$row['serial_no']] = $row['name'];
		}
		$this->data['result'] = "<label class='radio'>".form_radio("result","1")."符合</label>".form_dropdown("accept_plan_no",$plan_select_options,"","class='span5'").nbs(8)."<label class='radio'>".form_radio("result","0")."不符合</label>".form_input("deny_reason","","class='span4'");
		$this->data['action_btn'] = form_button("update","送出","class='btn btn-success'");
	}
	
	
	$this->load->view('templates/header');
	$this->load->view('templates/sidebar');
    $this->load->view('reward/form',$this->data);
  	$this->load->view('templates/footer');
  }
  
  public function update()
  {
  	if($this->session->userdata('status')!="admin")
    {
		redirect('/admin');
		return;
	}
  	//檢查有否權限
    if(!$this->reward_model->get_privilege('reward_reviewer'))
    {
		echo $this->info_modal("沒有權限","/admin/main");
		return;
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
    $reward_data = $this->reward_model->get_application(array("serial_no",$input_data['serial_no']))->row_array();
    
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
	
  }
  
  public function delete($SN)
  {
  	if($this->session->userdata('status')!="admin")
    {
		echo $this->info_modal("沒有權限","/admin");
		return;
	}
	//檢查有否權限
    if(!$this->reward_model->get_privilege('reward_reviewer'))
    {
		echo $this->info_modal("沒有權限","/admin/main");
		return;
    }
	$this->reward_model->delete_application($SN);
	
	echo $this->info_modal("刪除成功","/reward/list");
  }
}