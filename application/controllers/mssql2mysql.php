<?php      
class Mssql2mysql extends MY_Controller {
	
	public $cmnst_common;
	public $cmnst_facility;
	public $reward;
	public $order_class;
	public $order_machine;
	public $work;
	public $test;
	
  public function __construct()
  {
    parent::__construct();
	
	$this->cmnst_common = $this->load->database('common',TRUE);
	$this->cmnst_facility = $this->load->database('facility',TRUE);
    $this->reward = $this->load->database('reward',TRUE);
	$this->order_class = $this->load->database('order_class',TRUE);
	$this->order_machine = $this->load->database('order_machine',TRUE);
	$this->work = $this->load->database('work',TRUE);    
	$this->test = $this->load->database('test',TRUE);   
	
	$this->is_admin_login();
  }
  
  public function index()
  {
	//緊急修復卡機控管錯誤
//	$this->order_machine->where("FTime >=",date("Y/m/d H:i:s"));
//	$this->order_machine->delete("RunList");
//	$this->load->model('facility_model');
//	$this->cmnst_facility->where("start_time >=",date("Y-m-d H:i:s"));
//	$bookings = $this->cmnst_facility->get("facility_booking")->result_array();
//	$this->load->model('facility/access_ctrl_model');
//	foreach($bookings as $booking){
//		$this->access_ctrl_model->add($booking['facility_ID'],$booking['user_ID'],strtotime($booking['start_time']),strtotime($booking['end_time']));
//	}
	
	
	
//	$this->transfer_teacher_data();

//	$this->transfer_reward_application();
	
//	$this->transfer_user();

//	$this->transfer_school_data();

	
	
	
//	$this->transfer_facility_user_privilege();

//	$this->transfer_facility_access();
	
	//更新機台所屬卡機編號
//	$query = $this->order_machine->get("machine");
//	foreach($query->result_array() as $row)
//	{
//		$this->cmnst_facility->where("ID",$row['machine_no']);
//		$this->cmnst_facility->set("ctrl_no",$row['CtrlNo']);
//		$this->cmnst_facility->update("facility_list");
//	}
	//更新使用者所屬卡號
//	$query = $this->order_class->get("Iuser");
//	foreach($query->result_array() as $row)
//	{
//		$row = $this->big5_to_utf8($row);
//		$this->cmnst_common->where("ID",$row['身分證字號']);
//		$this->cmnst_common->where("group","normal");
////		$this->cmnst_common->set("card_num",empty($row['卡號'])?NULL:$row['卡號']);
//		$this->cmnst_common->set("security_verified",$row['IsFirst']=="pas"?1:0);
//		$this->cmnst_common->update("user_profile");
//	}

//	$this->transfer_facility_booking();
	
	//修正舊選課系統的開課代碼
//	$results = $this->order_class->get("history")->result_array();
//	foreach($results as $result)
//	{
//		$class_date = str_split($result['class_code'],4);
//		$this->order_class->set("class_code",date("Yn",strtotime($class_date[0].'-'.$class_date[1])));
//		$this->order_class->where("ID",$result['ID'])
//						  ->where("COURSEID",$result['COURSEID'])
//						  ->where("class_code",$result['class_code']);
//		$this->order_class->update("history");
//	}
  }
  public function transfer_facility_booking()
  {
//  	$this->cmnst_facility->query("SET FOREIGN_KEY_CHECKS=0");
  	ini_set('memory_limit', '4096M');
	
	$this->cmnst_facility->where("serial_no >",88431)
						 ->where("serial_no <",91399)
						 ->delete("facility_booking");
	$results = $this->order_machine->from("result")
									->where("PK >",88431)
									->where("PK <",91399)
									->get()
									->result_array();
	
//	$query = $this->test->query("select * from test");
	foreach($results as $row)
	{
		$mydata['serial_no'] = $row['PK'];
		$mydata['user_ID'] = $row['id'];
		$mydata['facility_ID'] = $row['machine_id'];
		if($row['order_reason'] == 'OEM')
		{
			$mydata['purpose'] = 'OEM';
		}else if($row['order_reason'] == 'repair')
		{
			$mydata['purpose'] = 'maintenance';
		}else if($row['order_reason'] == 'aclass')
		{
			$mydata['purpose'] = 'course';
		}else{
			$mydata['purpose'] = 'DIY';
		}
		$row['usetime'] = explode("~",$row['usetime']);
		$mydata['start_time'] = date("Y-m-d H:i:s" , strtotime( current($row['usetime']),strtotime($row['orderdate']) ));
		if(strcmp(end($row['usetime']),"00:00")==0){
			$mydata['end_time'] = date("Y-m-d H:i:s" , strtotime($row['orderdate'])+86400);
		}
		else{
			$mydata['end_time'] = date("Y-m-d H:i:s" , strtotime( end($row['usetime']),strtotime($row['orderdate']) ));
		}
		
			
		$mydata['booking_time'] = date("Y-m-d H:i:s" , strtotime($row['orderdate']));
		
		$tmp_sql = "SELECT ID FROM facility_list WHERE ID = '{$row['machine_id']}'";
		$tmp_q = $this->cmnst_facility->query($tmp_sql);
		if(!$this->cmnst_facility->affected_rows())
			continue;
		$user_profile = $this->cmnst_common->where("ID",$row['id'])
											->get("user_profile")
											->row_array();
		if(!$user_profile) continue;
		$this->cmnst_facility->insert("facility_booking",$mydata);

		
	}
//	$this->cmnst_facility->query("SET FOREIGN_KEY_CHECKS=1");
  }
  public function transfer_facility_access()
  {
  	ini_set('memory_limit', '4096M');

//  $query = $this->order_machine->get("MLink");
//	foreach($query->result_array() as $row)
//	{
//		$this->cmnst_facility->insert("MLink",$row);
//	}
	$this->cmnst_facility->truncate("RunList");
	$query = $this->order_machine->get("RunList");
	foreach($query->result_array() as $row)
	{
		$this->cmnst_facility->insert("RunList",$row);
	}
	
//	$this->cmnst_facility->truncate("Card");
//	$query = $this->order_machine->get("Card");
//	foreach($query->result_array() as $row)
//	{
//		$this->cmnst_facility->insert("Card",$row);
//	}
  }
  public function transfer_facility_user_privilege()
  {
  	$sql = "SELECT id,machine_id,power FROM authority";
	$query = $this->order_machine->query($sql);
	$result = $query->result_array();
	
	//TRUNCATE
//	$tmp_sql = "TRUNCATE TABLE facility_user_privilege";
//	$tmp_q = $this->cmnst_facility->query($tmp_sql);
		
	foreach($result as $row)
	{
		//檢查USER_ID是否存在
		$tmp_sql = "SELECT ID FROM user_profile WHERE ID = '{$row['id']}'";
		$tmp_q = $this->cmnst_common->query($tmp_sql);
		if(!$this->cmnst_common->affected_rows())
			continue;
			
		//檢查FACILITY_ID是否存在
		$facility = $this->cmnst_facility->where("ID",$row['machine_id'])->get("facility_list")->row_array();
		if(!$facility)
			continue;
		
		$privilege = $this->cmnst_facility->where("user_ID",$row['id'])->where("facility_ID",$row['machine_id'])->get("facility_user_privilege")->row_array();
		if($privilege)
			continue;
			
		//計算總使用時數
		$this->cmnst_facility->where("user_ID",$row['id']);
		$this->cmnst_facility->where("facility_ID",$row['machine_id']);
		$this->cmnst_facility->order_by("end_time","desc");
		$query = $this->cmnst_facility->get("facility_booking");
		$bookings = $query->result_array();
		$booking = $query->row_array();
		$total_secs_used=0;
		foreach($bookings as $b)
		{
			$total_secs_used += (strtotime($b['end_time'])-strtotime($b['start_time']));
		}
		$exp_date = time();
		if($booking)
		{
			//有BOOKING紀錄
			$exp_date = strtotime($b['end_time'])+$facility['extension_sec'];
		}else{
			//找最後認證時間
			$exp_date = time()+$facility['extension_sec'];
		}
		
		$this->cmnst_facility->set("user_ID",$row['id']);
		$this->cmnst_facility->set("facility_ID",$row['machine_id']);
		$this->cmnst_facility->set("privilege",$row['power']=='Super_user'?"super":"normal");
		$this->cmnst_facility->set("total_secs_used",$total_secs_used);
//		$this->cmnst_facility->set("expiration_date",$row['power']=='Super_user'?NULL:date("Y-m-d H:i:s",$exp_date));
		$this->cmnst_facility->set("suspended",empty($row['use_option'])?0:$row['use_option']=="Y"?0:1);
		$this->cmnst_facility->insert("facility_user_privilege");
	}
  }
  
