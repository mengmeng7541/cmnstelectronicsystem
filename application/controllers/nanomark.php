<?php
class Nanomark extends MY_Controller {
	private $data = array();
	
	private $test_outline = array("scale","functionality","biocompatibility");
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('nanomark_model');
		$this->load->model('nanomark/test_item_model');
		$this->load->model('facility_model');
		$this->load->model('admin_model');
		$this->load->model('user_model');
		
		
	}
	public function edit_config()
	{
		$this->is_admin_login();
		
		$admin_profile = $this->admin_model->get_admin_profile_list()->result_array();
		$admin_select_option = array();
		foreach($admin_profile as $row)
		{
			$admin_select_option[$row['ID']] = $row['name'];
		}
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("nanomark_super_admin");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_super_admin'] = form_dropdown("admin_ID[nanomark_super_admin][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("application_case_officer_1st");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_application_case_officer_1st'] = form_dropdown("admin_ID[application_case_officer_1st][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("application_case_officer_2nd");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_application_case_officer_2nd'] = form_dropdown("admin_ID[application_case_officer_2nd][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("application_case_officer_final");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_application_case_officer_final'] = form_dropdown("admin_ID[application_case_officer_final][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_quality_manager");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_quality_manager'] = form_dropdown("admin_ID[revision_quality_manager][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_technical_manager_scale");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_technical_manager_scale'] = form_dropdown("admin_ID[revision_technical_manager_scale][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_technical_manager_functionality");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_technical_manager_functionality'] = form_dropdown("admin_ID[revision_technical_manager_functionality][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_technical_manager_biocompatibility");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_technical_manager_biocompatibility'] = form_dropdown("admin_ID[revision_technical_manager_biocompatibility][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_report_signatory_scale");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_report_signatory_scale'] = form_dropdown("admin_ID[revision_report_signatory_scale][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_report_signatory_functionality");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_report_signatory_functionality'] = form_dropdown("admin_ID[revision_report_signatory_functionality][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_report_signatory_biocompatibility");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_report_signatory_biocompatibility'] = form_dropdown("admin_ID[revision_report_signatory_biocompatibility][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12" multiple="multiple"');
		
		$admin_privilege = $this->nanomark_model->get_admin_privilege_by_privilege("revision_lab_manager");
		$admin_privilege = $this->rotate_2D_array($admin_privilege);
		$this->data['select_revision_lab_manager'] = form_dropdown("admin_ID[revision_lab_manager][]",$admin_select_option,$admin_privilege['admin_ID'],'class="chosen span12"');
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/config',$this->data);
		$this->load->view('templates/footer');
	}
	public function update_config()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		if($this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'nanomark_super_admin')))
		{
			//更新管理者權限
			foreach($input_data['admin_ID'] as $privilege_key => $admin_ID_array)
			{
				$this->nanomark_model->delete_admin_privilege_by_privilege($privilege_key);
				foreach($admin_ID_array as $admin_ID)
				{
					$this->nanomark_model->add_admin_privilege($admin_ID,$privilege_key);
				}
			}
			echo $this->info_modal("更新成功");
		}else{
			echo $this->info_modal("沒有權限","","error");
		}
	}
	public function list_application()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_application',$this->data);
		$this->load->view('templates/footer');	
	}
	
	public function query_application()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$result = $this->nanomark_model->get_application_list($input_data)->result_array();
		
		$output['aaData'] = array();
		
		foreach ( $result as $aRow )
		{
			$row = array();
			
			$row[] = $aRow['ID'];
			$row[] = $aRow['report_title'];
			$row[] = $aRow['contact_name'];
			$row[] = $aRow['application_date'];
			$row[] = $aRow['work_start_date'];
			$row[] = $aRow['scheduled_completion_date'];
			
			if($aRow['checkpoint'] == "Case_Officer_1st" && 
			   $this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'application_case_officer_1st'))->row_array() )
			{
				$row[] = anchor("nanomark/edit_application/{$aRow['serial_no']}","審核","class='btn btn-warning'");
			}else if($aRow['checkpoint'] == "Case_Officer_2nd" && 
					 $this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'application_case_officer_2nd'))->row_array())
			{
				$row[] = anchor("nanomark/edit_application/{$aRow['serial_no']}","審核","class='btn btn-warning'");
			}else if($aRow['checkpoint'] == "Facility_Admin" &&
					 in_array($this->session->userdata('ID'),$this->nanomark_model->get_application_unsigned_facility_admin($aRow['serial_no'])))
			{
				$row[] = anchor("nanomark/edit_application/{$aRow['serial_no']}","審核","class='btn btn-warning'");
			}else if($aRow['checkpoint'] == "Case_Officer_Final" &&
					 $this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'application_case_officer_final'))->row_array())
			{
				$row[] = anchor("nanomark/edit_application/{$aRow['serial_no']}","審核","class='btn btn-warning'");
				
			}else if($aRow['checkpoint'] == "Client_Final" || $aRow['checkpoint'] == "Completed"){
				$row[] = anchor("nanomark/view_application/{$aRow['serial_no']}","已完成","class='btn btn-success'");
			}else if($this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'nanomark_super_admin'))->row_array()){
				$row[] = anchor("nanomark/edit_application/{$aRow['serial_no']}","進行中","class='btn btn-info'");
			}else{
				$row[] = anchor("nanomark/view_application/{$aRow['serial_no']}","進行中","class='btn btn-info'");
			}

			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	public function form_application()
	{		
		$this->is_user_login();
		
		$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
		
		$this->data['app_form'] = $this->load->view('nanomark/form_application_print_ver3',$this->data,TRUE);
		
		$this->data['action_url'] = site_url().'/nanomark/add_application';
		$this->data['action_btn'] = form_button("preview","預覽報告內容","class='btn btn-warning'")." ".form_submit("submit","送出","class='btn btn-success'")." ".form_reset("reset","重設","class='btn '");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/form_application',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_application($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$application = $this->nanomark_model->get_application_list(array("serial_no"=>$SN))->row_array();
			if(!$application) throw new Exception();
			$this->data = $application;
			$this->data['application_SN'] = $application['serial_no'];
			$this->data['application_ID'] = $application['ID'];
			$this->data['test_outline'] = explode(",",$application['test_outline']);
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			//specimen
			$specimens = $this->nanomark_model->get_specimen_list(array("application_SN"=>$application['serial_no']))->result_array();
			$this->data['specimens'] = $specimens;
			
			$this->data['action_url'] = site_url().'/nanomark/update_application';
			
			$unsigned_facility_admin_ID = $this->nanomark_model->get_application_unsigned_facility_admin($application['serial_no']);
			if(	$application['checkpoint']=='Case_Officer_1st' &&
				$this->nanomark_model->is_application_case_officer_1st())
			{
				foreach($specimens as $key => $sp)
				{
					if(empty($sp['facility_ID']))
					{
						if(empty($sp['outsourcing_SN'])){
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/form_outsourcing/{$sp['specimen_SN']}","外包","class='btn btn-mini btn-info'");
							$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
						}else if(empty($sp['client_signature'])){
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/edit_outsourcing/{$sp['specimen_SN']}","外包","class='btn btn-mini btn-info'");
							$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
						}else{
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/view_outsourcing/{$sp['specimen_SN']}","已同意","class='btn btn-mini btn-success'");
						}
						
					}else{
						$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
					}
				}
				$this->data['action_btn'][] = form_submit("update","更新","class='btn btn-warning'");
				$this->data['action_btn'][] = form_submit("submit","蓋章","class='btn btn-warning'");
				$this->data['action_btn'][] = form_button("del","刪除","class='btn btn-danger'");
				$this->data['action_btn'][] = form_reset("reset","重設","class='btn btn-inverse'");
				$this->data['action_btn'][] = anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");
			}else if(	$application['checkpoint']=='Case_Officer_2nd' &&
						$this->nanomark_model->is_application_case_officer_2nd()){
				$this->data['action_btn'][] = form_submit("submit","蓋章","class='btn btn-warning'");
				$this->data['action_btn'][] = anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");
				$this->data['readonly'] = 'disabled="disabled"';
			}else if(	$application['checkpoint']=='Facility_Admin' &&
						in_array($this->session->userdata('ID'),$unsigned_facility_admin_ID))
			{
				foreach($specimens as $key => $sp)
				{
					$test_item = $this->nanomark_model->get_test_item_list(array("serial_no"=>$sp['test_item']))->row_array();
					if($this->facility_model->get_user_privilege_list(
						array("facility_ID"=>$test_item['facility_ID'],
							  "user_ID"=>$this->session->userdata('ID'),
							  "privilege"=>"admin")
						)->row_array())
					{
						$this->data['specimens'][$key]['action_btn'] = anchor("/nanomark/edit_specimen/".$sp['specimen_SN'],"確認","class='btn btn-small btn-warning'");
					}
				}
				$this->data['readonly'] = 'disabled="disabled"';
			}else if(	$application['checkpoint']=='Case_Officer_Final' &&
						$this->nanomark_model->is_application_case_officer_final())
			{
				$this->data['action_btn'][] = form_submit("submit","完工","class='btn btn-warning'");
				$this->data['action_btn'][] = anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");
				$this->data['readonly'] = 'disabled="disabled"';
			}else if($this->nanomark_model->is_super_admin())
			{
				foreach($specimens as $key => $sp)
				{
					if(empty($sp['facility_ID']))
					{
						if(empty($sp['outsourcing_SN'])){
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/form_outsourcing/{$sp['specimen_SN']}","外包","class='btn btn-mini btn-info'");
							$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
						}else if(empty($sp['client_signature'])){
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/edit_outsourcing/{$sp['specimen_SN']}","外包","class='btn btn-mini btn-info'");
							$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
						}else{
							$this->data['specimens'][$key]['action_btn'][] = anchor("nanomark/view_outsourcing/{$sp['specimen_SN']}","已同意","class='btn btn-mini btn-success'");
						}
						
					}else{
						$this->data['specimens'][$key]['action_btn'][] = form_button("del_row","刪除","class='btn btn-mini btn-danger'");
					}
				}
				$this->data['action_btn'][] = form_submit("update","更新","class='btn btn-warning'");
				$this->data['action_btn'][] = anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");
				$this->data['action_btn'][] = form_button("del","刪除","class='btn btn-danger'");
				$this->data['action_btn'][] = form_reset("reset","重設","class='btn btn-inverse'");
			}else{
				$this->data['readonly'] = 'disabled="disabled"';
			}
			$this->data['action_btn'][] = anchor("/nanomark/view_application/".$application['serial_no'],"列印","class='btn '");
			
			//準備STAMP
			if(!empty($application['case_officer_ID']))
			{
				$case_officer = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$application['case_officer_ID']))->row_array();
				$this->data['stamp_url'][] = $case_officer['stamp'];
			}
			if(!empty($application['case_officer_2_ID']))
			{
				$case_officer_2 = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$application['case_officer_2_ID']))->row_array();
				$this->data['stamp_url'][] = $case_officer_2['stamp'];
			}
			$facility_admin_IDs = $this->nanomark_model->get_application_signed_facility_admin($application['serial_no']);
			if(!empty($facility_admin_IDs))
			{
				$facility_admin_profiles = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$facility_admin_IDs))->result_array();
				foreach($facility_admin_profiles as $row)
				{
					$this->data['stamp_url'][] = $row['stamp'];
				}
			}
			
			$this->data['app_form'] = $this->load->view('nanomark/form_application_print_ver3',$this->data,TRUE);
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_application',$this->data);
			$this->load->view('templates/footer');
			
			return;		
		}catch(Exception $e){
			$this->show_error_page();
			return;
		}
		
