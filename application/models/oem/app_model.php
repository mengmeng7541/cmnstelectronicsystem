<?php
class App_model extends Oem_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('oem_model');
	}
	
	
	//---------------------APP-------------------------------
	public function add($form_SN,$description,$app_cols,$app_type = 'normal')
	{
		$form = $this->oem_model->get_form_list(array("form_SN"=>$form_SN))->row_array();
		if(!$form)
		{
			throw new Exception("無此表單",ERROR_CODE);
		}
		$form_cols = $this->oem_model->get_form_col_list(array("form_SN"=>$form_SN,"col_enable"=>1))->result_array();
		if($form['form_parent_SN'])
		{
			$form_parent_cols = $this->oem_model->get_form_col_list(array("form_SN"=>$form['form_parent_SN'],"col_enable"=>1))->result_array();
		}
		
		//檢查欄位是否都已填
		if(isset($form_parent_cols))
		{
			foreach($form_parent_cols as $form_parent_col)
			{
				if($form_parent_col['col_rule']=="required")
				{
					$app_col = array_filter($app_cols,function($app_col) use($form_parent_col){
						return $app_col['form_col_SN']==$form_parent_col['form_col_SN'];
					});
					$_POST = reset($app_col);
					$this->form_validation->set_rules("col_value",$form_parent_col['col_cht_name'],$form_parent_col['col_rule']);
					if(!$this->form_validation->run())
					{
						throw new Exception(validation_errors(),WARNING_CODE);
					}
				}else{
					
				}
			}
		}
		foreach($form_cols as $form_col)
		{
			if($form_col['col_rule']=="required")
			{
				$app_col = array_filter($app_cols,function($app_col) use($form_col){
					return $app_col['form_col_SN']==$form_col['form_col_SN'];
				});
				$_POST = reset($app_col);
				$this->form_validation->set_rules("col_value",$form_col['col_cht_name'],$form_col['col_rule']);
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
			}else{
				
			}
		}
		
		
		$app_SN = $this->oem_model->add_app(array(
			"form_SN"=>$form_SN,
			"app_type"=>$app_type,
			"app_description"=>$description,
			"app_checkpoint"=>"user_init"
		));
		
		if(isset($form_parent_cols))
		{
			foreach($form_parent_cols as $form_parent_col)
			{
				$app_col = array_filter($app_cols,function($app_col) use($form_parent_col){
					return $app_col['form_col_SN']==$form_parent_col['form_col_SN'];
				});
				if($app_col)
				{
					$app_col = reset($app_col);
					$this->oem_model->add_app_col(array(
						"app_SN"=>$app_SN,
						"form_col_SN"=>$form_parent_col['form_col_SN'],
						"col_value"=>$app_col['col_value']
					));
				}
			}
		}
		foreach($form_cols as $form_col)
		{
			$app_col = array_filter($app_cols,function($app_col) use($form_col){
				return $app_col['form_col_SN']==$form_col['form_col_SN'];
			});
			if($app_col)
			{
				$app_col = reset($app_col);
				$this->oem_model->add_app_col(array(
					"app_SN"=>$app_SN,
					"form_col_SN"=>$form_col['form_col_SN'],
					"col_value"=>$app_col['col_value']
				));
			}
		}
		
		return $app_SN;
	}
	public function update($app_SN,$description,$app_cols,$app_type = 'normal')
	{
		
	}