  public function transfer_teacher_data()
  {
  	
  	$sql = "SELECT * from teacher";
	$query = $this->order_class->query($sql);
	$result = $query->result_array();
	
	$result = $this->big5_to_utf8($result);
	foreach($result as $row)
	{
		$sql2 = "INSERT INTO boss_profile (serial_no,email,name,organization,department,tel) VALUES (?,?,?,?,?,?)";
		$val2 = array($row['flownum'],$row['teach_mail'],$row['teach_name'],$row['teach_school'],$row['teach_apartment'],$row['teach_tel']);
		$query2 = $this->cmnst_common->query($sql2,$val2);
	}
  }
  public function transfer_school_data()
  {
  	$sql = "SELECT * FROM school";
	$query = $this->order_class->query($sql);
	$result = $query->result_array();
	$result = $this->big5_to_utf8($result);
	
	foreach($result as $row)
	{
		
		
		$sql2 = "INSERT INTO organization set
		serial_no  = {$row['school_no']},
		name = '{$row['school_name']}',
		area = '{$row['school_area']}'";
		$query2 = $this->cmnst_common->query($sql2);
	}
  }
  
  public function transfer_reward_application()
  {
  	$sql = "TRUNCATE TABLE Reward_application";
	$query = $this->cmnst_common->query($sql);
	
  	/*
	* transfer reward application
	*
	*/
	$sql = "SELECT CONVERT(VARCHAR(40),[apply_date],120) AS apply_date2,CONVERT(VARCHAR(40),[check_date],120) AS check_date2,* from basic";
	$query = $this->reward->query($sql);
	$result = $query->result_array();
	
	
	foreach($result as $row)
	{
		
		$s = $s = "SELECT flownum FROM teacher WHERE teach_name = '{$row['reward_name']}'";
		$q = $this->order_class->query($s);
		$r = $q->row_array();
		if(empty($r['flownum']))
			$awardees_no = NULL;
		else
			$awardees_no = $r['flownum'];

		$row = $this->big5_to_utf8($row);
		
		if(empty($row['apply']))
			$row['apply'] = '';
		else
			$row['apply'] = substr($row['apply'],0,1);
			
		if(empty($row['apply_result']))
			$row['apply_result'] = '';
		else
			$row['apply_result'] = substr($row['apply_result'],0,1);
			
		
		if(empty($row['result']))
			$row['result'] = NULL;
		else if(strpos($row['result'],'不符合')!==FALSE)
			$row['result'] = 0;
		else if(strpos($row['result'],'符合')!==FALSE)
			$row['result'] = 1;
			
		if($row['finish']=='Y')
			$row['finish']=1;
		else if($row['finish']=='N')
			$row['finish']=0;
		else
			$row['finish']=NULL;
		
		
		
		$sql2 = "
		INSERT INTO Reward_application SET 
		serial_no={$row['flownum']},
		application_date=DATE_FORMAT('{$row['apply_date2']}','%Y-%m-%d'),
		applicant_name='{$row['apply_name']}',
		department='{$row['place']}',
		tel='{$row['tel']}',
		email='{$row['email']}',
		research_field='{$row['research']}',
		paper_title='{$row['doc_name']}',
		journal='{$row['towhere']}',
		journal_year={$row['toyear']},
		upload_file='{$row['file_name']}',
		awardees_no='{$awardees_no}',
		apply_plan_no='{$row['apply']}',
		accept_plan_no='{$row['apply_result']}',
		review_date=DATE_FORMAT('{$row['check_date2']}','%Y-%m-%d'),
		reviewer_name='{$row['check_pro']}',
		deny_reason='{$row['deny_reason']}',
		result='{$row['result']}',
		is_review={$row['finish']}
		";
		$query2 = $this->cmnst_common->query($sql2);
	}
  }
  
