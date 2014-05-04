<?php
class Signature_model extends MY_Model {
  
	protected $curriculum_db;

	public function __construct()
	{
		parent::__construct();
		$this->curriculum_db = $this->load->database('curriculum',TRUE);
		
		$this->load->model('curriculum_model');
	}
	
	public function add($lesson_ID,$reg_ID,$signature_by = NULL)
	{
			
		$options = array(
			"lesson_ID"=>$lesson_ID,
			"reg_ID"=>$reg_ID
		);
		
		//取得簽到資訊
		$signature = $this->curriculum_model->get_signature_list($options)->row_array();
		
		
		if(!$signature){
			throw new Exception("查無資料",ERROR_CODE);
		}
		
		if(!empty($signature['lesson_signature_by']))
		{
			throw new Exception("不可重複簽到",ERROR_CODE);
		}
		
		if(!$this->curriculum_model->is_super_admin())
		{
			if(time()<strtotime(date("Y-m-d 00:00:00",strtotime($signature['lesson_start_time']."-1day"))))
			{
				throw new Exception("尚未開放簽到",ERROR_CODE);
			}
			
			if(time()>strtotime($signature['lesson_end_time']))
			{
				throw new Exception("開放簽到時間已過",ERROR_CODE);
			}
		}
		
		//確認是否已有資料
		if(empty($signature['signature_ID']))
		{
			$data = array(
				"lesson_ID"=>$lesson_ID,
				"reg_ID"=>$reg_ID,
				"signature_by"=>$signature_by
			);
			
			$signature_ID = $this->curriculum_model->add_signature($data);
		}else{
			$data = array(
				"signature_ID"=>$signature['signature_ID'],
				"signature_by"=>$signature_by
			);
			
			$this->curriculum_model->update_signature($data);
		}
		
		if(!empty($signature_by))
		{
			//簽到後開啟門禁權限
			$this->load->model('user_model');
			$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$signature['lesson_student_ID']))->row_array();
			if(!empty($user_profile['card_num']))//如果沒有卡片就沒辦法開門禁
			{
				$this->load->model('curriculum/course_model');
				$facility_IDs = $this->course_model->get_course_map_facility_ID($signature['course_ID']);
				if(!empty($facility_IDs))
				{
					$this->load->model('facility/access_ctrl_model');
					foreach($facility_IDs as $facility_ID)
					{
						$this->access_ctrl_model->add($facility_ID,$user_profile['ID'],strtotime($signature['lesson_start_time']),strtotime($signature['lesson_end_time']),TRUE);
					}
				}
			}
			
			//寄信通知並提供取消的連結
			$this->email->to($user_profile['email']);
			$this->email->subject("成大微奈米科技研究中心 -課程系統通知- [簽到成功]");
			$this->email->message(
				"{$user_profile['name']} 您好，<br>
				<br>
				您已完成此次課堂簽到，若您欲取消此次簽到，可利用以下連結進行線上簽到取消動作，或是亦可上本中心課程報名系統取消簽到，謝謝。
				取消簽到：".anchor("/curriculum/signature/del/email/{$signature['signature_ID']}/{$user_profile['ID']}/{$signature['lesson_signature_hash']}","點我")."<br>
				日期：".date("Y-m-d",strtotime($class['class_start_time']))."<br>
				時間：".date("H:i:s",strtotime($class['class_start_time']))."<br>
				課程名稱：{$class['course_cht_name']}<br>
				地點：{$class['location_cht_name']}<br>
				授課者：{$class['prof_name']}<br>"
			);
			$this->email->send();
		}
		
		return isset($signature_ID)?$signature_ID:$signature['signature_ID'];
	}
	
	public function del()
	{
		
	}
	
	public function update()
	{
		
	}
	
}