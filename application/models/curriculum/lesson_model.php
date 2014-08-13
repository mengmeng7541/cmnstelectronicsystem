<?php
class Lesson_model extends MY_Model {
  
//	protected $curriculum_db;

	public function __construct()
	{
		parent::__construct();
//		$this->curriculum_db = $this->load->database('curriculum',TRUE);
		
		$this->load->model('curriculum_model');
	}
	public function add($class_ID,$prof_ID,$start_time,$end_time,$remark)
	{
		if($start_time>=$end_time){
			throw new Exception("起始時間不可大於等於結束時間",WARNING_CODE);
		}
		//自動預約(先取得關聯的儀器，再幫他預約)
		$class = $this->curriculum_model->get_class_list(array("class_ID"=>$class_ID))->row_array();
		$facility_IDs = $this->course_model->get_course_map_facility_ID($class['course_ID']);
		if(!empty($facility_IDs)){
			$this->load->model('facility/booking_model');
			$booking_IDs = $this->booking_model->add($facility_IDs,$prof_ID,$start_time,$end_time,"course");
		}
		
		//新增課程
		$data = array(	
			"class_ID"=>$class_ID,
			"lesson_prof_ID"=>$prof_ID,
			"lesson_start_time"=>date("Y-m-d H:i:s",$start_time),
			"lesson_end_time"=>date("Y-m-d H:i:s",$end_time),
			"lesson_comment"=>$remark
		);
		$lesson_ID = $this->curriculum_model->add_lesson($data);
		
		//自動更新開課課程可註冊到期日
		$this->load->model('curriculum/class_model');
		$this->class_model->update_reg_end_time($class_ID);
		
		//新增課堂與預約儀器間關係
		if(isset($booking_IDs)){
			foreach((array)$booking_IDs as $booking_ID){
				$this->curriculum_model->add_lesson_booking_map(array("lesson_ID"=>$lesson_ID,"booking_ID"=>$booking_ID));
			}
		}
		
		
		return $lesson_ID;
	}
	public function update($lesson_ID,$prof_ID,$start_time,$end_time,$remark){
		if($start_time>=$end_time){
			throw new Exception("起始時間不可大於等於結束時間",WARNING_CODE);
		}
		//變更原先的自動預約
		$lesson = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$lesson_ID))->row_array();
		$booking_IDs = $this->get_lesson_map_booking_ID($lesson['lesson_ID'],"normal");
		if(!empty($booking_IDs)){
			$this->load->model('facility/booking_model');
			if($prof_ID != $lesson['lesson_prof_ID']){
				foreach($booking_IDs as $booking_ID){
					$this->booking_model->update_user($booking_ID,$prof_ID);
				}
			}
			if($start_time != strtotime($lesson['lesson_start_time']) || $end_time != strtotime($lesson['lesson_end_time'])){
				foreach($booking_IDs as $booking_ID){
					$this->booking_model->update_time($booking_ID,$start_time,$end_time);
				}
			}
		}
		
		
		//修改課堂資訊
		$data = array(	"lesson_ID"=>$lesson_ID,
						"lesson_prof_ID"=>$prof_ID,
						"lesson_start_time"=>date("Y-m-d H:i:s",$start_time),
						"lesson_end_time"=>date("Y-m-d H:i:s",$end_time),
						"lesson_comment"=>$remark);
		
		$this->curriculum_model->update_lesson($data);
		//自動更新開課課程可註冊到期日
		$this->load->model('curriculum/class_model');
		$this->class_model->update_reg_end_time($lesson['class_ID']);
		
		
	}
	public function del($lesson_ID)
	{
		if(empty($lesson_ID))
		{
			return;
		}
		//取得課堂資訊
		$lessons = $this->curriculum_model->get_lesson_list(array("lesson_ID"=>$lesson_ID))->result_array();
		if(!$lessons){
			throw new Exception("無此課堂",ERROR_CODE);
		}
		$lesson_IDs = sql_column_to_key_value_array($lessons,"lesson_ID");
		
		//先刪除預約紀錄
		$maps = $this->curriculum_model->get_lesson_booking_map(array("lesson_ID"=>$lesson_IDs))->result_array();
		$this->load->model('facility/booking_model');
		foreach($maps as $map){
			$this->booking_model->del($map['booking_ID']);
		}
		
		//然後刪除lesson紀錄
		$this->curriculum_model->del_lesson(array("lesson_ID"=>$lesson_IDs));
		
		//然後更新可預約時間
		$this->load->model('curriculum/class_model');
		foreach($lessons as $lesson)
		{
			$this->class_model->update_reg_end_time($lesson['class_ID']);
		}
	}
	
	public function get_lesson_map_booking_ID($lesson_ID,$booking_state = NULL){
		$results = $this->curriculum_model->get_lesson_booking_map(array("lesson_ID"=>$lesson_ID,"booking_state"=>$booking_state))->result_array();
		$output = array();
		foreach($results as $result){
			$output[] = $result['booking_ID'];
		}
		return $output;
	}
}
