<?php
class Unittest extends MY_Controller {
  public function __construct()
  {
    parent::__construct();
    
    
  
  }
  public function index()
  {
		$this->load->model('curriculum_model');
		$this->load->model('curriculum/class_model');
		$this->load->model('curriculum/lesson_model');
		$this->load->model('user_model');
		$this->load->model('facility/booking_model');
		
		
		$classes = $this->curriculum_model->get_class_list(array(
			"class_reg_start_time"=>date("Y-m-d 00:00:01",strtotime("-1day")),
			"class_reg_end_time"=>date("Y-m-d 00:00:00"),
			"class_state"=>"normal"
		))->result_array();
		
		
		
		
		
		foreach($classes as $class){
			
			//如果昨天截止報名
			if(strtotime(date("Y-m-d 00:00:01",strtotime("-1day"))) <= strtotime($class['class_reg_end_time']) && strtotime($class['class_reg_end_time']) <= strtotime(date("Y-m-d 00:00:00")))
			{
				
				
				
				//認證課程用特殊處理方式
				$original_canceled_reg_nums = 0;
				$original_canceled_reg_user_IDs = array();
				if($this->class_model->is_certification_class_only($class['class_type']))
				{
					//先取得原本沒開課的報名人數
					$original_canceled_regs = $this->curriculum_model->get_reg_list(array(
						"course_ID"=>$class['course_ID'],
						"class_code"=>$class['class_code'],
						"class_state"=>"canceled",
						"group_class_suite"=>TRUE
					));
					$original_canceled_reg_nums = $original_canceled_regs->num_rows();
					$original_canceled_reg_user_IDs = sql_result_to_column($original_canceled_regs->result_array(),"user_ID");
					var_dump ($original_canceled_reg_nums);
				}
//				
//				//判斷是否滿足大於等於最小開課人數
//				$reg_query = $this->curriculum_model->get_reg_list(array("class_ID"=>$class['class_ID']));
//				if($reg_query->num_rows()-$original_canceled_reg_nums >= $class['class_min_participants']){
//					foreach($reg_query->result_array() as $reg){
//						if(
//							empty($class['class_max_participants']) || 
//							$reg['reg_rank']-$original_canceled_reg_nums <= $class['class_max_participants']
//						)
//						{
//							if(!in_array($reg['user_ID'],$original_canceled_reg_user_IDs))
//							{
//								//正常開課，發信通知正取學員
//								$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
//								$this->email->to($user_profile['email']);
//								$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [正常開課]");
//								$this->email->message(
//									"{$user_profile['name']} 您好：<br>
//									<br>
//									本中心將預定於 {$class['class_start_time']}<br>
//									開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//									您的報名順序為 正取{$reg['reg_rank']}，請準時前往上課，謝謝。<br>
//									日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
//									時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
//									課程名稱：{$class['course_cht_name']}<br>
//									地點：{$class['location_cht_name']}<br>
//									授課者：{$class['prof_name']}<br>
//									"
//								);
//								$this->email->send();
//							}
//							
//						}else{
//							//發信通知備取學員，請他改選其他同課程
//							$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$reg['user_ID']))->row_array();
//							$this->email->to($user_profile['email']);
//							$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [備取未上]");
//							$this->email->message(
//								"{$user_profile['name']} 您好：<br>
//								<br>
//								本中心將預定於 {$class['class_start_time']}<br>
//								開設 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//								您的報名順序為 備取".($reg['reg_rank']-$class['class_max_participants'])."，很遺憾截至目前為止您尚未遞補上本次課程<br>
//								竭誠歡迎您盡速上本中心網站預約下一期的課程，以免向隅，謝謝。"
//							);
//							$this->email->send();
//						}
//					}
//					
//				}else{
//					//確認是否上一期是否沒開成且又同一人選課
//					
//					//停開，人數不足
//					$this->curriculum_model->update_class(array(
//						"class_ID"=>$class['class_ID'],
//						"class_state"=>"canceled"
//					));
//					
//					//發信通知授課者
//					$profs_ID = $this->class_model->get_class_profs_ID($class['class_ID']);
//					
//					if(!empty($profs_ID))
//					{
//						$prof_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$profs_ID))->result_array();
//						$this->email->to(sql_result_to_column($prof_profiles,"email"));
//						$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
//						$this->email->message(
//							"您好：<br>
//							<br>
//							本中心預定於 {$class['class_start_time']}<br>
//							開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//							因人數不足停開，請勿前來上課，特此通知，謝謝。"
//						);
//						$this->email->send();
//					}
//					
//					//發信通知已選課學員
//					$users_ID = sql_result_to_column($reg_query->result_array(),"user_ID");
//					if(!empty($users_ID))
//					{
//						$user_profiles = $this->user_model->get_user_profile_list(array("user_ID"=>$users_ID))->result_array();
//						$this->email->to(sql_result_to_column($user_profiles,"email"));
//						$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [停開]");
//						$this->email->message(
//							"您好：<br>
//							<br>
//							本中心預定於 {$class['class_start_time']}<br>
//							開設之 {$class['course_cht_name']} ".$this->curriculum_model->get_class_type_str($class['class_type'])." 課程<br>
//							因人數不足停開，請勿前來上課，特此通知，謝謝。"
//						);
//						$this->email->send();
//					}
//					
//					//把預約的儀器全部取消
//					$bookings = $this->curriculum_model->get_lesson_booking_map(array("class_ID"=>$class['class_ID']))->result_array();
//					if($bookings){
//						
//						$b_IDs = sql_result_to_column($bookings,"booking_ID");
//						$this->booking_model->del($b_IDs);
//					}
//				}
			}
			
		}
		
		echo "DONE";
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