  public function transfer_user()
  {
  	$sql = "TRUNCATE TABLE user";
	$query = $this->cmnst_common->query($sql);
	
  	/*
	* transfer user
	*
	*/
	$sql = "SELECT * from Iuser";
	$query = $this->order_class->query($sql);
	$result = $query->result_array();
	
	foreach($result as $row)
	{
		$row = array_values($row);
		
		
		$s = "SELECT * FROM teacher WHERE teach_name = '{$row[8]}'";
		$q = $this->order_class->query($s);
		$r = $q->row_array();
		if($r)
			$row[8] = $r['flownum'];
		else
		{
			echo $row[8];
			$row[8] = NULL;
			
		}
		

		if($row[13]=='no')
		{
			$row[13] = 1;
		}elseif($row[13]=='pas')
		{
			$row[13] = 0;
		}
		
		$s = "SELECT * FROM school WHERE school_name = '{$row[12]}'";
		$q = $this->order_class->query($s);
		$r = $q->row_array();
		if($r)
		{
			$row[12] = $r['school_no'];
		}else{
			$row[12] = NULL;
		}
		
		
		$row = $this->big5_to_utf8($row);
	
		
		
		$sql2 = "INSERT INTO user SET
		ID='{$row[0]}',		passwd='{$row[10]}',		name=?,
		tel='{$row[3]}',		mobile='{$row[4]}',
		address='{$row[5]}',		email='{$row[6]}',
		sex='{$row[2]}',		status='{$row[7]}',
		boss_no=?,		organization=?,		department='{$row[9]}',
		card_num='{$row[15]}',
				
		security_verified='{$row[13]}',		
		is_deny=0,		
		is_member='{$row[17]}',		
		discount='{$row[18]}',		
		enable_date='2000-01-01'";
		$arr = array($row[1],$row[8],$row[12]);
		$query2 = $this->cmnst_common->query($sql2,$arr);
		
	}  
  }
 

  
}