<?php
class Curriculum_model extends MY_Model {
	
	protected $curriculum_db;
	protected $common_db;

	public function __construct()
	{
		parent::__construct();
		$this->curriculum_db = $this->load->database('curriculum',TRUE);
		$this->common_db = $this->load->database('common',TRUE);
	}
	//course
	public function get_course_list($options = array())
	{
		$sTable = "course_list";
//		$sJoinTable = array("cf_map"=>"course_facility_map","facility"=>"cmnst_facility.facility_list");
		$this->curriculum_db->select(	"$sTable.*");
		if(!empty($options['course_ID']))
			$this->curriculum_db->where("$sTable.course_ID",$options['course_ID']);
		return $this->curriculum_db->get($sTable);
	}
	public function add_course($data = array())
	{
		return $this->update_course($data);	
	}
	public function update_course($data = array())
	{
		$this->curriculum_db->set("course_cht_name",$data['course_cht_name']);
		$this->curriculum_db->set("course_eng_name",$data['course_eng_name']);
		$this->curriculum_db->set("course_min_participants",$data['course_min_participants']);
		$this->curriculum_db->set("course_max_participants",$data['course_max_participants']);
		
		
		if(empty($data['course_ID'])){
			$this->curriculum_db->insert("course_list");
			return $this->curriculum_db->insert_id();
		}else{
			$this->curriculum_db->where("course_ID",$data['course_ID']);
			$this->curriculum_db->update("course_list");
			return $this->curriculum_db->affected_rows();
		}
	}
	public function del_course($data)
	{
		$this->curriculum_db->where("course_ID",$data['course_ID']);
		$this->curriculum_db->delete("course_list");
		return $this->curriculum_db->affected_rows();
	}
	//course_facility_map
	public function get_course_facility_map($options = array())
	{
		$sTable = "course_facility_map";
		if(!empty($options['course_ID']))
			$this->curriculum_db->where("course_ID",$options['course_ID']);
		return $this->curriculum_db->get($sTable);
	}
	public function add_course_facility_map($data)
	{
		if(is_array(current($data)))
		{
			$this->curriculum_db->insert_batch("course_facility_map",$data);
		}else if(!empty($data)){
			$this->curriculum_db->set("course_ID",$data['course_ID']);
			$this->curriculum_db->set("facility_ID",$data['facility_ID']);
			$this->curriculum_db->insert("course_facility_map");
		}
	}
	public function del_course_facility_map($data = array())
	{
		$this->curriculum_db->where("course_ID",$data['course_ID']);
		$this->curriculum_db->delete("course_facility_map");
	}
	public function get_pre_course_list($options = array())
	{
		if(isset($options['course_ID']))
		$this->curriculum_db->where("course_ID",$options['course_ID']);
		return $this->curriculum_db->get("course_prerequisite");
	}
	public function add_pre_course($data)
	{
		if(is_array(current($data)))
		{
			$this->curriculum_db->insert_batch("course_prerequisite",$data);
		}else if(!empty($data)){
			if(!empty($data['course_ID']))
			{
				$this->curriculum_db->set("course_ID",$data['course_ID']);
				$this->curriculum_db->set("pre_course_ID",$data['pre_course_ID']);
				$this->curriculum_db->insert("course_prerequisite");
			}else{
				throw new Exception("檔修課程選擇錯誤",ERROR_CODE);
			}
		}
	}
	public function del_pre_course($data)
	{
		$this->curriculum_db->where("course_ID",$data['course_ID']);
		$this->curriculum_db->delete("course_prerequisite");
	}
	//----------------------CLASS------------------------
	public function get_class_list($options = array())
	{
		$sTable = "class_list";
		$sJoinTable = array("course"=>"course_list","lesson"=>"lesson_list","user"=>"cmnst_common.user_profile","reg"=>"class_registration","location"=>"cmnst_common.location","state"=>"cmnst_curriculum.enum_class_state");
		if(isset($options['group_class_suite'])&&$options['group_class_suite']==TRUE)
		{
			$sSelect = "
				$sTable.class_ID,
				$sTable.course_ID,
				$sTable.class_code,
				GROUP_CONCAT(DISTINCT $sTable.class_type) AS class_type,
				$sTable.class_min_participants,
				MIN($sTable.class_max_participants) AS class_max_participants,
				$sTable.class_location,
				$sTable.class_reg_start_time,
				$sTable.class_reg_end_time,
				$sTable.class_reg_end_time_auto,
				$sTable.class_state,
				{$sJoinTable['state']}.state_name AS class_state_name,
				$sTable.class_remark,
				{$sJoinTable['course']}.course_cht_name,
				{$sJoinTable['course']}.course_eng_name,
				MIN({$sJoinTable['lesson']}.lesson_start_time) AS class_start_time,
				MAX({$sJoinTable['lesson']}.lesson_end_time) AS class_end_time,
				SUM(UNIX_TIMESTAMP({$sJoinTable['lesson']}.lesson_end_time)-UNIX_TIMESTAMP({$sJoinTable['lesson']}.lesson_start_time)) AS class_total_secs,
				{$sJoinTable['user']}.name AS prof_name,
				{$sJoinTable['location']}.location_cht_name AS location_cht_name
			";
		}else{
			$sSelect = "
				$sTable.*,
				{$sJoinTable['state']}.state_name AS class_state_name,
				{$sJoinTable['course']}.course_cht_name,
				{$sJoinTable['course']}.course_eng_name,
				MIN({$sJoinTable['lesson']}.lesson_start_time) AS class_start_time,
				MAX({$sJoinTable['lesson']}.lesson_end_time) AS class_end_time,
				SUM(UNIX_TIMESTAMP({$sJoinTable['lesson']}.lesson_end_time)-UNIX_TIMESTAMP({$sJoinTable['lesson']}.lesson_start_time)) AS class_total_secs,
				{$sJoinTable['user']}.name AS prof_name,
				{$sJoinTable['location']}.location_cht_name AS location_cht_name
			";
		}
		$this->curriculum_db->select($sSelect,FALSE);
		$this->curriculum_db->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = $sTable.course_ID","LEFT")
							->join($sJoinTable['lesson'],"{$sJoinTable['lesson']}.class_ID = $sTable.class_ID","LEFT")
							
							->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = {$sJoinTable['lesson']}.lesson_prof_ID","LEFT")
							->join($sJoinTable['location'],"{$sJoinTable['location']}.location_ID = $sTable.class_location","LEFT")
							->join($sJoinTable['state'],"{$sJoinTable['state']}.state_ID = $sTable.class_state","LEFT");
		if(isset($options['course_ID']))
			$this->curriculum_db->where("$sTable.course_ID",$options['course_ID']);
		if(isset($options['class_ID']))
			$this->curriculum_db->where("$sTable.class_ID",$options['class_ID']);
		if(isset($options['class_code']))
			$this->curriculum_db->like("$sTable.class_code",$options['class_code'],'after');
		if(isset($options['class_type']))
			$this->curriculum_db->where("$sTable.class_type",$options['class_type']);
		if(isset($options['class_state']))
		{
			$this->curriculum_db->where("$sTable.class_state",$options['class_state']);
		}
		$this->curriculum_db->order_by("$sTable.course_ID","ASC");
		$this->curriculum_db->order_by("$sTable.class_code","ASC");
		$this->curriculum_db->order_by("$sTable.class_type","ASC");
		$this->curriculum_db->order_by("{$sJoinTable['lesson']}.lesson_start_time","ASC");
		if(isset($options['class_reg_start_time']))
			$this->curriculum_db->where("class_reg_end_time >=",$options['class_reg_start_time']);
		if(isset($options['class_reg_end_time']))
			$this->curriculum_db->where("class_reg_start_time <=",$options['class_reg_end_time']);
		if(isset($options['class_start_time']))
			$this->curriculum_db->having("class_end_time >=",$options['class_start_time']);
		if(isset($options['class_end_time']))
			$this->curriculum_db->having("class_start_time <=",$options['class_end_time']);
		
		//用日期查詢
		if(isset($options['class_start_date']))
		{
			$this->curriculum_db->having("DATE(class_start_time)",$options['class_start_date']);
		}
		if(isset($options['class_reg_end_date']))
		{
			$this->curriculum_db->where("DATE(class_reg_end_time)",$options['class_reg_end_date']);
		}
		
		if(isset($options['group_class_suite'])&&$options['group_class_suite']==TRUE)
		{
			$this->curriculum_db->group_by("$sTable.course_ID,$sTable.class_code");
		}else{
			$this->curriculum_db->group_by("$sTable.class_ID");
		}
		
		return $this->curriculum_db->get($sTable);
	}
	public function add_class($data)
	{
		return $this->update_class($data);
	}
	public function update_class($data)
	{
		if(isset($data['course_ID']))
			$this->curriculum_db->set("course_ID",$data['course_ID']);
		if(isset($data['class_type']))
			$this->curriculum_db->set("class_type",$data['class_type']);
		if(isset($data['class_min_participants']))
			$this->curriculum_db->set("class_min_participants",$data['class_min_participants']);
		if(isset($data['class_max_participants']))
			$this->curriculum_db->set("class_max_participants",$data['class_max_participants']);
		if(isset($data['class_code']))
			$this->curriculum_db->set("class_code",$data['class_code']);
		if(isset($data['class_reg_start_time']))
			$this->curriculum_db->set("class_reg_start_time",$data['class_reg_start_time']);
		if(isset($data['class_reg_end_time']))
			$this->curriculum_db->set("class_reg_end_time",$data['class_reg_end_time']);
		if(isset($data['class_reg_end_time_auto']))
			$this->curriculum_db->set("class_reg_end_time_auto",$data['class_reg_end_time_auto']);
		if(isset($data['class_location']))
			$this->curriculum_db->set("class_location",$data['class_location']);
		if(isset($data['class_state']))
			$this->curriculum_db->set("class_state",$data['class_state']);
		if(isset($data['class_remark']))
			$this->curriculum_db->set("class_remark",$data['class_remark']);
		if(empty($data['class_ID']))
		{
			$this->curriculum_db->insert("class_list");
			return $this->curriculum_db->insert_id();
		}else{
			$this->curriculum_db->where("class_ID",$data['class_ID']);
			$this->curriculum_db->update("class_list");
			return $this->curriculum_db->affected_rows();
		}
		
	}
	public function del_class($data)
	{
		$this->curriculum_db->where("class_ID",$data['class_ID']);
		$this->curriculum_db->delete("class_list");
	}
	//-------------------------LESSON------------------------
	public function get_lesson_list($options = array())
	{
		$sTable = "lesson_list";
		$sJoinTable = array("class"=>"class_list","course"=>"course_list","user"=>"cmnst_common.user_profile","location"=>"cmnst_common.location");
		$this->curriculum_db->select("$sTable.lesson_ID AS lesson_ID,
									  $sTable.class_ID AS class_ID,
									  $sTable.lesson_prof_ID AS lesson_prof_ID,
									  $sTable.lesson_start_time AS lesson_start_time,
									  $sTable.lesson_end_time AS lesson_end_time,
									  $sTable.lesson_comment AS lesson_comment,
									  {$sJoinTable['class']}.course_ID AS course_ID,
									  {$sJoinTable['class']}.class_type AS lesson_type,
									  {$sJoinTable['course']}.course_cht_name AS course_cht_name,
									  {$sJoinTable['course']}.course_eng_name AS course_eng_name,
									  {$sJoinTable['user']}.name AS lesson_prof_name,
									  {$sJoinTable['location']}.location_cht_name AS location_cht_name");
		$this->curriculum_db->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.lesson_prof_ID","LEFT")
							->join($sJoinTable['class'],"{$sJoinTable['class']}.class_ID = $sTable.class_ID","LEFT")
							->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = {$sJoinTable['class']}.course_ID","LEFT")
							->join($sJoinTable['location'],"{$sJoinTable['location']}.location_ID = {$sJoinTable['class']}.class_location","LEFT");
		if(isset($options['course_ID']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.course_ID",$options['course_ID']);
		}
		if(isset($options['class_ID']))
		{
			$this->curriculum_db->where("$sTable.class_ID",$options['class_ID']);
		}
			
		if(isset($options['lesson_ID']))
		{
			$this->curriculum_db->where("$sTable.lesson_ID",$options['lesson_ID']);
		}
		if(isset($options['lesson_type']))
		{
			$this->curriculum_db->like("{$sJoinTable['class']}.class_type",$options['lesson_type']);
		}
		if(isset($options['class_code']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.class_code",$options['class_code']);
		}
		if(isset($options['class_state']))
		{
			$this->curriculum_db->where_in("{$sJoinTable['class']}.class_state",$options['class_state']);
		}
		if(isset($options['lesson_start_time']))
		{
			$this->curriculum_db->where("$sTable.lesson_end_time >=",$options['lesson_start_time']);
		}
		if(isset($options['lesson_end_time']))
		{
			$this->curriculum_db->where("$sTable.lesson_start_time <=",$options['lesson_end_time']);
		}
		return $this->curriculum_db->get($sTable);
	}
	public function get_lesson_booking_map($options = array())
	{
		$sTable = "lesson_booking_map";
		$sJoinTable = array("booking"=>"cmnst_facility.facility_booking","facility"=>"cmnst_facility.facility_list","user"=>"cmnst_common.user_profile","lesson"=>"lesson_list");
		$this->curriculum_db->select("	$sTable.lesson_ID AS lesson_ID,
										$sTable.booking_ID AS booking_ID,
										$sTable.booking_state AS booking_state,
										$sTable.booking_remark AS booking_remark,
										{$sJoinTable['user']}.name AS user_name,
										{$sJoinTable['facility']}.cht_name AS facility_cht_name,
										{$sJoinTable['facility']}.eng_name AS facility_eng_name,
										{$sJoinTable['booking']}.facility_ID AS facility_ID,
										{$sJoinTable['booking']}.start_time AS booking_start_time,
										{$sJoinTable['booking']}.end_time AS booking_end_time")
							->join($sJoinTable['booking'],"{$sJoinTable['booking']}.serial_no = $sTable.booking_ID","LEFT")
							->join($sJoinTable['facility'],"{$sJoinTable['booking']}.facility_ID = {$sJoinTable['facility']}.ID","LEFT")
							->join($sJoinTable['user'],"{$sJoinTable['booking']}.user_ID = {$sJoinTable['user']}.ID","LEFT")
							->join($sJoinTable['lesson'],"{$sJoinTable['lesson']}.lesson_ID = $sTable.lesson_ID","LEFT");
		$this->curriculum_db->where("{$sJoinTable['booking']}.cancel_time",NULL);
		if(isset($options['class_ID']))
		{
			$this->curriculum_db->where("{$sJoinTable['lesson']}.class_ID",$options['class_ID']);
		}
		if(isset($options['lesson_ID']))
			$this->curriculum_db->where("$sTable.lesson_ID",$options['lesson_ID']);
		if(isset($options['booking_ID']))
			$this->curriculum_db->where("$sTable.booking_ID",$options['booking_ID']);
		if(isset($options['booking_state']))
			$this->curriculum_db->where("$sTable.booking_state",$options['booking_state']);
		return $this->curriculum_db->get($sTable);
	}
	public function add_lesson($data)
	{
		$this->curriculum_db->set("class_ID",$data['class_ID']);
		$this->curriculum_db->set("lesson_prof_ID",$data['lesson_prof_ID']);
		$this->curriculum_db->set("lesson_start_time",$data['lesson_start_time']);
		$this->curriculum_db->set("lesson_end_time",$data['lesson_end_time']);
		$this->curriculum_db->set("lesson_comment",$data['lesson_comment']);
		$this->curriculum_db->insert("lesson_list");
		return $this->curriculum_db->insert_id();
	}
	public function add_lesson_booking_map($data)
	{
		$this->curriculum_db->set("lesson_ID",$data['lesson_ID']);
		$this->curriculum_db->set("booking_ID",$data['booking_ID']);
		if(isset($data['booking_state'])){
			$this->curriculum_db->set("booking_state",$data['booking_state']);
		}
		if(isset($data['booking_remark'])){
			$this->curriculum_db->set("booking_remark",$data['booking_remark']);
		}
		$this->curriculum_db->insert("lesson_booking_map");
	}
	public function update_lesson($data)
	{
		$this->curriculum_db->set("lesson_prof_ID",$data['lesson_prof_ID']);
		$this->curriculum_db->set("lesson_start_time",$data['lesson_start_time']);
		$this->curriculum_db->set("lesson_end_time",$data['lesson_end_time']);
		$this->curriculum_db->set("lesson_comment",$data['lesson_comment']);
		$this->curriculum_db->where("lesson_ID",$data['lesson_ID']);
		$this->curriculum_db->update("lesson_list");
		return $this->curriculum_db->affected_rows();
	}
	public function del_lesson($data)
	{
		$this->curriculum_db->where("lesson_ID",$data['lesson_ID']);
		$this->curriculum_db->delete("lesson_list");
	}
	//------------------------REG--------------------------
//	public function get_reg_list($options = array())
//	{
//		$sTable = "class_registration";
//		$sJoinTable = array("course"=>"course_list","class"=>"class_list","user"=>"cmnst_common.user_profile","boss"=>"cmnst_common.boss_profile","org"=>"cmnst_common.organization");
//		
//		$this->curriculum_db->select("	$sTable.*,
//										{$sJoinTable['class']}.class_code,
//										{$sJoinTable['class']}.class_type,
//										{$sJoinTable['class']}.class_max_participants,
//										{$sJoinTable['course']}.course_ID,
//										{$sJoinTable['course']}.course_cht_name,
//										{$sJoinTable['course']}.course_eng_name")
//							->from($sTable)
//							->join($sJoinTable['class'],"{$sJoinTable['class']}.class_ID = $sTable.class_ID","LEFT")
//							->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = {$sJoinTable['class']}.course_ID","LEFT")
//							;
//		if(isset($options['user_ID']))
//			$this->curriculum_db->where("$sTable.user_ID",$options['user_ID']);
//		if(isset($options['course_ID']))
//			$this->curriculum_db->where("{$sJoinTable['class']}.course_ID",$options['course_ID']);
//		if(isset($options['class_code']))
//			$this->curriculum_db->like("{$sJoinTable['class']}.class_code",$options['class_code'],'after');
//		if(isset($options['class_ID']))
//			$this->curriculum_db->where("$sTable.class_ID",$options['class_ID']);
//		if(isset($options['reg_state']))
//			$this->curriculum_db->where("$sTable.reg_state",$options['reg_state']);
//		if(isset($options['reg_ID']))
//			$this->curriculum_db->where("$sTable.reg_ID",$options['reg_ID']);
//		$this->curriculum_db->where("reg_cancel_time",NULL);
//		$this->curriculum_db->order_by("{$sJoinTable['course']}.course_ID","ASC");
//		$this->curriculum_db->order_by("{$sJoinTable['class']}.class_code","ASC");
//		$this->curriculum_db->order_by("{$sJoinTable['class']}.class_type","ASC");
//		$this->curriculum_db->order_by("$sTable.reg_time","ASC");
//		return $this->curriculum_db->get();
//	}
	public function get_reg_list($options = array())
	{
		$sTable = "class_registration";
		$sJoinTable = array("course"=>"course_list","class"=>"class_list","user"=>"cmnst_common.user_profile","boss"=>"cmnst_common.boss_profile","org"=>"cmnst_common.organization");
		
		if(isset($options['group_class_suite'])&&$options['group_class_suite']==TRUE)
		{
			$sSelect = "
				$sTable.reg_ID,
				$sTable.user_ID,
				$sTable.class_ID,
				$sTable.reg_by,
				$sTable.reg_time,
				$sTable.reg_canceled_by,
				$sTable.reg_cancel_time,
				$sTable.reg_confirmed_by,
				$sTable.reg_confirmation_time,
				$sTable.reg_certified_by,
				$sTable.reg_certification_time,
				$sTable.reg_state AS reg_state,
				MIN($sTable.reg_rank) AS reg_rank,
				{$sJoinTable['class']}.class_code,
				GROUP_CONCAT({$sJoinTable['class']}.class_type ORDER BY {$sJoinTable['class']}.class_type ASC) AS class_type,
				MIN({$sJoinTable['class']}.class_max_participants) AS class_max_participants,
				{$sJoinTable['class']}.class_state,
				{$sJoinTable['course']}.course_ID,
				{$sJoinTable['course']}.course_cht_name,
				{$sJoinTable['course']}.course_eng_name
				FROM
				 $sTable
			";
//(SELECT IF(@cur_group<>$sTable.class_ID,@cur_rank:=1,@cur_rank:=@cur_rank+1) AS reg_rank,@cur_group:=$sTable.class_ID,$sTable.* FROM $sTable, (SELECT @cur_rank:=0,@cur_group:=null) r WHERE reg_canceled_by IS NULL ORDER BY $sTable.class_ID ASC,$sTable.reg_time ASC)
		}else{
			$sSelect = "
				$sTable.*,
				{$sJoinTable['class']}.class_code,
				{$sJoinTable['class']}.class_type,
				{$sJoinTable['class']}.class_max_participants,
				{$sJoinTable['class']}.class_state,
				{$sJoinTable['course']}.course_ID,
				{$sJoinTable['course']}.course_cht_name,
				{$sJoinTable['course']}.course_eng_name
				FROM
				 $sTable
			";
		}
//(SELECT IF(@cur_group<>$sTable.class_ID,@cur_rank:=1,@cur_rank:=@cur_rank+1) AS reg_rank,@cur_group:=$sTable.class_ID,$sTable.* FROM $sTable, (SELECT @cur_rank:=0,@cur_group:=null) r WHERE reg_canceled_by IS NULL $additional_where ORDER BY $sTable.class_ID ASC,$sTable.reg_time ASC)
		$this->curriculum_db->select($sSelect,FALSE);
		$this->curriculum_db->join("(SELECT * FROM {$sJoinTable['class']} ORDER BY {$sJoinTable['class']}.class_type DESC) {$sJoinTable['class']}","{$sJoinTable['class']}.class_ID = $sTable.class_ID","LEFT");
//		$this->curriculum_db->join($sJoinTable['class'],"{$sJoinTable['class']}.class_ID = $sTable.class_ID","LEFT");
		$this->curriculum_db->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = {$sJoinTable['class']}.course_ID","LEFT");
		$this->curriculum_db->where("$sTable.reg_canceled_by",NULL);
		if(isset($options['user_ID']))
			$this->curriculum_db->where("$sTable.user_ID",$options['user_ID']);
		if(isset($options['course_ID']))
			$this->curriculum_db->where("{$sJoinTable['class']}.course_ID",$options['course_ID']);
		if(isset($options['class_code']))
			$this->curriculum_db->like("{$sJoinTable['class']}.class_code",$options['class_code'],'after');
		if(isset($options['class_reg_start_time']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.class_reg_end_time >=",$options['class_reg_start_time']);
		}
		if(isset($options['class_reg_end_time']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.class_reg_start_time <=",$options['class_reg_end_time']);
		}
		if(isset($options['class_ID']))
			$this->curriculum_db->where("$sTable.class_ID",$options['class_ID']);
		if(isset($options['reg_state']))
			$this->curriculum_db->where_in("$sTable.reg_state",$options['reg_state']);
		if(isset($options['reg_ID']))
			$this->curriculum_db->where("$sTable.reg_ID",$options['reg_ID']);
		if(isset($options['class_type']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.class_type",$options['class_type']);
		}
		if(isset($options['class_state']))
		{
			$this->curriculum_db->where("{$sJoinTable['class']}.class_state",$options['class_state']);
		}
		if(isset($options['reg_rank_start']))
		{
			$this->curriculum_db->where("$sTable.reg_rank >=",$options['reg_rank_start']);
		}
		if(isset($options['reg_rank_end']))
		{
			$this->curriculum_db->where("$sTable.reg_rank <=",$options['reg_rank_end']);
		}
		$this->curriculum_db->order_by("{$sJoinTable['course']}.course_ID","ASC");
		$this->curriculum_db->order_by("{$sJoinTable['class']}.class_code","ASC");
		$this->curriculum_db->order_by("{$sJoinTable['class']}.class_type","ASC");
		$this->curriculum_db->order_by("$sTable.class_ID","ASC");
		$this->curriculum_db->order_by("$sTable.reg_time","ASC");
		
		if(isset($options['group_class_suite']) && $options['group_class_suite']==TRUE)
		{
			$this->curriculum_db->group_by("{$sJoinTable['class']}.course_ID,{$sJoinTable['class']}.class_code,$sTable.user_ID");
		}


		
		return $this->curriculum_db->get();
	}
	public function add_reg($data = array())
	{
		$this->curriculum_db->set("user_ID",$data['user_ID']);
		$this->curriculum_db->set("class_ID",$data['class_ID']);
		$this->curriculum_db->set("reg_rank",$data['reg_rank']);
		if(isset($data['reg_by']))
		{
			$this->curriculum_db->set("reg_by",$data['reg_by']);
		}
		if(isset($data['reg_time']))
		{
			$this->curriculum_db->set("reg_time",$data['reg_time']);
		}
		$this->curriculum_db->insert("class_registration");
		return $this->curriculum_db->insert_id();
	}
	public function update_reg($data = array())
	{
		if(isset($data['reg_rank']))
		{
			$this->curriculum_db->set("reg_rank",$data['reg_rank']);
		}
		if(isset($data['reg_confirmed_by'])){
			$this->curriculum_db->set("reg_confirmed_by",$data['reg_confirmed_by']);
			$this->curriculum_db->set("reg_confirmation_time",date("Y-m-d H:i:s"));
		}
		if(isset($data['reg_certified_by'])){
			$this->curriculum_db->set("reg_certified_by",$data['reg_certified_by']);
			$this->curriculum_db->set("reg_certification_time",date("Y-m-d H:i:s"));
		}
		if(isset($data['reg_state']))
		{
			$this->curriculum_db->set("reg_state",$data['reg_state']);
		}
		$this->curriculum_db->where("reg_ID",$data['reg_ID']);
		$this->curriculum_db->update("class_registration");
		return $this->curriculum_db->affected_rows();
	}
	public function del_reg($data = array())
	{
		$sTable = "class_registration";
		$sJoinTable = array("class"=>"class_list");
		if(isset($data['user_ID']))
			$this->curriculum_db->where("$sTable.user_ID",$data['user_ID']);
		if(isset($data['course_ID']))
			$this->curriculum_db->where("{$sJoinTable['class']}.course_ID",$data['course_ID']);
		if(isset($data['class_code']))
			$this->curriculum_db->where("{$sJoinTable['class']}.class_code",$data['class_code']);
		if(isset($data['reg_state']))
			$this->curriculum_db->where("$sTable.reg_state",$data['reg_state']);
		if(isset($data['reg_ID']))
			$this->curriculum_db->where("$sTable.reg_ID",$data['reg_ID']);
		$this->curriculum_db->set("$sTable.reg_canceled_by",isset($data['reg_canceled_by'])?$data['reg_canceled_by']:$this->session->userdata('ID'));
		$this->curriculum_db->set("$sTable.reg_cancel_time",date("Y-m-d H:i:s"));
		$this->curriculum_db->update($sTable." JOIN {$sJoinTable['class']} ON $sTable.class_ID = {$sJoinTable['class']}.class_ID");
	}
	//----------------------signature------------------------
//	public function get_signature_list($options = array())
//	{
//		$sTable = "class_registration";
//		$sJoinTable = array("lesson"=>"lesson_list","signature"=>"lesson_signature","class"=>"class_list","course"=>"course_list","user"=>"cmnst_common.user_profile","location"=>"cmnst_common.location");
//		$this->curriculum_db->select("$sTable.*,
//									  {$sJoinTable['signature']}.signature_ID AS signature_ID,
//									  {$sJoinTable['signature']}.signature_by AS lesson_signature_by,
//									  {$sJoinTable['signature']}.signature_time AS lesson_signature_time,
//									  {$sJoinTable['signature']}.signature_confirmed_by AS lesson_confirmed_by,
//									  {$sJoinTable['signature']}.signature_confirmation_time AS lesson_confirmation_time,
//									  {$sJoinTable['signature']}.signature_remark AS lesson_signature_remark,
//									  {$sJoinTable['signature']}.signature_hash AS lesson_signature_hash,
//									  {$sJoinTable['lesson']}.lesson_ID AS lesson_ID,
//									  {$sJoinTable['lesson']}.lesson_start_time AS lesson_start_time,
//									  {$sJoinTable['lesson']}.lesson_end_time AS lesson_end_time,
//									  {$sJoinTable['class']}.class_type AS lesson_type,
//									  {$sJoinTable['course']}.course_ID AS course_ID,
//									  {$sJoinTable['course']}.course_cht_name AS course_cht_name,
//									  {$sJoinTable['course']}.course_eng_name AS course_eng_name,
//									  student.ID AS lesson_student_ID,
//									  student.name AS lesson_student_name,
//									  teacher.ID AS lesson_teacher_ID,
//									  teacher.name AS lesson_teacher_name,
//									  {$sJoinTable['location']}.location_cht_name AS location_name")
//							->from($sTable)
//							->join($sJoinTable['lesson'],"{$sJoinTable['lesson']}.class_ID = $sTable.class_ID","LEFT")
//							->join($sJoinTable['signature'],"{$sJoinTable['signature']}.reg_ID = $sTable.reg_ID AND {$sJoinTable['signature']}.lesson_ID = {$sJoinTable['lesson']}.lesson_ID","LEFT")
//							
//							->join($sJoinTable['class'],"{$sJoinTable['class']}.class_ID = {$sJoinTable['lesson']}.class_ID")
//							->join($sJoinTable['course'],"{$sJoinTable['course']}.course_ID = {$sJoinTable['class']}.course_ID")
//							->join($sJoinTable['user']." AS student","student.ID = $sTable.user_ID")
//							->join($sJoinTable['user']." AS teacher","teacher.ID = {$sJoinTable['lesson']}.lesson_prof_ID")
//							->join($sJoinTable['location'],"{$sJoinTable['location']}.location_ID = {$sJoinTable['class']}.class_location");
//		$this->curriculum_db->where("$sTable.reg_canceled_by",NULL);
//		$this->curriculum_db->where("{$sJoinTable['class']}.class_state !=","canceled");
//		if(isset($options['signature_ID']))
//		{
//			$this->curriculum_db->where("{$sJoinTable['signature']}.signature_ID",$options['signature_ID']);
//		}
//		if(isset($options['lesson_ID']))
//		{
//			$this->curriculum_db->where("{$sJoinTable['lesson']}.lesson_ID",$options['lesson_ID']);
//		}
//		if(isset($options['reg_ID']))
//		{
//			$this->curriculum_db->where("$sTable.reg_ID",$options['reg_ID']);
//		}
//		if(isset($options['student_ID']))
//		{
//			$this->curriculum_db->where("$sTable.user_ID",$options['student_ID']);
//		}
//		if(isset($options['teacher_ID']))
//		{
//			$this->curriculum_db->where("{$sJoinTable['lesson']}.lesson_prof_ID",$options['teacher_ID']);
//		}
//		
//		if(isset($options['lesson_start_time'])){
//			$this->curriculum_db->where("{$sJoinTable['lesson']}.lesson_end_time >=",$options['lesson_start_time']);
//		}
//		if(isset($options['lesson_end_time'])){
//			$this->curriculum_db->where("{$sJoinTable['lesson']}.lesson_start_time <=",$options['lesson_end_time']);
//		}
//		return $this->curriculum_db->get();
//	}
//	public function add_signature($data)
//	{
//		$sTable = "lesson_signature";
//		$this->curriculum_db->set("lesson_ID",$data['lesson_ID'])
//							->set("reg_ID",$data['reg_ID'])
//							->set("signature_hash",md5((string)time()));
//		if(isset($data['signature_by']))
//		{
//			$this->curriculum_db->set("signature_by",$data['signature_by'])
//								->set("signature_time",date("Y-m-d H:i:s"));
//		}
//		
//		if(isset($data['signature_remark']))
//		{
//			$this->curriculum_db->set("signature_remark",$data['signature_remark']);
//		}
//		
//		$this->curriculum_db->insert($sTable);
//		
//		return $this->curriculum_db->insert_id();
//	}
//	public function update_signature($data)
//	{
//		foreach((array)$data as $d)
//		{
//			if(isset($d['signature_by']))
//			{
//				$this->curriculum_db->set("signature_by",$d['signature_by']);
//				$this->curriculum_db->set("signature_time",date("Y-m-d H:i:s"));
//				$this->curriculum_db->set("signature_hash",md5((string)time()));
//			}
//			if(isset($d['signature_confirmed_by']))
//			{
//				$this->curriculum_db->set("signature_confirmed_by",$d['signature_confirmed_by']);
//				$this->curriculum_db->set("signature_confirmation_time",date("Y-m-d H:i:s"));
//			}
//			$this->curriculum_db->where("signature_ID",$d['signature_ID']);
//			if(isset($d['signature_hash']))
//			{
//				$this->curriculum_db->where("signature_hash",$d['signature_hash']);
//			}
//			$this->curriculum_db->update("lesson_signature");
//		}
//		return $this->curriculum_db->affected_rows();
//	}
//	public function del_signature($data)
//	{
//		$sTable = "lesson_signature";
//		
//		$this->curriculum_db->where("signature_ID",$data['signature_ID']);
//		$this->curriculum_db->delete($sTable);
//	}
	//----------------------privilege------------------------
	public function get_admin_privilege_list($options)
	{
		if(!empty($options['admin_ID']))
			$this->curriculum_db->where("admin_ID",$options['admin_ID']);
		if(!empty($options['privilege']))
			$this->curriculum_db->where("privilege",$options['privilege']);
		return $this->curriculum_db->get("curriculum_admin_privilege");
	}
	public function get_admin_privilege_by_privilege($privilege){
		$options = array("privilege"=>$privilege);
		return $this->get_admin_privilege_list($options)->result_array();
	}
	public function add_admin_privilege($input)
	{
		$this->curriculum_db->set("admin_ID",$input['admin_ID']);
		$this->curriculum_db->set("privilege",$input['privilege']);
		$this->curriculum_db->insert("curriculum_admin_privilege");
		return $this->curriculum_db->insert_id();
	}
	public function del_admin_privilege($input)
	{
		if(isset($input['serial_no']))
			$this->curriculum_db->where("serial_no",$input['serial_no']);
		if(isset($input['admin_ID']))
			$this->curriculum_db->where("admin_ID",$input['admin_ID']);
		if(isset($input['privilege']))
			$this->curriculum_db->where("privilege",$input['privilege']);
		$this->curriculum_db->delete("curriculum_admin_privilege");
	}
	
	public function is_super_admin($user_ID = NULL)
	{
		if(empty($user_ID))	$user_ID = $this->session->userdata('ID');
		$data = array(	"admin_ID"=>$user_ID,
						"privilege"=>"curriculum_super_admin");
		return $this->get_admin_privilege_list($data)->row_array();
	}
	//---------------------bulletin--------------------
	public function get_bulletin_list($options = array())
	{
		if(isset($options['bulletin_ID']))
		{
			$this->curriculum_db->where("bulletin_ID",$options['bulletin_ID']);
		}
		return $this->curriculum_db->get("bulletin");
		
	}
	public function update_bulletin($data)
	{
		$this->curriculum_db->set("bulletin_content",$data['bulletin_content']);
		$this->curriculum_db->where("bulletin_ID",$data['bulletin_ID']);
		$this->curriculum_db->update("bulletin");
	}
	//-----------------------通用------------------------
	public function get_class_type_str($type){
		$this->load->model('curriculum/class_model');
		$class_type = $this->class_model->get_class_type_select_options();
		$type = explode(",",$type);
		for($i=0;$i<count($type);$i++){
			$type[$i] = $class_type[$type[$i]];
		}
		return implode(",",$type);
	}
}