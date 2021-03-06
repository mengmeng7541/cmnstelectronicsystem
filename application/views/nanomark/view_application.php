<table id="table_application" width="940" cellpadding="0" cellspacing="0">
<style type="text/css">
#table_application {
  table-layout:fixed;
  border-collapse:collapse;
} 
#table_application th {
	
} 
#table_application td { 
  border:1px solid black;
} 
#table_application table{
  border-collapse:collapse;
} 
#table_application table th {
	border:1px solid black;
} 
#table_application table td { 
  border:1px solid black;
} 
</style> 
  <thead>
  <tr>
  	<th width="20"></th>
    <th width="60"></th>
    <th width="100"></th>
    <th width="60"></th>
    <th width="158"></th>
    <th width="180"></th>
    <th width="70"></th>
    <th width="110"></th>
    <th width="180"></th>
  </tr>
  <tr>
    <th colspan="2" rowspan="2" align="right"><img src="<?=base_url();?>img/CMNST.png" alt="" name="cmnst_logo" style="width:80px;height:80px;" id="cmnst_logo" /></th>
    <th colspan="2" rowspan="2" align="left">國立成功大學 <br>
      微奈米科技研究中心</th>
    <th colspan="2" rowspan="2" align="center"><h3>奈米技術產品測試實驗室<br>
      檢測委託單</h3></th>
    <th rowspan="2" align="center">&nbsp;</th>
    <th colspan="2" align="left"><h6>地址：701 台南市大學路 1 號<br>
      自強校區 科技大樓 5 樓 微奈米中心<br>
      電話：(06)2080017 傳真：(06)-2080103</h6></th>
  </tr>
  <tr>
    <td align="center">委託單編號</td>
    <td align="center">申 請 日</td>
  </tr>
  <tr>
    <th colspan="7">
      <label class="checkbox inline"><?=isset($test_outline)&&in_array("scale",$test_outline)?"■":"□";?>奈米尺度測試</label>
      <label class="checkbox inline"><?=isset($test_outline)&&in_array("functionality",$test_outline)?"■":"□";?>奈米功能測試</label>
      <label class="checkbox inline"><?=isset($test_outline)&&in_array("biocompatibility",$test_outline)?"■":"□";?>生物相容性測試</label>
      /
      <label class="radio inline"><?=!isset($priority)||$priority=="Standard"?"■":"□";?>普通件</label>
      <label class="radio inline"><?=isset($priority)&&$priority=="Priority"?"■":"□";?>速件</label>
    </th>
    <td align="center"><?=isset($ID)?$ID:"";?></td>
    <td align="center"><?=isset($application_date)?$application_date:"";?></td>
  </tr>
  <tr>
    <td>客戶資料</td>
    <td colspan="8">報告抬頭/廠商：<?=isset($report_title)?$report_title:"";?></div>
      
      收據抬頭：<?=isset($receipt_title)?$receipt_title:"";?>
      <br>
      報告地址：<?=isset($report_address)?$report_address:"";?>
      <br>
      郵寄地址：<?=isset($mail_address)?$mail_address:"";?>
      (報告書與收據之郵寄地址) 營利事業統一編號：<?=isset($VAT)?$VAT:"";?>
      
	  <br>
      聯絡人：<?=isset($contact_name)?$contact_name:"";?>
      
      聯絡電話：<?=isset($contact_tel)?$contact_tel:"";?>
      手機：<?=isset($contact_mobile)?$contact_mobile:"";?>
      
      傳真：<?=isset($contact_FAX)?$contact_FAX:"";?>
      
      Email：<?=isset($contact_email)?$contact_email:"";?>
    </td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>測試件資訊</td>
    <td colspan="8" align="center" valign="top">
    	<?
    		$this->table->set_template(array ( 'table_open'  => '<table id="table_nanomark_application_specimen" style="width:100%;">' ));
    		$this->table->set_heading(	array('width'=>80,'data'=>'編號'),
    									array('width'=>200,'data'=>'檢測項目'),
    									'樣品名稱',
    									'廠商名稱',
    									'產品廠牌',
    									'產品型號');
    		$specimens = isset($specimens)?$specimens:array("");
			foreach($specimens as $sp){
				
				$this->table->add_row(	isset($sp['ID'])?$sp['ID']:"",
										isset($test_item_select_options)?(isset($sp['test_item'])?$test_item_select_options[$sp['test_item']]:""):"",
										isset($sp['name'])?$sp['name']:"",
										isset($sp['company_name'])?$sp['company_name']:"",
										isset($sp['brand'])?$sp['brand']:"",
										isset($sp['model'])?$sp['model']:"");
			}
			
			echo $this->table->generate();
    	?>
	</td>
    </tr>
  
  </tbody>
  <tfoot>
  <tr>
    <td rowspan="8">注意事項</td>
    <td colspan="5" rowspan="8"><h6>1. 普通件(8-14 個工作天完成)，速件(5-7 個工作天完成)，速件費用為普通件 1.5 倍；抗菌及生物相容性檢測另議。 <br>
