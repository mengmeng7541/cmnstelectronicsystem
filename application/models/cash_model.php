<?php
class Cash_model extends MY_Model {
	protected $cash_db;

	public function __construct()
	{
		parent::__construct();
		$this->cash_db = $this->load->database("cash",TRUE);
	}
	
	//--------------------------PRIVILEGE-------------------------
	
	//--------------------RECEIPT-------------------------
	public function add_receipt($data)
	{
		$this->cash_db->set("receipt_type",$data['receipt_type']);
		$this->cash_db->set("receipt_ID",$data['receipt_ID']);
		$this->cash_db->set("receipt_title",$data['receipt_title']);
		$this->cash_db->set("receipt_opened_by",$data['receipt_opened_by']);
		$this->cash_db->set("receipt_opened_time",$data['receipt_opened_time']);
		if(isset($data['receipt_contact_name']))
			$this->cash_db->set("receipt_contact_name",$data['receipt_contact_name']);
		if(isset($data['receipt_contact_tel']))
			$this->cash_db->set("receipt_contact_tel",$data['receipt_contact_tel']);
		if(isset($data['receipt_contact_email']))
			$this->cash_db->set("receipt_contact_email",$data['receipt_contact_email']);
		if(isset($data['receipt_contact_address']))
			$this->cash_db->set("receipt_contact_address",$data['receipt_contact_address']);
		if(isset($data['receipt_delivery_way']))
			$this->cash_db->set("receipt_delivery_way",$data['receipt_delivery_way']);
		$this->cash_db->set("receipt_account",$data['receipt_account']);
		$this->cash_db->set("receipt_checkpoint",$data['receipt_checkpoint']);
		$this->cash_db->insert("cash_receipt");
		return $this->cash_db->insert_id();
	}
	public function update_receipt($data)
	{
		$this->cash_db->set("receipt_delivered_by",$data['receipt_delivered_by']);
		$this->cash_db->set("receipt_delivery_time",$data['receipt_delivery_time']);
		$this->cash_db->set("receipt_remark",$data['receipt_remark']);
		$this->cash_db->set("receipt_checkpoint",$data['receipt_checkpoint']);
		$this->cash_db->where("receipt_no",$data['receipt_no']);
		$this->cash_db->update("cash_receipt");
	}
	//-------------------BILL----------------------------
	public function get_bill_list()
	{
		
	}
	public function add_bill($data)
	{
		$this->cash_db->set("bill_type",$data['bill_type']);
		$this->cash_db->set("bill_ID",$data['bill_ID']);
		$this->cash_db->set("bill_org",$data['bill_org']);
		$this->cash_db->set("bill_boss",$data['bill_boss']);
		$this->cash_db->set("bill_amount_original",$data['bill_amount_original']);//理論上這兩個可以算出來，先為了配合抵扣系統做考量
		$this->cash_db->set("bill_discount_percent",$data['bill_discount_percent']);
		$this->cash_db->set("bill_amount_receivable",$data['bill_amount_receivable']);
		$this->cash_db->set("bill_time",$data['bill_time']);
		$this->cash_db->insert("cash_bill");
		return $this->cash_db->insert_id();
	}
	
	//----------------------ACCOUNT--------------------------
	public function add_account($data)
	{
		$this->cash_db->set("account_boss",$data['account_boss']);
		$this->cash_db->set("account_type",$data['account_type']);
		$this->cash_db->set("account_amount",$data['account_amount']);
		$this->cash_db->set("account_opened_by",$data['account_opened_by']);
		$this->cash_db->set("account_opening_time",$data['account_opening_time']);
		$this->cash_db->set("account_start_time",$data['account_start_time']);
		$this->cash_db->insert("cash_account");
		return $this->cash_db->insert_id();
	}
	//-----------------------ACCOUNT BILL MAPPING-----------------
	public function add_account_bill_map($data)
	{
		$this->cash_db->set("account_no",$data['account_no']);
		$this->cash_db->set("bill_no",$data['bill_no']);
		$this->cash_db->set("amount_transacted",$data['amount_transacted']);
		if(isset($data['transacted_by']))
			$this->cash_db->set("transacted_by",$data['transacted_by']);
		$this->cash_db->set("transaction_time",$data['transaction_time']);
		$this->cash_db->insert("cash_account_bill_map");
	}
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
			{$sJoinTable['user']}.email AS user_email,
			{$sJoinTable['user']}.mobile AS user_mobile,
			{$sJoinTable['user']}.address AS user_address,
			{$sJoinTable['user']}.boss_no AS user_boss_no,
			{$sJoinTable['user']}.organization AS user_org_no,
			{$sJoinTable['org']}.name AS org_name,
			{$sJoinTable['org']}.status_ID AS org_status_ID,
			IF({$sJoinTable['org']}.status_ID='academia',IF(reg_table.reg_confirmed_by IS NULL,price_table.Price_Count*0.5,price_table.Price_Count),IF(reg_table.reg_confirmed_by IS NULL,price_table.Price_Count_Ent*0.5,price_table.Price_Count_Ent)) AS bill_amount,
			IFNULL({$sJoinTable['aliance']}.discount_percent/10,1) AS bill_discount_percent,
			SUM({$sJoinTable['ab_map']}.amount_transacted) AS bill_amount_received
			FROM
			 (SELECT $sTable.* FROM $sTable LEFT JOIN  {$sJoinTable['class']} ON {$sJoinTable['class']}.class_ID = $sTable.class_ID ORDER BY {$sJoinTable['class']}.class_type ASC ) reg_table
		",FALSE);
		$this->cash_db->where("reg_table.reg_canceled_by",NULL);
		
		
		$this->cash_db->join("(SELECT * FROM {$sJoinTable['class']} ) class_table","class_table.class_ID = reg_table.class_ID","LEFT");
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
