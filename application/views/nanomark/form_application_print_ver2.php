<table width="940">
<style type="text/css">
table {
  border-collapse:collapse;
} 
th {
	
} 
td { 
  border:1px solid black;
} 

</style> 
  <thead>
  <tr>
    <th colspan="2" rowspan="2" align="right"><img name="cmnst_logo" src="/img/CMNST.png" width="80" height="80" alt=""></th>
    <th colspan="2" rowspan="2" align="left">國立成功大學 <br>
      微奈米科技研究中心</th>
    <th colspan="2" rowspan="2" align="center"><h3>奈米技術產品測試實驗室<br>
    檢測委託單</h3></th>
    <th rowspan="2">&nbsp;</th>
    <th colspan="2" align="left"><h6>地址：701 台南市大學路 1 號<br>
      自強校區 科技大樓 5 樓 微奈米中心<br>
      電話：(06)2080017 傳真：(06)-2080103</h6></th>
  </tr>
  <tr>
    <td width="12%" align="center">委託單編號</td>
    <td align="center">申 請 日</td>
  </tr>
  <tr>
    <th colspan="7">
      <label class="checkbox inline"><?php echo $is_scale_test; ?>奈米尺度測試</label>
      <label class="checkbox inline"><?php echo $is_functionality_test; ?>奈米功能測試</label>
      <label class="checkbox inline"><?php echo $is_biocompatibility_test; ?>生物相容性測試</label>
      /
      <label class="radio inline"><?php echo $standard_priority; ?>普通件</label>
      <label class="radio inline"><?php echo $priority; ?>速件</label>
    </th>
    <td align="center"><?php echo $application_ID; ?></td>
    <td align="center"><?php echo $application_date; ?></td>
  </tr>
  <tr>
    <td>客戶資料</td>
    <td colspan="8">報告抬頭/廠商：<?php echo $report_title; ?>
      
      收據抬頭：
      <input type="checkbox" name="as_report_title" value="1" >
      報告抬頭 <?php echo $receipt_title; ?>
      <br>
      報告地址：<?php echo $report_address; ?>
      <br>
      郵寄地址：<?php echo $mail_address; ?>
      (報告書與收據之郵寄地址) 營利事業統一編號：<?php echo $VAT; ?>
      
	  <br>
      聯絡人：<?php echo $contact_name; ?>
      
      聯絡電話：<?php echo $contact_tel; ?>
      手機：<?php echo $contact_mobile; ?>
      
      傳真：<?php echo $contact_FAX; ?>
      
      Email：<?php echo $contact_email; ?>
    </td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="2%" rowspan="3">測試件資訊</td>
    <td width="6%" align="center">編號</td>
    <td width="12%" align="center">檢測項目</td>
    <td width="20%" colspan="2" align="center">樣品名稱</td>
    <td width="20%" align="center">廠商名稱</td>
    <td colspan="2" align="center">產品廠牌</td>
    <td width="20%" align="center">產品型號</td>
  </tr>
  <?php echo $specimen; ?>
  
  <tr id="last_row">
    <td><a class="btn" id="add_row_btn">新增</a></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </tbody>
  <tfoot>
  <tr>
    <td rowspan="8">注意事項</td>
    <td colspan="5" rowspan="8"><h5>1. 普通件(8-14 個工作天完成)，速件(5-7 個工作天完成)，<u>速件</u>費用為普通件 1.5 倍<u>；抗菌及生物相容性檢測另議</u>。 <br>
2. 請客戶先行處理委託件(與其附件)之清潔工作，再送至本實驗室測試。 <br>
3. 請客戶於預定完工日前惠付測試費用，以利於完工日後領取測試件與測試報告。 <br>
4. 本委託單經客戶(委託人)與收件人(受託人)簽章後，即視同「委託契約」並具效力。 <br>
5. 若客戶不另行指定測試件的測試點數，本實驗室將以基本點數進行測試。 <br>
6. 測試件與測試報告領回後，如有疑問，<u>請於</u>壹週內提出報告修改申請，逾期概不受理。 <br>
7. <u>於</u>預定完工日後，本實驗室代管<u>其測畢件</u>最長<u>為</u>壹週，逾期不領回，致遭損失恕不負責。 <br>
8. 客戶取件時，請攜帶本委託單為憑證。 <br>
9. 本實驗室測試報告僅供經濟部奈米標章申請用，各項測試數據非經本實驗室同意不得用於商業廣 告之標示、法律訴訟之證據等其他用途，違者本實驗室得依法追訴。 <br>
10.送件充分了解該測試之樣品處理與量測方式，以及可能影響測試結果之變因，<u>有任何疑問應於送達前與實驗室人員進行討論</u>。<br>
11.工作天數須扣除例假日及委託當日 12:00 PM 後不予計算，<u>且於初</u>次委託<u>者需於</u>付費後開始計算工作天數。</h5></td>
    <td colspan="2" align="center">工作起始日</td>
    <td align="center">預定完工日</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><?php echo $work_start_date; ?></td>
    <td align="center"><?php echo $scheduled_completion_date; ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">費 用 合 計</td>
    </tr>
  <tr>
    <td colspan="3" align="right"><?php echo $total_fees; ?></td>
    </tr>
  <tr>
    <td width="8%" align="center">付 款</td>
    <td colspan="2" align="center">報告份數</td>
  </tr>
  <tr>
    <td align="center">
		<label class="radio"><?php echo $pay_when_send; ?>送件時</label><br>
		<label class="radio"><?php echo $pay_when_receive; ?>取件時</label>
    </td>
    <td colspan="2" align="center" style="font-size:12px;">
	尺度<?php echo $num_report_copies_scale; ?>份；<label class="radio"><?php echo $report_logo_scale_1; ?>含LOGO</label><label class="radio"><?php echo $report_logo_scale_0; ?>不含LOGO</label><br>
	功能性<?php echo $num_report_copies_functionality; ?>份；<label class="radio"><?php echo $report_logo_functionality_1; ?>含LOGO</label><label class="radio"><?php echo $report_logo_functionality_0; ?>不含LOGO</label><br>
	生物相容性<?php echo $num_report_copies_biocompatibility; ?>份；<label class="radio"><?php echo $report_logo_biocompatibility_1; ?>含LOGO</label><label class="radio"><?php echo $report_logo_biocompatibility_0; ?>不含LOGO</label>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">備 註</td>
    </tr>
  <tr>
    <td colspan="3" rowspan="3">檢測方法：<br><?php echo $comments; ?></td>
    </tr>
  <tr>
    <td colspan="3" align="center">客 戶 簽 章</td>
    <td colspan="2" align="center">收 件 人 簽 章</td>
    <td align="center">取 件 人 簽 章</td>
    </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    </tr>
  <tr>
    <th colspan="9" align="right"><h6>表單編號：DQP-03-01
    版次：5.0</h6></th>
  </tr>
  </tfoot>	
  
  <tr class='hide'>
    <td><!--<input name='specimen_ID[]' type='text' value='' size='6' class='input-mini'>--></td>
    <td><select name='specimen_test_item[]' value='' size='6' class='span12'><?php echo $test_items; ?></select></td>
    <td colspan='2'><input name='specimen_name[]' type='text' value='' size='6' class='span12'></td>
    <td><input name='specimen_company_name[]' type='text' value='' size='6' class='span12'></td>
    <td colspan='2'><input name='specimen_brand[]' type='text' value='' size='6' class='span12'></td>
    <td><input name='specimen_model[]' type='text' value='' size='6' class='span12'></td>
  </tr>
</table>