<?php
class Revision_model extends MY_Model {
	protected $nanomark_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->nanomark_db = $this->load->database('nanomark',TRUE);
		$this->load->model('nanomark_model');
	}
	
	public function add($report_ID,$mistake_outline,$mistake_description,$mistake_analysis,$disposal_revision)
	{
		$specimen = $this->nanomark_model->get_specimen_list(array("specimen_SN"=>$report_ID))->row_array();
		if(!$specimen)
		{
			throw new Exception("無此檢測件",ERROR_CODE);
		}
		//檢查對應的委託單檢測項目是否為自己的
		if($specimen['applicant_ID'] != $this->session->userdata('ID'))
		{
			throw new Exception("沒有權限",ERROR_CODE);
		}
		//檢查是否有同樣得報告在進行中(還沒寫)
		$revisions = $this->nanomark_model->get_report_revision_list(array("specimen_SN"=>$report_ID))->result_array();
		foreach($revisions as $revision)
		{
			if($revision['checkpoint']!='accepted'&&$revision['checkpoint']!='rejected')
			{
				throw new Exception("您此檢測件的報告修改正在處理中，請勿重複申請",ERROR_CODE);
			}
		}
		
		$revision_SN = $this->nanomark_model->add_report_revision(array(
			"specimen_SN"=>$specimen['specimen_SN'],
			"mistake_outline"=>implode(',',$mistake_outline),
			"mistake_description"=>$mistake_description,
			"mistake_analysis"=>$mistake_analysis,
			"disposal_revision"=>$disposal_revision
		));
		
		$this->send_quality_manager_notification($revision_SN);
		
		return $revision_SN;
	}
	public function update($revision_SN,$result,$comment,$admin_ID = NULL)
	{
		$report_revision = $this->nanomark_model->get_report_revision_list(array("revision_SN"=>$revision_SN))->row_array();
		if(!$report_revision)
		{
			throw new Exception("無此報告修改單",ERROR_CODE);
		}
		
		$unsigned_admins = $this->nanomark_model->get_revision_unsigned_admin($report_revision['serial_no']);
		if(!in_array($this->session->userdata('ID'),$unsigned_admins))
		{
			throw new Exception("沒有權限",ERROR_CODE);
		}
			
		$this->nanomark_model->add_revision_checkpoint(array(
			"revision_SN"=>$revision_SN,
			"checkpoint_ID"=>$report_revision['checkpoint'],
			"admin_ID"=>isset($admin_ID)?$admin_ID:$this->session->userdata('ID'),
			"admin_comment"=>$comment
		));
		
		if($result == 'rejected')
		{
			$this->nanomark_model->update_report_revision(array(
					"checkpoint"=>"rejected",
					"revision_SN"=>$report_revision['serial_no']
				));
		}else{
			//檢查審核完後是不是該關都審過了
			$unsigned_admins = $this->nanomark_model->get_revision_unsigned_admin($report_revision['serial_no']);
			if(empty($unsigned_admins))
			{
				//進到下一關
				$next_cp = array("quality_manager"=>"technical_manager",
								 "technical_manager"=>"report_signatory",
								 "report_signatory"=>"lab_manager",
								 "lab_manager"=>"accepted");
				$this->nanomark_model->update_report_revision(array(
					"checkpoint"=>$next_cp[$report_revision['checkpoint']],
					"revision_SN"=>$report_revision['serial_no']
				));
			}
		}
	}
	
	public function send_quality_manager_notification($revision_SN)
	{
		$revision = $this->nanomark_model->get_report_revision_list(array("revision_SN"=>$revision_SN))->row_array();
		
		//取得有QM權限的人
		$admins = $this->nanomark_model->get_admin_privilege_list(array("privilege"=>"revision_quality_manager"))->result_array();
		foreach($admins as $admin)
		{
			$this->email->to($admin['admin_email']);
			$this->email->subject("微奈米科技研究中心 -奈米標章報告修改申請通知-");
			$this->email->message("
				品質主管 {$admin['admin_name']} 您好：<br>
				{$revision['report_title']} 申請了報告修改，請上本中心系統審核，謝謝。
			");
			$this->email->send();
		}
		
	}
}