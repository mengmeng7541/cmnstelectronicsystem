<?php
class Nanomark_model extends MY_Model {
	protected $nanomark_db;
	
	public function __construct()
	{
		parent::__construct();
		$this->nanomark_db = $this->load->database('nanomark',TRUE);
	}
	
	public function add_application($input_data)
	{
		return $this->update_application($input_data,"add");
	}
	public function add_specimen($input_data)
	{
		$this->nanomark_db->set("application_SN",$input_data['application_SN']);
		$this->nanomark_db->set("ID",$input_data['specimen_ID']);
		$this->nanomark_db->set("test_item",$input_data['specimen_test_item']);
		$this->nanomark_db->set("name",$input_data['specimen_name']);
		$this->nanomark_db->set("company_name",$input_data['specimen_company_name']);
		$this->nanomark_db->set("brand",$input_data['specimen_brand']);
		$this->nanomark_db->set("model",$input_data['specimen_model']);
		$this->nanomark_db->insert("Nanomark_specimen");
		return $this->nanomark_db->insert_id();
	}
	public function update_application($input_data,$act = "update")
	{
		if(isset($input_data['application_ID']))
			$this->nanomark_db->set("ID",$input_data['application_ID']);
		if(isset($input_data['application_date']))
			$this->nanomark_db->set("application_date",$input_data['application_date']);
		if(isset($input_data['test_outline']))
			$this->nanomark_db->set("test_outline",$input_data['test_outline']);
		if(isset($input_data['priority']))
			$this->nanomark_db->set("priority",$input_data['priority']);
		if(isset($input_data['report_title']))
			$this->nanomark_db->set("report_title",$input_data['report_title']);
		if(isset($input_data['receipt_title']))
			$this->nanomark_db->set("receipt_title",$input_data['receipt_title']);
		if(isset($input_data['report_address']))
			$this->nanomark_db->set("report_address",$input_data['report_address']);
		if(isset($input_data['mail_address']))
			$this->nanomark_db->set("mail_address",$input_data['mail_address']);
		if(isset($input_data['VAT']))
			$this->nanomark_db->set("VAT",$input_data['VAT']);
		if(isset($input_data['applicant_ID']))
			$this->nanomark_db->set("applicant_ID",$input_data['applicant_ID']);
		if(isset($input_data['contact_name']))
			$this->nanomark_db->set("contact_name",$input_data['contact_name']);
		if(isset($input_data['contact_tel']))
			$this->nanomark_db->set("contact_tel",$input_data['contact_tel']);
		if(isset($input_data['contact_mobile']))
			$this->nanomark_db->set("contact_mobile",$input_data['contact_mobile']);
		if(isset($input_data['contact_FAX']))
			$this->nanomark_db->set("contact_FAX",$input_data['contact_FAX']);
		if(isset($input_data['contact_email']))
			$this->nanomark_db->set("contact_email",$input_data['contact_email']);
		if(isset($input_data['work_start_date']))
			$this->nanomark_db->set("work_start_date",$input_data['work_start_date']);
		if(isset($input_data['scheduled_completion_date']))
			$this->nanomark_db->set("scheduled_completion_date",$input_data['scheduled_completion_date']);
		if(isset($input_data['total_fees']))
			$this->nanomark_db->set("total_fees",$input_data['total_fees']);
		if(isset($input_data['when_pay']))
			$this->nanomark_db->set("when_pay",$input_data['when_pay']);
		if(isset($input_data['num_report_copies_scale']))
			$this->nanomark_db->set("num_report_copies_scale",$input_data['num_report_copies_scale']);
		if(isset($input_data['num_report_copies_functionality']))
			$this->nanomark_db->set("num_report_copies_functionality",$input_data['num_report_copies_functionality']);
		if(isset($input_data['num_report_copies_biocompatibility']))
			$this->nanomark_db->set("num_report_copies_biocompatibility",$input_data['num_report_copies_biocompatibility']);
		if(isset($input_data['report_logo_scale']))
			$this->nanomark_db->set("report_logo_scale",$input_data['report_logo_scale']);
		if(isset($input_data['report_logo_functionality']))
			$this->nanomark_db->set("report_logo_functionality",$input_data['report_logo_functionality']);
		if(isset($input_data['report_logo_biocompatibility']))
			$this->nanomark_db->set("report_logo_biocompatibility",$input_data['report_logo_biocompatibility']);
		if(isset($input_data['comments']))
			$this->nanomark_db->set("comments",$input_data['comments']);
		if(isset($input_data['client_signature']))
			$this->nanomark_db->set("client_signature",$input_data['client_signature']);
		if(isset($input_data['case_officer_ID']))
			$this->nanomark_db->set("case_officer_ID",$input_data['case_officer_ID']);
		if(isset($input_data['case_officer_2_ID']))
			$this->nanomark_db->set("case_officer_2_ID",$input_data['case_officer_2_ID']);
		if(isset($input_data['consignee_signature']))
			$this->nanomark_db->set("consignee_signature",$input_data['consignee_signature']);
		if($act=="add"){
			$this->nanomark_db->insert("Nanomark_application");
			return $this->nanomark_db->insert_id();
		}else if($act=="update"){
			$this->nanomark_db->where("serial_no",$input_data['serial_no']);
			$this->nanomark_db->update("Nanomark_application");
			return $this->nanomark_db->affected_rows();
		}
		
	}
	public function update_specimen($input_data)
	{
		if(isset($input_data['specimen_ID']))
			$this->nanomark_db->set("ID",$input_data['specimen_ID']);
		if(isset($input_data['specimen_test_item']))
			$this->nanomark_db->set("test_item",$input_data['specimen_test_item']);
		if(isset($input_data['specimen_name']))
			$this->nanomark_db->set("name",$input_data['specimen_name']);
		if(isset($input_data['specimen_company_name']))
			$this->nanomark_db->set("company_name",$input_data['specimen_company_name']);
		if(isset($input_data['specimen_brand']))
			$this->nanomark_db->set("brand",$input_data['specimen_brand']);
		if(isset($input_data['specimen_model']))
			$this->nanomark_db->set("model",$input_data['specimen_model']);
		if(isset($input_data['specimen_facility_admin_ID']))
			$this->nanomark_db->set("facility_admin_ID",$input_data['specimen_facility_admin_ID']);
		if(isset($input_data['specimen_facility_engineer_ID']))
			$this->nanomark_db->set("facility_engineer_ID",empty($input_data['specimen_facility_engineer_ID'])?NULL:$input_data['specimen_facility_engineer_ID']);
		if(isset($input_data['specimen_checkpoint']))
			$this->nanomark_db->set("checkpoint",$input_data['specimen_checkpoint']);
		$this->nanomark_db->where("serial_no",$input_data['specimen_serial_no']);	
		$this->nanomark_db->update("Nanomark_specimen");
		return $this->nanomark_db->affected_rows();
	}
	
