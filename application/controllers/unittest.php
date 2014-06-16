<?php
class Unittest extends MY_Controller {
	public function __construct()
	{
		parent::__construct();



	}
	
	public function index()
	{
		$this->output->enable_profiler(TRUE);
  		
  		
		$this->benchmark->mark('code_start');
		
		$this->benchmark->mark('code_end');
		
		echo $this->benchmark->elapsed_time('code_start','code_end');
	}
	
//	public function transfer_curriculum()
//	{
//		$this->load->model('user_model');
//		$this->load->model('curriculum_model');
//		$curriculum_db = $this->load->database('curriculum',TRUE);
//		$common_db = $this->load->database('common',TRUE);
//		
//		//先刪除舊的
//		$curriculum_db->where("class_code <=","2014-04-E");
//		$curriculum_db->where("class_code >=","2014-04-A");
//		$curriculum_db->delete("class_list");
////		$curriculum_db->where("lesson_start_time <=","2014-04-30 00:00:00");
////		$curriculum_db->delete("lesson_list");
////		return;
//		
//		$curriculum_db->select("SN,user_ID,class_code,class_name,course_ID,MIN(reg_time) AS reg_time,MAX(join_check) AS join_check,su,MAX(cashornot) AS cashornot,class_date");
//		$curriculum_db->where("class_code <=","2014-04-E");
//		$curriculum_db->where("class_code >=","2014-04-A");
//		$curriculum_db->group_by("course_ID,class_code,user_ID");
//		$results = $curriculum_db->get("old_curriculum")->result_array();
//		foreach($results as $result){
//			//先確認使用者存在
//			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$result['user_ID']))->row_array();
//			if(!$user_profile){
//				continue;
//			}
//			$class = $this->curriculum_model->get_class_list(array(
//				"course_ID"=>$result['course_ID'],
//				"class_code"=>$result['class_code']
//			))->row_array();
//			if(!$class){
//				//新增一class
//				$class_ID = $this->curriculum_model->add_class(array(
//					"course_ID"=>$result['course_ID'],
//					"class_code"=>$result['class_code'],
//					"class_type"=>$result['course_ID']==1?"":"training,implement,certification"
//				));
//				//取得SU的ID
//				if(empty($result['su']))
//				{
//					$su = NULL;
//				}else{
//					$su = $this->user_model->get_user_profile_list(array("user_name"=>$result['su']))->row_array();
//				}
//				
//				//新增一假的lesson
//				$this->curriculum_model->add_lesson(array(
//					"class_ID"=>$class_ID,
//					"lesson_prof_ID"=>$su?$su['ID']:NULL,
//					"lesson_start_time"=>date("Y-m-d H:i:s",strtotime($result['class_date'])),
//					"lesson_end_time"=>date("Y-m-d H:i:s",strtotime($result['class_date'])),
//					"lesson_comment"=>""
//				));
//			}else{
//				$class_ID = $class['class_ID'];
//			}
//			//新增註冊
//			$reg_ID = $this->curriculum_model->add_reg(array(
//				"user_ID"=>$result['user_ID'],
//				"class_ID"=>$class_ID,
//				"reg_by"=>$result['user_ID'],
//				"reg_time"=>$result['reg_time'],
//				"reg_rank"=>0
//			));
//			//更新註冊
//			if($result['join_check'])
//			{
//				$this->curriculum_model->update_reg(array(
//					"reg_confirmed_by"=>"Ava",
//					"reg_certified_by"=>"Ava",
//					"reg_state"=>"certified",
//					"reg_ID"=>$reg_ID
//				));
//			}
//		}
//		
//		$this->load->model('curriculum/reg_model');
//		$this->reg_model->refresh_rank(array("class_code"=>"2014-04-A"));
//	}
  
	public function transform_outage()
	{
		$this->is_admin_login();
		$this->load->model('facility_model');
		$this->load->model('facility/outage_model');
		$facilities = $this->facility_model->get_facility_list(array(
			"type"=>"facility"
		))->result_array();
		foreach($facilities as $facility)
		{
			if(!empty($facility['pause_start_time']))
			{
				$this->outage_model->add_outage(
					$facility['ID'],
					$facility['error_comment'],
					date("Y-m-d H:00:00",strtotime($facility['pause_start_time'])),
					date("Y-m-d H:00:00",strtotime($facility['pause_end_time']))
				);
			}else if($facility['state']=='fault')
			{
				$this->outage_model->add_outage($facility['ID'],$facility['error_comment'],date("Y-m-d 00:00:00"),NULL);
			}
		}
	}
  

}
?>
