
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
                        <h4><i class="icon-reorder"></i>測試報告修改申請表</h4>
                    </div>
                     <div class="widget-body form">
					   
					 <form id="form_nanomark_report_revision" method="post" action="<?=site_url();?>/nanomark/add_report_revision" class="form-horizontal">
<input type="hidden" name="serial_no" value="<?php if(!empty($serial_no)) echo $serial_no; ?>"/>
<div class="print_area">
<h2 align="center">國立成功大學 微奈米科技研究中心<br />
 奈米技術產品測試實驗室</h2>
<h2 align="center">實驗室測試報告修改申請表 </h2>
<style type="text/css">
table{
	table-layout:fixed;
}
table th{
	border:1px solid black;
}
table td{
	border:1px solid black;
}
#report_revision_table_1 {
	border:medium double black;
}
</style>
<table id="report_revision_table_1" width="940" border="0" cellspacing="0" cellpadding="0">
  <input type="hidden" name="specimen_SN" value="<?=isset($specimen_SN)?$specimen_SN:"";?>" />
  <tr style="display:none;">
    <td width="100">&nbsp;</td>
    <td width="200">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="200">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">委 託 單 位</td>
    <td colspan="2"><?=form_dropdown("org_name",isset($org_name_options)?$org_name_options:array(),"","class='span12' disabled ");?></td>
    <td align="center">填 寫 人</td>
    <td><?=isset($applicant_name)?$applicant_name:""; ?></td>
  </tr>
  <tr>
    <td align="center">報 告 編 號</td>
    <td colspan="2"><?=isset($report_ID_options)?form_dropdown("report_ID",$report_ID_options,"","class='span12 chosen'"):"您目前無可修改的報告書，歡迎委託檢測";?></td>
    <td align="center">填 寫 日 期</td>
    <td><?php if(!empty($application_date)) echo $application_date; ?></td>
  </tr>
  <tr>
    <td colspan="2" valign="top">報告錯誤處： <br />
      <label class="checkbox inline"><?=form_checkbox("mistake_outline[]","Report Incomplete",isset($report_incomplete)?$report_incomplete:FALSE);?> 報告不完整 </label><br />
      <label class="checkbox inline"><?=form_checkbox("mistake_outline[]","Typewritten Error",isset($typewritten_error)?$typewritten_error:FALSE);?> 繕打文字錯誤 </label><br />
      <label class="checkbox inline"><?=form_checkbox("mistake_outline[]","Data Error",isset($data_error)?$data_error:FALSE);?> 數據錯誤 </label><br />
      <label class="checkbox inline"><?=form_checkbox("mistake_outline[]","Result Incorrect",isset($result_incorrect)?$result_incorrect:FALSE);?> 確認數據結果不正確 </label><br />
      <label class="checkbox inline"><?=form_checkbox("mistake_outline[]","Others",isset($others)?$others:FALSE);?> 其他 </label><br />
    <td colspan="3" valign="top">錯誤說明：<br /><?=form_textarea(array(
	              'name'        => 'mistake_description',
				  'rows'		=> '5',
	              'value'       => isset($mistake_description)?$mistake_description:"",
				  'class'		=> 'span12',
	            ));?></td>
  </tr>
  <tr>
    <td colspan="5">錯誤原因分析：<br /><?=form_textarea(array(
	              'name'        => 'mistake_analysis',
				  'rows'		=> '5',
	              'value'       => isset($mistake_analysis)?$mistake_analysis:"",
				  'class'		=> 'span12',
	            ));?></td>
    </tr>
  <tr>
    <td colspan="5">處置  /  修改：<br /><?=form_textarea(array(
	              'name'        => 'disposal_revision',
				  'rows'		=> '5',
	              'value'       => isset($disposal_revision)?$disposal_revision:"",
				  'class'		=> 'span12',
	            ));?></td>
    </tr>
</table>

<table id="report_revision_table_2" width="940" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="200">審  核  批  示</th>
    <th width="738">意                 見</th>
  </tr>
  <tr>
    <th>品 質 主 管</th>
    <td><?php if(!empty($quality_manager_comment)) echo $quality_manager_comment; ?><?php if(!empty($quality_manager_signature)) echo $quality_manager_signature; ?></td>
  </tr>
  <tr>
    <th>技 術 主 管</th>
    <td><?php if(!empty($technical_manager_comment)) echo $technical_manager_comment; ?><?php if(!empty($technical_manager_signature)) echo $technical_manager_signature; ?></td>
  </tr>
  <tr>
    <th>報 告 簽 署 人</th>
    <td><?php if(!empty($report_signatory_comment)) echo $report_signatory_comment; ?><?php if(!empty($report_signatory_signature)) echo $report_signatory_signature; ?></td>
  </tr>
  <tr>
    <th>實 驗 室 主 管</th>
    <td><?php if(!empty($lab_manager_comment)) echo $lab_manager_comment; ?><?php if(!empty($lab_manager_signature)) echo $lab_manager_signature; ?></td>
  </tr>
</table>
</div>
<div class="form-actions">
  <?=isset($action_btn)?implode(' ',(array)$action_btn):""; ?>
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