	public function update_specimen_by_facility_admin($SN,$engineer_ID,$admin_ID = "")
	{
		if(empty($admin_ID)) $admin_ID=$this->session->userdata('ID');
		
		$sql = "UPDATE Nanomark_specimen SET 
		facility_admin_ID = '{$admin_ID}',
		facility_engineer_ID = '{$engineer_ID}' 
		WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		
		return $this->nanomark_db->affected_rows();
	}
	public function update_specimen_checkpoint($SN,$checkpoint)
	{
		$sql = "UPDATE Nanomark_specimen SET 
		checkpoint = '{$checkpoint}' 
		WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		
		return $this->nanomark_db->affected_rows();
	}
	public function add_quotation($input_data)
	{
		if(!empty($input_data['applicant_ID']))
			$this->nanomark_db->set("applicant_ID",$input_data['applicant_ID']);
		$this->nanomark_db->set("organization",$input_data['organization']);
		$this->nanomark_db->set("contact_name",$input_data['contact_name']);
		$this->nanomark_db->set("contact_email",$input_data['contact_email']);
		$this->nanomark_db->set("contact_tel",$input_data['contact_tel']);
		if(!empty($input_data['contact_FAX']))
			$this->nanomark_db->set("contact_FAX",$input_data['contact_FAX']);
		$this->nanomark_db->set("entrust_item",$input_data['entrust_item']);
		if(!empty($input_data['total_fees']))
			$this->nanomark_db->set("total_fees",$input_data['total_fees']);
		$this->nanomark_db->set("comments",$input_data['comments']);
		if(!empty($input_data['case_officer_ID']))
			$this->nanomark_db->set("case_officer_ID",$input_data['case_officer_ID']);
		$this->nanomark_db->insert("Nanomark_quotation");
		
		return $this->nanomark_db->insert_id();
	}
	//----------------specimen booking mapping-----------------
	public function get_specimen_booking_list($options = array())
	{
		$sTable = "Nanomark_specimen_booking_map";
		$sJoinTable = array("booking"=>"cmnst_facility.facility_booking");
		$this->nanomark_db->select("$sTable.booking_ID AS booking_ID,
									$sTable.specimen_ID AS specimen_ID,
									{$sJoinTable['booking']}.start_time AS booking_start_time,
									{$sJoinTable['booking']}.end_time AS booking_end_time")
						  ->from($sTable)
						  ->join($sJoinTable['booking'],"{$sJoinTable['booking']}.serial_no = $sTable.booking_ID");
		if(isset($options['specimen_ID']))
			$this->nanomark_db->where("$sTable.specimen_ID",$options['specimen_ID']);
		if(isset($options['booking_ID']))
			$this->nanomark_db->where("$sTable.booking_ID",$options['booking_ID']);
		$this->nanomark_db->where("{$sJoinTable['booking']}.cancel_time",NULL);
		return $this->nanomark_db->get();
	}
	public function add_specimen_booking_map($data)
	{
		$this->nanomark_db->set("specimen_ID",$data['specimen_ID'])
						  ->set("booking_ID",$data['booking_ID']);
		$this->nanomark_db->insert("Nanomark_specimen_booking_map");
	}
	public function del_specimen_booking_map()
	{
		
	}
	//---------------------------------------------------------
	public function update_quotation($input_data)
	{
		$this->nanomark_db->set("organization",$input_data['organization'])
						  ->set("contact_name",$input_data['contact_name'])
						  ->set("contact_email",$input_data['contact_email'])
						  ->set("contact_tel",$input_data['contact_tel'])
						  ->set("contact_FAX",$input_data['contact_FAX'])
						  ->set("entrust_item",$input_data['entrust_item'])
						  ->set("total_fees",$input_data['total_fees'])
						  ->set("comments",$input_data['comments']);
		if(isset($input_data['case_officer_ID']))
			$this->nanomark_db->set("case_officer_ID",$input_data['case_officer_ID']);
		$this->nanomark_db->where("serial_no",$input_data['quotation_ID']);
		return $this->nanomark_db->update("Nanomark_quotation");
	}
	public function del_quotation()
	{
		
	}
	public function add_quotation_test_item($input_data)
	{
		if(!is_array(current($input_data)))
			$input_data = (array)$input_data;
		
		foreach($input_data as $row)
		{
			if(empty($row['test_item_name'])) continue;
			$this->nanomark_db->set("quotation_ID",$row['quotation_ID']);
			$this->nanomark_db->set("test_item",$row['test_item_name']);
			$this->nanomark_db->set("amount",$row['test_item_amount']);
			if(isset($row['test_item_fees'])){
				$this->nanomark_db->set("fees",$row['test_item_fees']);
			}
			if(isset($row['test_item_total_fees'])){
				$this->nanomark_db->set("total_fees",$row['test_item_total_fees']);
			}
				
			$this->nanomark_db->insert("Nanomark_quotation_test_item");
		}
	}
	public function del_quotation_test_item($options)
	{
		if(isset($options['quotation_ID']))
			$this->nanomark_db->where("quotation_ID",$options['quotation_ID']);
		$this->nanomark_db->delete("Nanomark_quotation_test_item");
	}
	public function add_outsourcing($input_data)
	{
		$this->nanomark_db->set("specimen_SN",$input_data['specimen_SN']);
		$this->nanomark_db->set("verification_norm_no",$input_data['verification_norm_no']);
		$this->nanomark_db->set("test_items_name_1",$input_data['test_items_name_1']);
		$this->nanomark_db->set("test_items_name_2",$input_data['test_items_name_2']);
		$this->nanomark_db->set("test_items_1_amount",$input_data['test_items_1_amount']);
		$this->nanomark_db->set("outsourcing_organization",$input_data['outsourcing_organization']);
		$this->nanomark_db->insert("Nanomark_outsourcing");
		
	}
	public function add_report_revision($input_data)
	{
		$sql = "INSERT INTO Nanomark_revision 
				SET report_ID = '{$input_data['report_ID']}',
					application_date = CURDATE(),
					mistake_outline = '{$input_data['mistake_outline']}',
					mistake_description = '{$input_data['mistake_description']}',
					mistake_analysis = '{$input_data['mistake_analysis']}',
					disposal_revision = '{$input_data['disposal_revision']}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function get_new_quotation_ID()
	{
		$sql = "SELECT serial_no FROM Nanomark_quotation ORDER BY serial_no DESC LIMIT 1";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->row();
		return $result->serial_no;
	}
	public function get_new_application_ID()
	{
		$ym = date('ym-');
		
		$sql = "SELECT serial_no,ID FROM Nanomark_application WHERE ID LIKE '{$ym}%' ORDER BY ID DESC LIMIT 1";
		$query = $this->nanomark_db->query($sql);
		if($query->num_rows()){
			$result = $query->row_array();
			$result = $result['ID'];
			$result = explode('-',$result);
			$result[1] = sprintf("%02d",($result[1]+1));
			$result = $result[0]."-".$result[1];
		}else{
			$result = $ym."01";
		}
		return $result;	
	}
	
	public function get_new_specimen_ID($application_SN)
	{
		$this->nanomark_db->select("ID")
						  ->from("Nanomark_specimen")
						  ->where("application_SN",$application_SN)
						  ->order_by("serial_no","DESC")
						  ->limit(1);
		$result = $this->nanomark_db->get()->row_array();
		if($result){
			$result = explode('-',$result['ID']);
			$result[1] = sprintf("%03d",$result[1]+1);
			$result = implode('-',$result);
		}else{
			$this->nanomark_db->select("ID")
							  ->from("Nanomark_application")
							  ->where("serial_no",$application_SN);
			$result = $this->nanomark_db->get()->row_array();
			$result = $result['ID'].'1';
		}
		return $result;	
	}

	public function get_quotation_list($input_data = array(),$type = "")
	{
		if($type == "ARRAY")
		{
			$sTable = "Nanomark_quotation";
			$sJoinTable = "";
		  	$aColumns = array( "$sTable.quotation_date"=>"quotation_date", "$sTable.contact_name"=>"contact_name","$sTable.organization"=>"organization","$sTable.case_officer_ID"=>"case_officer_ID","$sTable.serial_no"=>"serial_no");
			$sJoin = "";
			$result = $this->get_jQ_DTs_array_with_join($this->nanomark_db,$sTable,$sJoin,$aColumns,$input_data);
			return $result;
		}else{
			$sTable = "Nanomark_quotation";
			$sJoinTable = "";
			
			$this->nanomark_db->select("*")
				 ->from("Nanomark_quotation")
				 ->order_by("serial_no","desc");
			if(!empty($input_data['serial_no']))
				$this->nanomark_db->where("serial_no",$input_data['serial_no']);
			
			
			return $this->nanomark_db->get();
		}
	}
	public function get_quotation_test_items_list($options = array())
	{
		if(isset($options['quotation_ID']))
			$this->nanomark_db->where("quotation_ID",$options['quotation_ID']);
		return $this->nanomark_db->get("Nanomark_quotation_test_item");
	}
	public function get_quotation_by_SN($serial_no)
	{
		$sql = "SELECT * FROM Nanomark_quotation WHERE serial_no = '{$serial_no}'";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->row_array();
		return $result;
	}
	public function get_quotation_by_applicant_ID($ID)
	{
		$sql = "SELECT * FROM Nanomark_quotation WHERE applicant_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->result();
		return $result;
	}
	public function get_quotation_test_items_by_quotation_ID($ID)
	{
		$sql = "SELECT * FROM Nanomark_quotation_test_item WHERE quotation_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->result();
		return $result;
	}
	public function get_application_list($options = array())
	{
		$sTable = "Nanomark_application";
		$sJoinTalbe = array("constant_checkpoint"=>"constant_nanomark_application_checkpoint");
		$this->nanomark_db->select("
			$sTable.serial_no,
			$sTable.ID,
			$sTable.application_date,
			$sTable.test_outline,
			$sTable.priority,
			$sTable.report_title,
			$sTable.receipt_title,
			$sTable.report_address,
			$sTable.mail_address,
			$sTable.VAT,
			$sTable.applicant_ID,
			$sTable.contact_name,
			$sTable.contact_tel,
			$sTable.contact_mobile,
			$sTable.contact_FAX,
			$sTable.contact_email,
			$sTable.work_start_date,
			$sTable.scheduled_completion_date,
			$sTable.total_fees,
			$sTable.when_pay,
			$sTable.num_report_copies_scale,
			$sTable.num_report_copies_functionality,
			$sTable.num_report_copies_biocompatibility,
			$sTable.report_logo_scale,
			$sTable.report_logo_functionality,
			$sTable.report_logo_biocompatibility,
			$sTable.comments,
			$sTable.client_signature,
			$sTable.case_officer_ID,
			$sTable.case_officer_2_ID,
			$sTable.consignee_signature,
			{$sJoinTalbe['constant_checkpoint']}.application_checkpoint_ID AS checkpoint,
			{$sJoinTalbe['constant_checkpoint']}.application_checkpoint_name AS checkpoint_name
		");
		$this->nanomark_db->join($sJoinTalbe['constant_checkpoint'],"{$sJoinTalbe['constant_checkpoint']}.application_checkpoint_no = $sTable.checkpoint");
		if(isset($options['serial_no']))
			$this->nanomark_db->where("serial_no",$options['serial_no']);
		if(isset($options['application_ID']))
			$this->nanomark_db->where("ID",$options['application_ID']);
		if(isset($options['applicant_ID']))
			$this->nanomark_db->where("applicant_ID",$options['applicant_ID']);
		return $this->nanomark_db->get($sTable);
	}
	public function get_application_by_applicant_ID($ID)
	{
		$sql = "SELECT * FROM Nanomark_application WHERE applicant_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->result();
		return $result;
	}
	/**
	* 
	* @param undefined $checkpoint
	* @param undefined $applicant_ID
	* 
	* @return
	*/
	public function get_application_by_checkpoint($checkpoint,$applicant_ID = "")
	{
		if(empty($applicant_ID))
			$sql = "SELECT * FROM Nanomark_application 
				WHERE checkpoint = '$checkpoint'";
		else
			$sql = "SELECT * FROM Nanomark_application 
				WHERE checkpoint = '$checkpoint' AND applicant_ID = '$applicant_ID'";
		
		$query = $this->nanomark_db->query($sql);
		return $query->result_array();
	}
	public function get_outsourcing_list($options = array())
	{
		$sTable = "Nanomark_outsourcing";
		$sJoinTable = array("specimen"=>"Nanomark_specimen","application"=>"Nanomark_application","norm"=>"Nanomark_verification_norm");
		$this->nanomark_db->select("$sTable.specimen_SN,
									$sTable.verification_norm_no,
									$sTable.test_items_name_1,
									$sTable.test_items_1_amount,
									$sTable.test_items_name_2,
									$sTable.outsourcing_organization,
									$sTable.client_signature,
									$sTable.signature_date,
									{$sJoinTable['norm']}.name AS verification_norm_name,
									{$sJoinTable['application']}.serial_no AS application_SN,
									{$sJoinTable['application']}.ID AS application_ID,
									{$sJoinTable['application']}.applicant_ID,
									{$sJoinTable['application']}.report_title,
									{$sJoinTable['application']}.contact_name,
									{$sJoinTable['specimen']}.ID AS specimen_ID,
									{$sJoinTable['specimen']}.name AS specimen_name");
		$this->nanomark_db->from($sTable);
		$this->nanomark_db->join($sJoinTable['specimen'],"{$sJoinTable['specimen']}.serial_no = $sTable.specimen_SN");
		$this->nanomark_db->join($sJoinTable['application'],"{$sJoinTable['application']}.serial_no = {$sJoinTable['specimen']}.application_SN");
		$this->nanomark_db->join($sJoinTable['norm'],"{$sJoinTable['norm']}.serial_no = $sTable.verification_norm_no","LEFT");
		if(isset($options['specimen_SN']))
			$this->nanomark_db->where("specimen_SN",$options['specimen_SN']);
		if(isset($options['applicant_ID'])){
			$this->nanomark_db->where("{$sJoinTable['application']}.applicant_ID",$options['applicant_ID']);
		}
			
		return $this->nanomark_db->get();

	}
	public function get_outsourcing_list_array($input_data)
	{
		$sTable = "Nanomark_outsourcing";
		$sJoinTable = "Nanomark_specimen";
	  	$aColumns = array( "$sTable.serial_no"=>"serial_no", "$sTable.specimen_ID"=>"specimen_ID", "$sJoinTable.name"=>"specimen_name","$sTable.verification_norm_no"=>"verification_norm_no","$sTable.outsourcing_organization"=>"outsourcing_organization","$sTable.client_signature"=>"client_signature");
		$sJoin = "INNER JOIN $sJoinTable ON $sTable.specimen_ID = $sJoinTable.ID";
		$result = $this->get_jQ_DTs_array_with_join($this->nanomark_db,$sTable,$sJoin,$aColumns,$input_data);
		return $result;
	}
	public function get_outsourcing_by_SN($serial_no)
	{
		$sql = "SELECT * FROM Nanomark_outsourcing WHERE serial_no = '{$serial_no}'";
		$query = $this->nanomark_db->query($sql);
		if($query->num_rows())
		{
			$result = $query->row();
		}else{
			$result = FALSE;
		}
		
		return $result;
	}
	public function get_outsourcing_by_applicant_ID($ID)
	{
		$sql = "SELECT O.* FROM Nanomark_outsourcing O INNER JOIN Nanomark_application A ON O.application_ID = A.ID WHERE A.applicant_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		
		$result = $query->result();
		return $result;
	}
	public function get_specimen_list($options = array())
	{
		$sTable = "Nanomark_specimen";
		$sJoinTable = array("test_item"=>"Nanomark_test_item","facility"=>"cmnst_facility.facility_list","application"=>"Nanomark_application","outsourcing"=>"Nanomark_outsourcing");
		$this->nanomark_db->select("$sTable.serial_no AS specimen_SN,
									$sTable.application_SN AS application_SN,
									$sTable.ID AS ID,
									$sTable.test_item AS test_item,
									$sTable.name AS name,
									$sTable.company_name AS company_name,
									$sTable.brand AS brand,
									$sTable.model AS model,
									$sTable.facility_admin_ID AS facility_admin_ID,
									$sTable.facility_engineer_ID AS facility_engineer_ID,
									$sTable.checkpoint AS checkpoint,
									{$sJoinTable['application']}.ID AS application_ID,
									{$sJoinTable['application']}.report_title AS report_title,
									{$sJoinTable['outsourcing']}.specimen_SN AS outsourcing_SN,
									{$sJoinTable['outsourcing']}.client_signature AS client_signature,
									{$sJoinTable['test_item']}.facility_ID AS facility_ID,
									{$sJoinTable['test_item']}.name AS test_item_name,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name");
		$this->nanomark_db->join($sJoinTable['test_item'],"{$sJoinTable['test_item']}.serial_no = $sTable.test_item","LEFT");
		$this->nanomark_db->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = {$sJoinTable['test_item']}.facility_ID","LEFT");
		$this->nanomark_db->join($sJoinTable['application'],"{$sJoinTable['application']}.serial_no = $sTable.application_SN","LEFT");
		$this->nanomark_db->join($sJoinTable['outsourcing'],"{$sJoinTable['outsourcing']}.specimen_SN = $sTable.serial_no","LEFT");
		if(isset($options['specimen_SN']))
			$this->nanomark_db->where("$sTable.serial_no",$options['specimen_SN']);
		if(isset($options['application_SN']))
			$this->nanomark_db->where("$sTable.application_SN",$options['application_SN']);
//		if(isset($options['application_ID']))
//			$this->nanomark_db->where("$sTable.application_ID",$options['application_ID']);
		if(isset($options['applicant_ID']))
			$this->nanomark_db->where("{$sJoinTable['application']}.applicant_ID",$options['applicant_ID']);
		if(isset($options['specimen_ID']))
			$this->nanomark_db->where("$sTable.ID",$options['specimen_ID']);
		return $this->nanomark_db->get($sTable);
	}
	public function get_specimen_by_SN($serial_no)
	{
		$sql = "SELECT * FROM Nanomark_specimen WHERE serial_no = '{$serial_no}'";
		$query = $this->nanomark_db->query($sql);
		$result = $query->row();
		return $result;
	}
	public function get_specimen_by_ID($ID)
	{
		$sql = "SELECT Nanomark_application.*,Nanomark_specimen.* FROM Nanomark_specimen LEFT JOIN Nanomark_application ON Nanomark_application.ID = Nanomark_specimen.application_ID WHERE Nanomark_specimen.ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		return $result = $query->row_array();
	}
	public function get_specimen_by_application_ID($ID)
	{
		$sql = "SELECT * FROM Nanomark_specimen WHERE application_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		$result = $query->result();
		return $result;
	}
	public function get_specimen_by_applicant_ID($ID)
	{
		$sql = "SELECT Nanomark_application.*,Nanomark_specimen.* FROM Nanomark_specimen LEFT JOIN Nanomark_application ON Nanomark_application.ID = Nanomark_specimen.application_ID WHERE Nanomark_application.applicant_ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		return $query->result_array();
	}
	public function add_test_item($input_data)
	{
		$sql = "INSERT INTO Nanomark_test_item SET 
		name = '{$input_data['name']}',
		facility_ID = ?";
		$arr = array($input_data['facility_ID']);
		$query = $this->nanomark_db->query($sql,$arr);
		return TRUE;
	}
	public function get_test_item_list($options = array())
	{
		$sTable = "Nanomark_test_item";
		$sJoinTable = array("facility"=>"cmnst_facility.facility_list");
		$this->nanomark_db->select("$sTable.*,
									{$sJoinTable['facility']}.cht_name AS facility_cht_name,
									{$sJoinTable['facility']}.eng_name AS facility_eng_name");
		$this->nanomark_db->join($sJoinTable['facility'],"{$sJoinTable['facility']}.ID = $sTable.facility_ID","LEFT");
		if(isset($options['serial_no']))
			$this->nanomark_db->where("serial_no",$options['serial_no']);
		return $this->nanomark_db->get("Nanomark_test_item");
	}
	public function get_test_items_list()
	{
		$sql = "SELECT * FROM Nanomark_test_item";
		$query = $this->nanomark_db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function get_test_item_by_SN($SN)
	{
		$sql = "SELECT * FROM Nanomark_test_item WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		$result = $query->row_array();
		return $result;
	}
	public function get_report_revision_list($options = array())
	{
		$sTable = "Nanomark_revision";
		$sJoinTable = array("specimen"=>"Nanomark_specimen","application"=>"Nanomark_application");
		$this->nanomark_db->select("
			$sTable.*,
			{$sJoinTable['application']}.serial_no AS application_SN
		")
						  ->from($sTable)
						  ->join($sJoinTable['specimen'],"{$sJoinTable['specimen']}.serial_no = $sTable.specimen_SN","LEFT")
						  ->join($sJoinTable['application'],"{$sJoinTable['application']}.serial_no = {$sJoinTable['specimen']}.application_SN","LEFT");
		if(isset($options['applicant_ID']))
		{
			$this->nanomark_db->where("{$sJoinTable['application']}.applicant_ID",$options['applicant_ID']);
		}
		if(isset($options['specimen_SN']))
		{
			$this->nanomark_db->where("{$sJoinTable['specimen']}.serial_no",$options['specimen_SN']);
		}
		if(isset($options['revision_SN']))
		{
			$this->nanomark_db->where("$sTable.serial_no",$options['revision_SN']);
		}
		return $this->nanomark_db->get();
		
	}
	public function get_report_revision_list_array($input_data)
	{
		$sTable = "Nanomark_revision";
		$sJoinTable = "";
	  	$aColumns = array( "$sTable.application_date"=>"application_date", "$sTable.application_ID"=>"application_ID","$sTable.report_ID"=>"report_ID","$sTable.mistake_description"=>"mistake_description","$sTable.checkpoint"=>"checkpoint","$sTable.serial_no"=>"serial_no");
		$sJoin = "";
		$result = $this->get_jQ_DTs_array_with_join($this->nanomark_db,$sTable,$sJoin,$aColumns,$input_data);
		return $result;
	}
	public function get_report_revision_by_applicant_ID($user_ID)
	{
		$sql = "SELECT r.* FROM Nanomark_revision AS r,Nanomark_application AS a 
				WHERE r.application_ID = a.ID AND a.applicant_ID = '{$user_ID}'";
		$query = $this->nanomark_db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function get_report_revision_by_SN($SN)
	{
		$sql = "SELECT * FROM Nanomark_revision 
				WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		$result = $query->row_array();
		return $result;
	}
	public function update_report_revision($SN,$privilege,$comment,$admin_ID = "")
	{
		if(empty($admin_ID)) $admin_ID=$this->session->userdata('ID');
		
		$sql = "UPDATE Nanomark_revision 
				SET {$privilege}_comment = '{$comment}',
					{$privilege}_ID = CONCAT_WS(',',{$privilege}_ID,'{$admin_ID}') 
				WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		return $this->nanomark_db->affected_rows();
	}
	public function update_report_revision_checkpoint($SN,$checkpoint)
	{
		$sql = "UPDATE Nanomark_revision 
				SET checkpoint = '{$checkpoint}' 
				WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		return $this->nanomark_db->affected_rows();
	}
	public function update_test_item_by_serial_no($SN,$input_data)
	{
		$sql = "UPDATE Nanomark_test_item SET 
		name = ?,
		facility_ID = ? 
		WHERE serial_no = ?";
		$arr = array($input_data['name'],$input_data['facility_ID'],$SN);
		$query = $this->nanomark_db->query($sql,$arr);
		
		return TRUE;
	}
	

	public function update_outsourcing($input_data)
	{
		if(isset($input_data['verification_norm_no']))
			$this->nanomark_db->set("verification_norm_no",$input_data['verification_norm_no']);
		if(isset($input_data['test_items_name_1']))
			$this->nanomark_db->set("test_items_name_1",$input_data['test_items_name_1']);
		if(isset($input_data['test_items_1_amount']))
			$this->nanomark_db->set("test_items_1_amount",$input_data['test_items_1_amount']);
		if(isset($input_data['test_items_name_2']))
			$this->nanomark_db->set("test_items_name_2",$input_data['test_items_name_2']);
		if(isset($input_data['outsourcing_organization']))
			$this->nanomark_db->set("outsourcing_organization",$input_data['outsourcing_organization']);
		if(isset($input_data['client_signature'])){
			$this->nanomark_db->set("client_signature",$input_data['client_signature']);
			$this->nanomark_db->set("signature_date",date("Y-m-d H:i:s"));
		}
		$this->nanomark_db->where("specimen_SN",$input_data['specimen_SN']);
		$this->nanomark_db->update("Nanomark_outsourcing");
		return $this->nanomark_db->affected_rows();
	}
	public function delete_quotation_test_item_by_quotation_SN($SN)
	{
		$sql = "DELETE FROM Nanomark_quotation_test_item WHERE quotation_ID = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function delete_application($ID)
	{
		$sql = "DELETE FROM Nanomark_application WHERE ID = '{$ID}'";
		$query = $this->nanomark_db->query($sql);
		
		return TRUE;
	}
	public function delete_specimen($options = array())
	{
		if(isset($options['specimen_SN']))
			$this->nanomark_db->where("serial_no",$options['specimen_SN']);
		if(isset($options['application_SN']))
			$this->nanomark_db->where("application_SN",$options['application_SN']);
		$this->nanomark_db->delete("Nanomark_specimen");
	}
	
	public function delete_outsourcing($SN)
	{
		$this->nanomark_db->where("serial_no",$SN);
		$this->nanomark_db->delete("Nanomark_outsourcing");
		return $this->nanomark_db->affected_rows();
	}
	
	public function add_customer_survey($input_data)
	{
		$this->nanomark_db->set("application_SN",$input_data['application_SN']);
		$this->nanomark_db->set("overall_quality",$input_data['overall_quality']);
		$this->nanomark_db->set("request_form",$input_data['request_form']);
		$this->nanomark_db->set("response_question",$input_data['response_question']);
		$this->nanomark_db->set("communication",$input_data['communication']);
		$this->nanomark_db->set("time_test",$input_data['time_test']);
		$this->nanomark_db->set("report",$input_data['report']);
		$this->nanomark_db->set("price",$input_data['price']);
		$this->nanomark_db->set("reason",$input_data['reason']);
		$this->nanomark_db->set("recommendation",$input_data['recommendation']);
		$this->nanomark_db->set("completed_by",$input_data['completed_by']);
		$this->nanomark_db->set("completed_date",$input_data['completed_date']);
		$this->nanomark_db->insert("Nanomark_customer_survey");
	}
	public function get_customer_survey_list($options = array())
	{
		$sTable = "Nanomark_customer_survey";
		$sJoinTable = array("application"=>"Nanomark_application","constant_checkpoint"=>"constant_nanomark_application_checkpoint");
		$this->nanomark_db->select("$sTable.serial_no AS customer_survey_SN,
									$sTable.application_SN AS application_SN,
									$sTable.overall_quality AS overall_quality,
									$sTable.request_form AS request_form,
									$sTable.response_question AS response_question,
									$sTable.communication AS communication,
									$sTable.time_test AS time_test,
									$sTable.report AS report,
									$sTable.price AS price,
									$sTable.reason AS reason,
									$sTable.recommendation AS recommendation,
									$sTable.completed_by AS completed_by,
									$sTable.completed_date AS completed_date,
									{$sJoinTable['application']}.serial_no AS application_SN,
									{$sJoinTable['application']}.ID AS application_ID,
									{$sJoinTable['application']}.applicant_ID AS applicant_ID,
									{$sJoinTable['application']}.report_title AS report_title")
						  ->from($sTable)
						  ->join($sJoinTable['application'],"{$sJoinTable['application']}.serial_no = $sTable.application_SN","RIGHT");
		$this->nanomark_db->where("{$sJoinTable['application']}.checkpoint >=","(SELECT application_checkpoint_no FROM {$sJoinTable['constant_checkpoint']} WHERE application_checkpoint_ID = 'Client_Final')",FALSE);
		if(isset($options['customer_survey_SN']))
		{
			$this->nanomark_db->where("$sTable.serial_no",$options['customer_survey_SN']);
		}
		if(isset($options['applicant_ID']))
		{
			$this->nanomark_db->where("{$sJoinTable['application']}.applicant_ID",$options['applicant_ID']);
		}
		return $this->nanomark_db->get();
		
	}
	public function get_customer_survey_list_array($input_data)
	{
		$sTable = "Nanomark_customer_survey";
		$sJoinTable = "";
	  	$aColumns = array( "$sTable.serial_no"=>"serial_no","$sTable.application_ID"=>"application_ID", "$sTable.completed_by"=>"completed_by","$sTable.completed_date	"=>"completed_date	");
		$sJoin = "";
		$result = $this->get_jQ_DTs_array_with_join($this->nanomark_db,$sTable,$sJoin,$aColumns,$input_data);
		return $result;
	}
	public function get_customer_survey_by_SN($SN)
	{
		$sql = "SELECT * FROM Nanomark_customer_survey WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		if($query->num_rows())
			return $query->row();
		else
			return FALSE;
	}
	public function get_customer_survey_by_applicant_ID($user_ID)
	{
		$sql = "SELECT c.* FROM Nanomark_customer_survey AS c,Nanomark_application AS a 
				WHERE c.application_ID = a.ID AND a.applicant_ID = '{$user_ID}'";
		$query = $this->nanomark_db->query($sql);
		return $query->result_array();
	}
	public function get_customer_survey_by_applcation_ID($app_ID)
	{
		$sql = "SELECT * FROM Nanomark_customer_survey WHERE application_ID = '{$app_ID}'";
		$query = $this->nanomark_db->query($sql);
		if($query->num_rows())
			return $query->row_array();
		else
			return FALSE;
	}
	public function update_application_checkpoint($app_SN,$checkpoint)
	{
		$this->nanomark_db->set("checkpoint",$checkpoint)
						  ->where("serial_no",$app_SN)
						  ->update("Nanomark_application");
		return $this->nanomark_db->affected_rows();
	}
	public function get_admin_privilege_by_privilege($privilege)
	{
		$sql = "SELECT * FROM Nanomark_admin_privilege 
				WHERE privilege = '{$privilege}'";
		$query = $this->nanomark_db->query($sql);
		return $query->result_array();
	}
	public function get_admin_privilege_list($options = array())
	{
	  	$sTable = "Nanomark_admin_privilege";
	  	$sJoinTable = array("user"=>"cmnst_common.user_profile");
	  	$this->nanomark_db->select("*,
	  								{$sJoinTable['user']}.name AS admin_name,
	  								{$sJoinTable['user']}.email AS admin_email")
	  					  ->from($sTable)
	  					  ->join($sJoinTable['user'],"{$sJoinTable['user']}.ID = $sTable.admin_ID");
	  	if(isset($options['privilege']))
	  		$this->nanomark_db->where_in("privilege",$options['privilege']);
	  	if(isset($options['admin_ID']))
	  		$this->nanomark_db->where("admin_ID",$options['admin_ID']);
	  	return $this->nanomark_db->get();

	}
	public function add_admin_privilege($admin_ID,$privilege)
	{
		$sql = "INSERT INTO Nanomark_admin_privilege 
				SET admin_ID = '{$admin_ID}',
					privilege = '{$privilege}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function delete_admin_privilege_by_privilege($privilege)
	{
		$sql = "DELETE FROM Nanomark_admin_privilege 
				WHERE privilege = '{$privilege}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function delete_admin_privilege_by_admin_ID($admin_ID)
	{
		$sql = "DELETE FROM Nanomark_admin_privilege 
				WHERE admin_ID = '{$admin_ID}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function delete_admin_privilege($admin_ID,$privilege)
	{
		$sql = "DELETE FROM Nanomark_admin_privilege 
				WHERE admin_ID = '{$admin_ID}' AND 
					  privilege = '{$privilege}'";
		$query = $this->nanomark_db->query($sql);
		return TRUE;
	}
	public function get_verification_norm_list()
	{
		return $this->nanomark_db->get("Nanomark_verification_norm");
	}
	public function get_verification_norm()
	{
		$sql = "SELECT * FROM Nanomark_verification_norm";
		$query = $this->nanomark_db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	public function add_verification_norm($input_data)
	{
		$sql = "INSERT INTO Nanomark_verification_norm 
				SET name = '{$input_data['name']}'";
		$query = $this->nanomark_db->query($sql);
		return $this->nanomark_db->affected_rows();
	}
	public function delete_verification_norm($SN)
	{
		$sql = "DELETE FROM Nanomark_verification_norm
				WHERE serial_no = '{$SN}'";
		$query = $this->nanomark_db->query($sql);
		return $this->nanomark_db->affected_rows();
	}
	//-----------------------通用-------------------------
	public function get_verification_norm_select_options()
	{
		$results = $this->get_verification_norm_list()->result_array();
		$output = array(""=>"");
		foreach($results as $row){
			$output[$row['serial_no']] = $row['name'];
		}
		return $output;
	}
	public function get_application_unsigned_facility_admin($app_SN)
	{
		$specimen = $this->get_specimen_list(array("application_SN"=>$app_SN))->result_array();
		//取得所有未審核的機台負責人
		$admin_ID = array();
		foreach($specimen as $row)
		{
			if(empty($row['facility_admin_ID']))
			{
				//根據測試項目，取得對應機台
				if(empty($row['test_item']))	continue;
				$test_item = $this->get_test_item_list(array("serial_no"=>$row['test_item']))->row_array();
				if(!$test_item) throw new Exception("無此檢測項目",ERROR_CODE);
				//再利用對應機台，找尋管理員
				if(empty($test_item['facility_ID'])) continue;
				$this->load->model('facility_model');
				$facility_admin_privilege = $this->facility_model->get_user_privilege_list(
				array("facility_ID"=>$test_item['facility_ID'],
					  "privilege"=>"admin")
				)->result_array();
				
				foreach($facility_admin_privilege as $row2)
				{
					array_push($admin_ID,$row2['user_ID']);
				}
			}
		}
		$admin_ID = array_unique($admin_ID);
		return $admin_ID;
	}
	public function get_application_signed_facility_admin($app_SN)
	{
		$specimen = $this->get_specimen_list(array("application_SN"=>$app_SN))->result_array();
		$signed_admin_ID = array();
		foreach($specimen as $row)
		{
			if(!empty($row['facility_admin_ID']))
			{
				array_push($signed_admin_ID,$row['facility_admin_ID']);
			}
		}
		$signed_admin_ID = array_unique($signed_admin_ID);
		$unsigned_admin_ID = $this->get_application_unsigned_facility_admin($app_SN);
		$signed_admin_ID = array_diff($signed_admin_ID,$unsigned_admin_ID);
		return $signed_admin_ID;
	}
	public function get_report_revision_unsigned_admin_by_checkpoint($revision_SN,$checkpoint)
	{
		$report_revision = $this->get_report_revision_list(array("revision_SN"=>$revision_SN))->row_array();
		$application = $this->get_application_list(array("serial_no"=>$report_revision['application_SN']))->row_array();
		$unsigned_admin_ID = array();
		
		$checkpoint = str_replace(" ","_",strtolower($checkpoint));
		if($checkpoint == "technical_manager" || $checkpoint == "report_signatory")
		{
			foreach($this->test_outline as $row)
			{
				if(!in_array($row,explode(",",$application->test_outline))) continue;
				
				$admin_privilege = $this->get_admin_privilege_by_privilege("revision_{$checkpoint}_{$row}");
				$admin_privilege = $this->rotate_2D_array($admin_privilege);
				if(!$this->array_in_array($admin_privilege['admin_ID'],explode(",",$report_revision["{$checkpoint}_ID"])))
				{
					$unsigned_admin_ID = array_merge($admin_privilege['admin_ID'],$unsigned_admin_ID);
				}
			}
		}else{
			$admin_privilege = $this->get_admin_privilege_by_privilege("revision_{$checkpoint}");
			foreach($admin_privilege as $row)
			{
				if(!in_array($row['admin_ID'],explode(",",$report_revision["{$checkpoint}_ID"])))
				{
					array_push($unsigned_admin_ID,$row['admin_ID']);
				}
			}
		}
		$unsigned_admin_ID = array_unique($unsigned_admin_ID);
		return $unsigned_admin_ID;
	}
	//---------------------權限確認------------------------
	public function is_super_admin($admin_ID = NULL)
	{
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		$data = array(	"admin_ID"=>$admin_ID,
						"privilege"=>"nanomark_super_admin");
		$result = $this->get_admin_privilege_list($data)->row_array();
		return $result;
	}
	private $is_app_case_officer_1st = NULL;
	public function is_application_case_officer_1st($admin_ID = NULL)
	{
		if(isset($this->is_app_case_officer_1st)) return $this->is_app_case_officer_1st;
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		$data = array(	"admin_ID"=>$admin_ID,
						"privilege"=>"application_case_officer_1st");
		$this->is_app_case_officer_1st = $this->get_admin_privilege_list($data)->row_array();
		return $this->is_app_case_officer_1st;
	}
	public function is_application_case_officer_2nd($admin_ID = NULL)
	{
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		$data = array(	"admin_ID"=>$admin_ID,
						"privilege"=>"application_case_officer_2nd");
		$result = $this->get_admin_privilege_list($data)->row_array();
		return $result;
	}
	public function is_application_case_officer_final($admin_ID = NULL)
	{
		if(empty($admin_ID)) $admin_ID = $this->session->userdata('ID');
		
		$data = array(	"admin_ID"=>$admin_ID,
						"privilege"=>"application_case_officer_final");
		$result = $this->get_admin_privilege_list($data)->row_array();
		return $result;
	}
	//------------------------email sender---------------------------------
	public function send_officer_1st_notification($app_SN)
	{
		$options = array("privilege"=>"application_case_officer_1st");
		$admin_profile = $this->get_admin_privilege_list($options)->row_array();
		if(!empty($admin_profile) && !empty($admin_profile['admin_email'])){
			$options = array("serial_no"=>$app_SN);
			$application = $this->get_application_list($options)->row_array();
			$this->email->to($admin_profile['admin_email']);
			$this->email->subject("成大微奈米技研中心 -奈米標章審核通知-");
			$this->email->message(
				"{$admin_profile['admin_name']} 您好：<br>
				客戶 {$application['report_title']} 申請了奈米標章委託，請上本中心網站審核，謝謝。"
			);
			$this->email->send();
		}
	}
	public function send_officer_2nd_notification($app_SN)
	{
		$options = array("privilege"=>"application_case_officer_2nd");
		$admin_profile = $this->get_admin_privilege_list($options)->row_array();
		if(!empty($admin_profile) && !empty($admin_profile['admin_email'])){
			$options = array("serial_no"=>$app_SN);
			$application = $this->get_application_list($options)->row_array();
			$this->email->to($admin_profile['admin_email']);
			$this->email->subject("成大微奈米技研中心 -奈米標章審核通知-");
			$this->email->message(
				"{$admin_profile['admin_name']} 您好：<br>
				客戶 {$application['report_title']} 申請了奈米標章委託，請上本中心網站審核，謝謝。"
			);
			$this->email->send();
		}
	}
	public function send_facility_admin_notification($app_SN)
	{
		$options = array("serial_no"=>$app_SN);
		$application = $this->get_application_list($options)->row_array();
		if(!$application) throw new Exception("無此委託單，無法送信",ERROR_CODE);
		//取得需要審核的admin email
		$admin_IDs = $this->get_application_unsigned_facility_admin($app_SN);
		$this->load->model('user_model');
		$admin_profiles = $this->user_model->get_user_profile_list(
			array("user_ID"=>$admin_IDs)
		)->result_array();
		if($admin_profiles){
			foreach($admin_profiles as $admin_profile)
			{
				$admin_email[] = $admin_profile['email'];
			}
			$this->email->to($admin_email);
			$this->email->subject("成大微奈米技研中心 -奈米標章檢測件審核通知-");
			$this->email->message(
				"{$admin_profile['name']} 您好：<br>
				客戶 {$application['report_title']} 申請了奈米標章委託，其中有部分檢測件為您所管理之儀器，故請上本中心網站預約並審核，謝謝。"
			);
			$this->email->send();
		}
		
		
	}
	public function send_officer_final_notification($app_SN)
	{
		$options = array("privilege"=>"application_case_officer_final");
		$admin_profile = $this->get_admin_privilege_list($options)->row_array();
		if(!empty($admin_profile) && !empty($admin_profile['admin_email'])){
			$options = array("serial_no"=>$app_SN);
			$application = $this->get_application_list($options)->row_array();
			$this->email->to($admin_profile['admin_email']);
			$this->email->subject("成大微奈米技研中心 -奈米標章審核通知-");
			$this->email->message(
				"{$admin_profile['admin_name']} 您好：<br>
				{$application['report_title']} 所申請之奈米標章委託已全部檢測完畢，請上本中心網站確認，謝謝。"
			);
			$this->email->send();
		}
	}
	public function send_customer_survey_notification($app_SN)
	{
		$options = array("serial_no"=>$app_SN);
		$application = $this->get_application_list($options)->row_array();
		
		//取得使用者資料
		$this->load->model('user_model');
		$options['user_ID'] = $application['applicant_ID'];
		$user_profile = $this->user_model->get_user_profile_list($options)->row_array();
		
		if(!empty($user_profile) && !empty($user_profile['email'])){
			
			$this->email->to($user_profile['email']);
			$this->email->subject("成大微奈米技研中心 -奈米標章檢測完工通知-");
			$this->email->message(
				"{$user_profile['name']} 您好：<br>
				貴公司 {$application['report_title']} 所申請之奈米標章委託已全部檢測完畢，請上本中心網站確認並填寫滿意度調查表，謝謝。"
			);
			$this->email->send();
		}
	}
	public function send_completed_notification($app_SN)
	{
		$options = array("serial_no"=>$app_SN);
		$application = $this->get_application_list($options)->row_array();
		
		$options = array("privilege"=>array("application_case_officer_1st",
											"application_case_officer_2nd",
											"application_case_officer_final"));
		$admin_profiles = $this->get_admin_privilege_list($options)->result_array();
		if($admin_profiles){
			foreach($admin_profiles as $admin_profile){
				$admin_email[] = $admin_profile['admin_email'];
			}
			$this->email->to(array_unique($admin_email));
			$this->email->subject("成大微奈米技研中心 -奈米標章檢測客戶簽收通知-");
			$this->email->message(
				"您好：<br>
				客戶 {$application['report_title']} 所申請之奈米標章委託已簽收，可上本中心網站確認，如有需要可列印保存，謝謝。"
			);
			$this->email->send();
		}
		
	}
}