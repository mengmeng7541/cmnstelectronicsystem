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
}