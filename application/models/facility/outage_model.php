<?php
class Outage_model extends MY_Model {
  
	public function __construct()
	{
		parent::__construct();
		$this->load->model("facility_model");
	}
	
	public function add_outage($f_SN,$remark,$start_time,$end_time = NULL)
	{
		//過濾參數
		$end_time = trim($end_time);
		$end_time = empty($end_time)?NULL:$end_time;
		
		//檢查時間順序
		if(!empty($end_time))
		{
			if($start_time >= $end_time)
			{
				throw new Exception("起始時間需小於結束時間",WARNING_CODE);
			}
		}
		
		//檢查有無此儀器
		$facilities = $this->facility_model->get_facility_list(array("ID"=>$f_SN))->result_array();
		foreach($facilities as $facility)
		{
			//檢查有沒有重複時間
			$outages = $this->facility_model->get_outage_list(array(
				"outage_start_time"=>$start_time,
				"outage_end_time"=>$end_time,
				"facility_SN"=>$facility['ID']
			))->result_array();
			if($outages)
			{
				throw new Exception("停機時段已存在，不可重複",ERROR_CODE);
			}
			
			//檢查時間有無整點
			if(!is_int_multiple_unit_time(array(strtotime($start_time),strtotime($end_time)),$facility['unit_sec']))
			{
				throw new Exception("請輸入單位倍數的時間",WARNING_CODE);
			}
		}
		
		foreach($facilities as $facility)
		{
			//新增
			$this->facility_model->add_outage(array(
				"facility_SN"=>$facility['ID'],
				"outage_remark"=>$remark,
				"outage_start_time"=>$start_time,
				"outage_end_time"=>$end_time
			));
			
			//更新使用者權限
			$this->load->model('facility/user_privilege_model');
			$this->user_privilege_model->update(NULL,$facility['ID']);
		}
	}
	public function update_outage($outage_SN,$remark,$start_time,$end_time = NULL)
	{
		//過濾參數
		$start_time = date("Y-m-d H:i:s",strtotime($start_time));
		$end_time = trim($end_time);
		$end_time = empty($end_time)?NULL:date("Y-m-d H:i:s",strtotime($end_time));
		//檢查outage存不存在
		$outage = $this->facility_model->get_outage_list(array(
			"outage_SN"=>$outage_SN
		))->row_array();
		if(!$outage)
		{
			throw new Exception("無此停機記錄",ERROR_CODE);
		}
		//檢查時間順序
		if(!empty($end_time))
		{
			if($start_time >= $end_time)
			{
				throw new Exception("起始時間需小於結束時間",WARNING_CODE);
			}
		}
		//檢查有沒有重複時間
		$outages = $this->facility_model->get_outage_list(array(
			"outage_start_time"=>$start_time,
			"outage_end_time"=>$end_time,
			"facility_SN"=>$outage['facility_SN']
		))->result_array();
		foreach($outages as $o)
		{
			if($o['outage_SN']!=$outage['outage_SN'])
			{
				throw new Exception("停機時段已存在，不可重複",ERROR_CODE);
			}
		}
		$facility = $this->facility_model->get_facility_list(array("ID"=>$outage['facility_SN']))->row_array();
		//檢查時間有無整點
		if(!is_int_multiple_unit_time(array(strtotime($start_time),strtotime($end_time)),$facility['unit_sec']))
		{
			throw new Exception("請輸入單位倍數的時間",WARNING_CODE);
		}
		
		//更新
		$this->facility_model->update_outage(array(
			"outage_SN"=>$outage['outage_SN'],
			"outage_remark"=>$remark,
			"outage_start_time"=>$start_time,
			"outage_end_time"=>$end_time
		));
		
		//更新使用者權限
		if($outage['outage_start_time']!=$start_time||$outage['outage_end_time']!=$end_time)
		{
			$this->load->model('facility/user_privilege_model');
			$this->user_privilege_model->update(NULL,$outage['facility_SN']);
		}
	}
	public function del_outage($SN = "")
	{
		//檢查outage存不存在
		$outage = $this->facility_model->get_outage_list(array(
			"outage_SN"=>$SN
		))->row_array();
		if(!$outage)
		{
			throw new Exception("無此停機記錄",ERROR_CODE);
		}
		
		$this->facility_model->del_outage(array("outage_SN"=>$SN));
		
		//更新使用者權限
		$this->load->model('facility/user_privilege_model');
		$this->user_privilege_model->update(NULL,$outage['facility_SN']);
	}
}