2. 請客戶先行處理委託件(與其附件)之清潔工作，再送至本實驗室測試。 <br>
3. 請客戶於預定完工日前惠付測試費用，以利於完工日後領取測試件與測試報告。 <br>
4. 本委託單經客戶(委託人)與收件人(受託人)簽章後，即視同「委託契約」並具效力。 <br>
5. 若客戶不另行指定測試件的測試點數，本實驗室將以基本點數進行測試。 <br>
6. 測試件與測試報告領回後，如有疑問，請於壹週內提出報告修改申請，逾期概不受理。 <br>
7. 於預定完工日後，本實驗室代管其測畢件最長為壹週，逾期不領回，致遭損失恕不負責。 <br>
8. 客戶取件時，請攜帶本委託單為憑證。 <br>
9. 本實驗室測試報告僅供經濟部奈米標章申請用，各項測試數據非經本實驗室同意不得用於商業廣 告之標示、法律訴訟之證據等其他用途，違者本實驗室得依法追訴。 <br>
10.送件充分了解該測試之樣品處理與量測方式，以及可能影響測試結果之變因，有任何疑問應於送達前與實驗室人員進行討論。<br>
11.工作天數須扣除例假日及委託當日 12:00 PM 後不予計算，且於初次委託者需於付費後開始計算工作天數。</h6></td>
    <td colspan="2" align="center">工作起始日</td>
    <td align="center">預定完工日</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><?=isset($work_start_date)?$work_start_date:"";?></td>
    <td align="center"><?=isset($scheduled_completion_date)?$scheduled_completion_date:"";?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">費 用 合 計</td>
    </tr>
  <tr>
    <td colspan="3" align="right"><?=isset($total_fees)?$total_fees:"0";?></td>
    </tr>
  <tr>
    <td align="center">付 款</td>
    <td colspan="2" align="center">報告份數</td>
  </tr>
  <tr>
    <td align="center">
		<label class="radio"><?=isset($when_pay)&&$when_pay=="Send"?"■":"□";?>送件時</label><br>
		<label class="radio"><?=isset($when_pay)&&$when_pay=="Receive"?"■":"□";?>取件時</label>
    </td>
    <td colspan="2" align="center" style="font-size:12px;">
	尺度<?=isset($num_report_copies_scale)?$num_report_copies_scale:"0";?>份；<label class="radio"><?=isset($report_logo_scale)&&$report_logo_scale?"■":"□";?>含LOGO</label><label class="radio"><?=isset($report_logo_scale)&&!$report_logo_scale?"■":"□";?>不含LOGO</label><br>
	功能性<?=isset($num_report_copies_functionality)?$num_report_copies_functionality:"0";?>份；<label class="radio"><?=isset($report_logo_functionality)&&$report_logo_functionality?"■":"□";?>含LOGO</label><label class="radio"><?=isset($report_logo_functionality)&&!$report_logo_functionality?"■":"□";?>不含LOGO</label><br>
	生物相容性<?=isset($num_report_copies_biocompatibility)?$num_report_copies_biocompatibility:"0";?>份；<label class="radio"><?=isset($report_logo_biocompatibility)&&$report_logo_biocompatibility?"■":"□";?>含LOGO</label><label class="radio"><?=isset($report_logo_biocompatibility)&&!$report_logo_biocompatibility?"■":"□";?>不含LOGO</label>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">檢測方法</td>
    <td align="center">備 註</td>
    </tr>
  <tr>
  	<td colspan="2" rowspan="3"><?=isset($verification_norm)?$verification_norm:"";?></td>
    <td rowspan="3"><?=isset($comments)?$comments:"";?></td>
    </tr>
  <tr>
    <td colspan="3" align="center">客 戶 簽 章</td>
    <td colspan="2" align="center">收 件 人 簽 章</td>
    <td align="center">取 件 人 簽 章</td>
    </tr>
  <tr>
    <td colspan="3" align="center"><?=isset($client_signature)?$client_signature:""; ?></td>
    <td colspan="2" align="center">
    <?
    	if(isset($stamp_url) && is_array($stamp_url))
    	{
			foreach($stamp_url as $stamp)
	    	{
				echo img($stamp);
			}
		}
    	
    ?>
    </td>
    <td align="center"><?=isset($consignee_signature)?$consignee_signature:""; ?></td>
    </tr>
  <tr>
    <th colspan="9" align="right"><h6>表單編號：DQP-03-01
    版次：5.0</h6></th>
  </tr>
  </tfoot>	
</table>