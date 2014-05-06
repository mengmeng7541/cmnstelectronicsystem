<?php
class Reg_model extends MY_Model {
  
	protected $curriculum_db;
//	protected $common_db;

	public function __construct()
	{
		parent::__construct();
		$this->curriculum_db = $this->load->database('curriculum',TRUE);
//		$this->common_db = $this->load->database('common',TRUE);
		
		$this->load->model('curriculum_model');
	}
	public function add($class_ID,$user_ID,$reg_by = NULL)
	{
		$reg_by = isset($reg_by)?$reg_by:$this->session->userdata('ID');
		
		//取得class資訊
		$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
		if(!$class) throw new Exception("無開此課！",ERROR_CODE);
		
		if($class['class_state'] == 'canceled')
		{
			throw new Exception("此課停開",ERROR_CODE);
		}
		
		//檢查是否已經報名
		$reg = $this->curriculum_model->get_reg_list(array("user_ID"=>$user_ID,"class_ID"=>$class_ID))->row_array();
		if($reg) throw new Exception("請勿重複報名！",ERROR_CODE);
		
		//課程管理者不受限制
		if(!$this->curriculum_model->is_super_admin())
		{
			//檢查是否選其他同課程(還在可報名的狀態)
			$regs = $this->curriculum_model->get_reg_list(array("user_ID"=>$user_ID,"course_ID"=>$class['course_ID'],"class_reg_start_time"=>date("Y-m-d H:i:s")))->result_array();
			foreach($regs as $r)//這樣做是為了只選認證時可以直接換成選整套課程
			{
				if($r['class_code'] != $class['class_code'])
					throw new Exception("您已選其他時段之同課程，請勿重複報名！",ERROR_CODE);
			}
			//先檢查是否在可選課日期內
			if(time()<strtotime($class['class_reg_start_time'])){
				throw new Exception("尚未到選課開放時間",ERROR_CODE);
			}
			if(time()>strtotime($class['class_reg_end_time'])){
				throw new Exception("已超過選課截止時間",ERROR_CODE);
			}
			//是否有檔修，有的話是否有通過擋修的認證課程
			$pre_courses = $this->curriculum_model->get_pre_course_list(array("course_ID"=>$class['course_ID']))->result_array();
			foreach($pre_courses as $pre_course)
			{
				$options = array(
					"course_ID"=>$pre_course['pre_course_ID'],
					"user_ID"=>$user_ID,
					"reg_state"=>"certified"
				);
				$reg = $this->curriculum_model->get_reg_list($options)->row_array();
				if(!$reg){
					throw new Exception("本課程有檔修限制，請先通過指定的預先課程認證",ERROR_CODE);
				}
			}
		}
		
		//判斷class有否一連串的課
		if(in_array('certification',explode(",",$class['class_type'])))
		{
			//只選這堂課之前先確認已有權限，只是因為過期所以才要重報，或者曾經選過只是最後認證未過
			
			//先判斷有沒有選過，>=confirmed
			$reg = $this->curriculum_model->get_reg_list(array(
				"course_ID"=>$class['course_ID'],
				"reg_state"=>array('confirmed','certified')
			))->row_array();
			if(!$reg)
			{
				//沒選過就要判斷是否已有權限
				$this->load->model('curriculum/course_model');
				$facility_IDs = $this->course_model->get_course_map_facility_ID($class['course_ID']);
				$this->load->model('facility_model');
				foreach($facility_IDs as $facility_ID)
				{
					$privilege = $this->facility_model->get_user_privilege_list(array(
						"facility_ID"=>$facility_ID,
						"user_ID"=>$user_ID
					))->row_array();
					if(!$privilege)
					{
						throw new Exception("您未曾通過本課程，不可直接報名認證",ERROR_CODE);
					}
				}
			}
			
			$data = array("class_ID"=>$class_ID,"user_ID"=>$user_ID,"reg_by"=>$reg_by);
			$this->curriculum_model->add_reg($data);
		}else{
			//先確認沒有選其他同課程ID與同開課代碼，有就刪掉
			$regs = $this->curriculum_model->get_reg_list(array("user_ID"=>$user_ID,"course_ID"=>$class['course_ID'],"class_code"=>$class['class_code']))->result_array();
			foreach($regs as $reg){
				$this->curriculum_model->del_reg(array("reg_ID"=>$reg['reg_ID']));
			}
			//把同開課代碼的課都選起來
			$classes = $this->curriculum_model->get_class_list(array("course_ID"=>$class['course_ID'],"class_code"=>$class['class_code']))->result_array();
			foreach($classes as $c)
			{
				$data = array("class_ID"=>$c['class_ID'],"user_ID"=>$user_ID,"reg_by"=>$reg_by);
				$this->curriculum_model->add_reg($data);
			}
		}
		
		//送信通知
		//若非正取，選課截止後通知是否備上
		$reg = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID'],"user_ID"=>$user_ID))->row_array();
		if(empty($class['class_max_participants'])||$reg['reg_rank']<=$class['class_max_participants'])
		{
			$this->load->model('user_model');
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
			if($user_profile && !empty($user_profile['email']))
			{
				$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
				$this->email->to($user_profile['email']);
				$this->email->subject("成大微奈米技研中心 -儀器訓練課程系統通知-");
				$this->email->message(
					"{$user_profile['name']} 您好，<br>
					<br>
					您已成功報名下列課程，屆時請記得準時上課，謝謝。<br>
					日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
					時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
					課程名稱：{$class['course_cht_name']}<br>
					地點：{$class['location_cht_name']}<br>
					授課者：{$class['prof_name']}"
				);
				$this->email->send();
			}
		}else{
			$this->load->model('user_model');
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
			if($user_profile && !empty($user_profile['email']))
			{
				$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
				$this->email->to($user_profile['email']);
				$this->email->subject("成大微奈米技研中心 -儀器訓練課程系統通知-");
				$this->email->message(
					"{$user_profile['name']} 您好，<br>
					<br>
					您已成功報名下列課程，然因人數過多，已為您排隊備取，屆時如有人取消將自動遞補，謝謝。<br>
					日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
					時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
					課程名稱：{$class['course_cht_name']}<br>
					地點：{$class['location_cht_name']}<br>
					授課者：{$class['prof_name']}"
				);
				$this->email->send();
			}
		}
		
		
		
	}

