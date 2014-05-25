<?php
class Unittest extends MY_Controller {
	public function __construct()
	{
		parent::__construct();



	}
//	private static $class_state = array(""=>"","normal"=>"正常","canceled"=>"停開","special"=>"加開");
	public function index()
	{
		$this->output->enable_profiler(TRUE);
  		
		$this->benchmark->mark('code_start');
		
		$this->benchmark->mark('code_end');
		
		
		echo $this->benchmark->elapsed_time('code_start','code_end');
	}
	
	public function transfer_curriculum()
	{
		$this->load->model('user_model');
		$this->load->model('curriculum_model');
		$curriculum_db = $this->load->database('curriculum',TRUE);
		$common_db = $this->load->database('common',TRUE);
		
		//先刪除舊的
		$curriculum_db->where("class_code <=","2014-04-E");
		$curriculum_db->delete("class_list");
//		return;
		
		$curriculum_db->select("SN,user_ID,class_code,class_name,course_ID,MIN(reg_time) AS reg_time,MAX(join_check) AS join_check,su,MAX(cashornot) AS cashornot,");
		$curriculum_db->group_by("course_ID,class_code,user_ID");
		$results = $curriculum_db->get("old_curriculum")->result_array();
		foreach($results as $result){
			//先確認使用者存在
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$result['user_ID']))->row_array();
			if(!$user_profile){
				continue;
			}
			$class = $this->curriculum_model->get_class_list(array(
				"course_ID"=>$result['course_ID'],
				"class_code"=>$result['class_code']
			))->row_array();
			if(!$class){
				//新增一class
				$class_ID = $this->curriculum_model->add_class(array(
					"course_ID"=>$result['course_ID'],
					"class_code"=>$result['class_code'],
					"class_type"=>$result['course_ID']==1?"":"training,implement,certification"
				));
			}else{
				$class_ID = $class['class_ID'];
			}
			//新增註冊
			$reg_ID = $this->curriculum_model->add_reg(array(
				"user_ID"=>$result['user_ID'],
				"class_ID"=>$class_ID,
				"reg_by"=>$result['user_ID'],
				"reg_time"=>$result['reg_time'],
				"reg_rank"=>0
			));
			//更新註冊
			if($result['join_check'])
			{
				$this->curriculum_model->update_reg(array(
					"reg_confirmed_by"=>"Ava",
					"reg_certified_by"=>"Ava",
					"reg_state"=>"certified",
					"reg_ID"=>$reg_ID
				));
			}
//			return;
		}
		
		$this->load->model('curriculum/reg_model');
		$this->reg_model->refresh_rank();
	}
  
//  public function form()
//  {
//      
//	  $this->load->view('templates/header');
//	  $this->load->view('templates/sidebar');
//	  $this->load->view('user/form');
//	  $this->load->view('templates/footer');
//    
//  }
  

}
?>
