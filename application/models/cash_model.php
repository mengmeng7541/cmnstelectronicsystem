<?php
class Cash_model extends MY_Model {
	protected $cash_db;

	public function __construct()
	{
		parent::__construct();
		$this->cash_db = $this->load->database("cash",TRUE);
	}
	
	//--------------------------PRIVILEGE-------------------------
	//----------------------CURRICULUM--------------------------
	public function get_curriculum_list($options = array()){
		$sTable = "cmnst_curriculum.class_registration";
		$sJoinTable = array(
			"ab_map"=>"cash_account_bill_map",
			"lesson"=>"cmnst_curriculum.lesson_list",
			"class"=>"cmnst_curriculum.class_list",
			"course"=>"cmnst_curriculum.course_list",
			"user"=>"cmnst_common.user_profile",
			"org"=>"cmnst_common.organization",
			"aliance"=>"cmnst_accounting.aliance_discount",
			"price"=>"cmnst_report.Course_Price_List",
			"bill"=>"cash_bill",
			"receipt"=>"cash_receipt"
		);
		
		$this->cash_db->select("
			reg_table.reg_ID,
			reg_table.user_ID,
			reg_table.class_ID,
			reg_table.reg_by,
			reg_table.reg_time,
			reg_table.reg_canceled_by,
			reg_table.reg_cancel_time,
			reg_table.reg_confirmed_by,
			reg_table.reg_confirmation_time,
			reg_table.reg_certified_by,
			reg_table.reg_certification_time,
			reg_table.reg_state AS reg_state,
			MIN(reg_table.reg_rank) AS reg_rank,
			class_table.class_code,
			GROUP_CONCAT(DISTINCT(class_table.class_type) ORDER BY class_table.class_type ASC) AS class_type,
			MIN(class_table.class_max_participants) AS class_max_participants,
			class_table.class_state,
			{$sJoinTable['course']}.course_ID,
			{$sJoinTable['course']}.course_cht_name,
			{$sJoinTable['course']}.course_eng_name,
			lesson_table.lesson_start_time AS lesson_start_time,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['org']}.status_ID AS org_status_ID,
			IF({$sJoinTable['org']}.status_ID='academia',price_table.Price_Count,price_table.Price_Count_Ent) AS bill_fee,
			IFNULL({$sJoinTable['aliance']}.discount_percent/10,1) AS bill_discount_percent,
			SUM({$sJoinTable['ab_map']}.amount_paid) AS bill_amount_paid
			FROM
			 (SELECT $sTable.* FROM $sTable LEFT JOIN  {$sJoinTable['class']} ON {$sJoinTable['class']}.class_ID = $sTable.class_ID ORDER BY {$sJoinTable['class']}.class_code ASC ) reg_table
		",FALSE);
		$this->cash_db->where("reg_table.reg_canceled_by",NULL);
		
		
		$this->cash_db->join("(SELECT * FROM {$sJoinTable['class']} ORDER BY {$sJoinTable['class']}.class_type DESC) class_table","class_table.class_ID = reg_table.class_ID","LEFT");
		$this->cash_db->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = class_table.course_ID","LEFT");
		$this->cash_db->join("(SELECT * FROM {$sJoinTable['lesson']} ORDER BY {$sJoinTable['lesson']}.lesson_start_time ASC) lesson_table","lesson_table.class_ID = class_table.class_ID","LEFT");
		
		$this->cash_db->group_by("class_table.course_ID,class_table.class_code,reg_table.user_ID");
		$this->cash_db->order_by("{$sJoinTable['course']}.course_ID","ASC");
		$this->cash_db->order_by("class_table.class_code","ASC");
		$this->cash_db->order_by("class_table.class_type","ASC");
		$this->cash_db->order_by("reg_table.class_ID","ASC");
		$this->cash_db->order_by("reg_table.reg_time","ASC");
		
		$this->cash_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = reg_table.user_ID","LEFT");
		$this->cash_db->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = {$sJoinTable['user']}.organization","LEFT");
		$this->cash_db->join($sJoinTable['aliance'],"{$sJoinTable['aliance']}.aliance_type = {$sJoinTable['org']}.aliance_no AND {$sJoinTable['aliance']}.discount_type = 1 AND DATE(lesson_table.lesson_start_time) BETWEEN DATE({$sJoinTable['aliance']}.discount_start) AND DATE({$sJoinTable['aliance']}.discount_end)","LEFT");//課程discount_type=1
		//AND DATE(lesson_table.lesson_start_time) >= {$sJoinTable['aliance']}.discount_start AND DATE(lesson_table.lesson_start_time) <= {$sJoinTable['aliance']}.discount_end
		$this->cash_db->join($sJoinTable['bill'],"{$sJoinTable['bill']}.bill_ID = reg_table.reg_ID AND {$sJoinTable['bill']}.bill_type = 'curriculum'","LEFT");
		$this->cash_db->join($sJoinTable['ab_map'],"{$sJoinTable['ab_map']}.bill_no = {$sJoinTable['bill']}.bill_no","LEFT");
		if(isset($options['class_code']))
		{
			$this->cash_db->like("class_table.class_code",$options['class_code'],'after');
		}
		
		//price
		$this->cash_db->join("(SELECT * FROM {$sJoinTable['price']} ORDER BY Price_Start_Date DESC) price_table","price_table.Course_ID = class_table.course_ID AND price_table.Price_Start_Date <= lesson_table.lesson_start_time AND ((class_table.class_type='certification' AND price_table.Is_Certification = 1) OR (class_table.class_type!='certification' AND price_table.Is_Certification = 0))","LEFT");
		
		if(isset($options['reg_ID']))
		{
			$this->cash_db->where_in("reg_table.reg_ID",$options['reg_ID']);
		}
		
		return $this->cash_db->get();
	}
}
