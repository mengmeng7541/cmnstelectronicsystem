
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     奈米技術產品測試實驗室
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>檢測服務報價單</h4>
                    </div>
                     <div class="widget-body form">
					 
<form id="form_nanomark_quotation" method="post" action="<?=site_url();?>/nanomark/email_quotation/<?=$serial_no?>" class="form-horizontal">
<!--<div class="control-group">
	<label class="control-label">選擇客戶</label>
	<div class="controls">
		<input type="text"/>
	</div>
</div>
<hr>-->
<div class="print_area">
  <table width="940" border="0" cellpadding="5" >
<style type="text/css">
table {
	
  border-collapse:collapse;
  table-layout:fixed;
} 
th {
	
} 
td { 
border:1px solid black;
} 
</style>
	<thead>
    <tr>
      <th width="60" align="center">&nbsp;</th>
      <th width="40" align="center">&nbsp;</th>
      <th align="center">&nbsp;</th>
      <th width="100" align="center">&nbsp;</th>
      <th width="100" align="center">&nbsp;</th>
      <th width="150" align="center">&nbsp;</th>
      <th width="200" align="center">&nbsp;</th>
    </tr>
    <tr>
      <th colspan="7" align="center"><h2>國立成功大學 微奈米科技研究中心<br>
        奈米技術產品測試實驗室<br>
        檢測服務報價單</h2></th>
    </tr>
    <tr>
      <th colspan="7" align="right">日期：<?=isset($quotation_date)?$quotation_date:"";?></th>
    </tr>
    <tr>
      <td colspan="2" align="center">委託機構</td>
      <td colspan="2"><?php echo $organization; ?></td>
      <td width="100" align="center">委測者</td>
      <td colspan="2"><?php echo $contact_name; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">聯絡電話</td>
      <td width="320"><?php echo $contact_tel; ?></td>
      <td align="center">傳真號碼</td>
      <td colspan="3"><?php echo $contact_FAX; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">E-mail</td>
      <td colspan="5"><?=empty($contact_email)?"":$contact_email; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">委託事項</td>
      <td colspan="5"><?php echo $entrust_item; ?></td>
    </tr>
    <tr>
      <td align="center">項次</td>
      <td colspan="3" align="center">檢驗項目</td>
      <td align="center">數量</td>
      <td align="center">單價</td>
      <td align="center">金額</td>
    </tr>
	</thead>
    <tbody>
		<?
		foreach($data as $row)
		{
			echo "<tr>
		      <td align='center'>{$row['index']}</td>
		      <td colspan='3'>{$row['name']}</td>
		      <td align='center'>{$row['amount']}</td>
		      <td align='center'>{$row['fees']}</td>
		      <td align='right'>{$row['total_fees']}</td>
		    </tr>";
		}
		?>
    </tbody>
	<tfoot>
    <tr>
      <td align="center">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="2" align="center">總金額(含稅)</td>
      <td align="right"><?php if(!empty($total_fees)) echo $total_fees; ?></td>
    </tr>
    <tr>
      <td align="center">備註</td>
      <td colspan="6"><?php echo $comments; ?></td>
    </tr>
    <tr>
      <td colspan="7"><ol>
        <li>本單依指定之範圍估價，成約後如工作項目或數量有變更，須另收費用。 </li>
        <li>本估價單有效期為十五天，逾期自動銷案。 </li>
        <li>如蒙委託，請先付費用。 </li>
      </ol>
        抬頭：國立成功大學 <br>
          聯絡地址：701台南市大學路1號 <br>
          聯絡電話：06-2757575  許郁佩  分機31380
          <br>
      傳真電話：06-2080103</td>
    </tr>
    <tr>
      <td colspan="7">中心簽章：<?=empty($case_officer_ID)?"":img(array("src"=>"img/cmnst-stamp.jpg",
																		 "width"=>"221",
																		 "height"=>"155"));?><br>
      含稅合計：<?=empty($total_fees)?"":$total_fees;?></td>
    </tr>
	<tr>
      <th colspan="7" align="right"><h6>DQP-02-06 V1.0</h6></th>
    </tr>
	</tfoot>
  </table>
</div>
	<div class="form-actions">
	   <button type="button" name="print_btn" class="btn">列印</button>
	   <? if($this->nanomark_model->is_super_admin()||$this->nanomark_model->is_application_case_officer_1st()){ ?>
		<button type="submit" name="send_email" class="btn btn-warning">重送Email</button>
		
	   <? } ?>
	   <?=anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");?>
	</div>

</form>
					
						
						
                     </div>
                  </div>	
               </div>
            </div>
            
            

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  


