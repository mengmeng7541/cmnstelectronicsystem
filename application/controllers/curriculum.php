<?php
class Curriculum extends MY_Controller {
	private $data = array();
	
	private static $booking_state = array(""=>"","normal"=>"正常","additional"=>"額外");
	public function __construct()
	{
		parent::__construct();

		$this->load->model('curriculum_model');
		$this->load->model('curriculum/course_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/lesson_model');
		$this->load->model('curriculum/reg_model');
		$this->load->model('curriculum/signature_model');
	}
	//----------------SYSTEM CONFIG------------------
	public function edit_config()
	{
		try{
			$this->is_admin_login();
			
			$this->load->model('admin_model');
			$this->data['admin_ID_select_options'] = $this->admin_model->get_admin_ID_select_options();
			
			//取得現在的系統設定
			$results = $this->curriculum_model->get_admin_privilege_by_privilege("curriculum_super_admin");
			$this->data['super_admin_IDs'] = sql_result_to_column($results,"admin_ID");
			
			//
			$this->data['bulletin'] = $this->curriculum_model->get_bulletin_list(array("bulletin_ID"=>"reg_warning"))->row_array();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_config',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function update_config()
	{
		try{
			$this->is_admin_login();
			
			if(!$this->curriculum_model->is_super_admin()){
				throw new Exception("權限不足",ERROR_CODE);
			}
			
			$this->form_validation->set_rules("curriculum_super_admin_ID[]","超級管理者","required");
			if(!$this->form_validation->run()){
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->curriculum_model->del_admin_privilege(array("privilege"=>"curriculum_super_admin"));
			foreach($input_data['curriculum_super_admin_ID'] as $ID){
				$data = array("admin_ID"=>$ID,"privilege"=>"curriculum_super_admin");
				$this->curriculum_model->add_admin_privilege($data);
			}
			
			echo $this->info_modal("變更成功");
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//--------------------Bulletin------------------
	public function update_bulletin()
	{
		try{
			$this->is_admin_login();
			
			$this->form_validation->set_rules("reg_warning_bulletin_content","公告","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->curriculum_model->update_bulletin(array(
				"bulletin_ID"=>"reg_warning",
				"bulletin_content"=>$input_data['reg_warning_bulletin_content']
			));
			
			echo $this->info_modal("變更成功");
		} catch (Exception $e) {
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//-----------------------------------------------
	public function list_course()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('curriculum/list_course',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_course()
	{
		$this->is_admin_login();
		
		$courses = $this->course_model->get_course_list_table()->result_array();
		$output['aaData'] = array();
		foreach($courses as $c)
		{
			$row = array();
			$row[] = $c['course_ID'];
			$row[] = $c['course_cht_name'];
			$row[] = $c['facility_cht_name'];
			$row[] = anchor("/curriculum/course/edit/".$c['course_ID'],"編輯","class='btn btn-warning'")." ".form_button("del","刪除","value='{$c['course_ID']}' class='btn btn-danger'");
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	public function form_course()
	{
		$this->is_admin_login();
		$this->edit_course();
	}
	public function edit_course($course_ID = NULL)
	{
		try{
			$this->is_admin_login();
			
			if(!empty($course_ID))
			{
				$course_ID = $this->security->xss_clean($course_ID);
			
				//取得課程資訊
				$course = $this->curriculum_model->get_course_list(array("course_ID"=>$course_ID))->row_array();
				if(!$course) throw new Exception();
				$this->data = $course;
				//取得課程相關儀器
				$facilities = $this->curriculum_model->get_course_facility_map(array("course_ID"=>$course['course_ID']))->result_array();
				if($facilities)
				{
					$facilities = $this->rotate_2D_array($facilities);
					$this->data['facility_ID'] = $facilities['facility_ID'];
				}
				//取得檔修資訊
				$pre_course_ID = $this->curriculum_model->get_pre_course_list(array("course_ID"=>$course_ID))->result_array();
				$this->data['pre_course_ID'] = sql_result_to_column($pre_course_ID,"pre_course_ID");
				
				//傳遞資料
				$this->data['course_ID'] = $course_ID;
			}
			//取得儀器列表
			$this->load->model('facility_model');
			$this->data['facility_select_options'] = $this->facility_model->get_facility_select_options("facility");
			//取得課程列表
			$this->data['course_ID_select_options'] = $this->course_model->get_course_ID_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_course',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function add_course()
	{	
		$this->is_admin_login();
		$this->update_course();
	}
	public function update_course()
	{
		try
		{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$this->form_validation->set_rules("course_cht_name","課程中文名稱","required");
			$this->form_validation->set_rules("course_eng_name","課程英文文名稱","required");
			$this->form_validation->set_rules("course_min_participants","預設最小開課人數","required");
			$this->form_validation->set_rules("course_max_participants","預設最大開課人數","required");
			if(!$this->form_validation->run())
				throw new Exception(validation_errors(),WARNING_CODE);
				
			$input_data = $this->input->post(NULL,TRUE);
			
			if(empty($input_data['course_ID']))
			{
				$data = array(	"course_cht_name"=>$input_data['course_cht_name'],
								"course_eng_name"=>$input_data['course_eng_name'],
								"course_min_participants"=>$input_data['course_min_participants'],
								"course_max_participants"=>$input_data['course_max_participants']);
				$insert_ID = $this->curriculum_model->add_course($data);
				
				//新增facility mapping
				$data = array();
				if(!empty($input_data['facility_ID']))
				{
					foreach($input_data['facility_ID'] as $facility_ID)
					{
						if(empty($facility_ID)) continue;
						$data[] = array("course_ID"=>$insert_ID,
										"facility_ID"=>$facility_ID);
					}
					$this->curriculum_model->add_course_facility_map($data);
				}
				//新增pre course
				if(!empty($input_data['pre_course_ID'])){
					$data = array();
					foreach($input_data['pre_course_ID'] as $pre_course_ID){
						if(empty($pre_course_ID)) continue;
						$data[] = array("course_ID"=>$insert_ID,
										"pre_course_ID"=>$pre_course_ID);
					}
					$this->curriculum_model->add_pre_course($data);
				}
				
				echo $this->info_modal("新增成功","/curriculum/course/list");
			}else{
				$data = array(	"course_ID"=>$input_data['course_ID'],
								"course_cht_name"=>$input_data['course_cht_name'],
								"course_eng_name"=>$input_data['course_eng_name'],
								"course_min_participants"=>$input_data['course_min_participants'],
								"course_max_participants"=>$input_data['course_max_participants']);
				$this->curriculum_model->update_course($data);
				
				$data = array("course_ID"=>$input_data['course_ID']);
				$this->curriculum_model->del_course_facility_map($data);//先刪
				$data = array();
				if(!empty($input_data['facility_ID']))
				{
					foreach($input_data['facility_ID'] as $facility_ID)
					{
						if(empty($facility_ID)) continue;
						$data[] = array("course_ID"=>$input_data['course_ID'],
										"facility_ID"=>$facility_ID);
					}
					$this->curriculum_model->add_course_facility_map($data);//再增
				}
				
				//pre
				//先刪
				$data = array("course_ID"=>$input_data['course_ID']);
				$this->curriculum_model->del_pre_course($data);
				//再增
				if(!empty($input_data['pre_course_ID'])){
					$data = array();
					foreach($input_data['pre_course_ID'] as $pre_course_ID){
						$data[] = array("course_ID"=>$input_data['course_ID'],
										"pre_course_ID"=>$pre_course_ID);
					}
					$this->curriculum_model->add_pre_course($data);
				}
				
				echo $this->info_modal("更新成功","/curriculum/course/list");
			}
			
			
		}
		catch(Exception $e)
		{
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_course($course_ID)
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
		
			$course_ID = $this->security->xss_clean($course_ID);
			if(empty($course_ID)) throw new Exception("無此課程",ERROR_CODE);
			
			$data = array("course_ID"=>$course_ID);
			$this->curriculum_model->del_course($data);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//-----------------------CLASS--------------------------
	public function list_class($class_type=NULL)
	{
		$this->is_user_login();
		
		$this->data['bulletin'] = $this->curriculum_model->get_bulletin_list(array("bulletin_ID"=>"reg_warning"))->row_array();
		$this->data['class_type'] = $class_type;
		
		if($this->is_admin_login(FALSE)){
			$action_btn[] = anchor("/curriculum/class/form","單一新增","class='btn btn-warning'");
			$action_btn[] = anchor("/curriculum/class/form/batch","批次新增","class='btn btn-warning'");
			$this->data['action_btn'] = $action_btn;
		}
				
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('curriculum/list_class',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_class()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->get(NULL,TRUE);
			
			
			$output['aaData'] = array();
			
			if($this->is_admin_login(FALSE))
			{
				$options = array(
					"class_code"=>$input_data['class_code']
				);
				$classes = $this->curriculum_model->get_class_list($options)->result_array();
				
				$this->load->model('facility_model');
				foreach($classes as $class)
				{
					
					$row = array();
					$row[] = "{$class['course_cht_name']} ({$class['course_eng_name']})";
					$row[] = end(explode("-",$class['class_code']));
					$row[] = $this->curriculum_model->get_class_type_str($class['class_type']);
					$row[] = $class['location_cht_name'];
					$row[] = $class['class_start_time'];
					$row[] = $class['class_end_time'];
					$row[] = $class['class_total_secs']/3600;
					$row[] = $class['prof_name'];
					$reg_participants = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']))->num_rows();
					$row[] = "$reg_participants/{$class['class_max_participants']}";
					$row[] = $class['class_state_name'];
					$row[] = $class['class_remark'];
					
					if(!$this->curriculum_model->is_super_admin())
					{
						$facility_IDs = $this->course_model->get_course_map_facility_ID($class['course_ID']);
						if(!empty($facility_IDs)){
							$privilege = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility_IDs,"privilege"=>"admin","user_ID"=>$this->session->userdata('ID')))->result_array();
							if(!$privilege){
								continue;
							}
						}
						
					}
					$row[] = implode(' ',array(
						anchor("/curriculum/class/edit/".$class['class_ID'],"編輯","class='btn btn-warning btn-small'"),
						form_button("del_class","刪除","class='btn btn-danger btn-small' value='{$class['class_ID']}'"),
						anchor("/curriculum/reg/form/".$class['class_ID'],"加選","class='btn btn-primary btn-small'"),
						anchor("/curriculum/lesson/list/".$class['class_ID'],"排課","class='btn btn-primary btn-small'")
					));
					
					$output['aaData'][] = $row;
				}
			}else{
				if(isset($input_data['class_type'])&&$input_data['class_type']=='certification')
				{
					$options = array(
						"class_code"=>$input_data['class_code'],
						"class_type"=>'certification'
					);
				}else{
					$options = array(
						"class_code"=>$input_data['class_code'],
						"group_class_suite"=>TRUE
					);
				}
				$classes = $this->curriculum_model->get_class_list($options)->result_array();
				
				foreach($classes as $class)
				{
					
					$row = array();
					$row[] = "{$class['course_cht_name']} ({$class['course_eng_name']})";
					$row[] = end(explode("-",$class['class_code']));
					
					$row[] = $this->curriculum_model->get_class_type_str($class['class_type']);
					$row[] = $class['class_start_time'];
					$reg_participants = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']))->num_rows();
					$row[] = "$reg_participants/{$class['class_max_participants']}";
					$row[] = $class['class_state_name'];
					$row[] = $class['class_remark'];
					
					$display = array();
					
					
					//先確認未停開
					if($class['class_state']!='canceled')
					{
						//取得開課的報名資訊
						$reg = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID'],"user_ID"=>$this->session->userdata('ID')))->row_array();
						
						if(time()<strtotime($class['class_reg_start_time'])){
							$display[] = form_label("尚未開放報名","",array("class"=>"label label-info"));
						}else if(time()>strtotime($class['class_reg_end_time'])){
							$display[] = form_label("報名已截止","",array("class"=>"label label-important"));
						}else{
							if(!$reg){
								$display[] = form_button("reg","報名","class='btn btn-primary' value='{$class['class_ID']}'");
							}
						}
						
						if($reg){
							
							//判斷正取還是備取
							if(empty($class['class_max_participants'])||$reg['reg_rank']<=$class['class_max_participants'])
							{
								$display[] = form_label("正取{$reg['reg_rank']}","",array("class"=>"label label-success"));
								
							}else{
								$display[] = form_label("備取".($reg['reg_rank']-$class['class_max_participants']),"",array("class"=>"label label-warning"));
								
							}
							
							if($reg['reg_state']=='selected'){
								$display[] = form_button("del","取消","class='btn btn-danger btn-small' value='{$reg['reg_ID']}'");
							}else if($reg['reg_state']=='confirmed')
							{
								$display[] = form_label("已確認","",array("class"=>"label label-info"));
							}else if($reg['reg_state']=='certified'){
								$display[] = form_label("已認證","",array("class"=>"label label-success"));
							}
							
						}
						
						$display[] = anchor("/curriculum/lesson/list/".$class['class_ID'],"瀏覽課表","class='btn btn-primary btn-mini'");
					}
					
					$row[] = implode(' ',$display);
					
					
					$output['aaData'][] = $row;
				}
			}
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
		
	}
	public function form_class()
	{
		$this->is_admin_login();
		$this->edit_class();
	}
	public function form_batch_class()
	{
		try{
			$this->is_admin_login();
			
			$this->data['course_ID_select_options'] = $this->course_model->get_course_ID_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/form_batch_class',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function edit_class($class_ID = NULL)
	{
		try{
			$this->is_admin_login();
		
			if(empty($class_ID))
			{
				
			}else{
				$class_ID = $this->security->xss_clean($class_ID);
				//get class
				$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
				if(!$class) throw new Exception("",ERROR_CODE);
				$this->data = $class;
			}
			
			$this->data['course_ID_select_options'] = $this->course_model->get_course_ID_select_options();				
			//取得課程狀態
			$this->data['class_state_ID_select_options'] = $this->class_model->get_class_state_ID_select_options();
			
			//取得地點資訊
			$this->load->model('common_model');
			$this->data['location_name_select_options'] = $this->common_model->get_location_ID_select_options();
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_class',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function add_batch_class()
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			if(isset($input_data['action_btn']) && $input_data['action_btn'] == "copy_month"){
				$this->form_validation->set_rules("class_copy_src_month","複製來源","required");
				$this->form_validation->set_rules("class_copy_dst_month","複製目標","required");
				$this->form_validation->set_rules("class_reg_start_date","開放註冊日期","required");
				$this->form_validation->set_rules("class_reg_start_time","開放註冊時間","required");
				$this->form_validation->set_rules("class_reg_end_date","結束註冊日期","required");
				$this->form_validation->set_rules("class_reg_end_time","結束註冊時間","required");
				if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
				
				if($input_data['class_copy_src_month'] == $input_data['class_copy_dst_month'])
				{
					throw new Exception("來源不可與目標月份相同",WARNING_CODE);
				}
				$src_classes = $this->curriculum_model->get_class_list(array("class_code"=>$input_data['class_copy_src_month']))->result_array();
				if(!$src_classes){
					throw new Exception("來源月份無開任何課程",ERROR_CODE);
				}
				$dst_classes = $this->curriculum_model->get_class_list(array("class_code"=>$input_data['class_copy_dst_month']))->result_array();
				if($dst_classes){
					throw new Exception("目標月份已有課程，不可覆蓋",ERROR_CODE);
				}
				
				foreach($src_classes as $src_class){
					$src_class_code = explode('-',$src_class['class_code']);
					$dst_class_code = $input_data['class_copy_dst_month'].'-'.$src_class_code[2];
					$data = array(  
						"course_ID"=>$src_class['course_ID'],
						"class_type"=>$src_class['class_type'],
						"class_min_participants"=>$src_class['class_min_participants'],
						"class_max_participants"=>$src_class['class_max_participants'],
						"class_code"=>$dst_class_code,
						"class_reg_start_time"=>$input_data['class_reg_start_date']." ".$input_data['class_reg_start_time'],
						"class_reg_end_time"=>$input_data['class_reg_end_date']." ".$input_data['class_reg_end_time'],
						"class_reg_end_time_auto"=>empty($input_data['class_reg_end_time_auto'])?0:1,
						"class_location"=>$src_class['class_location']
					);
					
					//先檢查是否有重複的課
					$class = $this->curriculum_model->get_class_list(array(
						"course_ID"=>$data['course_ID'],
						"class_code"=>$data['class_code'],
						"class_type"=>$data['class_type'])
					)->row_array();
					if($class){
						continue;
					}
				
					$class_ID = $this->curriculum_model->add_class($data);
					if($data['class_reg_end_time_auto']){
						$this->class_model->update_reg_end_time($class_ID);
					}
				}
			}else{
				$this->form_validation->set_rules("course_ID[]","課程名稱","required");
				$this->form_validation->set_rules("class_code[]","開課代碼","required");
				$this->form_validation->set_rules("class_reg_start_date","開放註冊日期","required");
				$this->form_validation->set_rules("class_reg_start_time","開放註冊時間","required");
				$this->form_validation->set_rules("class_reg_end_date","結束註冊日期","required");
				$this->form_validation->set_rules("class_reg_end_time","結束註冊時間","required");
				if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
				
				$this->load->model('facility_model');
				foreach($input_data['course_ID'] as $course_ID){
					$course = $this->curriculum_model->get_course_list(array("course_ID"=>$course_ID))->row_array();
					
					
					$data = array(  "course_ID"=>$course['course_ID'],
								"class_type"=>implode(",",isset($input_data['class_type'])?$input_data['class_type']:array()),
								"class_min_participants"=>$course['course_min_participants'],
								"class_max_participants"=>$course['course_max_participants'],
								"class_code"=>implode("-",$input_data['class_code']),
								"class_reg_start_time"=>$input_data['class_reg_start_date']." ".$input_data['class_reg_start_time'],
								"class_reg_end_time"=>$input_data['class_reg_end_date']." ".$input_data['class_reg_end_time'],
								"class_reg_end_time_auto"=>empty($input_data['class_reg_end_time_auto'])?0:1);
					
					//先檢查是否有重複的課
					$class = $this->curriculum_model->get_class_list(array(
						"course_ID"=>$data['course_ID'],
						"class_code"=>$data['class_code'],
						"class_type"=>$data['class_type'])
					)->row_array();
					if($class){
						continue;
					}
								
					$course_facility_map = $this->curriculum_model->get_course_facility_map(array("course_ID"=>$course['course_ID']))->row_array();
					if($course_facility_map){
						$facility = $this->facility_model->get_facility_list(array("ID"=>$course_facility_map['facility_ID']))->row_array();
						$data['class_location'] = $facility['location_ID'];
					}
				
					$class_ID = $this->curriculum_model->add_class($data);
					if($data['class_reg_end_time_auto']){
						$this->class_model->update_reg_end_time($class_ID);
					}
				}
			}
			
			echo $this->info_modal("新增成功","/curriculum/class/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function add_class()
	{
		$this->is_admin_login();
		$this->update_class();
	}
	public function update_class()
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("course_ID","課程名稱","required");
			$this->form_validation->set_rules("class_min_participants","最小人數限制","required");
			$this->form_validation->set_rules("class_max_participants","最大人數限制","required");
			$this->form_validation->set_rules("class_code[]","開課代碼","required");
			$this->form_validation->set_rules("class_location","開課地點","required");
			
			$this->form_validation->set_rules("class_reg_start_date","開放註冊日期","required");
			$this->form_validation->set_rules("class_reg_start_time","開放註冊時間","required");
			$this->form_validation->set_rules("class_reg_end_date","結束註冊日期","required");
			$this->form_validation->set_rules("class_reg_end_time","結束註冊時間","required");
			
			$this->form_validation->set_rules("class_state","開課狀況","required");
			if(!$this->form_validation->run()) throw new Exception(validation_errors(),WARNING_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$data = array(  "course_ID"=>$input_data['course_ID'],
							"class_type"=>implode(",",isset($input_data['class_type'])?$input_data['class_type']:array()),
							"class_min_participants"=>$input_data['class_min_participants'],
							"class_max_participants"=>$input_data['class_max_participants'],
							"class_code"=>implode("-",$input_data['class_code']),
							"class_reg_start_time"=>$input_data['class_reg_start_date']." ".$input_data['class_reg_start_time'],
							"class_reg_end_time"=>$input_data['class_reg_end_date']." ".$input_data['class_reg_end_time'],
							"class_reg_end_time_auto"=>empty($input_data['class_reg_end_time_auto'])?0:1,
							"class_location"=>$input_data['class_location'],
							"class_state"=>$input_data['class_state'],
							"class_remark"=>$input_data['class_remark']);
			
			if(empty($input_data['class_ID']))
			{
				//先檢查是否有重複的課
				$class = $this->curriculum_model->get_class_list(array(
					"course_ID"=>$data['course_ID'],
					"class_code"=>$data['class_code'],
					"class_type"=>$data['class_type'])
				)->row_array();
				if($class){
					throw new Exception("重複開課",ERROR_CODE);
				}
				
				$class_ID = $this->curriculum_model->add_class($data);
				if($data['class_reg_end_time_auto']){
					$this->class_model->update_reg_end_time($class_ID);
				}
				echo $this->info_modal("新增成功","/curriculum/class/list");
			}else{
				$data['class_ID'] = $input_data['class_ID'];
				$this->curriculum_model->update_class($data);
				if($data['class_reg_end_time_auto']){
					$this->class_model->update_reg_end_time($data['class_ID']);
				}
				echo $this->info_modal("修改成功","/curriculum/class/list");
			}
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function del_class($class_ID)
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$class_ID = $this->security->xss_clean($class_ID);
			
			$this->class_model->del($class_ID);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//---------------------------lesson---------------------------------
	public function list_lesson($class_ID)
	{
		try{
			$this->is_user_login();
			
			$class_ID = $this->security->xss_clean($class_ID);
			
			$this->data['class_ID'] = $class_ID;
			
			if($this->is_admin_login(FALSE)){
				$action_btn[] = anchor("/curriculum/lesson/form/".$class_ID,"新增","class='btn btn-primary'");
				$action_btn[] = anchor("/curriculum/class/list/","取消","class='btn btn-warning'");
			}else{
				$action_btn[] = anchor("/curriculum/class/list/","回前頁","class='btn btn-primary'");
			}
			$this->data['action_btn'] = $action_btn;
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/list_lesson',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function query_lesson()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->get(NULL,TRUE);
			
			if($this->is_admin_login(FALSE))
			{
				//get lesson
				$lessons = $this->curriculum_model->get_lesson_list(array("class_ID"=>$input_data['class_ID']))->result_array();
			}else{
				//get class
				$class = $this->curriculum_model->get_class_list(array(
					"class_ID"=>$input_data['class_ID']
				))->row_array();
				
				if($this->class_model->is_certification_class_only($class['class_type']))
				{
					//get lesson
					$lessons = $this->curriculum_model->get_lesson_list(array(
						"class_ID"=>$class['class_ID'],
					))->result_array();
				}else{
					//get lesson
					$lessons = $this->curriculum_model->get_lesson_list(array(
						"course_ID"=>$class['course_ID'],
						"class_code"=>$class['class_code']
					))->result_array();
				}
				
				
			}
			
			$class_type = $this->class_model->get_class_type_select_options();
			
			$output['aaData'] = array();
			foreach($lessons as $lesson)
			{
				$row = array();
				$row[] = $this->curriculum_model->get_class_type_str($lesson['lesson_type']);
				$row[] = $lesson['lesson_prof_name'];
				$row[] = $lesson['lesson_start_time'];
				$row[] = $lesson['lesson_end_time'];
				$row[] = $lesson['location_cht_name'];
				if($this->is_admin_login(FALSE)){
					$row[] = anchor("/curriculum/lesson/edit/".$lesson['lesson_ID'],"編輯","class='btn btn-warning btn-small'").' '.form_button("del","刪除","class='btn btn-danger btn-small' value='{$lesson['lesson_ID']}'");
				}else{
					$row[] = "";
				}
				
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
		
	}
	
	public function form_lesson($class_ID)
	{
		try{
			$this->is_admin_login();
		
			$class_ID = $this->security->xss_clean($class_ID);
			
			//確認開課ID存在
			$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
			if(!$class) throw new Exception("",ERROR_CODE);
			$this->data = $class;
			
			//取得可授課教授名單
			$this->data['user_ID_select_options'] =  $this->course_model->get_professor_ID_select_options($class['course_ID']);
			
			//傳入儀器ID
			$this->data['facility_ID_select_options'] = $this->course_model->get_facility_ID_select_options($class['course_ID']);
			$this->data['facility_ID'] = $this->course_model->get_course_map_facility_ID($class['course_ID']);
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_lesson',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
		
	}
	public function edit_lesson($lesson_ID)
	{
		try{
			$this->is_admin_login();

			$lesson_ID = $this->security->xss_clean($lesson_ID);
			if(empty($lesson_ID)) throw new Exception("");
			//取得課堂資訊
			$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$lesson_ID))->row_array();
			if(!$lesson) throw new Exception("");
			$this->data = $lesson;
			//取得開課資訊
			$class = $this->curriculum_model->get_class_list(array("class_ID"=>$lesson['class_ID']))->row_array();
			if(!$class) throw new Exception("",ERROR_CODE);
			$this->data = array_merge($this->data,$class);
			
			//取得可授課教授名單
			$this->data['user_ID_select_options'] =  $this->course_model->get_professor_ID_select_options($class['course_ID']);
			
			//傳入儀器ID
			$this->data['facility_ID_select_options'] = $this->course_model->get_facility_ID_select_options($class['course_ID']);
			$this->data['facility_ID'] = $this->course_model->get_course_map_facility_ID($class['course_ID']);
			//傳入已預約的booking_ID
			$this->data['booking_ID'] = $this->lesson_model->get_lesson_map_booking_ID($lesson['lesson_ID'],"normal");
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_lesson',$this->data);
			$this->load->view('templates/footer');
			
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_lesson()
	{
		$this->is_admin_login();
		$this->update_lesson();
	}
	public function update_lesson()
	{
		try{
			$this->is_admin_login();
			
			
			
			$this->form_validation->set_rules("user_ID","授課者","required");
			
			
			if(!$this->form_validation->run())
				throw new Exception(validation_errors(),WARNING_CODE);
			
			$input_data = $this->input->post(NULL,TRUE);
			
			
			
			if(empty($input_data['lesson_ID']))//新增一筆
			{
				$this->form_validation->set_rules("class_ID","開課代碼","required");
				if(!$this->form_validation->run())
				throw new Exception(validation_errors(),WARNING_CODE);
				
				//取得開課資訊
				$class = $this->curriculum_model->get_class_list(array("class_ID"=>$input_data['class_ID']))->row_array();
				if(!$class){
					throw new Exception("無開此課",ERROR_CODE);
				}
				$facility_IDs = $this->course_model->get_course_map_facility_ID($class['course_ID']);
				
				//檢查權限
				if(!$this->curriculum_model->is_super_admin())
				{
					$this->load->model('facility_model');
					if(empty($facility_IDs))
					{
						throw new Exception("權限不足！",ERROR_CODE);
					}
					$privilege = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility_IDs,"privilege"=>"admin","user_ID"=>$this->session->userdata('ID')))->row_array();
					if(!$privilege){
						throw new Exception("權限不足！",ERROR_CODE);
					}
					if(time()>strtotime(date("Y-m-d 12:00:00",strtotime($class['class_reg_start_time']." -1day")))){
						throw new Exception("已過可排課時間！",ERROR_CODE);
					}
				}
				
				
				
				
				if(empty($facility_IDs)){
					$this->form_validation->set_rules("lesson_start_date[]","授課時段","required");
					$this->form_validation->set_rules("lesson_start_time[]","授課時段","required");
					$this->form_validation->set_rules("lesson_end_date[]","授課時段","required");
					$this->form_validation->set_rules("lesson_end_time[]","授課時段","required");
					if(!$this->form_validation->run())
						throw new Exception(validation_errors(),WARNING_CODE);
						
					//新增課堂
					$this->lesson_model->add($class['class_ID'],$input_data['user_ID'],strtotime($input_data['lesson_start_date'].' '.$input_data['lesson_start_time']),strtotime($input_data['lesson_end_date'].' '.$input_data['lesson_end_time']),$input_data['lesson_comment']);
				}else{
					$this->form_validation->set_rules("booking_time[]","授課時段","required");
					if(!$this->form_validation->run())
						throw new Exception(validation_errors(),WARNING_CODE);
					
					$this->load->model('facility/booking_model');
					$booking_time = $this->booking_model->get_booking_time($input_data['booking_time'],$facility_IDs);
					//新增課堂
					$this->lesson_model->add($class['class_ID'],$input_data['user_ID'],$booking_time[0],$booking_time[1],$input_data['lesson_comment']);
				}
				
				
				echo $this->info_modal("新增成功","/curriculum/lesson/list/".$class['class_ID']);
			}else{
				$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$input_data['lesson_ID']))->row_array();
				if(!$lesson)
				{
					throw new Exception("無此課堂",ERROR_CODE);
				}
				$facility_IDs = $this->course_model->get_course_map_facility_ID($lesson['course_ID']);
				
				//檢查權限
				if(!$this->curriculum_model->is_super_admin())
				{
					if(empty($facility_IDs))
					{
						throw new Exception("權限不足！",ERROR_CODE);
					}
					$this->load->model('facility_model');
					$privilege = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility_IDs,"privilege"=>"admin","user_ID"=>$this->session->userdata('ID')))->row_array();
					if(!$privilege){
						throw new Exception("權限不足！",ERROR_CODE);
					}
					$class = $this->curriculum_model->get_class_list(array("class_ID"=>$lesson['class_ID']))->row_array();
					if(time()>strtotime(date("Y-m-d 12:00:00",strtotime($class['class_reg_start_time']." -1day")))){
						throw new Exception("已過可排課時間！",ERROR_CODE);
					}
				}
				
				
				
				
				if(empty($facility_IDs)){
					$this->form_validation->set_rules("lesson_start_date[]","授課時段","required");
					$this->form_validation->set_rules("lesson_start_time[]","授課時段","required");
					$this->form_validation->set_rules("lesson_end_date[]","授課時段","required");
					$this->form_validation->set_rules("lesson_end_time[]","授課時段","required");
					if(!$this->form_validation->run())
						throw new Exception(validation_errors(),WARNING_CODE);
					//修改課堂資訊
					$this->lesson_model->update($lesson['lesson_ID'],$input_data['user_ID'],strtotime($input_data['lesson_start_date'].' '.$input_data['lesson_start_time']),strtotime($input_data['lesson_end_date'].' '.$input_data['lesson_end_time']),$input_data['lesson_comment']);
				}else{
					$this->form_validation->set_rules("booking_time[]","授課時段","required");
					if(!$this->form_validation->run())
						throw new Exception(validation_errors(),WARNING_CODE);
					
					$this->load->model('facility/booking_model');
					$booking_time = $this->booking_model->get_booking_time($input_data['booking_time'],$facility_IDs);
					//修改課堂資訊
					$this->lesson_model->update($lesson['lesson_ID'],$input_data['user_ID'],$booking_time[0],$booking_time[1],$input_data['lesson_comment']);
				}
				
				echo $this->info_modal("修改成功","/curriculum/lesson/list/".$lesson['class_ID']);
			}
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_lesson($lesson_ID)
	{
		try{
			$this->is_admin_login();
			
			$lesson_ID = $this->security->xss_clean($lesson_ID);
			
			$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$lesson_ID))->row_array();
			
			
			if(!$this->curriculum_model->is_super_admin()){
				$facility_IDs = $this->curriculum_model->get_course_facility_map($lesson['course_ID']);
				if(empty($facility_IDs))
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
				$this->load->model('facility_model');
				$privilege = $this->facility_model->get_user_privilege_list(array(
					"facility_ID"=>$facility_IDs,
					"user_ID"=>$this->session->userdata('ID'),
					"privilege"=>"admin"
				))->row_array();
				if(!$privilege)
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
				$class = $this->curriculum_model->get_class_list(array("class_ID"=>$lesson['class_ID']))->row_array();
				if(time()>strtotime(date("Y-m-d 12:00:00",strtotime($class['class_reg_start_time']." -1day")))){
					throw new Exception("已過可排課時間！",ERROR_CODE);
				}
			}
			
			
			$this->lesson_model->del($lesson_ID);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}

	//--------------------------BOOKING------------------------------
	public function query_booking()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$output['aaData'] = array();
		if(empty($input_data['lesson_ID']))
		{
			echo json_encode($output);
			return;
		}
		//取得lesson_booking_map
		$bookings = $this->curriculum_model->get_lesson_booking_map(array("lesson_ID"=>$input_data['lesson_ID']))->result_array();
		foreach($bookings as $booking)
		{
			$row = array();
			$row[] = $booking['booking_ID'];
			$row[] = $booking['user_name'];
			$row[] = $booking['facility_cht_name']." (".$booking['facility_eng_name'].")";
			$row[] = $booking['booking_start_time'];
			$row[] = $booking['booking_end_time'];
			$row[] = implode(' ',array(self::$booking_state[$booking['booking_state']],
										empty($booking['booking_remark'])?"":"[{$booking['booking_remark']}]"));
			if($booking['booking_state']=='normal'){
				$row[] = "";
			}else{
				$row[] = form_button("del","刪除","class='btn btn-danger btn-small' value='{$booking['booking_ID']}'").' '.anchor("/curriculum/booking/edit/".$booking['booking_ID'],"編輯","class='btn btn-warning btn-small'");
			}
			
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	public function form_booking($lesson_ID = "")
	{
		try{
			$this->is_admin_login();
			
			$lesson_ID = $this->security->xss_clean($lesson_ID);
			$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$lesson_ID))->row_array();
			if(!$lesson) throw new Exception("");
			$this->data = $lesson;
			
			//取得可預約之儀器名單
			$this->data['facility_ID_select_options'] = $this->course_model->get_facility_ID_select_options($lesson['course_ID']);
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_booking',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			echo $this->show_error_page();
		}

	}
	
	public function edit_booking($booking_ID = "")
	{
		try{
			$this->is_admin_login();
			
			$booking_ID = $this->security->xss_clean($booking_ID);
			$booking = $this->curriculum_model->get_lesson_booking_map(array("booking_ID"=>$booking_ID))->row_array();
			if(!$booking) throw new Exception("");
			$this->data = $booking;
			
			$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$booking['lesson_ID']))->row_array();
			$this->data['facility_ID_select_options'] = $this->course_model->get_facility_ID_select_options($lesson['course_ID']);
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/edit_booking',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			echo $this->show_error_page();
		}
	}
	public function add_booking()
	{
		//alias for update_booking
		$this->update_booking();
	}
	public function update_booking()
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$this->form_validation->set_rules("booking_time[]","預約時段","required");
			$this->form_validation->set_rules("booking_remark","預約原因","required");
			if(!$this->form_validation->run())
				throw new Exception(validation_errors(),WARNING_CODE);
				
			$input_data = $this->input->post(NULL,TRUE);
			
			if(empty($input_data['booking_ID']))
			{
				$this->form_validation->set_rules("lesson_ID","課堂ID","required");
				$this->form_validation->set_rules("facility_ID","預約儀器","required");
				if(!$this->form_validation->run())
					throw new Exception(validation_errors(),WARNING_CODE);
					
				//取得課堂資訊
				$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$input_data['lesson_ID']))->row_array();
				if(!$lesson) throw new Exception("無此課堂！",ERROR_CODE);
				//取得儀器資訊
				$this->load->model('facility_model');
				$facility = $this->facility_model->get_facility_list(array("ID"=>$input_data['facility_ID']))->row_array();
				if(!$facility) throw new Exception("無此儀器",ERROR_CODE);
				//取得上課老師資訊
				$this->load->model('user_model');
				$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$lesson['lesson_prof_ID']))->row_array();
				if(!$user_profile) throw new Exception("無此使用者",ERROR_CODE);
				//檢查填選的時段
				$this->load->model('facility/booking_model');
				$this->booking_model->check_input_time($input_data['booking_time'],$facility['unit_sec']);
				//新增預約紀錄
				$booking_ID = $this->booking_model->add($facility['ID'],$lesson['lesson_prof_ID'],min($input_data['booking_time']),max($input_data['booking_time'])+$facility['unit_sec'],"course");
				
				//新增課堂與預約儀器間關係
				$this->curriculum_model->add_lesson_booking_map(array("lesson_ID"=>$lesson['lesson_ID'],"booking_ID"=>$booking_ID,"booking_state"=>"additional","booking_remark"=>$input_data['booking_remark']));
				
				echo $this->info_modal("預約成功","/curriculum/lesson/edit/".$lesson['lesson_ID']);
			}else{
				//先取得原本的預約資訊
				$booking = $this->curriculum_model->get_lesson_booking_map(array("booking_ID"=>$input_data['booking_ID']))->row_array();
				if(!$booking) throw new Exception("無此預約",ERROR_CODE);
				//取得選填的時段
				$this->load->model('facility/booking_model');
				$booking_time = $this->booking_model->get_booking_time($input_data['booking_time'],$booking['facility_ID']);
				//更新預約記錄
				$this->booking_model->update_time($booking['booking_ID'],$booking_time[0],$booking_time[1]);
				
				echo $this->info_modal("變更成功","/curriculum/lesson/edit/".$booking['lesson_ID']);
			}
			
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_booking($booking_ID)
	{
		try{
			$this->is_admin_login();
			if(!$this->curriculum_model->is_super_admin())	throw new Exception("權限不足！",ERROR_CODE);
			
			$booking_ID = $this->security->xss_clean($booking_ID);
			
			$this->load->model('facility/booking_model');
			$this->booking_model->del($booking_ID);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//-------------------------REGISTRATION--------------------------
	public function list_reg()
	{
		try{
			$this->is_user_login();
			
			$this->data['course_ID_select_options'] = $this->course_model->get_course_ID_select_options();
		
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/list_reg',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function query_reg()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->get(NULL,TRUE);
			
			$options = array(
				"class_code"=>isset($input_data['class_code'])?$input_data['class_code']:"",
				"course_ID"=>empty($input_data['course_ID'])?NULL:$input_data['course_ID'],
				"group_class_suite"=>TRUE
			);
			
			$regs = $this->curriculum_model->get_reg_list($options)->result_array();
			
			$output['aaData'] = array();
			$this->load->model('user_model');
			foreach($regs as $reg)
			{
				
				
				//查詢學員資料
				$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
				$row = array();
				$row[] = "{$reg['course_cht_name']} ({$reg['course_eng_name']})";
				$row[] = $reg['class_code'];
				$row[] = $this->curriculum_model->get_class_type_str($reg['class_type']);
				if(empty($reg['class_max_participants']) || $reg['reg_rank']<=$reg['class_max_participants'])
					$row[] = $user_profile['name'].form_label("正取".$reg['reg_rank'],"",array("class"=>"label label-success"));
				else
					$row[] = $user_profile['name'].form_label("備取".($reg['reg_rank']-$reg['class_max_participants']),"",array("class"=>"label label-warning"));
				$row[] = $user_profile['org_name'].$user_profile['department'];
				$row[] = $user_profile['mobile'];
				$row[] = $user_profile['email'];
				$row[] = $user_profile['boss_name'];
				
				
				
				$display = array();
				//取得授課人員名單
				$lessons = $this->curriculum_model->get_lesson_list(array(
					"course_ID"=>$reg['course_ID'],
					"class_code"=>$reg['class_code']
				))->result_array();
				$lesson_prof_IDs = array_unique(sql_result_to_column($lessons,"lesson_prof_ID"));
				if($this->curriculum_model->is_super_admin() || in_array($this->session->userdata('ID'),$lesson_prof_IDs))
				{
					if($reg['reg_state'] == "selected")
					{
						$display[] = form_checkbox("reg_ID[]",$reg['reg_ID']);
					}
						
					else if($reg['reg_state'] == "confirmed")
					{
						if($this->curriculum_model->is_super_admin()){
							$display[] = form_checkbox("reg_ID[]",$reg['reg_ID']);
						}else{
							if($reg['course_ID']==1)
							{
								//取得可認證授課人員名單
								$certification_lessons = $this->curriculum_model->get_lesson_list(array(
									"course_ID"=>$reg['course_ID'],
									"class_code"=>$reg['class_code']
								))->result_array();
								$certification_prof_IDs = array_unique(sql_result_to_column($lessons,"lesson_prof_ID"));
								if( in_array($this->session->userdata('ID'),$certification_prof_IDs))
								{
									$display[] = form_checkbox("reg_ID[]",$reg['reg_ID']);
								}
								

							}
							else if(in_array('certification',explode(",",$reg['class_type'])))
							{
								//取得可認證授課人員名單
								$certification_lessons = $this->curriculum_model->get_lesson_list(array(
									"course_ID"=>$reg['course_ID'],
									"class_code"=>$reg['class_code'],
									"lesson_type"=>"certification"
								))->result_array();
								$certification_prof_IDs = array_unique(sql_result_to_column($lessons,"lesson_prof_ID"));
								if( in_array($this->session->userdata('ID'),$certification_prof_IDs))
								{
									$display[] = form_checkbox("reg_ID[]",$reg['reg_ID']);
								}
								
							}
						}
						
					}
				}else if($reg['user_ID'] != $this->session->userdata('ID')){
					continue;
				}
				
					
				//顯示狀態
				if(!empty($reg['reg_confirmed_by']))
				{
					$display[] = form_label("已到","",array("class"=>"label label-success"));
				}
				if(!empty($reg['reg_certified_by']))
				{
					$display[] = form_label("已過","",array("class"=>"label label-success"));
				}
				
				//超級管理員隨時都可以取消
				if($this->curriculum_model->is_super_admin() && $reg['reg_state']=='selected'){
					$display[] = form_button("del","取消","class='btn btn-danger btn-small' value='{$reg['reg_ID']}'");
				}
					
				$row[] = implode(' ',$display);
				
				
				$output['aaData'][] = $row;
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
		
	}
	public function form_reg($class_ID = NULL)
	{
		//此表單為加選，不開放一般使用者
		try{
			$this->is_admin_login();
			
			//取得可選之課程列表
			$this->data['class_ID_select_options'] = $this->class_model->get_class_ID_select_options();
			$this->data['class_ID'] = $class_ID;
			
			//取得學員列表
			$this->load->model('user_model');
			$this->data['user_ID_select_options'] = $this->user_model->get_user_ID_select_options();
						
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('curriculum/form_reg',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function add_reg($class_ID = "")
	{
		try{
			$this->is_user_login();
			
			$class_ID = $this->security->xss_clean($class_ID);
			
			if(empty($class_ID)){
				if(!$this->curriculum_model->is_super_admin())
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
				$this->form_validation->set_rules("class_ID[]","課程","required");
				$this->form_validation->set_rules("user_ID[]","學員","required");
				if(!$this->form_validation->run())
				{
					throw new Exception(validation_errors(),WARNING_CODE);
				}
				$input_data = $this->input->post(NULL,TRUE);
				foreach($input_data['class_ID'] as $class_ID)
				{
					foreach($input_data['user_ID'] as $user_ID)
					{
						$this->reg_model->add($class_ID,$user_ID);
					}
				}
				echo $this->info_modal("加選成功","/curriculum/reg/list");
			}else{
				
				$this->reg_model->add($class_ID,$this->session->userdata('ID'));
				echo $this->info_modal("報名成功");
			}
			
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function update_reg()
	{
		try{
			$this->is_user_login();
			
			
			$this->form_validation->set_rules("reg_ID[]","學員","required");
			$this->form_validation->set_rules("action_btn","動作","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			if($input_data['action_btn']=="confirm")
			{
				$this->reg_model->confirm($input_data['reg_ID']);
				echo $this->info_modal("確認成功");
			}
			else if($input_data['action_btn']=="certify")
			{
				$this->reg_model->certify($input_data['reg_ID']);
				echo $this->info_modal("認證成功");
			}
			else
			{
				throw new Exception("未知動作",ERROR_CODE);
			}
			
			
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_reg($reg_ID = "")
	{
		try{
			$this->is_user_login();
			
			$reg_ID = $this->security->xss_clean($reg_ID);
			
			$this->reg_model->del($reg_ID);
			
			echo $this->info_modal("取消成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//----------------------簽到---------------------------
//	public function list_signature()
//	{
//		try{
//			$this->is_user_login();
//			
//			$this->load->view('templates/header');
//			$this->load->view('templates/sidebar');
//			$this->load->view('curriculum/list_signature',$this->data);
//			$this->load->view('templates/footer');
//		}catch(Exception $e){
//			$this->show_error_page();
//		}
//	}
//	public function query_signature()
//	{
//		try{
//			$this->is_user_login();
//			
//			$input_data = $this->input->get(NULL,TRUE);
//			
//			$output['aaData'] = array();
//			
//			//查詢可簽到的課程列表
//			if($this->curriculum_model->is_super_admin())
//			{
//				$options = array(
//					"lesson_start_time"=>date("Y-m-d 00:00:00",!empty($input_data['lesson_start_date'])?strtotime($input_data['lesson_start_date']):time()),
//					"lesson_end_time"=>date("Y-m-d 23:59:59",!empty($input_data['lesson_end_date'])?strtotime($input_data['lesson_end_date']):time())
//				);
//				$signatures = $this->curriculum_model->get_signature_list($options)->result_array();
//			}else{
//				//先以老師身分查詢
//				$options = array(
//					"teacher_ID"=>$this->session->userdata('ID'),
//					"lesson_start_time"=>date("Y-m-d 00:00:00",!empty($input_data['lesson_start_date'])?strtotime($input_data['lesson_start_date']):time()),
//					"lesson_end_time"=>date("Y-m-d 23:59:59",!empty($input_data['lesson_end_date'])?strtotime($input_data['lesson_end_date']):time())
//				);
//				$signatures = $this->curriculum_model->get_signature_list($options)->result_array();
//				
//				if(!$signatures)
//				{
//					//沒有再以學生身分查詢
//					$options = array(
//						"student_ID"=>$this->session->userdata('ID'),
//						"lesson_start_time"=>date("Y-m-d 00:00:00",!empty($input_data['lesson_start_date'])?strtotime($input_data['lesson_start_date']):time()),
//						"lesson_end_time"=>date("Y-m-d 23:59:59",!empty($input_data['lesson_end_date'])?strtotime($input_data['lesson_end_date']):time())
//					);
//					$signatures = $this->curriculum_model->get_signature_list($options)->result_array();
//				}
//				
//			}
//			
//			foreach($signatures as $signature)
//			{
//				$row = array();
//				$row[] = $signature['course_cht_name']." ({$signature['course_eng_name']})";
//				$row[] = $this->curriculum_model->get_class_type_str($signature['lesson_type']);
//				$row[] = $signature['lesson_teacher_name'];
//				$row[] = $signature['lesson_student_name'];
//				$row[] = $signature['lesson_start_time'];
//				$row[] = $signature['lesson_end_time'];
//				$row[] = $signature['location_name'];
//				$display = array();
//				if($signature['lesson_teacher_ID']==$this->session->userdata('ID'))
//				{
//					if(empty($signature['lesson_signature_by']))
//					{
//						$display[] = form_label("未到","",array("class"=>"label label-important"));
//					}else{
//						if(empty($signature['lesson_confirmed_by']))
//						{
//							$display[] = form_checkbox("signature_ID[]",$signature['signature_ID']);
//							$display[] = form_label("已到","",array("class"=>"label label-success"));
//						}else{
//							//安講，特殊課程
//							if($signature['course_ID'] == 1 || in_array('certification',explode(",",$signature['lesson_type'])))
//							{
//								if($signature['reg_state'] == 'confirmed')
//								{
//									$display[] = form_checkbox("reg_ID[]",$signature['reg_ID']);
//								}
//							}
//							$display[] = form_label("已到","",array("class"=>"label label-success"));
//							$display[] = form_label("已確認","",array("class"=>"label label-success"));
//							if($signature['reg_state']=='certified')
//							{
//								$display[] = form_label("已通過認證","",array("class"=>"label label-success"));
//							}
//						}
//						
//					}
//				}else{
//					if(empty($signature['lesson_signature_by']))
//					{
//						if(time()<strtotime($signature['lesson_start_time']." -1hour")){
//							$display[] = form_label("尚未開放簽到","",array("class"=>"label label-info"));
//						}
//						else if(time()<strtotime($signature['lesson_end_time']))
//						{
//							$display[] = form_button("sign","簽到","class='btn btn-primary' value='{$signature['lesson_ID']}'");
//						}else{
//							$display[] = form_label("簽到時間已過","",array("class"=>"label label-important"));
//						}
//						
//					}else{
//						$display[] = form_label("已到","",array("class"=>"label label-success"));
//						if(empty($signature['lesson_confirmed_by']))
//						{
//							if(time()<strtotime($signature['lesson_end_time']))
//							{
//								$display[] = form_button("del_sign","取消","class='btn btn-danger' value='{$signature['signature_ID']}'");
//							}
//						}
//						else
//						{
//							$display[] = form_label("已確認","",array("class"=>"label label-success"));
//						}
//						if($signature['reg_state']=='certified')
//						{
//							$display[] = form_label("已通過認證","",array("class"=>"label label-success"));
//						}
//					}
//				}
//				
//				$row[] = implode(' ',$display);
//				$output['aaData'][] = $row;
//			}
//			echo json_encode($output);
//		}catch(Exception $e){
//			echo json_encode($output);
//		}
//	}
//	public function add_signature($lesson_ID)
//	{
//		try{
//			$this->is_user_login();
//			
//			$lesson_ID = $this->security->xss_clean($lesson_ID);
//			
//			//檢查報名存在否
//			$signature = $this->curriculum_model->get_signature_list(array(
//				"lesson_ID"=>$lesson_ID,
//				"student_ID"=>$this->session->userdata('ID')
//			))->row_array();
//			if(!$signature)
//			{
//				throw new Exception("無此報名",ERROR_CODE);
//			}
//			
//			$this->signature_model->add($lesson_ID,$signature['reg_ID'],$this->session->userdata('ID'));
//			
//			echo $this->info_modal("簽到成功");
//		}catch(Exception $e){
//			echo $this->info_modal($e->getMessage(),"",$e->getCode());
//		}
//	}
//	public function add_signature_by_email($signature_ID,$signature_by,$hash)
//	{
//		try{
//			$data[] = array(
//				"signature_ID"=>$signature_ID,
//				"signature_by"=>$signature_by,
//				"signature_hash"=>$hash
//			);
//			$affected_rows = $this->curriculum_model->update_signature($data);
//			
//			$this->data['code'] = $affected_rows?SUCCESS_CODE:ERROR_CODE;
//			$this->data['message'] = $affected_rows?"簽到成功":"內部錯誤";
//			
//			$this->load->view('templates/header');
//			$this->load->view('templates/sidebar');
//			$this->load->view('templates/content',$this->data);
//			$this->load->view('templates/footer');
//		}catch(Exception $e){
//			$this->data['message'] = $e->getMessage();
//			$this->data['code'] = $e->getCode();
//			$this->load->view('templates/header');
//			$this->load->view('templates/sidebar');
//			$this->load->view('templates/content',$this->data);
//			$this->load->view('templates/footer');
//		}
//	}
//	public function update_signature()
//	{
//		try{
//			$this->is_user_login();
//			
//			$input_data = $this->input->post(NULL,TRUE);
//			
//			$action = isset($input_data['action_btn'])?$input_data['action_btn']:"";
//			if($action == 'confirm')
//			{
//				$this->form_validation->set_rules("signature_ID[]","簽到確認","required");
//				if(!$this->form_validation->run())
//				{
//					throw new Exception(validation_errors(),WARNING_CODE);
//				}
//				
//				
//				foreach($input_data['signature_ID'] as $signature_ID)
//				{
//					//先取得簽到資訊
//					$signature = $this->curriculum_model->get_signature_list(array("signature_ID"=>$signature_ID))->row_array();
//					if(!$signature)
//					{
//						throw new Exception("無此簽到紀錄",ERROR_CODE);
//					}
//					if($signature['lesson_teacher_ID'] != $this->session->userdata('ID') && !$this->curriculum_model->is_super_admin())
//					{
//						throw new Exception("權限不足",ERROR_CODE);
//					}
//					$data[] = array(
//						"signature_ID"=>$signature_ID,
//						"signature_confirmed_by"=>$this->session->userdata('ID')
//					);
//				}
//				$this->curriculum_model->update_signature($data);
//				
//				//確認是否為第一次確認
//				if($signature['reg_state'] == 'selected')
//				{
//					$this->reg_model->update($signature['reg_ID']);
//				}
//				
//				echo $this->info_modal("確認成功","/curriculum/signature/list");
//			}else if($action == 'certify')
//			{
//				$this->form_validation->set_rules("reg_ID[]","認證通過蓋章","required");
//				if(!$this->form_validation->run())
//				{
//					throw new Exception(validation_errors(),WARNING_CODE);
//				}
//				
//				foreach($input_data['reg_ID'] as $reg_ID)
//				{
//					$reg = $this->curriculum_model->get_reg_list(array("reg_ID"=>$reg_ID))->row_array();
//					if(!$reg)
//					{
//						throw new Exception("無此報名紀錄",ERROR_CODE);
//					}
//					//確認是否為第一次認證
//					if($reg['reg_state'] == 'confirmed')
//					{
//						$this->reg_model->update($reg['reg_ID']);
//					}
//				}
//				
//				echo $this->info_modal("認證成功","/curriculum/signature/list");
//			}else{
//				throw new Exception("未知的動作",ERROR_CODE);
//			}
//			
//			
//		}catch(Exception $e){
//			echo $this->info_modal($e->getMessage(),"",$e->getCode());
//		}
//	}
//	public function del_signature($sign_ID)
//	{
//		try{
//			$this->is_user_login();
//			
//			$sign_ID = $this->security->xss_clean($sign_ID);
//			
//			//取得簽到資訊
//			$signature = $this->curriculum_model->get_signature_list(array(
//				"signature_ID"=>$sign_ID,
//				"student_ID"=>$this->session->userdata('ID')
//			))->row_array();
//			if(!$signature)
//			{
//				throw new Exception("無此簽到紀錄");
//			}
//			if(!$this->curriculum_model->is_super_admin())
//			{
//				if(!empty($signature['lesson_confirmed_by']))
//				{
//					throw new Exception("授課人員已確認，不可取消",ERROR_CODE);
//				}
//				if(time()>strtotime($signature['lesson_end_time']))
//				{
//					throw new Exception("此堂課已結束，不可取消",ERROR_CODE);
//				}
//			}
//			
//			
//			$this->curriculum_model->del_signature(array(
//				"signature_ID"=>$sign_ID
//			));
//			
//			echo $this->info_modal("取消成功");
//		}catch(Exception $e){
//			echo $this->info_modal($e->getMessage(),"",$e->getCode());
//		}
//	}
	//---------------------通用------------------------
	
}
?>
