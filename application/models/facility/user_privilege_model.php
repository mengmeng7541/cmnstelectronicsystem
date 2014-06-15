<?php
class User_privilege_model extends MY_Model {
  
  public function __construct()
  {
  	parent::__construct();
    $this->load->model("facility_model");
    $this->load->model("user_model");
  }
  public function get_available_users($facility_ID,$privilege)
  {
	$user_privileges = $this->facility_model->get_user_privilege_list(array("facility_ID"=>$facility_ID,"privilege"=>$privilege))->result_array();
	foreach($user_privileges as $up)
	{
		$select_options[$up['user_ID']] = $up['user_name'];
	}
	return $select_options;
  }
  /**
  * 新增使用者儀器使用權限，自動判定該儀器的延長權限，不用設定到期日
  * @param undefined $facilities_ID
  * @param undefined $user_ID
  * @param undefined $privilege
  * 
  * @return
  */
  public function add($facilities_ID,$user_ID,$privilege)
  {
  	$facilities_ID = (array)$facilities_ID;
  	//確認沒有重複的權限
	$data = array();
	foreach($facilities_ID as $facility_ID)
	{
		$row = array();
		
		$row['user_ID'] = $user_ID;
		$row['facility_ID'] = $facility_ID;
		
		$data[] = $row;
	}
	$this->facility_model->del_user_privilege($data);
	//再新增
	$data = array();
	foreach($facilities_ID as $facility_ID)
	{
		//取得儀器資訊
		$facility = $this->facility_model->get_facility_list(array("ID"=>$facility_ID))->row_array();
		
		$row = array();
		
		$row['user_ID'] = $user_ID;
		$row['facility_ID'] = $facility_ID;
		$row['privilege'] = $privilege;
		if($privilege != "normal" || empty($facility['extension_sec']))
			$row['expiration_date'] = NULL;
		else
			$row['expiration_date'] = date("Y-m-d H:i:s",time()+$facility['extension_sec']);
		$row['suspended'] = 0;
		
		$data[] = $row;
	}
	$this->facility_model->add_user_privilege($data);
  }
  /**
  * 更新總使用時數與到期日
  * 
  * @return
  */
  public function update($user_ID,$facility_ID)
  {
	//先找尋上下關係的儀器
	$facility_IDs = $this->facility_model->get_vertical_group_facilities($facility_ID,array("facility_only"=>TRUE,"no_child"=>TRUE));
	//再尋找水平關係
	$facility_IDs = array_merge($facility_IDs,$this->facility_model->get_horizontal_group_facilities($facility_ID));
	//UNIQUE
	$facility_IDs = array_unique($facility_IDs);
	foreach($facility_IDs as $facility_ID)
	{
		//取得此儀器權限
		$privileges = $this->facility_model->get_user_privilege_list(array(
			"user_ID"=>$user_ID,
			"facility_ID"=>$facility_ID
		))->result_array();
		foreach($privileges as $privilege)
		{
			$temp_facility_IDs = $this->facility_model->get_vertical_group_facilities($privilege['facility_ID'],array("facility_only"=>TRUE));
			$temp_facility_IDs = array_merge($temp_facility_IDs,$this->facility_model->get_horizontal_group_facilities($privilege['facility_ID']));
			//(先取得過往的預約紀錄，找出最近一次的預約紀錄，加上延展時間)
			$old_booking = $this->facility_model->get_facility_booking_list(
			array("user_ID"=>$privilege['user_ID'],
				  "facility_ID"=>$temp_facility_IDs)
			)->row_array();
			if(!$old_booking)
			{
				$old_booking['end_time'] = $privilege['verification_time'];
			}
				
			//尋找停機記錄
			$outage_total_time = 0;
			do{
				//漸進式尋找真正到期日
				$old_expiration_date = date("Y-m-d H:i:s",strtotime($old_booking['end_time'])+$privilege['extension_sec']+$outage_total_time);
				$outages = $this->facility_model->get_outage_list(array(
					"facility_SN"=>$temp_facility_IDs,
					"outage_start_time"=>$old_booking['end_time'],
					"outage_end_time"=>$old_expiration_date
				))->result_array();
				$outage_times = array();
				foreach($outages as $outage)
				{
					if(empty($outage['outage_end_time'])) continue;
					$outage_times[] = array(strtotime($outage['outage_start_time']),strtotime($outage['outage_end_time']));
				}
				$outage_times = range_array_unique($outage_times);
				$outage_total_time = 0;
				foreach($outage_times as $o_time)
				{
					$outage_total_time += ($o_time[1]-$o_time[0]);
				}
				$expiration_date = date("Y-m-d H:i:s",strtotime($old_booking['end_time'])+$privilege['extension_sec']+$outage_total_time);
			}while($old_expiration_date!=$expiration_date);
				
			//(最後更新資料)
			$this->facility_model->update_user_privilege(
			array("serial_no"=>$privilege['serial_no'],
				  "expiration_date"=>(empty($privilege['expiration_date'])||empty($privilege['extension_sec']))?NULL:$expiration_date,
				  "total_secs_used"=>$this->get_total_secs_used($privilege['user_ID'],$privilege['facility_ID']))
			);
		}
	}
  }
  
  public function get_total_secs_used($user_ID,$facility_ID)
  {
  	$bookings = $this->facility_model->get_facility_booking_list(
					array("user_ID"=>$user_ID,
						  "facility_ID"=>$facility_ID)
				)->result_array();
	$secs = 0;
	foreach($bookings as $b)
	{
		$secs += (strtotime($b['end_time'])-strtotime($b['start_time']));
	}
	return $secs;
  }
}