//	public function save($app_SN,$description)
//	{
//		//for user
//		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
//		if(!$app)
//		{
//			throw new Exception("無此申請單",ERROR_CODE);
//		}
//		
//		$this->oem_model->update_app(array(
//			"app_description"=>$description,
//			"app_checkpoint"=>"",
//			"app_SN"=>$app['app_SN']
//		));
//	}
//	public function submit($app_SN,$description)
//	{
//		//for user
//		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
//		if(!$app)
//		{
//			throw new Exception("無此申請單",ERROR_CODE);
//		}
//		
//		$this->oem_model->update_app(array(
//			"app_description"=>$description,
//			"app_checkpoint"=>"facility_admin_init",
//			"app_SN"=>$app['app_SN']
//		));
//	}
	public function confirm($app_SN,$admin_ID,$comment,$action,$app_hour = NULL)
	{
		//for Admin
		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
		if(!$app)
		{
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		//確認權限
		switch($app['app_checkpoint'])
		{
			case 'facility_admin_init':
				//代工單管理員或其技術長
				if($app['form_admin_ID']!=$admin_ID){
					throw new Exception("權限不足",ERROR_CODE);
				}
				$this->oem_model->update_app(array("app_estimated_hour"=>$app_hour,"app_SN"=>$app['app_SN']));
				break;
			case 'common_lab_deputy_section_chief':
				$this->load->model('admin_model');
				$privilege = $this->admin_model->get_org_chart_list(array(
					"admin_ID"=>$admin_ID,
					"team_ID"=>"common_lab",
					"status_ID"=>"deputy_section_chief"
				))->row_array();
				if(!$privilege){
					throw new Exception("權限不足",ERROR_CODE);
				}
				break;
			case 'user_boss':
				//由controller控制權限
				break;
			case 'facility_admin_final':
				//代工單管理員或其技術長
				if($app['form_admin_ID']!=$admin_ID){
					throw new Exception("權限不足",ERROR_CODE);
				}
				break;
			case 'common_lab_section_chief':
				$this->load->model('admin_model');
				$privilege = $this->admin_model->get_org_chart_list(array(
					"admin_ID"=>$this->session->userdata('ID'),
					"team_ID"=>"common_lab",
					"status_ID"=>"section_chief"
				))->row_array();
				if(!$privilege){
					throw new Exception("權限不足",ERROR_CODE);
				}
				break;
			default:
				throw new Exception("未知的狀態",ERROR_CODE);
		}
		
		$this->oem_model->add_app_checkpoint(array(
			"app_SN"=>$app['app_SN'],
			"checkpoint_ID"=>$app['app_checkpoint'],
			"checkpoint_admin_ID"=>$admin_ID,
			"checkpoint_comment"=>$comment
		));
		
		if($action=="accept")
		{
			$next_cp = array(
				"facility_admin_init"=>"common_lab_deputy_section_chief",
				"common_lab_deputy_section_chief"=>"user_boss",
				"user_boss"=>"facility_admin_final",
				"common_lab_section_chief"=>"facility_admin_final"
//				"facility_admin_final"=>"customer_final",
			);
			$this->oem_model->update_app(array(
				"app_checkpoint"=>$next_cp[$app['app_checkpoint']],
				"app_SN"=>$app['app_SN']
			));
			
		}
		else if($action=="reject")
		{
			$this->oem_model->update_app(array(
				"app_checkpoint"=>"rejected",
				"app_SN"=>$app['app_SN']
			));
		}
		else if($action=="apply_redo")
		{
			$this->oem_model->update_app(array(
				"app_checkpoint"=>"common_lab_section_chief",
				"app_SN"=>$app['app_SN']
			));
		}
		
		$this->send_email($app['app_SN']);
	}
	public function del($data)
	{
		
	}
	public function send_email($app_SN)
	{
		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
		if(!$app){
			return;	
		}
		if($app['app_checkpoint']=="facility_admin_init")
		{
			$this->load->model('oem/form_model');
			$admin_privileges = $this->form_model->get_facility_admin_profile($app['form_SN']);
			foreach($admin_privileges as $admin)
			{
				$this->email->to($admin['user_email']);
				$this->email->subject("成大微奈米科技研究中心");
				$this->email->message("
					{$admin['user_name']} 您好<br>
					使用者 {$app['user_name']} 申請 {$app['form_cht_name']} ({$app['form_eng_name']}) 代工服務<br>
					請上本中心系統審核，謝謝。
				");
				$this->email->send();
			}
		}else if($app['app_checkpoint']=="common_lab_deputy_section_chief")
		{
			$this->load->model('admin_model');
			$admins = $this->admin_model->get_org_chart_list(array(
				"team_ID"=>"common_lab",
				"status_ID"=>"deputy_section_chief"
			))->result_array();
			foreach($admins as $admin)
			{
				$this->email->to($admin['admin_email']);
				$this->email->subject("成大微奈米科技研究中心");
				$this->email->message("
					{$admin['team_name']} {$admin['status_name']} {$admin['admin_name']} 您好<br>
					使用者 {$app['user_name']} 申請 {$app['form_cht_name']} ({$app['form_eng_name']}) 代工服務<br>
					請上本中心系統審核，謝謝。
				");
				$this->email->send();
			}
		}else if($app['app_checkpoint']=="user_boss")
		{
			//寄信通知老師/主管線上簽核
			$this->load->model('user_model');
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$app['app_user_ID']))->row_array();
			$this->email->to($user_profile['boss_email']);
			$this->email->subject("成大微奈米科技研究中心");
			$this->email->message("
				{$user_profile['boss_name']} 您好：<br>
				您的學生/下屬 {$user_profile['name']} 已申請代工單：{$app['form_cht_name']} ({$app['form_eng_name']})
				<a href='".site_url("oem/app/edit/{$app['app_SN']}?app_token={$app['app_token']}")."'>請點此審核</a>
			");
			$this->email->send();
		}else if($app['app_checkpoint']=="facility_admin_final")
		{
			$this->load->model('oem/form_model');
			$admin_privileges = $this->form_model->get_facility_admin_profile($app['form_SN']);
			foreach($admin_privileges as $admin)
			{
				$this->email->to($admin['user_email']);
				$this->email->subject("成大微奈米科技研究中心");
				$this->email->message("
					{$admin['user_name']} 您好<br>
					使用者 {$app['user_name']} 之 主管/老師 已同意 申請 {$app['form_cht_name']} ({$app['form_eng_name']}) 代工服務<br>
					請上本中心系統排定代工時段，謝謝。
				");
				$this->email->send();
			}
		}else if($app['app_checkpoint']=='user_final'){
			//通知使用上去填滿意度調查表
			$this->email->to($app['user_email']);
			$this->email->subject("成大微奈米科技研究中心");
			$this->email->message("
				{$app['user_name']} 您好<br>
				您申請 {$app['form_cht_name']} ({$app['form_eng_name']}) 之代工服務已全部完工<br>
				竭誠邀請您填寫滿意度調查表，本中心將為您提供更好的代工服務<br>
				<a href='".site_url('oem/survey/new/'.$app['form_SN'])."'>請點此填寫</a>，謝謝。
			");
			$this->email->send();
		}else if($app['app_checkpoint']=='common_lab_section_chief'){
			//通知組長上去審核
			
		}else if($app['app_checkpoint']=='completed'){
			//通知所有相關人員
			
		}else if($app['app_checkpoint']=="rejected")
		{
			//通知所有相關人員
		}
		
	}
//	public function get_facility_admin_profile($app_SN)
//	{
//		$app = $this->oem_model->get_app_list(array("app_SN"=>$app_SN))->row_array();
//		if(!$app)
//		{
//			return array();
//		}
//		if($app['form_parent_SN'])
//		{
//			$parent_form = $this->oem_model->get_form_list(array("form_SN"=>$app['form_parent_SN']))->row_array();
//		}
//		$form = $this->oem_model->get_form_list(array("form_SN"=>$app['form_SN']))->row_array();
//		
//		if(isset($parent_form))
//		{
//			$parent_form_facility_map = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$parent_form['form_SN']))->result_array();
//		}
//		$form_facility_map = $this->oem_model->get_form_facility_map_list(array("form_SN"=>$form['form_SN']))->result_array();
//		if(isset($parent_form_facility_map))
//		{
//			$form_facility_map = array_merge($form_facility_map,$parent_form_facility_map);
//		}
//		$this->load->model('facility_model');
//		$admin_privileges = $this->facility_model->get_user_privilege_list(array(
//			"facility_ID"=>array_unique(sql_column_to_key_value_array($form_facility_map,"facility_ID"))
//		))->result_array();
//		return $admin_privileges;
//	}
}
