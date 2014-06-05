<?php
class Cash_model extends MY_Model {
	protected $cash_db;

	public function __construct()
	{
		parent::__construct();
		$this->cash_db = $this->load->database("cash",TRUE);
	}
	
	//--------------------------PRIVILEGE-------------------------
	public function is_super_admin($admin_ID = "")
	{
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		return $this->get_admin_privilege_list(array("admin_ID"=>$admin_ID,"privilege"=>"cash_super_admin"))->num_rows();
	}
	public function get_admin_privilege_list($options = array())
	{
		if(isset($options['admin_ID']))
			$this->cash_db->where("admin_ID",$options['admin_ID']);
		if(isset($options['privilege']))
			$this->cash_db->where("privilege",$options['privilege']);
		return $this->cash_db->get("cash_admin_privilege");
	}
	//--------------------RECEIPT-------------------------
	public function get_receipt_list($options = array())
	{
		$sTable = "cash_receipt";
		$sJoinTable = array("account"=>"cash_account");
		
		$this->cash_db->select("
			$sTable.*,
			{$sJoinTable['account']}.account_amount AS receipt_amount
		");
		$this->cash_db->join($sJoinTable['account'],"{$sJoinTable['account']}.account_no = $sTable.receipt_account","LEFT");
		
		if(isset($options['receipt_no']))
		{
			$this->cash_db->where("$sTable.receipt_no",$options['receipt_no']);
		}
		
		return $this->cash_db->get($sTable);	
	}
	public function add_receipt($data)
	{
		$this->cash_db->set("receipt_type",$data['receipt_type']);
		if(isset($data['receipt_ID']))
		{
			$this->cash_db->set("receipt_ID",$data['receipt_ID']);
		}
		$this->cash_db->set("receipt_title",$data['receipt_title']);
		$this->cash_db->set("receipt_initialized_by",$data['receipt_initialized_by']);
		$this->cash_db->set("receipt_initialization_time",$data['receipt_initialization_time']);
		if(isset($data['receipt_contact_name']))
			$this->cash_db->set("receipt_contact_name",$data['receipt_contact_name']);
		if(isset($data['receipt_contact_tel']))
			$this->cash_db->set("receipt_contact_tel",$data['receipt_contact_tel']);
		if(isset($data['receipt_contact_email']))
			$this->cash_db->set("receipt_contact_email",$data['receipt_contact_email']);
		if(isset($data['receipt_contact_address']))
			$this->cash_db->set("receipt_contact_address",$data['receipt_contact_address']);
		if(isset($data['receipt_note']))
			$this->cash_db->set("receipt_note",$data['receipt_note']);
		if(isset($data['receipt_delivery_way']))
			$this->cash_db->set("receipt_delivery_way",$data['receipt_delivery_way']);
		$this->cash_db->set("receipt_account",$data['receipt_account']);
		$this->cash_db->set("receipt_checkpoint",$data['receipt_checkpoint']);
		$this->cash_db->insert("cash_receipt");
		return $this->cash_db->insert_id();
	}
	public function update_receipt($data)
	{
		if(isset($data['receipt_ID']))
		{
			$this->cash_db->set("receipt_ID",$data['receipt_ID']);
		}
		if(isset($data['receipt_opened_by']))
		{
			$this->cash_db->set("receipt_opened_by",$data['receipt_opened_by']);
			$this->cash_db->set("receipt_opening_time",$data['receipt_opening_time']);
		}
		if(isset($data['receipt_delivered_by']))
		{
			$this->cash_db->set("receipt_delivered_by",$data['receipt_delivered_by']);
			$this->cash_db->set("receipt_delivery_time",$data['receipt_delivery_time']);
		}
		if(isset($data['receipt_remark']))
		{
			$this->cash_db->set("receipt_remark",$data['receipt_remark']);
		}
		if(isset($data['receipt_checkpoint']))
		{
			$this->cash_db->set("receipt_checkpoint",$data['receipt_checkpoint']);
		}
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
	public function get_curriculum_bill_list($options = array()){
		$sTable = "cmnst_curriculum.class_registration";
		$sJoinTable = array(
			"ab_map"=>"cmnst_cash.cash_account_bill_map",
			"lesson"=>"cmnst_curriculum.lesson_list",
			"class"=>"cmnst_curriculum.class_list",
			"course"=>"cmnst_curriculum.course_list",
			"user"=>"cmnst_common.user_profile",
			"org"=>"cmnst_common.organization",
			"aliance"=>"cmnst_accounting.aliance_discount",
			"price"=>"cmnst_report.Course_Price_List",
			"bill"=>"cmnst_cash.cash_bill",
			"receipt"=>"cmnst_cash.cash_receipt"
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
			{$sJoinTable['class']}.class_code,
			GROUP_CONCAT(DISTINCT({$sJoinTable['class']}.class_type) ORDER BY {$sJoinTable['class']}.class_type ASC) AS class_type,
			MIN({$sJoinTable['class']}.class_max_participants) AS class_max_participants,
			{$sJoinTable['class']}.class_state,
			{$sJoinTable['course']}.course_ID,
			{$sJoinTable['course']}.course_cht_name,
			{$sJoinTable['course']}.course_eng_name,
			MIN({$sJoinTable['lesson']}.lesson_start_time) AS lesson_start_time,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['user']}.email AS user_email,
			{$sJoinTable['user']}.mobile AS user_mobile,
			{$sJoinTable['user']}.address AS user_address,
			{$sJoinTable['user']}.boss_no AS user_boss_no,
			{$sJoinTable['user']}.organization AS user_org_no,
			{$sJoinTable['org']}.name AS org_name,
			{$sJoinTable['org']}.status_ID AS org_status_ID,
			'curriculum' AS bill_type,
			reg_table.reg_ID AS bill_ID,
			IF({$sJoinTable['org']}.status_ID='academia',IF(reg_table.reg_confirmed_by IS NULL,price_table.Price_Count*0.5,price_table.Price_Count),IF(reg_table.reg_confirmed_by IS NULL,price_table.Price_Count_Ent*0.5,price_table.Price_Count_Ent)) AS bill_amount,
			IFNULL({$sJoinTable['aliance']}.discount_percent/10,1) AS bill_discount_percent,
			SUM({$sJoinTable['ab_map']}.amount_transacted) AS bill_amount_received,
			GROUP_CONCAT({$sJoinTable['receipt']}.receipt_ID) AS receipt_ID,
			{$sJoinTable['receipt']}.receipt_delivery_way AS receipt_delivery_way,
			{$sJoinTable['receipt']}.receipt_checkpoint AS receipt_checkpoint
			FROM
			 (SELECT $sTable.* FROM $sTable LEFT JOIN  {$sJoinTable['class']} ON {$sJoinTable['class']}.class_ID = $sTable.class_ID ORDER BY {$sJoinTable['class']}.class_type ASC ) reg_table
		",FALSE);
		$this->cash_db->where("reg_table.reg_canceled_by",NULL);
		
		
		$this->cash_db->join($sJoinTable['class'],"{$sJoinTable['class']}.class_ID = reg_table.class_ID","LEFT");
		$this->cash_db->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = {$sJoinTable['class']}.course_ID","LEFT");
		$this->cash_db->join($sJoinTable['lesson'],"{$sJoinTable['lesson']}.class_ID = {$sJoinTable['class']}.class_ID","LEFT");
		
		$this->cash_db->group_by("{$sJoinTable['class']}.course_ID,{$sJoinTable['class']}.class_code,reg_table.user_ID");
		$this->cash_db->order_by("{$sJoinTable['course']}.course_ID","ASC");
		$this->cash_db->order_by("{$sJoinTable['class']}.class_code","ASC");
		$this->cash_db->order_by("{$sJoinTable['class']}.class_type","ASC");
		$this->cash_db->order_by("reg_table.class_ID","ASC");
		$this->cash_db->order_by("reg_table.reg_time","ASC");
		
		$this->cash_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = reg_table.user_ID","LEFT");
		$this->cash_db->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = {$sJoinTable['user']}.organization","LEFT");
		$this->cash_db->join($sJoinTable['aliance'],"{$sJoinTable['aliance']}.aliance_type = {$sJoinTable['org']}.aliance_no AND {$sJoinTable['aliance']}.discount_type = 1 AND DATE({$sJoinTable['lesson']}.lesson_start_time) BETWEEN DATE({$sJoinTable['aliance']}.discount_start) AND DATE({$sJoinTable['aliance']}.discount_end)","LEFT");//課程discount_type=1
		$this->cash_db->join($sJoinTable['bill'],"{$sJoinTable['bill']}.bill_ID = reg_table.reg_ID AND {$sJoinTable['bill']}.bill_type = 'curriculum'","LEFT");
		$this->cash_db->join($sJoinTable['ab_map'],"{$sJoinTable['ab_map']}.bill_no = {$sJoinTable['bill']}.bill_no","LEFT");
		$this->cash_db->join($sJoinTable['receipt'],"{$sJoinTable['receipt']}.receipt_account = {$sJoinTable['ab_map']}.account_no","LEFT");
		if(isset($options['class_code']))
		{
			$this->cash_db->like("{$sJoinTable['class']}.class_code",$options['class_code'],'after');
		}
		
		//price
		$this->cash_db->join("(SELECT * FROM {$sJoinTable['price']} ORDER BY Price_Start_Date DESC) price_table","price_table.Course_ID = {$sJoinTable['class']}.course_ID AND price_table.Price_Start_Date <= {$sJoinTable['lesson']}.lesson_start_time AND (({$sJoinTable['class']}.class_type='certification' AND price_table.Is_Certification = 1) OR ({$sJoinTable['class']}.class_type!='certification' AND price_table.Is_Certification = 0))","LEFT");
		
		if(isset($options['reg_ID']))
		{
			$this->cash_db->where_in("reg_table.reg_ID",$options['reg_ID']);
		}
		
		$q = $this->cash_db->get();
		
//		echo $this->cash_db->last_query();
		return $q;
	}
	//--------------------NANOMARK------------------
	public function get_nanomark_bill_list($options = array())
	{
		$sTable = "cmnst_nanomark.Nanomark_application";
		$sJoinTable = array(
			"ab_map"=>"cmnst_cash.cash_account_bill_map",
			"specimen"=>"cmnst_nanomark.Nanomark_specimen",
			"user"=>"cmnst_common.user_profile",
			"org"=>"cmnst_common.organization",
			"aliance"=>"cmnst_accounting.aliance_discount",
			"bill"=>"cmnst_cash.cash_bill",
			"receipt"=>"cmnst_cash.cash_receipt"
		);
		
		$this->cash_db->select("
			$sTable.*,
			specimen_table.name AS specimen_name,
			{$sJoinTable['user']}.name AS user_name,
			{$sJoinTable['user']}.email AS user_email,
			{$sJoinTable['user']}.mobile AS user_mobile,
			{$sJoinTable['user']}.address AS user_address,
			{$sJoinTable['user']}.boss_no AS user_boss_no,
			{$sJoinTable['user']}.organization AS user_org_no,
			{$sJoinTable['org']}.name AS org_name,
			{$sJoinTable['org']}.status_ID AS org_status_ID,
			'nanomark' AS bill_type,
			IFNULL({$sJoinTable['aliance']}.discount_percent/10,1) AS bill_discount_percent,
			SUM({$sJoinTable['ab_map']}.amount_transacted) AS bill_amount_received,
			GROUP_CONCAT({$sJoinTable['receipt']}.receipt_ID) AS receipt_ID,
			{$sJoinTable['receipt']}.receipt_delivery_way AS receipt_delivery_way,
			{$sJoinTable['receipt']}.receipt_checkpoint AS receipt_checkpoint
		",FALSE);
		$this->cash_db->from($sTable);
		$this->cash_db->join("(SELECT application_SN,GROUP_CONCAT(name) AS name FROM {$sJoinTable['specimen']} GROUP BY application_SN) specimen_table","specimen_table.application_SN = $sTable.serial_no","LEFT");
		$this->cash_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.applicant_ID","LEFT");
		$this->cash_db->join($sJoinTable['org'],"{$sJoinTable['org']}.serial_no = {$sJoinTable['user']}.organization","LEFT");
		$this->cash_db->join($sJoinTable['bill'],"{$sJoinTable['bill']}.bill_ID = $sTable.serial_no AND {$sJoinTable['bill']}.bill_type = 'nanomark'","LEFT");
		$this->cash_db->join($sJoinTable['ab_map'],"{$sJoinTable['ab_map']}.bill_no = {$sJoinTable['bill']}.bill_no","LEFT");
		$this->cash_db->join($sJoinTable['receipt'],"{$sJoinTable['receipt']}.receipt_account = {$sJoinTable['ab_map']}.account_no","LEFT");
		
		$this->cash_db->join($sJoinTable['aliance'],"{$sJoinTable['aliance']}.aliance_type = {$sJoinTable['org']}.aliance_no AND {$sJoinTable['aliance']}.discount_type = 4 AND DATE($sTable.application_date) BETWEEN DATE({$sJoinTable['aliance']}.discount_start) AND DATE({$sJoinTable['aliance']}.discount_end)","LEFT");//標章discount_type=4
		$this->cash_db->group_by("$sTable.serial_no");
		
		if(isset($options['application_SN']))
		{
			$this->cash_db->where_in("$sTable.serial_no",$options['application_SN']);
		}
		
		return $this->cash_db->get();
	}
}
