<?php
class Card_application_model extends Facility_model {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_refundable_card_num($user_ID)
	{
		$refundable = array();
		$this->load->model('user_model');
		$user_profile = $this->user_model->get_user_profile_list(array("user_ID"=>$user_ID))->row_array();
		if(!$user_profile)
		{
			return $refundable;
		}
		if(!empty($user_profile['card_num']))
		{
			$refundable[$user_profile['card_num']] = $user_profile['card_num'];
		}
		
		//找出核發與補發的卡號減掉沒在"之後"被退卡的卡號
		$sTable = "facility_card_application";
		$this->facility_db->select("
			app_table.*
			FROM (SELECT * FROM $sTable ORDER BY issuance_date DESC) app_table
		",FALSE);
		$this->facility_db->where("app_table.canceled_by",NULL);
		$this->facility_db->where("app_table.user_ID",$user_profile['ID']);
		$this->facility_db->group_by("app_table.card_num");
		$this->facility_db->having("app_table.type","apply");
		$this->facility_db->or_having("app_table.type","reissue");
		$apps = $this->facility_db->get()->result_array();
		$card_nums = sql_column_to_key_value_array($apps,"card_num","card_num");
		
		$refundable = array_merge($refundable,$card_nums);
		$refundable = array_unique($refundable);
		return $refundable;
	}
	
	public function cancel($SN)
	{
		$app = $this->get_card_application_list(array("serial_no"=>$SN))->row_array();
		if(!$app){
			throw new Exception("無此申請單",ERROR_CODE);
		}
		
		//在核發或補發狀態下並已指定卡號
		if(!empty($app['card_num']) && ($app['type']=='apply' || $app['type']=='reissue'))
		{
			//取得退卡的卡號，若與現在卡號相同，則解除綁定
			$user_profile = $this->user_model->get_user_profile_by_ID($app['user_ID']);
			if($app['card_num'] == $user_profile['card_num'])
			{
				//把卡號從帳號綁定移除
				$this->user_model->update_user_card_num($app['user_ID']);
			}
		}
		
		$this->update_card_application(array(
			"canceled_by"=>$this->session->userdata('ID'),
			"checkpoint"=>"Canceled",
			"serial_no"=>$app['serial_no']
		));
		
		//取得管理員mail
		$this->load->model('access_model');
		$admins = $this->access_model->get_privilege_list(array("privilege"=>"access_super_admin"))->result_array();
		$admin_emails = sql_column_to_key_value_array($admins,"admin_email");
		
		//發送通知
		$this->email->to($app['user_email']);
		$this->email->cc($admin_emails);
		$this->email->subject("成大微奈米科技研究中心 -磁卡申請退件通知-");
		$this->email->message("
			{$app['user_name']} 您好：<br>
			您的磁卡申請編號 {$app['serial_no']} 已退件，有任何問題歡迎電洽本中心，謝謝。
		");
		$this->email->send();
	}
}