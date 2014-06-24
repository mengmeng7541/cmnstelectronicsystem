<?php
class Facility extends MY_Controller {
	private $data = array();
	private static $facility_category = array("奈米微影製程","奈米表面與磊晶","奈米材料分析","非破壞性分析","100"=>"奈米標章");
	private $booking_purpose = array("DIY"=>"自行操作","OEM"=>"代工","course"=>"課程","maintenance"=>"維護","nanomark"=>"奈米標章");
	private $card_application_type = array("apply"=>"請卡","refund"=>"退卡","reissue"=>"補發");
	private static $privilege_level = array("novice"=>"初心者","normal"=>"一般使用者","super"=>"超級使用者","admin"=>"儀器管理者","super_admin"=>"超級管理員");
	private static $facility_state = array("normal"=>"正常","fault"=>"故障","migrated"=>"已移轉");
	private static $access_link_type = array("","門禁","儀器");
	public function __construct()
	{
		parent::__construct();
		
		
		$this->load->model('facility_model');
		$this->load->model('facility/access_ctrl_model');
		$this->load->model('facility/booking_model');
		$this->load->model('facility/user_privilege_model');
		$this->load->model('user_model');
		$this->load->model('admin_model');
		$this->load->model('common_model');
		
	}
	//---------------------------儀器-----------------------------------
	
	public function list_facility()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_facility',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_facility()
	{
		$this->is_admin_login();

		$input_data = $this->input->get(NULL,TRUE);
		$input_data['type'] = 'facility';
		
		$facilities = $this->facility_model->get_facility_list_with_admin($input_data)->result_array();
		$output['aaData'] = array();
		foreach ( $facilities as $facility )
		{
			$row = array();
			
			$row[] = $facility['new_ID'];
			$row[] = "{$facility['cht_name']}({$facility['eng_name']})";
			$row[] = $facility['ctrl_no'];
//			$row[] = self::$facility_category[$facility['facility_tech']];
//			$row[] = $facility['facility_class'];
			$row[] = self::$facility_state[$facility['facility_state']];
			$row[] = $facility['admin_name'];
			$row[] = anchor("/facility/admin/facility/edit/{$facility['ID']}","編輯","class='btn btn-warning'");
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	public function edit_facility_config($facility_ID = "")
	{
		$this->is_admin_login();
		
		$facility = $this->facility_model->get_facility_by_ID($facility_ID);
		if(empty($facility_ID))
		{
			$this->data['action'] = site_url()."/facility/admin/facility/add";
		}else{
			$this->data = $facility;
			$this->data['action'] = site_url()."/facility/admin/facility/update";
			
			//取得機台目前管理員
			$privilege = $this->facility_model->get_user_privilege_list(
			array("facility_ID"=>$facility_ID,
				  "privilege"=>"admin")
			)->result_array();
			if($privilege)
			{
				$privilege = $this->rotate_2D_array($privilege);
				$this->data['admin_ID'] = $privilege['user_ID'];
			}
		}
		
		//取得類別列表
		$facility_category = $this->facility_model->get_facility_category_list();
		$facility_category_options = array(""=>"");
		$facility_tech_options = array(""=>"");
		foreach($facility_category as $row)
		{
			if($row['class_ID'] == NULL)
			{
				$facility_tech_options[$row['tech_ID']] = $row['name'];
			}else{
				$facility_category_options[$facility_tech_options[$row['tech_ID']]][$row['tech_ID'].",".$row['class_ID']] = $row['name'];
			}
		}
		$this->data['facility_category_options'] = $facility_category_options;
		//取的地點列表
		$this->data['facility_location_ID_select_options'] = $this->common_model->get_location_ID_select_options();
		//取得管理員列表
		$admin_profile = $this->admin_model->get_admin_profile_list()->result_array();
		$admin_list_options = array(""=>"");
		foreach($admin_profile as $row)
		{
			$admin_list_options[$row['ID']] = $row['name'];
		}
		$this->data['admin_ID_select_options'] = $admin_list_options;
		
		//取得機台列表
		$facilities = $this->facility_model->get_facility_list()->result_array();
		$facility_list_options = array(""=>"");
		foreach($facilities as $row)
		{
			$facility_list_options[$row['ID']] = $row['cht_name']."({$row['eng_name']})";
		}
		$this->data['facility_ID_select_options'] = $facility_list_options;
		
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_facility_config',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_batch_facility()
	{
		$this->is_admin_login();
		
		
		if($this->facility_model->is_facility_super_admin())
		{
			$facilities = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
			if($facilities)
			{
				foreach($facilities as $row)
				{
					$facility_ID_select_options[$row['ID']] = $row['cht_name']." ({$row['eng_name']})";
				}
				$this->data['facility_ID_select_options'] = $facility_ID_select_options;
			}
		}else{
			$privilege = $this->facility_model->get_user_privilege(array("user_ID"=>$this->session->userdata('ID'),"privilege"=>"admin"))->result_array();
			if($privilege)
			{
				foreach($privilege as $row)
				{
					$facility_ID_select_options[$row['ID']] = $row['cht_name']." ({$row['eng_name']})";
				}
				$this->data['facility_ID_select_options'] = $facility_ID_select_options;
			}
			
		}
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_batch_facilities',$this->data);
		$this->load->view('templates/footer');
	}
	public function add_facility()
	{
		$this->is_admin_login();
		
		//表單驗證
		$this->form_validation->set_rules("ID","儀器編號","callback_facility_ID_not_existed");
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","warning");
			return;
		}
		
		$this->update_facility_config("add");
	}
	public function update_facility_config($action = "update")
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		//表單驗證
		$this->form_validation->set_rules("ID","儀器編號","required");
		$this->form_validation->set_rules("cht_name","中文名稱","required");
		$this->form_validation->set_rules("eng_name","英文名稱","required");
		$this->form_validation->set_rules("status","儀器狀況","required");
		$this->form_validation->set_rules("enable_booking","是否開放預約","required");
		$this->form_validation->set_rules("enable_privilege","檢查預約權限","required");
		$this->form_validation->set_rules("enable_occupation","預約時段鎖定","required");
		$this->form_validation->set_rules("min_sec","最低預約時間","required");
		$this->form_validation->set_rules("unit_sec","單位預約時間","required");
		$this->form_validation->set_rules("extension_sec","權限延長時間","required");
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","warning");
			return;
		}
		//確認權限
		if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($input_data['ID']))
		{
			echo $this->info_modal("沒有權限","","error");
			return;
		}
		
		//最低預約時間不可小於單位預約時間
		if($input_data['min_sec'] < $input_data['unit_sec'])
		{
			echo $this->info_modal("最低預約時間不可小於單位預約時間","","warning");
			return;
		}
		
		//父機台
		if(empty($input_data['parent_ID'])) $input_data['parent_ID'] = NULL;
		//類別
		if(!empty($input_data['facility_category_ID']))
		{
			$input_data['facility_category_ID'] = explode(",",$input_data['facility_category_ID']);
		}
		if(COUNT($input_data['facility_category_ID'])==2)
		{
			$input_data['facility_tech_ID'] = $input_data['facility_category_ID'][0];
			$input_data['facility_class_ID'] = $input_data['facility_category_ID'][1];
		}else{
			$input_data['facility_tech_ID'] = NULL;
			$input_data['facility_class_ID'] = NULL;
		}
		
		//寫入前對輸入的資料作變更
		if(empty($input_data['ctrl_no'])) $input_data['ctrl_no'] = NULL;
		if(empty($input_data['location_ID'])) $input_data['location_ID'] = NULL;
		
		
		
		//更新機台設定
		if($action=="add")
		{
			$this->facility_model->add_facility($input_data);
			
		}else if($action=="update"){
			//取得舊的資訊
			$facility = $this->facility_model->get_facility_list(array("ID"=>$input_data['ID']))->row_array();
			if(!$facility)
			{
				echo $this->info_modal("儀器不存在","","error");
				return;
			}
			
			$this->facility_model->update_facility_by_ID($input_data);
			
			//更新使用權限
			if($facility['extension_sec'] != $input_data['extension_sec'])
			{
				$this->load->model('facility/user_privilege_model');
				$this->user_privilege_model->update(NULL,$facility['ID']);
			}
			
		}
		
		//更新機台管理員
		//先刪除
		$this->facility_model->del_user_privilege(array("facility_ID"=>$input_data['ID'],"privilege"=>"admin"));
		if(!empty($input_data['admin_ID']))
		{
			
			//確保管理員不重複
			$input_data['admin_ID'] = array_unique($input_data['admin_ID']);
			$data = array();
			foreach($input_data['admin_ID'] as $admin_ID)
			{
				if(empty($admin_ID)) continue;
				
				$row = array();
				
				$row['user_ID'] = $admin_ID;
				$row['facility_ID'] = $input_data['ID'];
				
				$data[] = $row;
			}
			if(!empty($data))
				$this->facility_model->del_user_privilege($data);
			//再新增
			$data = array();
			foreach($input_data['admin_ID'] as $admin_ID)
			{
				if(empty($admin_ID)) continue;
				
				$row = array();
				
				$row['user_ID'] = $admin_ID;
				$row['facility_ID'] = $input_data['ID'];
				$row['privilege'] = "admin";
				$row['expiration_date'] = NULL;
				
				$data[] = $row;
			}
			if(!empty($data))
				$this->facility_model->add_user_privilege($data);
		}
		