	public function del($reg_ID = "",$reg_canceled_by = NULL)
	{
		if(!isset($reg_canceled_by))
		{
			$reg_canceled_by = $this->session->userdata('ID');
		}
		
		
		$reg = $this->curriculum_model->get_reg_list(array("reg_ID"=>$reg_ID))->row_array();
		if(!$reg) throw new Exception("無此報名！",ERROR_CODE);
		
		//檢查權限
		if(!$this->curriculum_model->is_super_admin() && $reg['user_ID'] != $this->session->userdata('ID'))
		{
			throw new Exception("權限不足！",ERROR_CODE);
		}
		
		//取得class資訊
		$class = $this->curriculum_model->get_class_list(array("class_ID"=>$reg['class_ID']))->row_array();
		if(!$class) throw new Exception("無開此課！",ERROR_CODE);
		
		if($reg['reg_state'] != "selected"){
			throw new Exception("報名已確認，不可刪除",ERROR_CODE);
		}
		
		//連同相關報名之課程一起刪除
		//注意若已過第一堂課的報名期限，就不可以再取消(只報名認證除外)
		$reg = $this->curriculum_model->get_reg_list(array(
			"user_ID"=>$reg['user_ID'],
			"course_ID"=>$reg['course_ID'],
			"class_code"=>$reg['class_code'],
			"group_class_suite"=>TRUE
		))->row_array();
		$this->load->model('curriculum/class_model');
		if($this->class_model->is_certification_class_only($reg['class_type']))
		{
			//只報名認證的
			
		}else{
			//報名全套的
			//(取得第一堂課的開課資訊)
			$class = $this->curriculum_model->get_class_list(array("class_ID"=>$reg['class_ID']))->row_Array();
			//課程管理者不受限制
			if(!$this->curriculum_model->is_super_admin())
			{
				//先檢查是否在可選課日期內
				if(time()<strtotime($class['class_reg_start_time'])){
					throw new Exception("尚未到選課開放時間",ERROR_CODE);
				}
				if(time()>strtotime($class['class_reg_end_time'])){
					throw new Exception("已超過選課截止時間",ERROR_CODE);
				}
			}
		}
		
		$data = array(	"user_ID"=>$reg['user_ID'],
						"course_ID"=>$reg['course_ID'],
						"class_code"=>$reg['class_code'],
						"reg_canceled_by"=>$reg_canceled_by);
		$this->curriculum_model->del_reg($data);
	}
	//變更註冊狀態
//	public function update($reg_IDs = "",$updated_by = NULL)
//	{
//		if(!isset($updated_by))
//		{
//			$updated_by = $this->session->userdata('ID');
//		}
//		
//		foreach((array)$reg_IDs as $reg_ID)
//		{
//			$reg = $this->curriculum_model->get_reg_list(array("reg_ID"=>$reg_ID))->row_array();
//			if(!$reg) throw new Exception("無此報名！",ERROR_CODE);
//			
//			//檢查權限
//			if(!$this->curriculum_model->is_super_admin())
//			{
//				$this->load->model('curriculum/lesson_model');
//				$lessons = $this->curriculum_model->get_lesson_list(array(
//					"course_ID"=>$reg['course_ID'],
//					"class_code"=>$reg['class_code']
//				))->result_array();
//				//只要是有上過課的老師就有權限確認或認證
//				if(!$lessons || !in_array($this->session->userdata('ID'),sql_result_to_column($lessons,"lesson_prof_ID")))
//				{
//					throw new Exception("權限不足！",ERROR_CODE);
//				}
//			}
//			
//			if($reg['reg_state'] == "selected"){
//				//其餘同class_code也要同時確認
//				$data = array(	"user_ID"=>$reg['user_ID'],
//								"course_ID"=>$reg['course_ID'],
//								"class_code"=>$reg['class_code']);
//				$regs =  $this->curriculum_model->get_reg_list($data)->result_array();
//				
//				foreach($regs as $r)
//				{
//					$data = array(	"reg_state"=>"confirmed",
//									"reg_confirmed_by"=>$updated_by,
//									"reg_ID"=>$r['reg_ID']);
//					$this->curriculum_model->update_reg($data);
//				}
//			}else if($reg['reg_state'] == "confirmed"){
//				//已確認，接下來要通過認證
//				if($reg['course_ID']=="1"){
//					//安全講習，較特殊
//					$this->load->model('user_model');
//					$this->user_model->update_user_security_verification($reg['user_ID']);
//				}else{
//					//先檢查是否為認證課程
//					if(!in_array('certification',explode(",",$reg['class_type']))) throw new Exception("此課程不含認證課程！",ERROR_CODE); 
//					//開啟相關儀器權限(先取得該課程相關的儀器，再開啟權限)
//					$data = array("course_ID"=>$reg['course_ID']);
//					$results = $this->curriculum_model->get_course_facility_map($data)->result_array();
//					$this->load->model('facility/user_privilege_model');
//					foreach($results as $result)
//					{
//						$this->user_privilege_model->add($result['facility_ID'],$reg['user_ID'],"normal");
//					}
//				}
//				//更改狀態為已認證
//				$data = array(	"reg_state"=>"certified",
//								"reg_certified_by"=>$updated_by,
//								"reg_ID"=>$reg['reg_ID']);
//				$this->curriculum_model->update_reg($data);
//			}
//		}
//		
//	}
	public function confirm($reg_IDs = "",$updated_by = NULL)
	{
		if(!isset($updated_by))
		{
			$updated_by = $this->session->userdata('ID');
		}
		
		foreach((array)$reg_IDs as $reg_ID)
		{
			$reg = $this->curriculum_model->get_reg_list(array("reg_ID"=>$reg_ID))->row_array();
			if(!$reg) throw new Exception("無此報名！",ERROR_CODE);
			
			//檢查權限
			if(!$this->curriculum_model->is_super_admin())
			{
				$this->load->model('curriculum/lesson_model');
				$lessons = $this->curriculum_model->get_lesson_list(array(
					"course_ID"=>$reg['course_ID'],
					"class_code"=>$reg['class_code']
				))->result_array();
				//只要是有上過課的老師就有權限確認或認證
				if(!$lessons || !in_array($this->session->userdata('ID'),sql_result_to_column($lessons,"lesson_prof_ID")))
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
			}
			
			if($reg['reg_state'] == "selected"){
				//其餘同class_code也要同時確認
				$data = array(	"user_ID"=>$reg['user_ID'],
								"course_ID"=>$reg['course_ID'],
								"class_code"=>$reg['class_code']);
				$regs =  $this->curriculum_model->get_reg_list($data)->result_array();
				
				foreach($regs as $r)
				{
					$data = array(	"reg_state"=>"confirmed",
									"reg_confirmed_by"=>$updated_by,
									"reg_ID"=>$r['reg_ID']);
					$this->curriculum_model->update_reg($data);
				}
			}else{
				throw new Exception("學員已到，不用再次確認",ERROR_CODE);
			}
		}
	}
	public function certify($reg_IDs = "",$updated_by = NULL)
	{
		if(!isset($updated_by))
		{
			$updated_by = $this->session->userdata('ID');
		}
		
		foreach((array)$reg_IDs as $reg_ID)
		{
			$reg = $this->curriculum_model->get_reg_list(array("reg_ID"=>$reg_ID))->row_array();
			if(!$reg) throw new Exception("無此報名！",ERROR_CODE);
			
			//檢查權限
			if(!$this->curriculum_model->is_super_admin())
			{
				$this->load->model('curriculum/lesson_model');
				$lessons = $this->curriculum_model->get_lesson_list(array(
					"course_ID"=>$reg['course_ID'],
					"class_code"=>$reg['class_code'],
					"lesson_type"=>"certification"
				))->result_array();
				//只要是有上過認證課的老師就有權限
				if(!$lessons || !in_array($this->session->userdata('ID'),sql_result_to_column($lessons,"lesson_prof_ID")))
				{
					throw new Exception("權限不足！",ERROR_CODE);
				}
			}
			
			if($reg['reg_state'] == "confirmed"){
				//已確認，要通過認證
				if($reg['course_ID']=="1"){
					//安全講習，較特殊
					$this->load->model('user_model');
					$this->user_model->update_user_security_verification($reg['user_ID']);
				}else{
					//先檢查是否為認證課程
					if(!in_array('certification',explode(",",$reg['class_type']))) throw new Exception("此課程不含認證課程！",ERROR_CODE); 
					//開啟相關儀器權限(先取得該課程相關的儀器，再開啟權限)
					$data = array("course_ID"=>$reg['course_ID']);
					$results = $this->curriculum_model->get_course_facility_map($data)->result_array();
					$this->load->model('facility/user_privilege_model');
					foreach($results as $result)
					{
						$this->user_privilege_model->add($result['facility_ID'],$reg['user_ID'],"normal");
					}
				}
				//更改狀態為已認證
				$data = array(	"reg_state"=>"certified",
								"reg_certified_by"=>$updated_by,
								"reg_ID"=>$reg['reg_ID']);
				$this->curriculum_model->update_reg($data);
			}else{
				throw new Exception("尚未確認已到",ERROR_CODE);
			}
		}
	}
	
	
}