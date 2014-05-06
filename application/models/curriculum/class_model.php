<?php
class Class_model extends MY_Model {
  
	protected $curriculum_db;

	public function __construct()
	{
		parent::__construct();
		$this->curriculum_db = $this->load->database('curriculum',TRUE);
		
		$this->load->model('curriculum_model');
	}
	public function del($class_ID){
		
		//先確認無人報名
		$reg = $this->curriculum_model->get_reg_list(array("class_ID"=>$class_ID))->row_array();
		if($reg){
			throw new Exception("此課已有人報名，不可刪除",ERROR_CODE);
		}
		
		//先刪除所有報名的人
		$this->curriculum_db->where("class_ID",$class_ID);
		$this->curriculum_db->delete("class_registration");
		//再真正刪除課堂
		$this->curriculum_model->del_class(
			array("class_ID"=>$class_ID)
		);
	}
	public function update_reg_end_time($class_ID){
		$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
		if(!$class){
			throw new Exception("無此開課資訊",ERROR_CODE);
		}
		
		//若不自動更新，則跳脫此函式
		if($class['class_reg_end_time_auto']==0){
			return;
		}
		
		//尋找相關開課
		$classes = $this->curriculum_model->get_class_list(array("course_ID"=>$class['course_ID'],"class_code"=>$class['class_code']))->result_array();
		if(!$classes){
			return;
		}
		$class_start_time = min(sql_result_to_column($classes,"class_start_time"));
		
		$class_total_secs = 0;
		foreach($classes as $c){
			$class_total_secs += $c['class_total_secs'];
		}
		
		foreach($classes as $c){
			//注意如果有單獨的認證，那堂就不用考量全套課程的起始時間
			$tmp_class_type = explode(',',$c['class_type']);
			if(count($tmp_class_type)==1 && $tmp_class_type[0]=='certification')
			{
				if(empty($class_start_time)){
					$class_reg_end_time = $c['class_reg_start_time'];
				}else{
					if($c['class_total_secs']/3600 < 10){//小於十小時
						$class_reg_end_time = date("Y-m-d 00:00:00",strtotime($c['class_start_time'].' -2day'));
					}else{
						$class_reg_end_time = date("Y-m-d 00:00:00",strtotime($c['class_start_time'].' -1week'));
					}
				}
			}else{
				if(empty($class_start_time)){
					$class_reg_end_time = $c['class_reg_start_time'];
				}else{
					if($class_total_secs/3600 < 10){//小於十小時
						$class_reg_end_time = date("Y-m-d 00:00:00",strtotime($class_start_time.' -2day'));
					}else{
						$class_reg_end_time = date("Y-m-d 00:00:00",strtotime($class_start_time.' -1week'));
					}
				}
			}
			
			
			$this->curriculum_model->update_class(
				array(
					"class_reg_end_time"=>$class_reg_end_time,
					"class_ID"=>$c['class_ID']
				)
			);
		}
		
	}
	
	public function is_certification_class_only($class_type)
	{
		$class_type = explode(',',$class_type);
		return count($class_type)==1&&$class_type[0]=='certification';
	}
	
	public function get_class_profs_ID($class_ID)
	{
		$lessons = $this->curriculum_model->get_lesson_list(array(
			"class_ID"=>$class_ID
		))->result_array();
		return $profs_ID = array_unique(sql_result_to_column($lessons,"lesson_prof_ID"));
	}
	
	public function get_class_ID_select_options($class_code = NULL)
	{
		$classes = $this->curriculum_model->get_class_list(array("class_code"=>$class_code))->result_array();
		$output = array();
		foreach($classes as $class){
			$output[$class['class_ID']] = $class['course_cht_name']." [{$class['class_code']}]";
		}
		return $output;
	}
	public function get_class_type_select_options()
	{
		$output = array(
			""=>"",
			"theory"=>"理論",
			"training"=>"訓練",
			"implement"=>"實作",
			"certification"=>"認證",
		);
		return $output;
	}
}