		if($action=="add")
		{
			echo $this->info_modal("新增成功","/facility/admin/facility/list");
		}else if($action=="update"){
			echo $this->info_modal("更新成功","/facility/admin/facility/list");
		}
	}
	public function update_batch_facility()
	{
		try{
			$this->is_admin_login();
		
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("facility_SN[]","儀器","required");
			$this->form_validation->set_rules("outage_start_date","停機起始日期","required");
			$this->form_validation->set_rules("outage_start_time","停機起始時間","required");
			$this->form_validation->set_rules("outage_remark","停機原因","required");
			
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$this->load->model('facility/outage_model');
			$this->outage_model->add_outage(
				$input_data['facility_SN'],
				$input_data['outage_remark'],
				"{$input_data['outage_start_date']} {$input_data['outage_start_time']}",
				"{$input_data['outage_end_date']} {$input_data['outage_end_time']}"
			);
			
			echo $this->info_modal("設定成功","/facility/admin/facility/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//---------------------------門禁-----------------------------------
	public function query_door()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$door = $this->facility_model->get_door_list_array($input_data);
		$output['aaData'] = array();
		foreach ( $door as $aRow )
		{
			$row = array();
			
			$row[] = $aRow['ID'];
			if(!empty($aRow['parent_ID']))
			{
				$parent_door = $this->facility_model->get_facility_by_ID($aRow['parent_ID']);
				$row[] = $parent_door['cht_name'];
			}else{
				$row[] = "";
			}
			$row[] = $aRow['name'];
			$row[] = $aRow['ctrl_no'];
			$row[] = anchor("/facility/admin/door/edit/{$aRow['ID']}","編輯","class='btn btn-warning '");
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	public function edit_door($door_ID = "")
	{
		$this->is_admin_login();
		
		$doors = $this->facility_model->get_door_list();
		$door_ID_options = array(""=>"");
		foreach($doors as $row)
		{
			$door_ID_options[$row['ID']] = $row['cht_name'];
		}
		
		if(empty($door_ID))
		{
			$this->data['action'] = site_url()."/facility/admin/door/add";
			$door['parent_ID'] = "";
		}else{
			//取得門禁資訊
			$door = $this->facility_model->get_facility_list(array("ID"=>$door_ID))->row_array();
			
			$this->data = $door;
			$this->data['action'] = site_url()."/facility/admin/door/update";
		}
		//取的地點列表
		$this->data['facility_location_ID_select_options'] = $this->common_model->get_location_ID_select_options();
		//取得父門禁列表
		$this->data['parent_ID'] = form_dropdown("parent_ID",$door_ID_options,$door['parent_ID'],"class='span12'");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_door',$this->data);
		$this->load->view('templates/footer');
	}
	public function add_door()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		if(empty($input_data['parent_ID'])) $input_data['parent_ID'] = NULL;
		if(empty($input_data['location_ID'])) $input_data['location_ID'] = NULL;
		
		$this->facility_model->add_door($input_data);
		
		echo $this->info_modal("新增成功","/facility/admin/facility/list");
	}
	public function update_door()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		if(empty($input_data['parent_ID'])) $input_data['parent_ID'] = NULL;
		if(empty($input_data['location_ID'])) $input_data['location_ID'] = NULL;
		
		$this->facility_model->update_door($input_data);
		
		echo $this->info_modal("更新成功","/facility/admin/facility/list");
	}
	//START---------------------------權限管理-------------------------------
	public function list_user_privilege()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_user_privilege',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_user_privilege()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$result = $this->facility_model->get_user_privilege_list_array($input_data);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $result['iTotal'],
			"iTotalDisplayRecords" => $result['iFilteredTotal'],
			"aaData" => array()
		);
		
		foreach ( $result['rResult'] as $aRow )
		{
			$row = array();
			
			$row[] = $aRow['user_ID'];
			$row[] = $aRow['user_name'];
			$row[] = $aRow['user_email'];
			$row[] = "{$aRow['facility_cht_name']} ({$aRow['facility_eng_name']})";
			$row[] = $aRow['privilege'];
			$row[] = $aRow['expiration_date'];
			$row[] = anchor(site_url()."/facility/admin/privilege/edit/".$aRow['SN'],"編輯","class='btn btn-warning'");

			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	public function edit_user_privilege($SN = "")
	{
		$this->is_admin_login();
		
		$facilities = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
		$facility_ID_select_options = array();
		foreach($facilities as $facility)
		{
			$facility_ID_select_options[$facility['ID']] = "{$facility['cht_name']} ({$facility['eng_name']})";
		}
		
		if(empty($SN))
		{
			//新增
			//取得使用者選單
			$this->load->model('user_model');
			$this->data['user_ID_select_options'] = $this->user_model->get_user_ID_select_options();
			$this->data['action'] = site_url()."/facility/admin/privilege/add";
			$this->data['facility_ID_select'] = form_dropdown("facility_ID[]",$facility_ID_select_options,"","multiple='multiple' class='span12 chosen'");
		}else{
			//更新
			//先取得資料
			$result = $this->facility_model->get_user_privilege_list(array("serial_no"=>$SN))->row_array();
			$this->data = $result;
			$this->data['facility_ID_select'] = $result['facility_cht_name'];
			$this->data['action'] = site_url()."/facility/admin/privilege/update/".$SN;
		}
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_user_privilege',$this->data);
		$this->load->view('templates/footer');
	}
	public function update_user_privilege($SN = "")
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		if(empty($SN))
		{
			//add_user_privilege
			//新增
			$this->form_validation->set_rules('user_ID','使用者帳號','callback_user_ID_existed');
			$this->form_validation->set_rules('facility_ID','儀器名稱','required');
			$this->form_validation->set_rules('privilege','權限設定','required');
			if(!$this->form_validation->run())
			{
				echo $this->info_modal(validation_errors(),"","warning");
				return;
			}
			
			$this->user_privilege_model->add($input_data['facility_ID'],$input_data['user_ID'],$input_data['privilege']);
			
			echo $this->info_modal("新增成功","/facility/admin/privilege/list");
			
		}else{
			//更新
			$input_data['serial_no'] = $SN;
			$this->facility_model->update_user_privilege($input_data);
			echo $this->info_modal("更新成功","/facility/admin/privilege/list");
		}
	}
	public function edit_batch_user_privilege()
	{
		try{
			$this->is_admin_login();
			
		}catch(Exception $e){
			$this->show_error_page();
		}
	}
	public function update_bacth_user_privilege()
	{
		try{
			$this->is_admin_login();
			if(!$this->facility_model->is_facility_super_admin())
			{
				throw new Exception("權限不足",ERROR_CODE);
			}
		}catch(Exception $e){
			$this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	//END---------------------------權限管理-------------------------------
	
	
	//START-------------------------預約紀錄--------------------------------
	public function list_booking()
	{
		$this->is_user_login();
		
		
		$facility_ID_select_options = array(""=>"");
		$facilities = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
		foreach($facilities as $facility)
		{
			$facility_ID_select_options[$facility['ID']] = "{$facility['cht_name']} ({$facility['eng_name']})";
		}
		$this->data['facility_ID_select_options'] = $facility_ID_select_options;
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_booking',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_booking()
	{
		$this->is_user_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		//先處理送進來的過濾器
		if(!empty($input_data['facility_ID']))
			$input_data['facility_ID'] = explode("|",$input_data['facility_ID']);
		$data = array();
		if($this->is_admin_login(FALSE))
		{
			//超級管理者顯示全部的儀器預約記錄，個別管理者只能顯示他們自己管理的儀器與他自己的紀錄

			//2014-02-25，孟倫說中心人員都可以看所有紀錄
			if(!empty($input_data['facility_ID']))
			{
				$data['facility_ID'] = $input_data['facility_ID'];
			}
			
		}
		else if($this->is_user_login())
		{
			//使用者
			$data['user_ID'] = $this->session->userdata('ID');
			if(!empty($input_data['facility_ID']))
				$data['facility_ID'] = $input_data['facility_ID'];
		}
		
		if(!empty($input_data['start_date']))
			$data['start_time'] = date("Y-m-d H:i:s",strtotime($input_data['start_date']." ".$input_data['start_time']));
		if(!empty($input_data['end_date']))
			$data['end_time'] = date("Y-m-d H:i:s",strtotime($input_data['end_date']." ".$input_data['end_time']));
		$bookings = $this->facility_model->get_facility_booking_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($bookings as $row)
		{
			$r = array();
			
			$r[] = $row['user_ID'];
			$r[] = $row['user_name'];
			$r[] = "{$row['facility_cht_name']} ({$row['facility_eng_name']})";
			$r[] = $this->booking_purpose[$row['purpose']];
			$r[] = $row['start_time'];
			$r[] = $row['end_time'];
			
			//如果現在時間不在預約時間之後，則可刪除或變更
			if(time() > strtotime($row['start_time']." -1 Hour"))
			{
				if(!empty($row['nocharge_serial_no']))
				{
					if(isset($row['nocharge_result']))
					{
						if($row['nocharge_result'])
						{
							$r[] = anchor("/facility/".$this->session->userdata('status')."/nocharge/view/".$row['nocharge_serial_no'],"不計費通過","class='btn btn-success btn-mini'");
						}else{
							$r[] = anchor("/facility/".$this->session->userdata('status')."/nocharge/view/".$row['nocharge_serial_no'],"不計費退件","class='btn btn-inverse btn-mini'");
						}
					}else{
						$r[] = form_label("不計費進行中","",array("class"=>"label label-info"));
					}
				}else{
					if($row['purpose']=="DIY" && $row['user_ID']==$this->session->userdata('ID'))
					{
						$r[] = anchor("/facility/".$this->session->userdata('status')."/nocharge/form/{$row['serial_no']}","不計費申請","class='btn btn-warning btn-small'");	
					}else{
						$r[] = "";
					}
				}
			}else{
				if($this->is_admin_login(FALSE))
				{
					//讓儀器管理員與最高管理員可以看到按鈕
					if($this->facility_model->is_facility_admin($row['facility_ID']) || $this->facility_model->is_facility_super_admin() || $row['user_ID'] == $this->session->userdata('ID'))
					{
						$r[] = form_button("del","取消","class='btn btn-danger btn-small' value='{$row['serial_no']}'")." ".anchor("/facility/".$this->session->userdata('status')."/booking/edit/{$row['serial_no']}","變更","class='btn btn-warning btn-small'");
					}else{
						$r[] = "";
					}
				}else if($this->is_user_login()){
					//使用者
					$r[] = form_button("del","取消","class='btn btn-danger btn-small' value='{$row['serial_no']}'")." ".anchor("/facility/".$this->session->userdata('status')."/booking/edit/{$row['serial_no']}","變更","class='btn btn-warning btn-small'");
				}
					
				
				
			}
				
				
			$output['aaData'][] = $r;
		}
		
		
		echo json_encode($output);
		
	}
	
	//END-------------------------預約紀錄--------------------------------
	
	//START-------------------------使用者預約動作--------------------------------
	//available facilities
	public function list_available()
	{
		$this->is_user_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_available',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_available()
	{
		$this->is_user_login();
		
		if($this->facility_model->is_facility_super_admin())
		{
			$data = array("type"=>"facility");
			
			$facilities = $this->facility_model->get_facility_list($data)->result_array();
			
			$output['aaData'] = array();
			foreach($facilities as $facility)
			{
				$row = array();
				
				$row[] = "{$facility['cht_name']} ({$facility['eng_name']})";
				
				$display = array();
				$display[] = self::$facility_state[$facility['facility_state']];
				if($facility['facility_state']=='fault')
				{
					$display[] = "({$facility['outage_remark']})";
				}
				$row[] = implode(' ',$display);
				
				$row[] = $facility['enable_booking']?"是":"否";
				$row[] = self::$privilege_level['super_admin'];
				$row[] = "無限制";
				$row[] = "";
				$row[] = $facility['note'];
				$row[] = anchor("/facility/".$this->session->userdata('status')."/booking/form/{$facility['ID']}","預約","class='btn btn-primary'");
				
				$output['aaData'][] = $row;
			}
		}else{
			$facilities = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
			
			foreach($facilities as $facility)
			{
				if($facility['enable_privilege'])
				{
					$privilege = $this->facility_model->get_user_privilege_list(array(
						"user_ID"=>$this->session->userdata('ID'),
						"facility_ID"=>$facility['ID']
					))->row_array();
					if(!$privilege)
					{
						continue;
					}
				}
				
				$row = array();
				
				$row[] = "{$facility['cht_name']} ({$facility['eng_name']})";
				
				$display = array();
				$display[] = self::$facility_state[$facility['facility_state']];
				if($facility['facility_state']=='fault')
				{
					$display[] = "({$facility['outage_remark']})";
				}
				$row[] = implode(' ',$display);
				
				$row[] = $facility['enable_booking']?"是":"否";
				if($facility['enable_privilege'])
				{
					$row[] = self::$privilege_level[$privilege['privilege']];
					$row[] = empty($privilege['expiration_date'])?"無限制":$privilege['expiration_date'];
					$row[] = $this->secs_to_hours($privilege['total_secs_used']);
				}else{
					$row[] = "---";
					$row[] = "無限制";
					$row[] = "---";
				}
				$row[] = $facility['note'];
				
				
				if(($facility['enable_booking'] && $facility['state']=="normal") || $this->facility_model->is_facility_admin($facility['ID']))
				{
					$row[] = anchor("/facility/".$this->session->userdata('status')."/booking/form/{$facility['ID']}","預約","class='btn btn-primary'");
				}else{
					$row[] = "";
				}
					
				$output['aaData'][] = $row;
			}
		}
		echo json_encode($output);
	}
	public function form_booking($f_ID)
	{
		$this->is_user_login();
		
		$f_ID = $this->security->xss_clean($f_ID);
		
		$facility = $this->facility_model->get_facility_list(array("ID"=>$f_ID))->row_array();
		$this->data = $facility;
		
		//取得該使用者的儀器使用權限(超級管理員不適用)
		
		//取得可代操作的使用者名單
		if($this->is_admin_login(FALSE))
		{
			$privileges = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility['ID'],"privilege"=>"super"))->result_array();
			$user_ID_select_options = array($this->session->userdata('ID')=>"");
			foreach($privileges as $p)
			{
				$user_ID_select_options[$p['user_ID']] = $p['user_name'];
			}
			//(包含管理員)
			$privileges = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility['ID'],"privilege"=>"admin"))->result_array();
			foreach($privileges as $p)
			{
				$user_ID_select_options[$p['user_ID']] = $p['user_name'];
			}
			$this->data['user_ID_select_options'] = $user_ID_select_options;
		}
		
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/form_booking',$this->data);
		$this->load->view('templates/footer');
	}

	public function edit_booking($SN)
	{
		$this->is_user_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$booking = $this->facility_model->get_facility_booking_list(
		array("serial_no"=>$SN))->row_array();
		if(!$booking)
		{
			$this->show_error_page();
			return;
		}
		//超級管理員與儀器管理員不再此限
		if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($booking['facility_ID']))
		{
			if($booking['user_ID'] != $this->session->userdata('ID'))
			{
				$this->show_error_page();
				return;
			}
		}
		
		$this->data = $booking;
		
//		$privilege = $this->facility_model->get_user_privilege_list(
//		array("user_ID"=>$booking['user_ID'],
//			  "facility_ID"=>$booking['facility_ID'])
//		)->row_array();
//		$this->data['privilege'] = $privilege['privilege'];
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_booking',$this->data);
		$this->load->view('templates/footer');
	}
	//START----------------------------可預約時間----------------------------
	public function query_time()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->get(NULL,TRUE);
			
			if(empty($input_data['query_date']))
				$input_data['query_date'] = date("Y-m-d");
			else
				$input_data['query_date'] = date("Y-m-d",strtotime($input_data['query_date']."-2 days"));
			
			$output['aaData'] = array();
			
			//預約變更
			if(!empty($input_data['booking_ID']))
			{
				$booking = $this->facility_model->get_facility_booking_list(
				array("serial_no"=>$input_data['booking_ID']))->result_array();
				if(!$booking)
				{
					throw new Exception();
				}
				
				
				//取得儀器資訊
				$facility_IDs = array_unique(sql_result_to_column($booking,"facility_ID"));
				//取得所有關聯的儀器
				$facilities_ID = $this->facility_model->get_vertical_group_facilities($facility_IDs,array("facility_only"=>TRUE));
				$facilities = $this->facility_model->get_facility_list(array("ID"=>$facilities_ID))->result_array();
				//取得所有的可預約時間
				foreach($facilities as $val)//把沒有受限的儀器排除
				{
					if(!$val['enable_occupation'])
					{
						$facilities_ID = array_diff($facilities_ID,array($val['ID']));
					}
				}
				$data = array("start_time"=>date("Y-m-d H:i:s", strtotime($input_data['query_date'])),
									"end_time"=>date("Y-m-d H:i:s", strtotime($input_data['query_date']."+5 days")),
									"facility_ID"=>$facilities_ID);
				$bookings = $this->facility_model->get_facility_booking_list($data)->result_array();
				
				//分析被佔領的時間
				$min_unit_sec = min(sql_result_to_column($facilities,"unit_sec"));
				$occupied_time = array();
				foreach($bookings as $b)
				{
					$start_time = strtotime($b['start_time']);
					$end_time = strtotime($b['end_time']);
					for($i = $start_time;$i < $end_time;$i += $min_unit_sec)
					{
							$occupied_time[$i] = TRUE;
					}
				}
				
				
				
				//標注原先預約的時間
				foreach($booking as $b){
					for($i = strtotime($b['start_time']);$i < strtotime($b['end_time']);$i+=$min_unit_sec){
						$occupied_time[$i] = FALSE;
					}
				}
				
				//儀器暫時停止預約時段亦要標註(非管理員適用)
				foreach($facilities as $facility){
					if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($facility['ID']))
					{
						//取得暫停時間
						$outages = $this->facility_model->get_outage_list(array(
							"facility_SN"=>$facility['ID'],
							"outage_start_time"=>$data['start_time'],
							"outage_end_time"=>$data['end_time']
						))->result_array();
						foreach($outages as $outage)
						{
							$start_time = strtotime($outage['outage_start_time']);
							$end_time = strtotime(isset($outage['outage_end_time'])?$outage['outage_end_time']:$data['end_time']);
							for($i = $start_time;$i < $end_time;$i += $min_unit_sec)
							{
									$occupied_time[$i] = TRUE;
							}
						}
					}
				}
				
				
				
				//輸出
				$max_unit_sec = max(sql_result_to_column($facilities,"unit_sec"));
				for($i=strtotime($input_data['query_date']);$i<strtotime($input_data['query_date']." +1 days");$i+=$max_unit_sec)
	        	{
	         		$row = array(date("H:i",$i)." ~ ".date("H:i",$i+$max_unit_sec));
	         		for($j=$i;$j<strtotime($input_data['query_date']." +5 days");$j+=(24*60*60))
	         		{
						
						if($max_unit_sec==$min_unit_sec){
							if(isset($occupied_time[$j]))
							{
								if($occupied_time[$j])
									$row[] = "X";
								else
									$row[] = form_checkbox("booking_time[]",$j,TRUE);
							}else{
								$row[] = form_checkbox("booking_time[]",$j,FALSE);
							}
						}else{
							
							$occupied = FALSE;
							$occupied_myself = FALSE;
							for($k=$j;$k<$j+$max_unit_sec;$k+=$min_unit_sec){
								if(isset($occupied_time[$k]) && $occupied_time[$k]==TRUE){
									$occupied = TRUE;
								}else if(isset($occupied_time[$k]) && $occupied_time[$k]==FALSE){
									$occupied_myself = TRUE;
								}
							}
							
							if($occupied)
							{
								$row[] = "X";
							}else if($occupied_myself){
								$row[] = form_checkbox("booking_time[]",$j,TRUE);
							}else{
								$row[] = form_checkbox("booking_time[]",$j,FALSE);
							}
						}
					}
					$output['aaData'][] = $row;
				}
			
			//新的預約
			}else if(!empty($input_data['facility_ID']))
			{
				$facility = $this->facility_model->get_facility_list(
				array("ID"=>$input_data['facility_ID'],
					  "type"=>"facility")
				)->result_array();
				if(!$facility)
				{
					throw new Exception();
				}
				
				//取得所有關聯的儀器
				$f_IDs = sql_result_to_column($facility,"ID");
				$facilities_ID = $this->facility_model->get_vertical_group_facilities($f_IDs,array("facility_only"=>TRUE));
				$facilities = $this->facility_model->get_facility_list(array(
					"ID"=>$facilities_ID
				))->result_array();
				//取得所有的可預約時間
				foreach($facilities as $val)//把沒有受限的儀器排除
				{
					if(!$val['enable_occupation'])
					{
						$facilities_ID = array_diff($facilities_ID,array($val['ID']));
					}
				}
				$data = array("start_time"=>date("Y-m-d H:i:s", strtotime($input_data['query_date'])),
							  "end_time"=>date("Y-m-d H:i:s", strtotime($input_data['query_date']." +5 days")),
							  "facility_ID"=>$facilities_ID);
				$bookings = $this->facility_model->get_facility_booking_list($data)->result_array();
				
				//分析被佔領的時間
				$min_unit_sec = min(sql_result_to_column($facilities,"unit_sec"));
				$occupied_time = array();
				foreach($bookings as $booking)
				{
					$start_time = strtotime($booking['start_time']);
					$end_time = strtotime($booking['end_time']);
					for($i = $start_time;$i < $end_time;$i += $min_unit_sec)
					{
						$occupied_time[$i] = TRUE;
					}
				}
				
				//儀器暫時停止預約時段亦要標註(非管理員適用)
				foreach($facilities as $facility){
					if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($facility['ID']))
					{
						//取得暫停時間
						$outages = $this->facility_model->get_outage_list(array(
							"facility_SN"=>$facility['ID'],
							"outage_start_time"=>$data['start_time'],
							"outage_end_time"=>$data['end_time']
						))->result_array();
						foreach($outages as $outage)
						{
							$start_time = strtotime($outage['outage_start_time']);
							$end_time = strtotime(isset($outage['outage_end_time'])?$outage['outage_end_time']:$data['end_time']);
							for($i = $start_time;$i < $end_time;$i += $min_unit_sec)
							{
									$occupied_time[$i] = TRUE;
							}
						}
					}
				}
				
				
				//輸出
				$max_unit_sec = max(sql_result_to_column($facilities,"unit_sec"));
				for($i=strtotime($input_data['query_date']);$i<strtotime($input_data['query_date']." +1 days");$i+=$max_unit_sec)
	        	{
	         		$row = array(date("H:i",$i)." ~ ".date("H:i",$i+$max_unit_sec));
	         		for($j=$i;$j<strtotime($input_data['query_date']." +5 days");$j+=(24*60*60))
	         		{
	         			if($max_unit_sec==$min_unit_sec){
							$row[] = empty($occupied_time[$j])?form_checkbox("booking_time[]",$j,FALSE):"X";
						}else{
							
							$occupied = FALSE;
							for($k=$j;$k<$j+$max_unit_sec;$k+=$min_unit_sec){
								if(isset($occupied_time[$k]) && $occupied_time[$k]==TRUE){
									$occupied = TRUE;
								}
							}
							$row[] = $occupied?"X":form_checkbox("booking_time[]",$j,FALSE);
						}
						
					}
					$output['aaData'][] = $row;
				}
			}
			
			echo json_encode($output);
		}catch(Exception $e){
			echo json_encode($output);
		}
		
	}
	//END----------------------------可預約時間----------------------------
	public function add_booking()
	{
		try{
			$this->is_user_login();
		
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("facility_ID","儀器","required");
			$this->form_validation->set_rules("booking_time","預約時段","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			//確認有該機台
			$facility = $this->facility_model->get_facility_list(array("ID"=>$input_data['facility_ID']))->row_array();
			if(!$facility)
			{
				throw new Exception("無此儀器！",ERROR_CODE);
			}
			
			//(如果是管理員，可指定儀器預約者，不需本人操作)
			if($this->facility_model->is_facility_super_admin() || $this->facility_model->is_facility_admin($facility['ID']))
			{
				$data = array("user_ID"=>empty($input_data['user_ID'])?$this->session->userdata('ID'):$input_data['user_ID']);
			}else{
				$data = array("user_ID"=>$this->session->userdata('ID'));
			}
			$user_profile = $this->user_model->get_user_profile_list($data)->row_array();

			//確認操作者有該機台權限
			
			if($facility['enable_privilege'])
			{
				$privilege = $this->facility_model->get_user_privilege_list(
				array("user_ID"=>$user_profile['ID'],
					  "facility_ID"=>$facility['ID'])
					  )->row_array();
				if(!$privilege)
				{
					throw new Exception("使用者沒有儀器的使用權限！",ERROR_CODE);
				}
			}
			
			//取得相關儀器
			$facilities_IDs = $this->facility_model->get_vertical_group_facilities($facility['ID'],array("facility_only"=>TRUE));
			$facilities = $this->facility_model->get_facility_list(array("ID"=>$facilities_IDs))->result_array();
			$max_unit_sec = max(sql_column_to_key_value_array($facilities,"unit_sec"));
			
			$max_time = max($input_data['booking_time'])+$max_unit_sec;
			$min_time = min($input_data['booking_time']);
			//以下非中心人員要檢查
			if(!$this->is_admin_login(FALSE))
			{
				//確認機台未故障(非管理者適用)
				if($facility['state']!='normal')
				{
					throw new Exception("此儀器目前無法使用。",ERROR_CODE);
					
				}
				//確認有開放預約(非管理者適用)
				if(empty($facility['enable_booking']))
				{
					throw new Exception("此儀器目前不開放預約。",ERROR_CODE);
					
				}
				//確認通過安全講習(非管理者適用)
				if(!$user_profile['security_verified'])
				{
					throw new Exception("您尚未通過安全講習課程！",ERROR_CODE);
					
				}
				//確認未被停權(非管理者適用)
				if($facility['enable_privilege'] && $privilege['suspended'])
				{
					throw new Exception("您的使用權限被暫時停止。",ERROR_CODE);
					
				}
				//確認有達低消(非管理者適用)
				if($max_time-$min_time<$facility['min_sec'])
				{
					throw new Exception("此儀器最低預約時間為".gmdate("H小時i分s秒",$facility['min_sec']),"","warning");
					
				}
				//確認權限未到期(非管理者適用)
				if($facility['enable_privilege'] && !empty($privilege['expiration_date']))
				{
					if($min_time > strtotime($privilege['expiration_date']))
					{
						throw new Exception("您預約的時段該儀器使用權限已過期，請重新認證。",ERROR_CODE);
						
					}
				}
				//確認時段未暫停預約(非管理者適用)
				$outages = $this->facility_model->get_outage_list(array(
					"facility_SN"=>$facility['ID'],
					"outage_start_time"=>date("Y-m-d H:i:s",$min_time),
					"outage_end_time"=>date("Y-m-d H:i:s",$max_time)
				))->result_array();
				foreach($outages as $outage){
					if($max_time > strtotime($outage['outage_start_time']) && ($min_time < strtotime($outage['outage_end_time']) || $outage['outage_end_time'] == NULL))
					{
						throw new Exception("此儀器於{$outage['outage_start_time']} ~ {$outage['outage_end_time']}時段暫時停止預約",ERROR_CODE);
					}
				}
				
				//確認結束時間小於五天後(非管理者適用)
				if( $max_time > strtotime("+5 days 00:00:00"))
				{
					throw new Exception("預約時間不可大於5天後。",ERROR_CODE);
					
				}
			}
			
			
			//確認輸入了正確的時間
			$this->booking_model->check_input_time($input_data['booking_time'],$max_unit_sec);
			
			//新增一筆記錄(若為自行操作，要判斷是否跨日，要拆單)
			if(empty($input_data['purpose']) || $input_data['purpose']=="DIY")
			{
				foreach(split_utime_by_day($min_time,$max_time) as $time){
					$this->booking_model->add($facility['ID'],$user_profile['ID'],$time[0],$time[1],"DIY");
				}
			}else{
				$this->booking_model->add($facility['ID'],$user_profile['ID'],$min_time,$max_time,$input_data['purpose']);
			}
			
			
			//更新權限到期日與總使用時數
			$this->user_privilege_model->update($user_profile['ID'],$facility['ID']);
			
			//寄信(給使用者與老師)
			if(empty($user_profile['email']))
			{
				throw new Exception("使用者的email為空，請完善個人資料。",WARNING_CODE);
			}
			$this->email->to($user_profile['email']);
			if(!empty($user_profile['boss_email']))
				$this->email->cc($user_profile['boss_email']);
			$this->email->subject("成大微奈米科技研究中心 -儀器預約通知‏-");
			$this->email->message("{$user_profile['name']} 您好：<br>
								   感謝您預約成功大學微奈米科技研究中心的儀器，您的預約結果如下：<br>
								   使用儀器：{$facility['cht_name']} ({$facility['eng_name']})<br>
								   預約時段：".date("Y-m-d H:i:s",$min_time)." ~ ".date("Y-m-d H:i:s",$max_time)."<br>
								   <br>");
			$this->email->send();
			
			echo $this->info_modal("預約成功","/facility/".$this->session->userdata('status')."/booking/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
		
	}
	public function update_booking($SN)
	{
		try{
			$this->is_user_login();
		
			$SN = $this->security->xss_clean($SN);
			
			$this->form_validation->set_rules("booking_time","預約時段","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$booking = $this->facility_model->get_facility_booking_list(
							array("serial_no"=>$SN)
						)->row_array();
			//確認有此預約單
			if(!$booking)
			{
				throw new Exception("無此預約紀錄！",ERROR_CODE);
			}
			
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$booking['user_ID']))->row_array();
			$privilege = $this->facility_model->get_user_privilege_list(
			array("user_ID"=>$booking['user_ID'],
				  "facility_ID"=>$booking['facility_ID'])
			)->row_array();
			//確認使用權限
			if(!$privilege)
			{
				echo $this->info_modal("使用者無使用權限。","","warning");
				return;
			}
			
			//確認為本人(超級使用者與儀器管理員不在此限)
			if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($booking['facility_ID']))
			{
				if($booking['user_ID'] != $this->session->userdata('ID'))
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
			}
			
			$facility = $this->facility_model->get_facility_list(array("ID"=>$booking['facility_ID']))->row_array();
			$ori_min_time = strtotime($booking['start_time']);
			$ori_max_time = strtotime($booking['end_time']);
			$max_time = max($input_data['booking_time'])+$facility['unit_sec'];
			$min_time = min($input_data['booking_time']);
			//修改：１小時前可修改，只能延長時間，24小時內不得換時段。一天前可換當日時段。
			if($ori_min_time-time()>24*60*60)//大於24小時
			{
				$min_available_time = strtotime(date("Y-m-d",$ori_min_time));
				$max_available_time = strtotime(date("Y-m-d",$ori_max_time+24*60*60-1));//加1天，故意少1秒避免邊界判斷錯誤
				if($max_time > $max_available_time || $min_time < $min_available_time)
				{
					throw new Exception("只能更換當日時段！",WARNING_CODE);
				}
			}
			else if($ori_min_time-time()>60*60)//大於1小時
			{
				if($min_time > $ori_min_time || $max_time < $ori_max_time)
				{
					throw new Exception("距離原預約時間小於24小時，故原時段不允許更換，只能延長預約時數。",WARNING_CODE);
				}
			}else
			{
				throw new Exception("已超過原預約時段前1小時，無法變更，若需延長時數請直接預約。",ERROR_CODE);
			}

			
			
			//以下非中心人員要確認
			if(!$this->is_admin_login(FALSE))
			{
				//確認有達低消
				if($max_time+$facility['unit_sec']-$min_time<$facility['min_sec'])
				{
					throw new Exception("此儀器最低預約時間為".gmdate("H小時i分s秒",$facility['min_sec']),WARNING_CODE);
				}
				//確認權限未到期
				if(!empty($privilege['expiration_date']))
				{
					if($min_time > strtotime($privilege['expiration_date']))
					{
						throw new Exception("您預約的時段該儀器使用權限已過期，請重新認證。",ERROR_CODE);
					}
				}
				//確認時段未暫停預約
				$outages = $this->facility_model->get_outage_list(array(
					"facility_SN"=>$facility['ID'],
					"outage_start_time"=>date("Y-m-d H:i:s",$min_time),
					"outage_end_time"=>date("Y-m-d H:i:s",$max_time)
				))->result_array();
				foreach($outages as $outage){
					if($max_time > strtotime($outage['outage_start_time']) && ($min_time < strtotime($outage['outage_end_time']) || $outage['outage_end_time'] == NULL))
					{
						throw new Exception("此儀器於{$outage['outage_start_time']} ~ {$outage['outage_end_time']}時段暫時停止預約",ERROR_CODE);
					}
				}
			}
			
			
			$this->booking_model->check_input_time($input_data['booking_time'],$facility['unit_sec']);
			
			
			//寄信(給使用者與老師)
			if(empty($user_profile['email']))
			{
				throw new Exception("您的email為空，請先完善您的個人資料。",WARNING_CODE);
			}
			$this->email->to($user_profile['email']);
			if(!empty($user_profile['boss_email']))
				$this->email->cc($user_profile['boss_email']);
			$this->email->subject("成大微奈米科技研究中心 -儀器預約變更通知‏-");
			$this->email->message("{$user_profile['name']} 您好：<br>
								   感謝您預約成功大學微奈米科技研究中心的儀器，您的預約變更結果如下：<br>
								   使用儀器：{$facility['cht_name']} ({$facility['eng_name']})<br>
								   原預約時段：".$booking['start_time']." ~ ".$booking['end_time']."<br>
								   變更後時段：".date("Y-m-d H:i:s",$min_time)." ~ ".date("Y-m-d H:i:s",$max_time)."<br>
								   <br>");
			$this->email->send();
			
			//更新!!
			$this->booking_model->update_time($booking['serial_no'],$min_time,$max_time);
			
			//更新權限相關資料
			$this->user_privilege_model->update($user_profile['ID'],$facility['ID']);
			
			
			
			echo $this->info_modal("預約變更成功","/facility/".$this->session->userdata('status')."/booking/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	
	public function del_booking($SN)
	{
		try{
			$this->is_user_login();
		
			$SN = $this->security->xss_clean($SN);
			
			$booking = $this->facility_model->get_facility_booking_list(array("serial_no"=>$SN))->row_array();
			//確認預約記錄存在
			if(!$booking)
			{
				throw new Exception("無此預約紀錄！",ERROR_CODE);
			}
			//以下非超級管理者或儀器管理者適用
			if(!$this->facility_model->is_facility_super_admin() && !$this->facility_model->is_facility_admin($booking['facility_ID']))
			{
				//只能刪除自己的預約紀錄
				if($booking['user_ID'] != $this->session->userdata('ID'))
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
				//確認該月取消次數小於等於三次
				$cancelled_bookings = $this->facility_model->get_facility_booking_list(
				array("user_ID"=>$booking['user_ID'],
					  "start_cancel_time"=>date("Y-m-d H:i:s",strtotime("-1 month")),
					  "purpose"=>"DIY")
				)->result_array();
				if(count($cancelled_bookings) >= 3)
				{
					throw new Exception("您最近一個月已取消達三次，不可再取消。",ERROR_CODE);
				}
				//確認現在時間不在預約時間之後
				if(time() > strtotime($booking['start_time']." -1 Hour"))
				{
					throw new Exception("已超過預約時間前一小時，無法取消。",ERROR_CODE);
				}
			}
			
			//刪除預約紀錄
			$this->booking_model->del($SN);
			
			echo $this->info_modal("取消成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//END-------------------------使用者預約動作--------------------------------
	
	//START----------------------------儀器調教維修單------------------------------
	public function list_maintenance()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_maintenance',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_maintenance()
	{
		$this->is_admin_login();
		
		$data = array();
		
		//非超級管理者
		if(!$this->facility_model->is_facility_super_admin())
		{
			$privileges = $this->facility_model->get_user_privilege_list(array("user_ID"=>$this->session->userdata('ID'),"privilege"=>"admin"))->result_array();
			if($privileges)
			{
				$privileges = $this->rotate_2D_array($privileges);
				$data['facility_ID'] = $privileges['facility_ID'];
			}else{
				$data['facility_ID'] = array();
			}
		}
		$maintenances = $this->facility_model->get_maintenance_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($maintenances as $m)
		{
			$row = array();
			$row[] = $m['serial_no'];
			$row[] = $m['subject'];
			$row[] = $m['applicant_name'];
			$row[] = $m['facility_cht_name']." ({$m['facility_eng_name']})";
			$row[] = $m['apply_time'];
			if(strtotime($m['booking_start_time']) > time())
				$row[] = anchor('/facility/admin/maintenance/edit/'.$m['serial_no'],"編輯","class='btn btn-warning'");
			else
				$row[] = anchor('/facility/admin/maintenance/view/'.$m['serial_no'],"瀏覽","class='btn btn-primary'");
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	public function form_maintenance($f_ID)
	{
		$this->is_admin_login();
		
		$f_ID = $this->security->xss_clean($f_ID);
		
		$facility = $this->facility_model->get_facility_list(
		array("ID"=>$f_ID)
		)->row_array();
		$this->data = $facility;
		$this->data['facility_cht_name'] = $facility['cht_name'];
		$this->data['facility_eng_name'] = $facility['eng_name'];
		$this->data['action_url'] = site_url()."/facility/admin/maintenance/add";
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_maintenance',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_maintenance($SN)
	{
		$this->is_admin_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$maintenance = $this->facility_model->get_maintenance_list(
						array("serial_no"=>$SN)
						)->row_array();
		if(!$maintenance)
		{
			$this->show_error_page();
			return;
		}
		$this->data = $maintenance;
		$this->data['action_url'] = site_url()."/facility/admin/maintenance/update";
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_maintenance',$this->data);
		$this->load->view('templates/footer');
	}
	public function add_maintenance()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("facility_ID","儀器","required");
			$this->form_validation->set_rules("subject","事由","required");
			$this->form_validation->set_rules("booking_time","預約時段","required");
			if(!empty($input_data['subject']) && $input_data['subject'] == "外部維修")
			{
				$this->form_validation->set_rules("content","描述","required");
			}
			if(!$this->form_validation->run())
			{
				echo $this->info_modal(validation_errors(),"","warning");
				return;
			}
			//取得使用者基本資料
			$user_profile = $this->user_model->get_user_profile_list(
							array("user_ID"=>$this->session->userdata('ID'))
							)->row_array();
			//先確認有權限
			$privilege =	$this->facility_model->get_user_privilege_list(
							array("facility_ID"=>$input_data['facility_ID'],
								  "user_ID"=>$user_profile['ID'],
								  "privilege"=>"admin")
							)->row_array();
			if(!$privilege)
			{
				echo $this->info_modal("權限不足！","","error");
				return;
			}
			//取得儀器基本資料
			$facility = $this->facility_model->get_facility_list(
						array("ID"=>$input_data['facility_ID'])
						)->row_array();
			
			//取得共同實驗室組組長資料
			$this->load->model('admin_model');
			$managers = $this->admin_model->get_org_chart_list(array(
				"team_ID"=>"common_lab",
				"status_ID"=>"section_chief"
			))->result_array();
				
			//確認選擇了連續時段
			$this->booking_model->check_input_time($input_data['booking_time'],$facility['unit_sec']);
			
			//寫入預約紀錄
			$min_time = min($input_data['booking_time']);
			$max_time = max($input_data['booking_time'])+$facility['unit_sec'];
			$booking_ID = $this->booking_model->add($facility['ID'],$this->session->userdata('ID'),$min_time,$max_time,"maintenance");
			
			//寫入維修單並取得維修調教單號
			$data = array("applicant_ID"=>$this->session->userdata('ID'),
					  "facility_ID"=>$input_data['facility_ID'],
					  "subject"=>$input_data['subject'],
					  "content"=>$input_data['content'],
					  "booking_ID"=>$booking_ID,
					  "result"=>"1");
			$serial_no = $this->facility_model->add_maintenance($data);
			
			//寄信通知組長
			foreach($managers as $manager){
				$this->email->to($manager['admin_email']);
				$this->email->subject("成大微奈米科技研究中心 -儀器維修調教通知-");
				$this->email->message("{$manager['team_name']} {$manager['status_name']} {$manager['admin_name']} 您好：<br>
										中心儀器：{$facility['cht_name']}<br>
										被該儀器管理員 {$user_profile['name']} 申請 {$input_data['subject']}<br>
										申請原因： {$input_data['content']}<br>
										使用時段為：".date("Y-m-d H:i",$min_time)."~".date("Y-m-d H:i",$max_time)."<br>
										系統特此通知，謝謝");
				$this->email->send();
			}
			
			echo $this->info_modal("新增成功","/facility/admin/booking/list/");	
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function update_maintenance()
	{
		$this->is_admin_login();
		
		try{
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("serial_no","維修單編號","required");
			if(!$this->form_validation->run())
			{
				echo $this->info_modal(validation_errors(),"","warning");
				return;
			}
			
			$maintenance = $this->facility_model->get_maintenance_list(array("serial_no"=>$input_data['serial_no']))->row_array();
			$this->load->model('admin_model');
			if(	!isset($maintenance['result']) && 
				in_array($this->session->userdata('ID'),sql_result_to_column($this->admin_model->get_org_chart_list(array(
					"team_ID"=>"common_lab",
					"status_ID"=>"section_chief"
				))->result_array(),"admin_ID"))
			)
			{
				//審核者
				$this->form_validation->set_rules("result","審核結果","required");
				if(!$this->form_validation->run())
					throw new Exception(validation_errors(),WARNING_CODE);
				
				$data = array("serial_no"=>$input_data['serial_no'],
							  "result"=>$input_data['result'],
							  "manager_ID"=>$this->session->userdata('ID'));
				$this->facility_model->update_maintenance($data);
				
				echo $this->info_modal("審核成功","/facility/admin/maintenance/list");
			}else{
				
				//該單申請者
				if($maintenance['applicant_ID'] != $this->session->userdata('ID'))
					throw new Exception("權限不足",ERROR_CODE);
				
				if(!empty($maintenance['booking_ID']) || !$maintenance['result'])
					throw new Exception("無法變更",ERROR_CODE);
					
				$this->form_validation->set_rules("booking_time","時段","required");
				if(!$this->form_validation->run())
					throw new Exception(validation_errors(),WARNING_CODE);
				
				$facility = $this->facility_model->get_facility_list(array("ID"=>$maintenance['facility_ID']))->row_array();
				
				$max_time = max($input_data['booking_time'])+$facility['unit_sec'];
				$min_time = min($input_data['booking_time']);
				//確認選擇了連續時段
				for($i=$min_time;$i<$max_time;$i+=$facility['unit_sec'])
					if(!in_array($i,$input_data['booking_time']))
						throw new Exception("請選擇連續時段！",WARNING_CODE);
				
				//新增預約單
				$bookingID = $this->booking_model->add($maintenance['facility_ID'],$this->session->userdata('ID'),$min_time,$max_time,"maintenance");
	
				//更新維修單
				$data = array("serial_no"=>$input_data['serial_no'],
							  "booking_ID"=>$bookingID);
				$this->facility_model->update_maintenance($data);
				
				echo $this->info_modal("預約成功","/facility/admin/maintenance/list");
			}
		
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
			return;
		}
	}
	public function del_maintenance()
	{
		$this->is_admin_login();
	}
	//END----------------------------儀器調教維修------------------------------
	
	//--------------------------儀器停機------------------------------
//	public function list_outage()
//	{
//		
//	}
	public function query_outage()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->get(NULL,TRUE);
			$outages = $this->facility_model->get_outage_list($input_data)->result_array();
			
			$output['aaData'] = $outages;
//			foreach($outages as $outage)
//			{
//				$row = array();
//				$row[] = $outage['outage_start_time'];
//				$row[] = $outage['outage_end_time'];
//				$row[] = $outage['outage_remark'];
//				$display = array();
//				$display[] = form_button("edit","編輯","class='btn btn-small btn-warning' value='{$outage['outage_SN']}' ng-click='get_facility_outage()'");
//				$display[] = form_button("del","刪除","class='btn btn-small btn-danger' value='{$outage['outage_SN']}'");
//				$row[] = implode(' ',$display);
//				$output['aaData'][] = $row;
//			}
			
			echo json_encode($output);
		}catch(Exception $e){
			
		}
	}
	public function update_outage()
	{
		try{
			$this->is_admin_login();
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("facility_SN","儀器編號","required");
			$this->form_validation->set_rules("outage_start_date","停機起始日期","required");
			$this->form_validation->set_rules("outage_start_time","停機起始時間","required");
			$this->form_validation->set_rules("outage_remark","停機原因","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			$this->load->model('facility/outage_model');
			if(empty($input_data['outage_SN']))
			{
				$this->outage_model->add_outage(
					$input_data['facility_SN'],
					$input_data['outage_remark'],
					"{$input_data['outage_start_date']} {$input_data['outage_start_time']}",
					!empty($input_data['outage_end_date'])&&!empty($input_data['outage_end_time'])?"{$input_data['outage_end_date']} {$input_data['outage_end_time']}":NULL);
					
				echo $this->info_modal("新增成功");
			}else{
				$this->outage_model->update_outage(
					$input_data['outage_SN'],
					$input_data['outage_remark'],
					"{$input_data['outage_start_date']} {$input_data['outage_start_time']}",
					!empty($input_data['outage_end_date'])&&!empty($input_data['outage_end_time'])?"{$input_data['outage_end_date']} {$input_data['outage_end_time']}":NULL
				);

				echo $this->info_modal("更新成功");
			}
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	public function del_outage($SN = "")
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$this->load->model('facility/outage_model');
			$this->outage_model->del_outage($SN);
			
			echo $this->info_modal("刪除成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
	}
	
  	//---------------------------------------門禁卡機控制------------------------------------------
  	public function list_access_card()
	{
		$this->is_admin_login();
		
		$facility_ctrl_no_select_options = array(""=>"全部儀器");
		foreach($this->facility_model->get_facility_list(array("type"=>"facility"))->result_array() as $row)
		{
			$facility_ctrl_no_select_options[$row['ctrl_no']] = $row['cht_name'];
		}
		$this->data['facility_ctrl_no_select'] = form_dropdown("facility_ctrl_no",$facility_ctrl_no_select_options,"","id='facility_ctrl_no' class='input-xxlarge chosen-with-diselect'");
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_access_card',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_access_card()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		if(empty($input_data['start_time'])) $input_data['start_time']="00:00:00";
		if(empty($input_data['end_time'])) $input_data['end_time']="00:00:00";
		
		$result = $this->facility_model->get_access_card_list_json($input_data);
		
		echo $result;
	}
	public function list_access_ctrl()
	{
		$this->is_admin_login();
		
		$facility_ID_select_options = array(""=>"全部儀器");
		foreach($this->facility_model->get_facility_list()->result_array() as $row)
		{
			if(!empty($row['ctrl_no']))
				$facility_ctrl_no_select_options[$row['ctrl_no']] = "[{$row['ctrl_no']}] {$row['cht_name']} ({$row['eng_name']})";
		}
		$this->data['facility_ctrl_no_select_options'] = $facility_ctrl_no_select_options;
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_access_ctrl',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_access_ctrl()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->get(NULL,TRUE);
		
		$data = array();
		if(!empty($input_data['start_date']))
		{
			$data['start_time'] = $input_data['start_date']." ".$input_data['start_time'];
		}
		if(!empty($input_data['end_date']))
		{
			$data['end_time'] = $input_data['end_date']." ".$input_data['end_time'];
		}
		if(!empty($input_data['facility_ctrl_no']))
		{
			$data['facility_ctrl_no'] = $input_data['facility_ctrl_no'];
		}
		
		$result = $this->facility_model->get_access_ctrl_list($data)->result_array();
		$output['aaData'] = array();
		foreach ( $result as $aRow )
		{
			$row = array();
			
			$row[] = $aRow['access_time'];
			$row[] = $aRow['action']=="Add"?"開啟":($aRow['action']=="Del"?"關閉":"");
			$row[] = $aRow['card_num'];
			//利用卡號查詢使用者
			$user_profile = $this->user_model->get_user_profile_by_card_num($aRow['card_num']);
			$row[] = empty($user_profile)?"---":$user_profile['name'];
			//利用卡機控制編號查詢機台名稱
			$facility_profile = $this->facility_model->get_facility_by_ctrl_no($aRow['ctrl_no']);
			$row[] = empty($facility_profile)?"---":"[{$facility_profile['ctrl_no']}] {$facility_profile['cht_name']}";
			$row[] = $aRow['flag']?form_label("已執行","",array('class'=>'label label-mini')):form_button("access_ctrl_del","刪除","class='btn btn-danger btn-mini' value='{$aRow['serial_no']}'");
			$row[] = $aRow['status']=="T"?form_button("","成功","class='btn btn-success btn-mini'"):($aRow['status']=="F"?form_button("access_ctrl_update","失敗","class='btn btn-danger btn-mini' value='{$aRow['serial_no']}'"):"---");
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	public function edit_access_ctrl($SN = "")
	{
		$this->is_admin_login();
		
		if(empty($SN))
		{
			$this->data['action'] = site_url()."/facility/admin/access/ctrl/add";
			foreach($this->facility_model->get_facility_list()->result_array() as $row)
			{
				if(!empty($row['ctrl_no']))
					$facility_ID_select_options[$row['ID']] = "[{$row['ctrl_no']}] {$row['cht_name']} ({$row['eng_name']})";
			}
			$this->data['facility_ID_select_options'] = $facility_ID_select_options;
			
			$this->data['user_ID_select_options'] = $this->access_ctrl_model->get_user_ID_select_options();
		}else{
			$this->data['action'] = site_url()."/facility/admin/access/ctrl/update/".$SN;
			//還沒完成
		}
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_access_ctrl',$this->data);
		$this->load->view('templates/footer');
	}
	public function update_access_ctrl($SN = "")
	{
		$this->is_admin_login();
		
		if(empty($SN))
		{
			$input_data = $this->input->post(NULL,TRUE);
		
			$this->form_validation->set_rules('user_ID', '使用者帳號', 'required');
//			$this->form_validation->set_rules('card_num', '卡號', 'required|min_length[8]|max_length[8]');
			if(empty($input_data['all_door']) && empty($input_data['all_facility']))
				$this->form_validation->set_rules('facility_ID', '儀器', 'required');
			if(!$this->form_validation->run()){
				echo $this->info_modal(validation_errors(),"","warning");
				return;
			}
			
			
			$facilities_ID = array();
			if(!empty($input_data['all_door']))
			{
				$facilities = $this->facility_model->get_facility_list(array("type"=>"door"))->result_array();
				$facilities = $this->rotate_2D_array($facilities);
				$facilities_ID = array_merge($facilities['ID'],$facilities_ID);
			}
			if(!empty($input_data['all_facility']))
			{
				$facilities = $this->facility_model->get_facility_list(array("type"=>"facility"))->result_array();
				$facilities = $this->rotate_2D_array($facilities);
				$facilities_ID = array_merge($facilities['ID'],$facilities_ID);
			}
			if(!empty($input_data['facility_ID']))
				$facilities_ID = array_merge($input_data['facility_ID'],$facilities_ID);
			if(empty($input_data['start_date']))
				$start_time = 0;
			else if(empty($input_data['start_time']))
				$start_time = strtotime($input_data['start_date']);
			else
				$start_time = strtotime($input_data['start_date']." ".$input_data['start_time']);
			if(empty($input_data['end_date']))
				$end_time = 0;
			else if(empty($input_data['end_time']))
				$end_time = strtotime($input_data['end_date']);
			else
				$end_time = strtotime($input_data['end_date']." ".$input_data['end_time']);
			$this->access_ctrl_model->add($facilities_ID,$input_data['user_ID'],$start_time,$end_time);
			
			echo $this->info_modal("新增成功","/facility/admin/access/ctrl/list");
		}else{
			//更新(失敗重發)
			$SN = $this->security->xss_clean($SN);
			
			$this->access_ctrl_model->reset_flag($SN);
			
			echo "OK";
		}
	}
	public function del_access_ctrl($SN)
	{
		$this->is_admin_login();
		
		$SN = $this->security->xss_clean($SN);
		
		$this->facility_model->del_access_ctrl(array("serial_no"=>$SN));
		
		echo "OK";
	}
	//-----------------------門禁卡片管理-----------------------
	public function list_card_application()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_card_application',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_card_application()
	{
		$this->is_admin_login();
		
		$result = $this->facility_model->get_card_application_list();
		
		$output['aaData'] = array();
		foreach($result->result_array() as $aRow)
		{
			$row = array();
			
			$row[] = $aRow['serial_no'];
			$row[] = $this->card_application_type[$aRow['type']];
			$row[] = $aRow['comment'];
			$row[] = $aRow['application_date'];
			$row[] = $aRow['user_ID'];
			$row[] = $aRow['user_name'];
			$row[] = $aRow['user_email'];
			$row[] = $aRow['user_mobile'];
			$row[] = $aRow['card_num'];
			$row[] = $aRow['issuance_date'];
			$row[] = $aRow['admin_name'];
			//
			$display = array();
			if(empty($aRow['AB_form_verified_by']) && $aRow['type']=='apply')
			{
				$display[] = form_button("verify","AB表確認","class='btn btn-primary btn-small' value='{$aRow['serial_no']}'");
			}
			if($aRow['checkpoint'] == "Officer")
			{
				if($aRow['type'] == "apply" || $aRow['type'] == "reissue")
					$display[] = form_button("notify","通知領卡","class='btn btn-primary btn-small' value='{$aRow['serial_no']}'");
				else if($aRow['type'] == "refund")
					$display[] = form_button("issue","退卡確認","class='btn btn-warning btn-small' value='{$aRow['serial_no']}'");
				$display[] = form_button("cancel","強制取消","class='btn btn-small btn-danger' value='{$aRow['serial_no']}'");
			}
			else if($aRow['checkpoint'] == "Notified")
			{
				$display[] = form_button("issue","押金確認","class='btn btn-warning btn-small' value='{$aRow['serial_no']}'");
				$display[] = form_button("cancel","強制取消","class='btn btn-small btn-danger' value='{$aRow['serial_no']}'");
			}
			else if($aRow['checkpoint'] == "Completed")
			{
				$display[] = form_label("已結案","",array("class"=>'label label-success'));
			}
			$row[] = implode(' ',$display);
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	public function edit_card_application()
	{
		try{
			$this->is_user_login();
		
			$user_profile = $this->user_model->get_user_profile_by_ID($this->session->userdata('ID'));
			if(!$user_profile)
			{
				throw new Exception();
			}
			$this->data = $user_profile;
			
			//偵測可退還的卡號
			$this->load->model('facility/card_application_model');
			$this->data['refundable_card_nums'] = $this->card_application_model->get_refundable_card_num($user_profile['ID']);
			
			$this->load->view('templates/header');
			$this->load->view('templates/sidebar');
			$this->load->view('facility/edit_card_application',$this->data);
			$this->load->view('templates/footer');
		}catch(Exception $e){
			$this->show_error_page();
		}
		
	}
	public function add_card_application()
	{
		try{
			$this->is_user_login();
		
			//for user only
			$user_profile = $this->user_model->get_user_profile_by_ID($this->session->userdata('ID'));
			
			$input_data = $this->input->post(NULL,TRUE);
			
			//先確認目前有否卡片
			if($input_data['type'] == "apply"){
				if(!empty($user_profile['card_num']))
				{
					echo $this->info_modal("您已有磁卡，請勿重複申請。","","error");
					return;
				}
			}else if($input_data['type'] == "refund"){
				//確認有填寫卡號與退卡原因
				$this->form_validation->set_rules("card_num","磁卡卡號","required");
				$this->form_validation->set_rules("comment","退卡原因","required");
				if(!$this->form_validation->run())
				{
					echo $this->info_modal(validation_errors(),"","warning");
					return;
				}
				//確認此卡可退
				$this->load->model('facility/card_application_model');
				$refundables = $this->card_application_model->get_refundable_card_num($this->session->userdata('ID'));
				if(!in_array($input_data['card_num'],$refundables))
				{
					throw new Exception("此卡不可退",ERROR_CODE);
				}
			}else if($input_data['type'] == "reissue"){
				//檢查原先已有卡片才可申請
				if(empty($user_profile['card_num']))
				{
					throw new Exception("您未有磁卡，不可申請補發",ERROR_CODE);
				}
			}else{
				echo $this->info_modal("未知錯誤","","error");
				return;
			}
			//再確認有無重複申請
			$card_app_result = $this->facility_model->get_card_application_list(
				array("user_ID"=>$this->session->userdata('ID'))
			)->row_array();
			if($card_app_result)
			{
				if($card_app_result['checkpoint'] != "Completed" && $card_app_result['checkpoint'] != "Canceled")
				{
					echo $this->info_modal("您的申請已經在處理中，請勿重複申請。","","error");
					return;
				}
			}
			
			//傳資料
			$input_data['user_ID'] = $user_profile['ID'];
			$result = $this->facility_model->add_card_application($input_data);
			
			if($result)
			{
				if($input_data['type'] == "apply" || $input_data['type'] == "reissue")
					echo $this->info_modal("申請已送出，五個工作天後本中心會通知前來領卡，請留意您的E-mail，謝謝。");
				else if($input_data['type'] == "refund")
					echo $this->info_modal("申請已送出，請直接前往中心領回押金，謝謝。");
				
			}	
			else
				echo $this->info_modal("內部錯誤","","error");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function update_card_application($SN = "")
	{
		try{
			//for admin only
			$this->is_admin_login();
			
			$input_data = $this->input->post(NULL,TRUE);
			
			$card_app = $this->facility_model->get_card_application_list(array("serial_no"=>$SN))->row_array();
			
			if(!$card_app)
			{
				echo $this->info_modal("無此申請單","","error");
				return;
			}

			if($card_app['checkpoint'] == "Officer")
			{
				if($card_app['type'] == "apply" || $card_app['type'] == "reissue")
				{
					//檢查輸入有無錯誤
					$this->form_validation->set_rules('card_num', '卡號', 'required|min_length[8]|max_length[8]');
					if(!$this->form_validation->run())
					{
						echo $this->info_modal(validation_errors(),"","warning");
						return;
					}
					//把卡號綁定帳號
					$this->user_model->update_user_card_num($card_app['user_ID'],$input_data['card_num']);
					//更新關卡
					$input_data['checkpoint'] = "Notified";
					//寄MAIL通知領卡
					$this->email->to($card_app['user_email']);
					$this->email->subject("微奈米科技研究中心－通知領取磁卡");
					$this->email->message("您好，您的磁卡已經申請成功，請攜帶大頭照、500元押金至微奈米中心領卡，第一次申請者必須連同攜帶AB量表於領卡時繳交，謝謝您。"); 
					$this->email->send();
				}else if($card_app['type'] == "refund")
				{
					//取得退卡的卡號，若與現在卡號相同，則解除綁定
					$user_profile = $this->user_model->get_user_profile_by_ID($card_app['user_ID']);
					if($card_app['card_num'] == $user_profile['card_num'])
					{
						//把卡號從帳號綁定移除
						$this->user_model->update_user_card_num($card_app['user_ID']);
					}
					$input_data['officer_ID'] = $this->session->userdata('ID');
					$input_data['checkpoint'] = "Completed";
				}
			}else if($card_app['checkpoint'] == "Notified")
			{
				$input_data['officer_ID'] = $this->session->userdata('ID');
				$input_data['checkpoint'] = "Completed";
				
				if($card_app['type'] == "reissue")
				{
					//把所有的預約改成現在的卡號
					$this->load->model('facility/access_ctrl_model');
					$this->access_ctrl_model->exchange($card_app['user_ID'],$card_app['card_num']);
				}
			}else if($card_app['checkpoint'] == "Completed")
			{
				echo $this->info_modal("此案已結","","error");
				return;
			}
			
			$input_data['serial_no'] = $SN;
			$result = $this->facility_model->update_card_application($input_data);
			
			if($result)
				echo $this->info_modal("更新成功","/facility/admin/card/list");
			else
				echo $this->info_modal("內部錯誤","","error");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function del_card_application($SN = "")
	{
		try{
			$this->is_admin_login();
			
			$SN = $this->security->xss_clean($SN);
			
			$this->load->model('facility/card_application_model');
			$this->card_application_model->cancel($SN);
			
			echo $this->info_modal("退件成功");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	//-------------------------預約不計費----------------------------
	public function form_nocharge($ID = NULL)
	{
		$this->is_user_login();
		
		$ID = $this->security->xss_clean($ID);
		//先確認此預約ID的申請者是否為本人
		$booking = $this->facility_model->get_facility_booking_list(array("serial_no"=>$ID,"user_ID"=>$this->session->userdata('ID')))->row_array();
		if(!$booking)
		{
			$this->show_error_page();
			return;
		}
		$this->data = $booking;
		$this->data['booking_ID'] = $booking['serial_no'];
		$this->data['action_url'] = site_url()."/facility/user/nocharge/add";
		
		$this->data['page'] = "form";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_nocharge',$this->data);
		$this->load->view('templates/footer');
	}
	public function edit_nocharge($ID = NULL)
	{
		$this->is_admin_login();
		
		$ID = $this->security->xss_clean($ID);
		
		$booking = $this->facility_model->get_booking_nocharge_list(array("booking_ID"=>$ID))->row_array();
		if(!$booking)
		{
			$this->show_error_page();
			return;
		}
		$this->data = $booking;
		$this->data['action_url'] = site_url()."/facility/admin/nocharge/update";
		
		$this->data['page'] = "edit";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_nocharge',$this->data);
		$this->load->view('templates/footer');
	}
	public function view_nocharge($ID)
	{
		$this->is_user_login();
		
		$ID = $this->security->xss_clean($ID);
		$booking = $this->facility_model->get_booking_nocharge_list(array("booking_ID"=>$ID))->row_array();
		if(!$booking)
		{
			$this->show_error_page();
			return;
		}
		if(!$this->is_admin_login(FALSE))
		{
			//不為中心人員登入時，只能看自己的
			if($booking['user_ID'] != $this->session->userdata('ID') )
			{
				$this->show_error_page();
				return;
			}
		}
		$this->data = $booking;
		
		$this->data['page'] = "view";
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_nocharge',$this->data);
		$this->load->view('templates/footer');
	}
	public function list_nocharge()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_nocharge',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_nocharge()
	{
		$this->is_admin_login();
		
		//若不為最高管理員，限制只能看到自己管理的儀器的不計費申請單
		$data = array();
		if(!$this->facility_model->is_facility_super_admin())
		{
			$privileges = $this->facility_model->get_user_privilege_list(array("user_ID"=>$this->session->userdata('ID'),"privilege"=>"admin"))->result_array();
			if($privileges)
			{
				$privileges = $this->rotate_2D_array($privileges);
				$data['facility_ID'] = array_unique($privileges['facility_ID']);
			}else{
				$data['facility_ID'] = array("");
			}
			
		}
		
		$nocharges = $this->facility_model->get_booking_nocharge_list($data)->result_array();
		
		$output['aaData'] = array();
		foreach($nocharges as $nocharge)
		{
			$row = array();
			
			$row[] = $nocharge['booking_ID'];
			$row[] = $nocharge['apply_date'];
			$row[] = $nocharge['user_ID'];
			$row[] = $nocharge['user_name'];
			$row[] = "{$nocharge['facility_cht_name']} ({$nocharge['facility_eng_name']})";
			$row[] = "{$nocharge['start_time']} ~ {$nocharge['end_time']}";
			if(isset($nocharge['result']))
				if($nocharge['result'])
					$row[] = anchor("/facility/admin/nocharge/view/{$nocharge['booking_ID']}","通過","class='label label-success'");
				else
					$row[] = anchor("/facility/admin/nocharge/view/{$nocharge['booking_ID']}","退件","class='label label-inverse'");
			else
				$row[] = anchor("/facility/admin/nocharge/edit/{$nocharge['booking_ID']}","審核","class='btn btn-primary'");
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	public function add_nocharge()
	{
		$this->is_user_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		//確保有填寫原因
		$this->form_validation->set_rules("reason","不計費原因","required");
		if(!$this->form_validation->run())
		{
			echo $this->info_modal(validation_errors(),"","warning");
			return;
		}
		//然後先確認此預約ID的申請者是否為本人
		$booking = $this->facility_model->get_facility_booking_list(array("serial_no"=>$input_data['booking_ID'],"user_ID"=>$this->session->userdata('ID')))->row_array();
		if(!$booking)
		{
			echo $this->info_modal("權限不足！","","error");
			return;
		}
		//再確認無重複申請
		$nocharge = $this->facility_model->get_booking_nocharge_list(array("booking_ID"=>$input_data['booking_ID']))->row_array();
		if($nocharge)
		{
			echo $this->info_modal("您此筆預約不計費已存在，請勿重複申請。","","error");
			return;
		}
		//插入一筆資料
		$result = $this->facility_model->add_booking_nocharge($input_data);
		
		//通知儀器負責人
		$admin_profile = $this->facility_model->get_user_privilege_list(
		array("facility_ID"=>$booking['facility_ID'],
			  "privilege"=>"admin"))->result_array();
		if($admin_profile)
		{
			$admin_profile = $this->rotate_2D_array($admin_profile);
			$this->email->to($admin_profile['user_email']);
			$this->email->subject("成大微奈米科技研究中心 -使用者 {$booking['user_name']} 預約不計費申請通知-");
			$this->email->message("致儀器負責人：<br>
								   您管理的儀器 {$booking['facility_cht_name']}<br>
								   有使用者提出不計費申請，請上中心系統審核，謝謝。");
			$this->email->send();
		}
		if($result)
			echo $this->info_modal("申請成功，請等候審查結果，謝謝。","/facility/".$this->session->userdata('status')."/booking/list");
		else
			echo $this->info_modal("內部錯誤","","error");
	}
	public function update_nocharge()
	{
		try{
			$this->is_admin_login();
		
			$input_data = $this->input->post(NULL,TRUE);
			
			$this->form_validation->set_rules("result","審查結果","required");
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			if(empty($input_data['result'])){
				$this->form_validation->set_rules("comment","退件理由","required");
			}else{
				$this->form_validation->set_rules("start_date","不計費起始日期","required");
				$this->form_validation->set_rules("start_time","不計費起始時間","required");
				$this->form_validation->set_rules("end_date","不計費結束日期","required");
				$this->form_validation->set_rules("end_time","不計費結束時間","required");
			}
			if(!$this->form_validation->run())
			{
				throw new Exception(validation_errors(),WARNING_CODE);
			}
			
			//取得原預約資料
			$booking = $this->facility_model->get_facility_booking_list(array("serial_no"=>$input_data['booking_ID']))->row_array();
			
			//準備資料
			$data['admin_ID'] = $this->session->userdata('ID');
			$data['comment'] = $input_data['comment'];
			$data['start_time'] = $input_data['start_date']." ".$input_data['start_time'];
			$data['end_time'] = $input_data['end_date']." ".$input_data['end_time'];
			//檢查是否超過原預約時段
			if($data['start_time'] < $booking['start_time'] || $data['end_time'] > $booking['end_time'])
			{
				throw new Exception("核可不計費時段超出原預約時段",WARNING_CODE);
			}
			//檢查是否為整點時段(30分為單位)
			if(strtotime($data['start_time'])%1800 != 0 || strtotime($data['end_time'])%1800 != 0)
			{
				throw new Exception("核可不計費時段超出原預約時段",WARNING_CODE);
			}
			$data['result'] = $input_data['result'];
			$data['booking_ID'] = $input_data['booking_ID'];
			$result = $this->facility_model->update_booking_nocharge($data);
			
			if(!$result)
			{
				throw new Exception("內部錯誤",ERROR_CODE);
			}
			
			//寄信通知使用者
			$application = $this->facility_model->get_booking_nocharge_list(array("booking_ID"=>$input_data['booking_ID']))->row_array();
			$this->email->to($application['user_email']);
			$this->email->subject("成大微奈米科技研究中心 -預約不計費證明審核通知-");
			$app_result = array("退件","通過");
			$this->email->message("{$application['user_name']} 您好，您的儀器預約編號{$application['booking_ID']}所申請之不計費證明已完成。<br>
								   審核結果為：{$app_result[$application['result']]}<br>
								   核可之不計費時段為：{$input_data['start_date']} {$input_data['start_time']}~{$input_data['end_date']} {$input_data['end_time']}<br>
								   備註：{$application['comment']}<br>
								   非常感謝您的使用，若有任何問題歡迎致電本中心，謝謝。");
			$this->email->send();
			
			echo $this->info_modal("審查成功","/facility/admin/nocharge/list");
		}catch(Exception $e){
			echo $this->info_modal($e->getMessage(),"",$e->getCode());
		}
		
	}
	public function del_nocharge()
	{
		$this->is_user_login();
	}
	//-----------------------卡機連線資訊---------------------------
	public function list_access_link()
	{
		$this->is_admin_login();
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/list_access_link',$this->data);
		$this->load->view('templates/footer');
	}
	public function query_access_link()
	{
		$this->is_admin_login();
		
		$access_links = $this->facility_model->get_access_link_list()->result_array();
		
		$output['aaData'] = array();
		
		foreach($access_links as $l)
		{
			$row = array();
			
			$row[] = $l['CtrlNo'];
			$row[] = self::$access_link_type[$l['MType']];
			$row[] = $l['Tcpip'];
			$row[] = anchor("/facility/admin/access/link/edit/{$l['CtrlNo']}","編輯","class='btn btn-warning btn-small'")." ".form_button("del","刪除","class='btn btn-danger btn-small' value='{$l['CtrlNo']}'");
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	public function edit_access_link($No = NULL)
	{
		$this->is_admin_login();
		
		$No = $this->security->xss_clean($No);
		
		if(empty($No))
		{
			$this->data['action'] = "add";
		}else{
			$result = $this->facility_model->get_access_link_list(array("CtrlNo"=>$No))->row_array();
			$this->data = $result;
			$this->data['action'] = "update";
		}
		
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('facility/edit_access_link',$this->data);
		$this->load->view('templates/footer');
	}
	public function add_access_link()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		$result = $this->facility_model->add_access_link($input_data);
		
		if($result)
			echo $this->info_modal("新增成功","/facility/admin/access/link/list");
		else
			echo $this->info_modal("內部錯誤","","error");
	}
	public function update_access_link()
	{
		$this->is_admin_login();
		
		$input_data = $this->input->post(NULL,TRUE);
		
		$result = $this->facility_model->update_access_link($input_data);
		
		if($result)
			echo $this->info_modal("修改成功","/facility/admin/access/link/list");
		else
			echo $this->info_modal("內部錯誤","","error");
	}
	public function del_access_link($No)
	{
		$this->is_admin_login();
		
		$No = $this->security->xss_clean($No);
		
		$result = $this->facility_model->del_access_link(array("CtrlNo"=>$No));
	}
	//---------------FORM_VALIDATION-----------------------
	public function facility_ID_not_existed($str)
	{
		
		if ($this->facility_model->get_facility_by_ID($str))
		{
			$this->form_validation->set_message('facility_ID_not_existed', '%s 已存在，不可重複');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
}