//			$unsigned_facility_admin_ID = $this->nanomark_model->get_application_unsigned_facility_admin($application->ID);
//			if($application->checkpoint == "Facility_Admin" && in_array($this->session->userdata('ID'),$unsigned_facility_admin_ID)){
//				
//				//第三關，給相關機台負責人簽名
//				//判斷目前登入的機台管理者有沒有審核的項目
//				$this->data['new_row_btn'] = "";
//				$readonly = " disabled='disabled'";
//				$this->data['specimen'] = "";
//				$specimen = $this->nanomark_model->get_specimen_by_application_ID($application->ID);
//
//				foreach($specimen as $row)
//				{
//					$confirm_btn = "";
//					
//						$test_item = $this->nanomark_model->get_test_item_by_SN($row->test_item);
//						if($this->facility_model->get_user_privilege_list(
//						array("facility_ID"=>$test_item['facility_ID'],
//							  "user_ID"=>$this->session->userdata('ID'),
//							  "privilege"=>"admin")
//						)->row_array())
//						{	//此人有管理權
//							if(empty($row->facility_admin_ID))
//							{
//								//尚未確認
//								$confirm_btn = form_button("update_specimen","確認","class='btn btn-small btn-warning'");
//							}else{
//								//已有確認
//								$confirm_btn = form_button("","確認","class='btn btn-small '".$readonly);
//							}
//							//取得工程師列表
//							$admin_profile_list = $this->admin_model->get_admin_profile_list()->result_array();
//							$specimen_engineer_select_options = array();
//							foreach($admin_profile_list as $row2)
//							{
//								$specimen_engineer_select_options[$row2['ID']] = $row2['name'];
//							}
//							$this->data['specimen_engineer_select'] = form_dropdown("specimen_facility_engineer_ID",$specimen_engineer_select_options,"","class='span12 '");
//						}else{
//							//無管理權
//							
//						}
//					
//						
//				}
//				
//				
//				$case_officer = $this->admin_model->get_admin_profile_by_ID($application->case_officer_ID);
//				$case_officer_2 = $this->admin_model->get_admin_profile_by_ID($application->case_officer_2_ID);
//				$this->data['case_officer_signature'] = "";
//				$this->data['case_officer_signature'] .= empty($case_officer)?"":img($case_officer['stamp']);
//				$this->data['case_officer_signature'] .= empty($case_officer_2)?"":img($case_officer_2['stamp']);
//				$this->data['action_btn'] = "";
//
//			}else if($application->checkpoint == "Case_Officer_Final" && $this->nanomark_model->get_admin_privilege_list('application_case_officer_final')){
//				$this->data['new_row_btn'] = "";
//				$readonly = " disabled='disabled'";
//				
//				$this->data['specimen'] = "";
//				$specimen = $this->nanomark_model->get_specimen_by_application_ID($application->ID);
//				
//				$case_officer = $this->admin_model->get_admin_profile_by_ID($application->case_officer_ID);
//				$case_officer_2 = $this->admin_model->get_admin_profile_by_ID($application->case_officer_2_ID);
//				$facility_admin = $this->nanomark_model->get_application_signed_facility_admin($application->ID);
//				$this->data['case_officer_signature'] = "";
//				$this->data['case_officer_signature'] .= empty($case_officer)?"":img($case_officer['stamp']);
//				$this->data['case_officer_signature'] .= empty($case_officer_2)?"":img($case_officer_2['stamp']);
//				foreach($facility_admin as $row)
//				{
//					$facility_admin_profile = $this->admin_model->get_admin_profile_by_ID($row);
//					$this->data['case_officer_signature'] .= empty($facility_admin_profile)?"":img($facility_admin_profile['stamp']);
//				}
//				//工作完成時，點擊完工按鈕
//				$this->data['action_btn'] = form_button("update","完工","class='btn btn-success'");
//			}else if($this->nanomark_model->get_admin_privilege_list('nanomark_super_admin')){
//				//超級管理員
//				$specimen = $this->nanomark_model->get_specimen_by_application_ID($application->ID);
//				$this->data['specimen'] = "";
//				foreach($specimen as $row)
//				{
//					$this->data['specimen'] .= "<tr>
//					    <td>".form_input("specimen_serial_no[]",$row->serial_no,"class='hide'").anchor("nanomark/form_outsourcing/{$row->serial_no}","外包","class='btn btn-small btn-info'")."<br>".form_button("del_row","刪除","class='btn btn-small btn-danger'")."</td>
//					    <td>".form_dropdown("specimen_test_item[]", $test_item_select_options, $row->test_item, "class='span12 chosen'")."</td>
//					    <td>".form_input("specimen_name[]",$row->name,"class='span12'")."</td>
//					    <td>".form_input("specimen_company_name[]",$row->company_name,"class='span12'")."</td>
//					    <td>".form_input("specimen_brand[]",$row->brand,"class='span12'")."</td>
//					    <td>".form_input("specimen_model[]",$row->model,"class='span12'")."</td>
//					  </tr>";
//				}
//				$this->data['new_row_btn'] = form_button("","新增","class='btn btn-small btn-inverse' id='add_row_btn'");
//				
//				$case_officer = $this->admin_model->get_admin_profile_by_ID($application->case_officer_ID);
//				$case_officer_2 = $this->admin_model->get_admin_profile_by_ID($application->case_officer_2_ID);
//				$facility_admin = $this->nanomark_model->get_application_signed_facility_admin($application->ID);
//				$this->data['case_officer_signature'] = "";
//				$this->data['case_officer_signature'] .= empty($case_officer)?"":img($case_officer['stamp']);
//				$this->data['case_officer_signature'] .= empty($case_officer_2)?"":img($case_officer_2['stamp']);
//				foreach($facility_admin as $row)
//				{
//					$facility_admin_profile = $this->admin_model->get_admin_profile_by_ID($row);
//					$this->data['case_officer_signature'] .= empty($facility_admin_profile)?"":img($facility_admin_profile['stamp']);
//				}
//				
//				$this->data['action_btn'] = form_button("update","更新","class='btn btn-success'")." ".form_button("del","刪除","class='btn btn-danger'")." ".form_reset("reset","重設","class='btn '");
//			}else{
//				$this->view_application($application->ID);
//				return;
//			}
//
//		$this->data['app_form'] = $this->load->view('nanomark/form_application_print_ver3',$this->data,TRUE);
//	
//		$this->load->view('templates/header');
//		$this->load->view('templates/sidebar');
//		$this->load->view('nanomark/form_application',$this->data);
//		$this->load->view('templates/footer');
	}
	public function view_application($SN)
	{
		try{
			$this->is_user_login();
		
			$SN = $this->security->xss_clean($SN);
			
			//是否為自己的單子，管理者不在此限
			$application = $this->nanomark_model->get_application_list(array("serial_no"=>$SN))->row_array();
			if($application['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE)){
				throw new Exception();
			}
			$application['test_outline'] = explode(",",$application['test_outline']);
			$this->data = $application;
			$specimens = $this->nanomark_model->get_specimen_list(array("application_SN"=>$application['serial_no']))->result_array();
			$this->data['specimens'] = $specimens;
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			//準備STAMP
			if(!empty($application['case_officer_ID']))
			{
				$case_officer = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$application['case_officer_ID']))->row_array();
				$this->data['stamp_url'][] = $case_officer['stamp'];
			}
			if(!empty($application['case_officer_2_ID']))
			{
				$case_officer_2 = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$application['case_officer_2_ID']))->row_array();
				$this->data['stamp_url'][] = $case_officer_2['stamp'];
			}
			$facility_admin_IDs = $this->nanomark_model->get_application_signed_facility_admin($application['serial_no']);
			if(!empty($facility_admin_IDs))
			{
				$facility_admin_profiles = $this->admin_model->get_admin_profile_list(array("admin_ID"=>$facility_admin_IDs))->result_array();
				foreach($facility_admin_profiles as $row)
				{
					$this->data['stamp_url'][] = $row['stamp'];
				}
			}
			
			$this->data['app_form'] = $this->load->view('nanomark/view_application',$this->data,TRUE);
			
			
			//if(!empty($application->case_officer_ID) && !empty($application->case_officer_2_ID))
			$this->data['action_btn'] = form_button("print_btn","列印","class='btn btn-inverse'");
		
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	//new application
	public function add_application()
	{
		try{
			$this->is_user_login();
			
			$this->form_validation->set_rules("test_outline[]","測試主題","required");
			$this->form_validation->set_rules("priority","優先權","required");
			$this->form_validation->set_rules("report_title","報告抬頭","required");
			$this->form_validation->set_rules("receipt_title","收據抬頭","required");
			$this->form_validation->set_rules("report_address","報告地址","required");
			$this->form_validation->set_rules("mail_address","郵寄地址","required");
			$this->form_validation->set_rules("VAT","統編","required");
			$this->form_validation->set_rules("contact_name","聯絡人","required");
			$this->form_validation->set_rules("contact_tel","聯絡電話","required");
			$this->form_validation->set_rules("contact_mobile","手機","required");
			$this->form_validation->set_rules("contact_email","Email","required");
			$this->form_validation->set_rules("when_pay","何時付款","required");
			if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			//取得委託單編號
			$application_ID = $this->nanomark_model->get_new_application_ID();
			
			//寫入委託明細
			$data = array(	"application_ID"=>$application_ID,
							"test_outline"=>implode(",",$input_data['test_outline']),
							"priority"=>$input_data['priority'],
							"report_title"=>$input_data['report_title'],
							"receipt_title"=>$input_data['receipt_title'],
							"report_address"=>$input_data['report_address'],
							"mail_address"=>$input_data['mail_address'],
							"VAT"=>$input_data['VAT'],
							"applicant_ID"=>$this->session->userdata('ID'),
							"contact_name"=>$input_data['contact_name'],
							"contact_tel"=>$input_data['contact_tel'],
							"contact_mobile"=>$input_data['contact_mobile'],
							"contact_FAX"=>$input_data['contact_FAX'],
							"contact_email"=>$input_data['contact_email'],
							"when_pay"=>$input_data['when_pay'],
							"num_report_copies_scale"=>$input_data['num_report_copies_scale'],
							"num_report_copies_functionality"=>$input_data['num_report_copies_functionality'],
							"num_report_copies_biocompatibility"=>$input_data['num_report_copies_biocompatibility'],
							"report_logo_scale"=>isset($input_data['report_logo_scale'])?$input_data['report_logo_scale']:0,
							"report_logo_functionality"=>isset($input_data['report_logo_functionality'])?$input_data['report_logo_functionality']:0,
							"report_logo_biocompatibility"=>isset($input_data['report_logo_biocompatibility'])?$input_data['report_logo_biocompatibility']:0,
							"comments"=>$input_data['comments'],
							"client_signature"=>$input_data['contact_name']);
			$application_SN = $this->nanomark_model->add_application($data);
			
			//寫入樣品明細
			$len = count($input_data['specimen_name']);
			for($i = 0; $i < $len; $i++){
				if(empty($input_data['specimen_name'][$i]))
					continue;
				if(!empty($input_data['specimen_serial_no'][$i]))
					continue;
					
				$data['application_SN'] = $application_SN;
				$data['specimen_ID'] = $this->nanomark_model->get_new_specimen_ID($application_SN);
				$data['specimen_test_item'] = empty($input_data['specimen_test_item'][$i])?NULL:$input_data['specimen_test_item'][$i];
				$data['specimen_name'] = $input_data['specimen_name'][$i];
				$data['specimen_company_name'] = $input_data['specimen_company_name'][$i];
				$data['specimen_brand'] = $input_data['specimen_brand'][$i];
				$data['specimen_model'] = $input_data['specimen_model'][$i];
				
				$this->nanomark_model->add_specimen($data);
			}
			
			//發信
			$this->nanomark_model->send_officer_1st_notification($application_SN);
			
			
			echo $this->info_modal("新增成功","/nanomark/list_progress");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	
	public function update_application()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("application_SN","委託編號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$application = $this->nanomark_model->get_application_list(array("serial_no"=>$input_data['application_SN']))->row_array();
			if($application['checkpoint'] == "Case_Officer_1st" && $this->nanomark_model->is_application_case_officer_1st())
			{
				//第一關
				$data = $input_data;//這個要改，有安全疑慮
				$data['serial_no'] = $input_data['application_SN'];
				if($this->get_user_action("submit"))
				{
					$data['case_officer_ID'] = $this->session->userdata('ID');
					$this->nanomark_model->update_application_checkpoint($application['serial_no'],"Case_Officer_2nd");
					
					//發信
					$this->nanomark_model->send_officer_2nd_notification($data['serial_no']);
					
				}
				$data['test_outline'] = implode(",",$input_data['test_outline']);
				$data['application_date'] = implode(" ",$input_data['application_date']);
				
				$this->nanomark_model->update_application($data);
				$application = $this->nanomark_model->get_application_list(array("serial_no"=>$application['serial_no']))->row_array();//重新取得資訊
				
				//specimen
				//找出原先存在，更新後不存在的，則先刪除
				if(!empty($input_data['specimen_serial_no']))
				{
					$old_specimen = $this->nanomark_model->get_specimen_list(array("application_SN"=>$application['serial_no']))->result_array();
					
					foreach($old_specimen as $row)
					{
						if(!in_array($row['specimen_SN'],$input_data['specimen_serial_no']))
						{
							$this->nanomark_model->delete_specimen(array("specimen_SN"=>$row['specimen_SN']));
						}
					}
				}else{
					$this->nanomark_model->delete_specimen(array("application_SN"=>$application['serial_no']));
				}
				//原先存在，更新後亦存在的，則更新
				$len = empty($input_data['specimen_serial_no'])?0:count($input_data['specimen_serial_no']);
				for($i = 0;$i<$len;$i++)
				{
					//更新時先檢查權限
					$specimen = $this->nanomark_model->get_specimen_list(array("specimen_SN"=>$input_data['specimen_serial_no'][$i]))->row_array();
					if(!$specimen) throw new Exception("檢測件不存在！",ERROR_CODE);
					if($specimen['application_SN'] != $application['serial_no']) throw new Exception("權限不足！",ERROR_CODE);
					
					$new_specimen['specimen_ID'] = $application['ID'].($i+1);//ID重排
					$new_specimen['specimen_test_item'] = empty($input_data['specimen_test_item'][$i])?NULL:$input_data['specimen_test_item'][$i];
					$new_specimen['specimen_name'] = $input_data['specimen_name'][$i];
					$new_specimen['specimen_company_name'] = $input_data['specimen_company_name'][$i];
					$new_specimen['specimen_brand'] = $input_data['specimen_brand'][$i];
					$new_specimen['specimen_model'] = $input_data['specimen_model'][$i];
					$new_specimen['specimen_serial_no'] = $input_data['specimen_serial_no'][$i];
					$this->nanomark_model->update_specimen($new_specimen);
								
				}
				//原先不存在，更新後才出現的，則新增
				$len = count($input_data['specimen_name']);
				for($i = 0; $i < $len; $i++){
					if(empty($input_data['specimen_name'][$i]))
						continue;
					if(!empty($input_data['specimen_serial_no'][$i]))
						continue;
						
					$data['application_SN'] = $application['serial_no'];
					$data['specimen_ID'] = $this->nanomark_model->get_new_specimen_ID($application['serial_no']);
					$data['specimen_test_item'] = empty($input_data['specimen_test_item'][$i])?NULL:$input_data['specimen_test_item'][$i];
					$data['specimen_name'] = $input_data['specimen_name'][$i];
					$data['specimen_company_name'] = $input_data['specimen_company_name'][$i];
					$data['specimen_brand'] = $input_data['specimen_brand'][$i];
					$data['specimen_model'] = $input_data['specimen_model'][$i];
					
					$this->nanomark_model->add_specimen($data);
				}
			}
			else if($application['checkpoint'] == "Case_Officer_2nd" && $this->nanomark_model->is_application_case_officer_2nd())
			{
				//第二關
				$data = array(	"case_officer_2_ID"=>$this->session->userdata('ID'),
								"serial_no"=>$application['serial_no']);
				$this->nanomark_model->update_application($data);
				$unsigned_facility_admin_ID = $this->nanomark_model->get_application_unsigned_facility_admin($application['serial_no']);
				if(!empty($unsigned_facility_admin_ID))
				{
					$this->nanomark_model->update_application_checkpoint($application['serial_no'],"Facility_Admin");
					$this->nanomark_model->send_facility_admin_notification($application['serial_no']);//送信通知
				}else{
					$this->nanomark_model->update_application_checkpoint($application['serial_no'],"Case_Officer_Final");
				}
			}else if($application['checkpoint'] == "Case_Officer_Final" && $this->nanomark_model->is_application_case_officer_final())
			{
				//第四關
				$this->nanomark_model->update_application_checkpoint($application['serial_no'],"Client_Final");
				//送信通知客戶可簽收
				$this->nanomark_model->send_customer_survey_notification($application['serial_no']);
			}else if($this->nanomark_model->is_super_admin()){
				//超級管理員
				$data = $input_data;
				$data['serial_no'] = $input_data['application_SN'];
				$data['test_outline'] = implode(",",$input_data['test_outline']);
				$data['application_date'] = implode(" ",$input_data['application_date']);
				
				$this->nanomark_model->update_application($data);
				$application = $this->nanomark_model->get_application_list(array("serial_no"=>$application['serial_no']))->row_array();//重新取得資訊
				//specimen
				//找出原先存在，更新後不存在的，則先刪除
				if(!empty($input_data['specimen_serial_no']))
				{
					$old_specimen = $this->nanomark_model->get_specimen_list(array("application_SN"=>$application['serial_no']))->result_array();
					
					foreach($old_specimen as $row)
					{
						if(!in_array($row['specimen_SN'],$input_data['specimen_serial_no']))
							$this->nanomark_model->delete_specimen(array("specimen_SN"=>$row['specimen_SN']));
					}
				}else{
					$this->nanomark_model->delete_specimen(array("application_SN"=>$application['serial_no']));
				}
				//原先存在，更新後亦存在的，則更新
				$len = empty($input_data['specimen_serial_no'])?0:count($input_data['specimen_serial_no']);
				for($i = 0;$i<$len;$i++)
				{
					//更新時先檢查權限
					$specimen = $this->nanomark_model->get_specimen_list(array("specimen_SN"=>$input_data['specimen_serial_no'][$i]))->row_array();
					if(!$specimen) throw new Exception("檢測件不存在！",ERROR_CODE);
					if($specimen['application_SN'] != $application['serial_no']) throw new Exception("權限不足！",ERROR_CODE);
					
					$new_specimen['specimen_ID'] = $application['ID'].($i+1);//ID重排
					$new_specimen['specimen_test_item'] = empty($input_data['specimen_test_item'][$i])?NULL:$input_data['specimen_test_item'][$i];
					$new_specimen['specimen_name'] = $input_data['specimen_name'][$i];
					$new_specimen['specimen_company_name'] = $input_data['specimen_company_name'][$i];
					$new_specimen['specimen_brand'] = $input_data['specimen_brand'][$i];
					$new_specimen['specimen_model'] = $input_data['specimen_model'][$i];
					$new_specimen['specimen_serial_no'] = $input_data['specimen_serial_no'][$i];
					$this->nanomark_model->update_specimen($new_specimen);
								
				}
				//原先不存在，更新後才出現的，則新增
				$len = count($input_data['specimen_name']);
				for($i = 0; $i < $len; $i++){
					if(empty($input_data['specimen_name'][$i]))
						continue;
					if(!empty($input_data['specimen_serial_no'][$i]))
						continue;
						
					$data['application_SN'] = $application['serial_no'];
					$data['specimen_ID'] = $this->nanomark_model->get_new_specimen_ID($application['serial_no']);
					$data['specimen_test_item'] = empty($input_data['specimen_test_item'][$i])?NULL:$input_data['specimen_test_item'][$i];
					$data['specimen_name'] = $input_data['specimen_name'][$i];
					$data['specimen_company_name'] = $input_data['specimen_company_name'][$i];
					$data['specimen_brand'] = $input_data['specimen_brand'][$i];
					$data['specimen_model'] = $input_data['specimen_model'][$i];
					
					$this->nanomark_model->add_specimen($data);
				}
			}

			echo $this->info_modal("更新成功","/nanomark/edit_application/{$application['serial_no']}");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//specimen facility booking map
	public function query_booking()
	{
		$this->is_admin_login();
		
		$output['aaData'] = array();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$options = array("specimen_ID"=>isset($input_data['specimen_ID'])?$input_data['specimen_ID']:"");
		$results = $this->nanomark_model->get_specimen_booking_list($options)->result_array();
		foreach($results as $result){
			$row = array();
			$row[] = $result['booking_start_time'];
			$row[] = $result['booking_end_time'];
			$row[] = form_button("del","刪除","class='btn btn-danger' value='{$result['booking_ID']}'");
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	public function del_booking($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			//判斷是否是最後一個的刪除，是就要NULL掉engineer_ID的欄位
			$options = array("booking_ID"=>$SN);
			$result = $this->nanomark_model->get_specimen_booking_list($options)->row_array();
			if(!$result)
			{
				throw new Exception("無此紀錄！",ERROR_CODE);
			}
			$options = array("specimen_ID"=>$result['specimen_ID']);
			$results = $this->nanomark_model->get_specimen_booking_list($options)->result_array();
			if(count($results)<=1)
			{
				$data = array("specimen_facility_engineer_ID"=>"",
							  "specimen_serial_no"=>$result['specimen_ID']);
				$this->nanomark_model->update_specimen($data);
			}
			
			$this->load->model('facility/booking_model');
			$this->booking_model->del($SN);
			
			echo $this->info_modal("刪除成功","/nanomark/edit_specimen/".$result['specimen_ID']);
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function edit_specimen($SN)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$specimen = $this->nanomark_model->get_specimen_list(array("specimen_SN"=>$SN))->row_array();
			if(!$specimen) throw new Exception();
			$this->data = $specimen;
			
			$this->load->model('nanomark/specimen_model');
			$this->data['engineer_ID_select_options'] = $this->specimen_model->get_engineer_ID_select_options($specimen['test_item']);
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/edit_specimen',$this->data);
			$this->load->view('templates/footer');
			
		}catch(Exception $e){
			$this->show_error_page();
		}
	}

	public function update_specimen()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("specimen_SN","檢測件編號","required");
			if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
			
			$options = array("specimen_SN"=>$input_data['specimen_SN']);
			$specimen = $this->nanomark_model->get_specimen_list($options)->row_array();
			if(!$specimen) throw new Exception("無此檢測件！",ERROR_CODE);
			
			if($input_data['action_btn']=="booking")
			{
				$this->form_validation->set_rules("engineer_ID","檢測人員","required");
				$this->form_validation->set_rules("facility_ID","預約儀器","required");
				$this->form_validation->set_rules("booking_time[]","預約時間","required");
				if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
				$facility = $this->facility_model->get_facility_list(array("ID"=>$specimen['facility_ID']))->row_array();
				if(!$facility) throw new Exception("無此儀器",ERROR_CODE);
				//檢查權限
				if(!$this->facility_model->get_user_privilege_list(
				array("facility_ID"=>$specimen['facility_ID'],
					  "user_ID"=>$this->session->userdata('ID'),
					  "privilege"=>"admin")
				)->row_array())
				{
					throw new Exception("沒有權限",ERROR_CODE);
				}
				
				//寫入預約
				$this->load->model('facility/booking_model');
				$this->booking_model->check_input_time($input_data['booking_time'],$facility['unit_sec']);
				$booking_ID = $this->booking_model->add($specimen['facility_ID'],$input_data['engineer_ID'],min($input_data['booking_time']),max($input_data['booking_time'])+$facility['unit_sec'],"nanomark");
				//寫入map
				$data = array("specimen_ID"=>$specimen['specimen_SN'],"booking_ID"=>$booking_ID);
				$this->nanomark_model->add_specimen_booking_map($data);
				
				//更新資料
				$this->nanomark_model->update_specimen(array(	"specimen_facility_engineer_ID"=>$input_data['engineer_ID'],
																"specimen_serial_no"=>$specimen['specimen_SN']));
				echo $this->info_modal("預約成功","/nanomark/edit_specimen/{$specimen['specimen_SN']}");
			}else if($input_data['action_btn']=="confirm")
			{
				//先確認已有工程師ID
				if(empty($specimen['facility_engineer_ID']))
				{
					throw new Exception("請指定檢測工程師",ERROR_CODE);
				}
				//更新資料
				$this->nanomark_model->update_specimen(array(	"specimen_facility_admin_ID"=>$this->session->userdata('ID'),
																"specimen_checkpoint"=>"Completed",
																"specimen_serial_no"=>$specimen['specimen_SN']));
				//確認是否為最後檢測件的審核
				if(!count($this->nanomark_model->get_application_unsigned_facility_admin($specimen['application_SN'])))
				{
					//set checkpoint to next
					$this->nanomark_model->update_application_checkpoint($specimen['application_SN'],"Case_Officer_Final");
					//送信通知下一關
					$this->nanomark_model->send_officer_final_notification($specimen['application_SN']);
				}
				
				echo $this->info_modal("審核成功","/nanomark/edit_application/{$specimen['application_SN']}");
			}else{
				throw new Exception("未知的動作",ERROR_CODE);
			}
			
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	
	

	
	
	
	public function delete_application($ID = '')
	{
		$this->is_admin_login();
		
		if(empty($ID))
			$ID = $this->input->post("application_ID",TRUE);
		
		$this->nanomark_model->delete_application($ID);
		
		echo $this->info_modal("刪除成功","/nanomark/list_application");
	}
	public function form_quotation()
	{
		$this->is_user_login();

		$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
		
		$this->data['action'] = site_url()."/nanomark/add_quotation";
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/form_quotation',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_quotation($SN = NULL)
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
		
			$quotation = $this->nanomark_model->get_quotation_by_SN($SN);
			if(!$quotation)	throw new Exception();
			$this->data = $quotation;
			
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			$this->data['data'] = array();
			$q_test_items = $this->nanomark_model->get_quotation_test_items_list(array("quotation_ID"=>$quotation['serial_no']))->result_array();
			$i = 0;
			foreach($q_test_items as $row)
			{
				$r = array();
			
				$r['index'] = ++$i;
				$r['serial_no'] = $row['serial_no'];
				$r['test_item'] = $row['test_item'];
				$r['amount'] = $row['amount'];
				$r['fees'] = $row['fees'];
				$r['total_fees'] = $row['total_fees'];
			    
			    $this->data['data'][] = $r;
			}
			
			$this->data['action'] = site_url()."/nanomark/update_quotation";
			$this->data['quotation_date'] = date("Y-m-d");

			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_quotation',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
		
	}
	public function view_quotation($SN)
	{
		//確認有編號
		if(!isset($SN))
		{
			$this->show_error_page();
			return;
		}
		//確認瀏覽密碼
		$code = $this->input->get("code");
		$code = $this->encrypt->decode($code);
		if($SN != $code)
		{
			$this->show_error_page();
			return;
		}
		
		$quotation = $this->nanomark_model->get_quotation_by_SN($SN);
		//確認瀏覽者權限
//		if(!empty($quotation['applicant_ID']))
//		{
//			$this->is_user_login();
//			if($quotation['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE))
//			{
//				$this->show_error_page();
//				return;
//			}
//		}
		
		$this->data = $quotation;
		
		$this->data['data'] = array();
		$quotation_test_items = $this->nanomark_model->get_quotation_test_items_by_quotation_ID($quotation['serial_no']);
		$i = 0;
		foreach($quotation_test_items as $row)
		{
			$r = array();
			
			$r['index'] = ++$i;
			$test_item = $this->nanomark_model->get_test_item_by_SN($row->test_item);
			$r['name'] = $test_item['name'];
			$r['amount'] = $row->amount;
			$r['fees'] = $row->fees;
			$r['total_fees'] = $row->total_fees;
		    
		    $this->data['data'][] = $r;
		}
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/view_quotation',$this->data);
		$this->load->view('templates/footer');
	}
	public function add_quotation()
	{	
		$this->is_user_login();
		
		$this->form_validation->set_rules("organization","委託機構","required");
		$this->form_validation->set_rules("contact_name","委測者","required");
		$this->form_validation->set_rules("contact_tel","聯絡電話","required");
		$this->form_validation->set_rules("contact_email","E-mail","required");
		$this->form_validation->set_rules("entrust_item","委託事項","required");
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","error");
			return;
		}
		
		$input_data = $this->input->post(NULL,TRUE);
		
		if(	$this->nanomark_model->is_super_admin() || 
			$this->nanomark_model->is_application_case_officer_1st())
		{
			if(isset($input_data['submit_btn'])){
				//直接送到客戶
				$quotation_ID = $this->nanomark_model->add_quotation(
					array("organization"=>$input_data['organization'],
						  "contact_name"=>$input_data['contact_name'],
						  "contact_email"=>$input_data['contact_email'],
						  "contact_tel"=>$input_data['contact_tel'],
						  "contact_FAX"=>$input_data['contact_FAX'],
						  "entrust_item"=>$input_data['entrust_item'],
						  "total_fees"=>$input_data['total_fees'],
						  "comments"=>$input_data['comments'],
						  "case_officer_ID"=>$this->session->userdata('ID'),
						  )
					);
				//email給使用者
				$this->_email_quotation();
			}else{
				//先儲存
				$quotation_ID = $this->nanomark_model->add_quotation(
					array("organization"=>$input_data['organization'],
						  "contact_name"=>$input_data['contact_name'],
						  "contact_email"=>$input_data['contact_email'],
						  "contact_tel"=>$input_data['contact_tel'],
						  "contact_FAX"=>$input_data['contact_FAX'],
						  "entrust_item"=>$input_data['entrust_item'],
						  "total_fees"=>$input_data['total_fees'],
						  "comments"=>$input_data['comments'],
						  )
					);
			}
			
		}else{
			//寫入報價單
			$quotation_ID = $this->nanomark_model->add_quotation(
			array("applicant_ID"=>$this->session->userdata('ID'),
				  "organization"=>$input_data['organization'],
				  "contact_name"=>$input_data['contact_name'],
				  "contact_email"=>$input_data['contact_email'],
				  "contact_tel"=>$input_data['contact_tel'],
				  "contact_FAX"=>$input_data['contact_FAX'],
				  "entrust_item"=>$input_data['entrust_item'],
				  "comments"=>$input_data['comments'],
				  )
			);
		}

		//寫入需求列表	
		$data = array();
		for($i=0;$i<count($input_data['test_item_name']);$i++)
		{
			$row = array();
			$row['quotation_ID'] = $quotation_ID;
			$row['test_item_name'] = $input_data['test_item_name'][$i];
			$row['test_item_amount'] = $input_data['test_item_amount'][$i];
			if(	$this->nanomark_model->is_super_admin() || 
				$this->nanomark_model->is_application_case_officer_1st())
			{
				$row['test_item_fees'] = $input_data['test_item_fees'][$i];
				$row['test_item_total_fees'] = $input_data['test_item_total_fees'][$i];
			}
			$data[] = $row;
		}
		$this->nanomark_model->add_quotation_test_item($data);
		
		echo $this->info_modal("新增成功","/nanomark/list_progress");
	}
	public function update_quotation()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("serial_no","報價單號","required");
			$this->form_validation->set_rules("organization","委託機構","required");
			$this->form_validation->set_rules("contact_name","委測者","required");
			$this->form_validation->set_rules("contact_tel","聯絡電話","required");
			$this->form_validation->set_rules("contact_email","E-mail","required");
			$this->form_validation->set_rules("entrust_item","委託事項","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			if(	!$this->nanomark_model->is_super_admin() &&
				!$this->nanomark_model->is_application_case_officer_1st())
			{
				throw new Exception("權限不足！",ERROR_CODE);
			}
			
			$data = array(	"quotation_ID"=>$input_data['serial_no'],
							"organization"=>$input_data['organization'],
							"contact_name"=>$input_data['contact_name'],
							"contact_email"=>$input_data['contact_email'],
							"contact_tel"=>$input_data['contact_tel'],
							"contact_FAX"=>$input_data['contact_FAX'],
							"entrust_item"=>$input_data['entrust_item'],
							"total_fees"=>$input_data['total_fees'],
							"comments"=>$input_data['comments']);
			if($this->get_user_action("submit")) $data['case_officer_ID'] = $this->session->userdata('ID');
			$this->nanomark_model->update_quotation($data);
			
			//先刪除
			$this->nanomark_model->del_quotation_test_item(array("quotation_ID"=>$input_data['serial_no']));
			//後新增
			$data = array();
			for($i=0;$i<count($input_data['test_item_name']);$i++)
			{
				$row = array();
				$row['quotation_ID'] = $input_data['serial_no'];
				$row['test_item_name'] = $input_data['test_item_name'][$i];
				$row['test_item_amount'] = $input_data['test_item_amount'][$i];
				$row['test_item_fees'] = $input_data['test_item_fees'][$i];
				$row['test_item_total_fees'] = $input_data['test_item_total_fees'][$i];
				$data[] = $row;
			}
			$this->nanomark_model->add_quotation_test_item($data);
			
			if($this->get_user_action("submit")){
				//email給使用者
				$this->_email_quotation($input_data['serial_no']);
			}
			
			echo $this->info_modal("更新成功","/nanomark/list_quotation");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function email_quotation($SN)
	{
		if(!$this->is_admin_login(FALSE))
		{
			echo $this->info_modal("權限不足","","error");
			return;
		}
		
		$SN = $this->security->xss_clean($SN);
		
		$this->_email_quotation($SN);
		
		echo $this->info_modal("重送成功");
	}
	public function _email_quotation($SN = "")
	{
		
		if(empty($SN))
			$quotation = $this->nanomark_model->get_quotation_list()->row_array();
		else
			$quotation = $this->nanomark_model->get_quotation_by_SN($SN);
		if(!$quotation)
			return;
		if(empty($quotation['case_officer_ID']))
			return;
		//寄信告訴使用者結果
		$this->email->to($quotation['contact_email']);
		$this->email->subject("成大奈米技術產品測試實驗室 -檢測服務報價單-");
		$this->email->message("{$quotation['contact_name']} 您好：<br>
								您有一份報價單：{$quotation['entrust_item']}<br>
								請點下列網址查詢與列印：".anchor("/nanomark/view_quotation/{$quotation['serial_no']}?code=".urlencode($this->encrypt->encode($quotation['serial_no'])))."<br>
								如蒙委託，請上本".anchor(site_url(),"實驗室網站")."填寫委託單(如無帳號請先註冊)，謝謝。");
		$this->email->send();
		
	}
	public function form_outsourcing($SN)
	{
		try{
			$this->is_admin_login();
		
			$this->security->xss_clean($SN);
			

			$options = array("specimen_SN"=>$SN);
			$specimen = $this->nanomark_model->get_specimen_list($options)->row_array();
			if(!$specimen) throw new Exception();
			$this->data = $specimen;

			$this->data['verification_norm_select_options'] = $this->nanomark_model->get_verification_norm_select_options();
			
			
			//取得檢測項目
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			
			//ACTION BUTTON
			$this->data['action_btn'][] = form_submit("","送出","class='btn btn-success'");
			$this->data['action_btn'][] = anchor("nanomark/edit_application/{$specimen['application_SN']}","取消","class='btn btn-warning'");
			
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_outsourcing',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function edit_outsourcing($SN)
	{
		
		try{
			$this->is_user_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$options = array("specimen_SN"=>$SN);
			$outsourcing = $this->nanomark_model->get_outsourcing_list($options)->row_array();
			if(!$outsourcing) throw new Exception();
			$this->data = $outsourcing;
			
			if($outsourcing['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE))
			{
				throw new Exception();
			}
			
			//獲取奈米標章檢測規範列表
			$this->data['verification_norm_select_options'] = $this->nanomark_model->get_verification_norm_select_options();
			
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			//取得檢測項目
			$this->data['test_items_name_1'] = explode(",",$outsourcing['test_items_name_1']);
			$this->data['test_items_name_2'] = explode(",",$outsourcing['test_items_name_2']);
			
			if($this->is_admin_login(FALSE))
			{
				
				
				//ACTION URL
				$this->data['action_url'] = site_url().'/nanomark/update_outsourcing/';				
				//ACTION BUTTON
				$this->data['action_btn'][] = form_button("update","更新","class='btn btn-success'");
				$this->data['action_btn'][] = form_button("del","刪除","class='btn btn-danger'");
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('nanomark/form_outsourcing',$this->data);
				$this->load->view('templates/footer');
			}else{
				
				//ACTION BUTTON
				$this->data['action_btn'][] = form_button("update","同意","class='btn btn-success'");
				
				$this->load->view('templates/header');
				$this->load->view('templates/sidebar');
				$this->load->view('nanomark/view_outsourcing',$this->data);
				$this->load->view('templates/footer');
			}
			
			
			
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function view_outsourcing($SN)
	{
		try{
			$this->is_user_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$options = array("specimen_SN"=>$SN);
			$outsourcing = $this->nanomark_model->get_outsourcing_list($options)->row_array();
			if(!$outsourcing)
			{
				throw new Exception();
			}
			$this->data = $outsourcing;
			
			if($outsourcing['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE))
			{
				throw new Exception();
			}
			
			$this->data['verification_norm_select_options'] = $this->nanomark_model->get_verification_norm_select_options();
			
			$this->data['test_item_select_options'] = $this->test_item_model->get_test_item_select_options();
			
			//取得檢測項目
			$this->data['test_items_name_1'] = explode(",",$outsourcing['test_items_name_1']);
			$this->data['test_items_name_2'] = explode(",",$outsourcing['test_items_name_2']);
			
			
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/view_outsourcing',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}

	
	
	public function add_outsourcing()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("specimen_SN","檢測件編號","required");
			$this->form_validation->set_rules("verification_norm_no","規範","required");
			$this->form_validation->set_rules("test_items_name_1[]","項目1","required");
			$this->form_validation->set_rules("test_items_name_2[]","項目2","required");
			$this->form_validation->set_rules("test_items_1_amount","數量","required");
			$this->form_validation->set_rules("outsourcing_organization","公司","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			//get post data
			$input_data = $this->input->post(NULL,TRUE);
			
			$options = array("specimen_SN"=>$input_data['specimen_SN']);
			$specimen = $this->nanomark_model->get_specimen_list($options)->row_array();
			if(!$specimen) throw new Exception("無此檢測件",ERROR_CODE);
			
			$data = array(
				"specimen_SN"=>$input_data['specimen_SN'],
				"verification_norm_no"=>$input_data['verification_norm_no'],
				"test_items_name_1"=>implode(',',$input_data['test_items_name_1']),
				"test_items_name_2"=>implode(',',$input_data['test_items_name_2']),
				"test_items_1_amount"=>$input_data['test_items_1_amount'],
				"outsourcing_organization"=>$input_data['outsourcing_organization']
			);
			$this->nanomark_model->add_outsourcing($data);
			
			echo $this->info_modal("新增成功","/nanomark/edit_application/{$specimen['application_SN']}");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
		
	}
	public function update_outsourcing()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("specimen_SN","檢測件編號","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			$options = array("specimen_SN"=>$input_data['specimen_SN']);
			$outsourcing = $this->nanomark_model->get_outsourcing_list($options)->row_array();
			if(!$outsourcing) throw new Exception("無此外包單",ERROR_CODE);
			
			
			if($this->nanomark_model->is_super_admin() || $this->nanomark_model->is_application_case_officer_1st()){
				$this->form_validation->set_rules("verification_norm_no","規範","required");
				$this->form_validation->set_rules("test_items_name_1[]","項目1","required");
				$this->form_validation->set_rules("test_items_name_2[]","項目2","required");
				$this->form_validation->set_rules("test_items_1_amount","數量","required");
				$this->form_validation->set_rules("outsourcing_organization","公司","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$data = array(
					"specimen_SN"=>$input_data['specimen_SN'],
					"verification_norm_no"=>$input_data['verification_norm_no'],
					"test_items_name_1"=>implode(',',$input_data['test_items_name_1']),
					"test_items_name_2"=>implode(',',$input_data['test_items_name_2']),
					"test_items_1_amount"=>$input_data['test_items_1_amount'],
					"outsourcing_organization"=>$input_data['outsourcing_organization']
				);
				$this->nanomark_model->update_outsourcing($data);
				
				echo $this->info_modal("更新成功",$this->whence->pop());
			}else{
				//確認是否為自己的外包單		
				if($outsourcing['applicant_ID'] != $this->session->userdata('ID')){
					throw new Exception("權限不足",ERROR_CODEs);
				}
				$data['specimen_SN'] = $outsourcing['specimen_SN'];
				$data['client_signature'] = $outsourcing['contact_name'];
				$this->nanomark_model->update_outsourcing($data);
				
				echo $this->info_modal("確認成功","/nanomark/list_progress/");
				
			}
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function delete_outsourcing($serial_no)
	{
		try{
			$this->is_admin_login();
			
			$serial_no = $this->security->xss_clean($serial_no);
			
			$this->nanomark_model->delete_outsourcing($serial_no);
			
			echo $this->info_modal("刪除成功",$this->whence->pop());
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function list_progress()
	{
		$this->is_user_login();
		
		$tmpl = array (
                    'table_open'          => '<table class="table table-hover">',
              );
		$this->table->set_template($tmpl);
		
		$user_ID = $this->session->userdata('ID');
		
		//查詢報價單狀態
		$this->table->set_heading("編號","狀態");
		$quotation = $this->nanomark_model->get_quotation_by_applicant_ID($user_ID);
		foreach($quotation as $row)
		{
			if(empty($row->case_officer_ID)){
				$this->table->add_row($row->serial_no,anchor("nanomark/view_quotation/{$row->serial_no}","報價中","class='btn btn-info'"));
				
			}else{
				$this->table->add_row($row->serial_no,anchor("nanomark/view_quotation/{$row->serial_no}","已完成","class='btn btn-success'"));
			}
		}
		$this->data['table_list_quotation'] = $this->table->generate();
		$this->table->clear();
		//查詢委託單狀態
		$this->table->set_heading("編號","狀態");
		$application = $this->nanomark_model->get_application_list(array("applicant_ID"=>$user_ID))->result_array();
		foreach($application as $row)
		{
			if($row['checkpoint'] == "Client_Final"){
				$this->table->add_row($row['ID'],anchor("nanomark/form_customer_survey/{$row['serial_no']}","請點我","class='btn btn-warning'"));
			}else if($row['checkpoint'] == "Completed"){
				$this->table->add_row($row['ID'],anchor("nanomark/view_application/{$row['serial_no']}","已完成","class='btn btn-success'"));
			}else{
				$this->table->add_row($row['ID'],anchor("nanomark/view_application/{$row['serial_no']}","進行中","class='btn btn-info'"));
			}
		}
		$this->data['table_list_application'] = $this->table->generate();
		$this->table->clear();
		
		//查詢外包單狀態
		$this->table->set_heading("委託單編號","樣品編號","狀態");
		$options = array("applicant_ID"=>$user_ID);
		$outsourcings = $this->nanomark_model->get_outsourcing_list($options)->result_array();
		foreach($outsourcings as $row)
		{
			if(empty($row['client_signature'])){
				$this->table->add_row($row['application_ID'],$row['specimen_ID'],anchor("nanomark/view_outsourcing/{$row['specimen_SN']}","請點我","class='btn btn-info'"));
			}else{
				$this->table->add_row($row['application_ID'],$row['specimen_ID'],anchor("nanomark/view_outsourcing/{$row['specimen_SN']}","已完成","class='btn btn-success'"));
			}
		}
		$this->data['table_list_outsourcing'] = $this->table->generate();
		$this->table->clear();
		
		//查詢顧客滿意度調查表狀態
		$this->table->set_heading("委託單編號","狀態");
		$options = array("applicant_ID"=>$this->session->userdata('ID'));
		$customer_survey = $this->nanomark_model->get_customer_survey_list($options)->result_array();
		foreach($customer_survey as $row)
		{
			$this->table->add_row($row['application_SN'],anchor("nanomark/view_customer_survey/{$row['customer_survey_SN']}","已完成","class='btn btn-success'"));
		}
		$this->data['table_list_customer_survey'] = $this->table->generate();
		$this->table->clear();
		
		//查詢報告修改申請表狀態
		$this->table->set_heading("申請單編號","委託單編號","申請日期","狀態");
		$options = array("applicant_ID"=>$this->session->userdata('ID'));
		$report_revision = $this->nanomark_model->get_report_revision_list($options)->result_array();
		foreach($report_revision as $row)
		{
			if($row['checkpoint']=="Accepted")
			{
				$this->table->add_row($row['serial_no'],$row['application_ID'],$row['application_date'],anchor("nanomark/view_report_revision/{$row['serial_no']}","已完成","class='btn btn-success'"));
			}else{
				$this->table->add_row($row['serial_no'],$row['application_ID'],$row['application_date'],anchor("nanomark/view_report_revision/{$row['serial_no']}","進行中","class='btn btn-info'"));
			}
		}
		$this->data['table_list_report_revision'] = $this->table->generate();
		$this->table->clear();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_progress',$this->data);
		$this->load->view('templates/footer');
	}
	
	public function list_quotation()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_quotation',$this->data);
		$this->load->view('templates/footer');	
	}
	public function list_quotation_query()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$result = $this->nanomark_model->get_quotation_list($input_data,"ARRAY");
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $result['iTotal'],
			"iTotalDisplayRecords" => $result['iFilteredTotal'],
			"aaData" => array()
		);
		
		foreach ( $result['rResult'] as $aRow )
		{
			$row = array();
			
			$row[] = $aRow['quotation_date'];
			$row[] = $aRow['contact_name'];
			$row[] = $aRow['organization'];
			
			
			if(empty($aRow['case_officer_ID']))
			{
				if($this->nanomark_model->get_admin_privilege_list(array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>'application_case_officer_1st'))->row_array())
				{
					$row[] = anchor("/nanomark/edit_quotation/{$aRow['serial_no']}","請點我","class='btn btn-warning'");
				}else{
					$row[] = anchor("/nanomark/view_quotation/{$aRow['serial_no']}?code=".urlencode($this->encrypt->encode($aRow['serial_no'])),"進行中","class='btn btn-info'");
				}
			}else{
				$row[] = anchor("/nanomark/view_quotation/{$aRow['serial_no']}?code=".urlencode($this->encrypt->encode($aRow['serial_no'])),"已完成","class='btn btn-success'");
			}	

			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	
	
	public function list_outsourcing()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_outsourcing',$this->data);
		$this->load->view('templates/footer');
	}
	
	public function list_outsourcing_query()
	{
		try{
			$this->is_admin_login();
			
			$outsourcings = $this->nanomark_model->get_outsourcing_list()->result_array();
			
			$output['aaData'] = array();
			foreach($outsourcings as $outsourcing)
			{
				$row = array();
				
				$row[] = $outsourcing['specimen_ID'];
				$row[] = $outsourcing['specimen_name'];
				$row[] = $outsourcing['specimen_company_name'];
				$row[] = $outsourcing['verification_norm_name'];
				$row[] = $outsourcing['outsourcing_organization'];
				
				if(!empty($outsourcing['client_signature']))
					$row[] = anchor("nanomark/view_outsourcing/{$outsourcing['specimen_SN']}","已同意","class='btn btn-success'");
				else
					$row[] = anchor("nanomark/edit_outsourcing/{$outsourcing['specimen_SN']}","等待中","class='btn btn-info '");

				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			
			echo json_encode($output);
		}
	}
	
	
	
	public function form_customer_survey($app_SN)
	{
		try{
			$this->is_user_login();
		
			$options = array("serial_no"=>$app_SN);
			$application = $this->nanomark_model->get_application_list($options)->row_array();
			//檢查權限
			if($application['applicant_ID'] != $this->session->userdata('ID'))
			{
				throw new Exception();
			}
			$this->data = $application;
			
			$this->data['company_name'] = $application['report_title'];
			$this->data['application_SN'] = $application['serial_no'];
			$this->data['application_ID'] = $application['ID'];
			$this->data['overall_quality'] = "";
			$this->data['request_form'] = "";
			$this->data['response_question'] = "";
			$this->data['communication'] = "";
			$this->data['time_test'] = "";
			$this->data['report'] = "";
			$this->data['price'] = "";
			for($i=0;$i<5;$i++)
			{
				$val = 5-$i;
				$this->data['overall_quality'] .= "<td align='center'><label class='radio'>".form_radio("overall_quality",$val)."</label></td>";
				$this->data['request_form'] .= "<td align='center'><label class='radio'>".form_radio("request_form",$val)."</label></td>";
				$this->data['response_question'] .= "<td align='center'><label class='radio'>".form_radio("response_question",$val)."</label></td>";
				$this->data['communication'] .= "<td align='center'><label class='radio'>".form_radio("communication",$val)."</label></td>";
				$this->data['time_test'] .= "<td align='center'><label class='radio'>".form_radio("time_test",$val)."</label></td>";
				$this->data['report'] .= "<td align='center'><label class='radio'>".form_radio("report",$val)."</label></td>";
				$this->data['price'] .= "<td align='center'><label class='radio'>".form_radio("price",$val)."</label></td>";
			}
			
			$this->data['reason'] = "<textarea name='reason' class='span12' rows='3'></textarea>";
			$this->data['recommendation'] = "<textarea name='recommendation' class='span12' rows='2'></textarea>";
			$this->data['completed_by'] = form_input("completed_by","{$application['contact_name']}","class='input-large'");
			$this->data['completed_date'] = "";
			
			$this->data['action_btn'] = form_submit("","送出","class='btn btn-success'");
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_customer_survey',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
			
	}
	public function view_customer_survey($SN)
	{
		try{
			$this->is_user_login();
		
			$SN = $this->security->xss_clean($SN);
			
			$options = array("customer_survey_SN"=>$SN);
			$customer_survey = $this->nanomark_model->get_customer_survey_list($options)->row_array();
			if(!$customer_survey)
			{
				throw new Exception();
			}
			if($customer_survey['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE))
			{
				throw new Exception();
			}
			
			$this->data['company_name'] = $customer_survey['report_title'];
			$this->data['application_ID'] = $customer_survey['application_ID'];
			$this->data['overall_quality'] = "";
			$this->data['request_form'] = "";
			$this->data['response_question'] = "";
			$this->data['communication'] = "";
			$this->data['time_test'] = "";
			$this->data['report'] = "";
			$this->data['price'] = "";
			for($i=0;$i<5;$i++)
			{
				if(5-$customer_survey['overall_quality'] == $i)
					$this->data['overall_quality'] .= "<td align='center'>V</td>";
				else
					$this->data['overall_quality'] .= "<td></td>";
					
				if(5-$customer_survey['request_form'] == $i)
					$this->data['request_form'] .= "<td align='center'>V</td>";
				else
					$this->data['request_form'] .= "<td></td>";
					
				if(5-$customer_survey['response_question'] == $i)
					$this->data['response_question'] .= "<td align='center'>V</td>";
				else
					$this->data['response_question'] .= "<td></td>";
					
				if(5-$customer_survey['communication'] == $i)
					$this->data['communication'] .= "<td align='center'>V</td>";
				else
					$this->data['communication'] .= "<td></td>";
					
				if(5-$customer_survey['time_test'] == $i)
					$this->data['time_test'] .= "<td align='center'>V</td>";
				else
					$this->data['time_test'] .= "<td></td>";
					
				if(5-$customer_survey['report'] == $i)
					$this->data['report'] .= "<td align='center'>V</td>";
				else
					$this->data['report'] .= "<td></td>";
					
				if(5-$customer_survey['price'] == $i)
					$this->data['price'] .= "<td align='center'>V</td>";
				else
					$this->data['price'] .= "<td></td>";
			}
			$this->data['reason'] = $customer_survey['reason'];
			$this->data['recommendation'] = $customer_survey['recommendation'];
			$this->data['completed_by'] = $customer_survey['completed_by'];
			$this->data['completed_date'] = $customer_survey['completed_date'];
			
			$this->data['action_btn'] = form_button("print_btn","列印","class='btn'");
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_customer_survey',$this->data);
			$this->load->view('templates/footer');	
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function add_customer_survey()
	{
		try{
			$this->is_user_login();
			
			$this->form_validation->set_rules("application_SN","報告編號","required");
			$this->form_validation->set_rules("overall_quality","我們整體的服務水平","required");
			$this->form_validation->set_rules("request_form","工作申請表","required");
			$this->form_validation->set_rules("response_question","解決您所提出的問題","required");
			$this->form_validation->set_rules("communication","與我們實驗室職員的溝通","required");
			$this->form_validation->set_rules("time_test","測試所需時間能否合乎您的需要","required");
			$this->form_validation->set_rules("report","測試報告證書上所叙述的資料","required");
			$this->form_validation->set_rules("price","測試費用","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
		
			$input_data = $this->input->post(NULL,TRUE);
			//確認單子是委託人的
			$application = $this->nanomark_model->get_application_list(array("serial_no"=>$input_data['application_SN']))->row_Array();
			if(!$application)
			{
				throw new Exception("無此委託單",ERROR_CODE);
			}
			if($application['applicant_ID'] != $this->session->userdata('ID'))
			{
				throw new Exception("沒有權限",ERROR_CODE);
			}
			
			$data = array("application_SN"=>$input_data['application_SN'],
						  "overall_quality"=>$input_data['overall_quality'],
						  "request_form"=>$input_data['request_form'],
						  "response_question"=>$input_data['response_question'],
						  "communication"=>$input_data['communication'],
						  "time_test"=>$input_data['time_test'],
						  "report"=>$input_data['report'],
						  "price"=>$input_data['price'],
						  "reason"=>empty($input_data['reason'])?"":$input_data['reason'],
						  "recommendation"=>empty($input_data['recommendation'])?"":$input_data['recommendation'],
						  "completed_by"=>empty($input_data['completed_by'])?"":$input_data['completed_by'],
						  "completed_date"=>date("Y-m-d H:i:s"));
			$this->nanomark_model->add_customer_survey($data);
			$this->nanomark_model->update_application(array("consignee_signature"=>$application['contact_name'],
															"serial_no"=>$application['serial_no']));
			$this->nanomark_model->update_application_checkpoint($application['serial_no'],"Completed");
			
			//送信通知大家客戶已簽收
			$this->nanomark_model->send_completed_notification($application['serial_no']);
			
			echo $this->info_modal("謝謝您撥空填寫，歡迎再度光臨","/nanomark/list_progress");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
		
	}
	public function list_customer_survey()
	{
		$this->is_admin_login();

		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_customer_survey',$this->data);
		$this->load->view('templates/footer');	
		
	}
	public function list_customer_survey_query()
	{
		try{
			$this->is_admin_login();
			
			$surveys = $this->nanomark_model->get_customer_survey_list()->result_array();
			
			$output['aaData'] = array();
			foreach($surveys as $survey)
			{
				$row = array();
			
				$row[] = $survey['application_ID'];
				$row[] = $survey['report_title'];
				$row[] = $survey['completed_by'];
				$row[] = $survey['completed_date'];
				
				if(empty($survey['customer_survey_SN']))
				{
					$row[] = anchor("nanomark/view_application/{$survey['application_SN']}","待填寫","class='btn btn-info'");
				}else{
					$row[] = anchor("nanomark/view_customer_survey/{$survey['customer_survey_SN']}","已完成","class='btn btn-success'");
				}
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
			
		}catch(Exception $e){
			echo json_encode($output);
		}
	}
	public function list_report_revision()
	{
		$this->is_user_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_report_revision',$this->data);
		$this->load->view('templates/footer');	
	}
	public function list_report_revision_query()
	{
		if($this->is_admin_login(FALSE))
		{
			$revisions = $this->nanomark_model->get_report_revision_list()->result_array();
		
			$output['aaData'] = array();
			
			foreach($revisions as $revision)
			{
				$row = array();
				
				$row[] = $revision['application_date'];
				$row[] = $revision['application_ID'];
				$row[] = $revision['report_title'];
				$row[] = $revision['report_ID'];
				$row[] = $revision['mistake_description'];
				
				
				if(in_array($this->session->userdata('ID'),$this->nanomark_model->get_report_revision_unsigned_admin_by_checkpoint($revision['serial_no'],$revision['checkpoint'])))
				{
					$row[] = anchor("/nanomark/edit_report_revision/{$revision['serial_no']}","請點我","class='btn btn-warning'");
				}
				else if($revision['checkpoint'] == "Accepted")
				{
					$row[] = anchor("/nanomark/view_report_revision/{$revision['serial_no']}","已完成","class='btn btn-success'");
				}
				else if($revision['checkpoint'] == "Rejected")
				{
					$row[] = anchor("/nanomark/view_report_revision/{$revision['serial_no']}","未通過","class='btn btn-error'");
				}	
				else
				{
					$row[] = anchor("/nanomark/view_report_revision/{$revision['serial_no']}","進行中","class='btn btn-info'");
				}

				$output['aaData'][] = $row;
			}
			
			echo json_encode( $output );
		}
		else if($this->is_user_login())
		{
			
		}
		
		
		
	}
	
	public function form_report_revision()
	{
		try{
			$this->is_user_login();
			
			//列出可修改的檢測項目報告列表
			$specimen = $this->nanomark_model->get_specimen_list(array("applicant_ID"=>$this->session->userdata('ID')))->result_array();
			
			foreach($specimen as $row)
			{
				if($row['checkpoint'] == "Completed"){
					$this->data['report_ID_options'][$row['specimen_SN']] = $row['ID'];
					$this->data['org_name_options'][$row['ID']] = $row['report_title'];
				}
			}
			
			$applicant_profile = $this->user_model->get_user_profile_by_ID($this->session->userdata('ID'));
			$this->data['applicant_name'] = $applicant_profile['name'];
			
			$this->data['action_btn'] = form_submit("","送出","class='btn btn-warning'");

			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('nanomark/form_report_revision',$this->data);
			$this->load->view('templates/footer');	
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function edit_report_revision($revision_SN)
	{
		$this->is_admin_login();
		
		$revision_SN = $this->security->xss_clean($revision_SN);
		
		$report_revision = $this->nanomark_model->get_report_revision_list(array("revision_SN"=>$revision_SN))->row_array();
		$application = $this->nanomark_model->get_application_list(array("serial_no"=>$report_revision['application_SN']))->row_array();
		$applicant_profile = $this->user_model->get_user_profile_by_ID($application['applicant_ID']);
		
		$checkpoints = array("Quality Manager","Technical Manager","Report Signatory","Lab Manager");
		foreach($checkpoints as $row)
		{
			$cp = str_replace(" ","_",strtolower($row));
			//文字
			if($report_revision['checkpoint']==$row)
			{
				$this->data["{$cp}_comment"] = form_textarea(array(
	              'name'        => "{$cp}_comment",
				  'rows'		=> '5',
	              'value'       => $report_revision["{$cp}_comment"],
				  'class'		=> 'span12',
	            ));
			}else{
				$this->data["{$cp}_comment"] = $report_revision["{$cp}_comment"];
			}
			//印章
			if(!empty($report_revision["{$cp}_ID"]))
			{
				$report_revision["{$cp}_ID"] = explode(",",$report_revision["{$cp}_ID"]);
				$this->data["{$cp}_signature"] = "";
				foreach($report_revision["{$cp}_ID"] as $row2)
				{
					$admin_profile = $this->admin_model->get_admin_profile_by_ID($row2);
					$this->data["{$cp}_signature"] .= img($admin_profile['stamp']);
				}
			}
		}
		
		
		$this->data['serial_no'] = $revision_SN;
		$this->data['organization_name'] = $application->report_title;
		$this->data['applicant_name'] = $applicant_profile['name'];
		$this->data['report_ID'] = $report_revision['report_ID'];
		$this->data['application_date'] = $report_revision['application_date'];
		
		$report_revision['mistake_outline'] = explode(",",$report_revision['mistake_outline']);
		$mistake_outline = array("Report Incomplete","Typewritten Error","Data Error","Result Incorrect","Report Incomplete","Others");
		foreach($mistake_outline as $row)
		{
			$this->data[str_replace(" ","_",strtolower($row))] = in_array($row,$report_revision['mistake_outline'])?"■":"□";
		}
		
		$this->data['mistake_description'] = $report_revision['mistake_description'];
		$this->data['mistake_analysis'] = $report_revision['mistake_analysis'];
		$this->data['disposal_revision'] = $report_revision['disposal_revision'];
			
		$this->data['action_btn'] = form_button("update","簽名","value='accept' class='btn btn-warning'")." ".form_button("update","駁回","value='reject' class='btn btn-inverse'");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/form_report_revision',$this->data);
		$this->load->view('templates/footer');
	}
	public function view_report_revision($revision_SN)
	{
		$this->is_user_login();
		
		$report_revision = $this->nanomark_model->get_report_revision_list(array("revision_SN"=>$revision_SN))->row_array();
		$application = $this->nanomark_model->get_application_list(array("serial_no"=>$report_revision['application_SN']))->row_array();
		if($application['applicant_ID'] != $this->session->userdata('ID') && !$this->is_admin_login(FALSE))
		{
			echo $this->show_error_page();
			return;
		}
		$applicant_profile = $this->user_model->get_user_profile_by_ID($application->applicant_ID);
		
		$checkpoints = array("Quality Manager","Technical Manager","Report Signatory","Lab Manager");
		foreach($checkpoints as $row)
		{
			$cp = str_replace(" ","_",strtolower($row));
			//文字
			$this->data["{$cp}_comment"] = $report_revision["{$cp}_comment"];
			//印章
			if(!empty($report_revision["{$cp}_ID"]))
			{
				$report_revision["{$cp}_ID"] = explode(",",$report_revision["{$cp}_ID"]);
				$this->data["{$cp}_signature"] = "";
				foreach($report_revision["{$cp}_ID"] as $row2)
				{
					$admin_profile = $this->admin_model->get_admin_profile_by_ID($row2);
					$this->data["{$cp}_signature"] .= img($admin_profile['stamp']);
				}
			}
			
			
		}
		
		
		$this->data['organization_name'] = $application->report_title;
		$this->data['applicant_name'] = $applicant_profile['name'];
		$this->data['report_ID'] = $report_revision['report_ID'];
		$this->data['application_date'] = $report_revision['application_date'];
		
		$report_revision['mistake_outline'] = explode(",",$report_revision['mistake_outline']);
		$mistake_outline = array("Report Incomplete","Typewritten Error","Data Error","Result Incorrect","Report Incomplete","Others");
		foreach($mistake_outline as $row)
		{
			$this->data[str_replace(" ","_",strtolower($row))] = in_array($row,$report_revision['mistake_outline'])?"■":"□";
		}
		
		$this->data['mistake_description'] = $report_revision['mistake_description'];
		$this->data['mistake_analysis'] = $report_revision['mistake_analysis'];
		$this->data['disposal_revision'] = $report_revision['disposal_revision'];
			
		$this->data['action_btn'] = form_button("print_btn","列印","class='btn' ");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/form_report_revision',$this->data);
		$this->load->view('templates/footer');
	}
	public function update_report_revision()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		$report_revision = $this->nanomark_model->get_report_revision_list(array("revision_SN"=>$input_data['serial_no']))->row_array();
		$application = $this->nanomark_model->get_application_list(array("serial_no"=>$report_revision['application_SN']))->row_array();
		
		if(in_array($this->session->userdata('ID'),$this->nanomark_model->get_report_revision_unsigned_admin_by_checkpoint($report_revision['serial_no'],$report_revision['checkpoint'])))
		{
			$cp = str_replace(" ","_",strtolower($report_revision['checkpoint']));
			$this->nanomark_model->update_report_revision($report_revision['serial_no'],$cp,$input_data["{$cp}_comment"]);
			if($input_data['result']=="reject")
			{
				$this->nanomark_model->update_report_revision_checkpoint($report_revision['serial_no'],"Rejected");
			}else if(!count($this->nanomark_model->get_report_revision_unsigned_admin_by_checkpoint($report_revision['serial_no'],$report_revision['checkpoint'])))
			{
				$next_cp = array("Quality Manager"=>"Technical Manager",
								 "Technical Manager"=>"Report Signatory",
								 "Report Signatory"=>"Lab Manager",
								 "Lab Manager"=>"Accepted");
				$this->nanomark_model->update_report_revision_checkpoint($report_revision['serial_no'],$next_cp[$report_revision['checkpoint']]);
			}
		}
		else
		{
			echo $this->info_modal("沒有權限","","error");
			return;
		}
		echo $this->info_modal("審核成功","/nanomark/edit_report_revision/{$report_revision['serial_no']}");
	}
	public function add_report_revision()
	{
		try{
			$this->is_user_login();
			
			$this->form_validation->set_rules("report_ID","報告編號","required");
			$this->form_validation->set_rules("mistake_outline[]","報告編號","required");
			$this->form_validation->set_rules("mistake_description","錯誤說明","required");
			$this->form_validation->set_rules("mistake_analysis","錯誤原因分析","required");
			$this->form_validation->set_rules("disposal_revision","處置/修改","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			$input_data = $this->input->post(NULL,TRUE);
			$specimen = $this->nanomark_model->get_specimen_by_ID($input_data['report_ID']);
			//檢查對應的委託單檢測項目是否為自己的
			if($specimen['applicant_ID'] != $this->session->userdata('ID'))
			{
				echo $this->info_modal("沒有權限","","error");
				return;
			}
			$input_data['application_ID'] = $specimen['application_ID'];
			$input_data['mistake_outline'] = implode(",",$input_data['mistake_outline']);
			$this->nanomark_model->add_report_revision($input_data);
			
			echo $this->info_modal("申請成功","/nanomark/list_progress");
		}catch(Exception $e){
			$this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function list_test_item()
	{
		$this->is_admin_login();
		
		$tmpl = array ( 'table_open'  => '<table class="table table-hover" id="table_nanomark_test_item">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading("檢測項目","對應機台","");
		
		$facility = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
		$facility_select_options = array(""=>"");
		foreach($facility as $row)
		{
			$facility_select_options[$row['ID']] = $row['cht_name']."({$row['eng_name']})";
		}
		
		$test_item = $this->nanomark_model->get_test_items_list();
		$this->table->add_row(form_input("name","","class='input-large'"),form_dropdown("facility_ID",$facility_select_options,"","class='input-xxlarge chosen-with-diselect'"),form_button("add","新增","class='btn btn-warning'"));
		foreach($test_item as $row)
		{
			$this->table->add_row(form_hidden("serial_no",$row['serial_no']).form_input("name",$row['name'],"class='input-large'"),form_dropdown("facility_ID",$facility_select_options,$row['facility_ID'],"class='input-xxlarge chosen-with-diselect'"),form_button("update","更新","class='btn btn-success'"));
		}
		
		
		$this->data['table_list_test_item'] = $this->table->generate();
		
		
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_test_item',$this->data);
		$this->load->view('templates/footer');	
	}
	public function add_test_item()
	{
		$this->is_admin_login();
		$input_data = $this->input->post(NULL,TRUE);
		if(empty($input_data['facility_ID'])) $input_data['facility_ID'] = NULL;
		$this->nanomark_model->add_test_item($input_data);
		echo $this->info_modal("新增成功","/nanomark/list_test_item");
	}
	public function update_test_item()
	{
		$this->is_admin_login();
		$input_data = $this->input->post(NULL,TRUE);
		if(empty($input_data['facility_ID'])) $input_data['facility_ID'] = NULL;
		$this->nanomark_model->update_test_item_by_serial_no($input_data['serial_no'],$input_data);
		echo $this->info_modal("更新成功","");
	}
	public function list_verification_norm()
	{
		$this->is_admin_login();
		
		$verification_norm = $this->nanomark_model->get_verification_norm();
		
		$tmpl = array('table_open'=>'<table class="table table-hover" id="table_list_verification_norm">');
		$this->table->set_template($tmpl);
		$this->table->set_heading( "規範名稱","狀態");
		$this->table->add_row(form_input("name","","class='span12'"),form_button("add","新增","class='btn btn-primary'"));
		foreach($verification_norm as $row)
		{
			$this->table->add_row($row['name'],anchor(site_url()."/nanomark/del_verification_norm/".$row['serial_no'],"刪除","class='btn btn-danger'"));
		}
		$this->data['table_list_verification_norm'] = $this->table->generate();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('nanomark/list_verification_norm',$this->data);
		$this->load->view('templates/footer');	
	}
	public function add_verification_norm()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		$this->nanomark_model->add_verification_norm($input_data);
		
		echo $this->info_modal("新增成功","/nanomark/list_verification_norm");
	}
//	public function update_verification_norm()
//	{
//		$this->is_admin_login();
//		
//		echo $this->info_modal("更新成功","");
//	}
	public function del_verification_norm($SN)
	{
		$this->is_admin_login();
		
		$SN = intval($SN);//XSS clean
		
		$this->nanomark_model->delete_verification_norm($SN);
		
		redirect('/nanomark/list_verification_norm');
	}

}