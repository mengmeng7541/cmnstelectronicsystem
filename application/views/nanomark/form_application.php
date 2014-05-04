
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
                        <h4><i class="icon-reorder"></i>檢測工作委託單(*者為必定填寫)</h4>
                     </div>
                     <div class="widget-body form" id="widget_application">
					    <form id="form_nanomark_application" action="<?=isset($action_url)?$action_url:""?>" method="post" class="form-inline">
					       <?=form_hidden("application_SN",isset($application_SN)?$application_SN:"");?>
						   <div class="print_area"><?php echo $app_form; ?></div>
						   
						   <div class="form-actions">
							<?
							if(isset($action_btn))
							{
								foreach((array)$action_btn as $a_btn)
								{
									echo $a_btn.' ';
								}
							}
							?>
						   </div>
					    </form>
						
						
						
                     </div>
					 
					 <div class="widget-body form hide" id="widget_preview_report">
						注意：以下紅字為根據您所填寫委託單之內容，所對應產生之資訊，請先確認欄位無誤後再行送出，謝謝。
						<style type="text/css">
						#widget_preview_report table {
						  border: thick double black;
						} 
						#widget_preview_report th {
							
						} 
						#widget_preview_report td { 
						  border: 0px;
						  padding: 10px;
						}
						</style>             
						<table width="940" border="0">
						     <tr>
						       <td align="center">&nbsp;</td>
						       <td align="center">&nbsp;</td>
						       <td colspan="2"><h3 align="center">國立成功大學
						         微奈米科技研究中心</h3>
					           <h3 align="center">奈米技術產品測試實驗室</h3></td>
						     </tr>
						     <tr>
						       <td colspan="4" align="center"><h2>測試報告</h2></td>
						     </tr>
						     <tr>
						       <td width="150">&nbsp;</td>
						       <td width="150">&nbsp;</td>
						       <td width="300">&nbsp;</td>
						       <td width="300"><p>報告日期：2013年7月29日</p>
						       <p>報告編號：1307-04-1</p></td>
						     </tr>
						     <tr>
						       <td colspan="4" style="border:1px dashed black;">
							   <p>樣品名稱：<span id="specimen_name" style="color:red;">123</span></p>
						       <p>委託項目：尺度量測</p>
						       <p>委託單位：<span id="report_title" style="color:red;">123</span></p>
						       <p>委託單位地址：<span id="report_address" style="color:red;">123</span></p>
						       <p>委託日期：<span id="application_date" style="color:red;">123</span></p></td>
						     </tr>
						     <tr>
						       <td colspan="4"><p>上項樣品經本實驗室測試，結果如內文。</p>
						       <p>本報告含封面及 <u> 5</u>_ 頁內文，分離使用無效。</p></td>
						     </tr>
						     <tr>
						       <td>&nbsp;</td>
						       <td>&nbsp;</td>
						       <td>&nbsp;</td>
						       <td align="center">____________________<br />
						       實驗室主管</td>
						     </tr>
						     <tr>
						       <td>&nbsp;</td>
						       <td>&nbsp;</td>
						       <td>&nbsp;</td>
						       <td align="center">____________________<br />
						       報告簽署人</td>
						     </tr>
						     <tr>
						       <td colspan="4"><p>地址：701台南市大學路1號微奈米科技研究中心 <br />
						       聯絡電話：06-2757575 #31380<br />
						       E-mail：nanomark@mail.mina.ncku.edu.tw</p></td>
						     </tr>
						     

						</table>
						<table width="940" border="0">
							 <tr>
						       <td align="center"><h2>內頁資訊</h2></td>
							 </tr>
						     <tr>
						       <td>
						         <p>．樣品資訊：[廠商名稱：<span id="specimen_company_name" style="color:red;">123</span>]、[樣品名稱：<span id="specimen_name" style="color:red;">123</span>]、[樣品廠牌：<span id="specimen_brand" style="color:red;">123</span>] 、[樣品型號：<span id="specimen_model" style="color:red;">123</span>]</p>
						         <p>．測試方法：TN-008奈米表面處理抗污衛生陶瓷器驗證規範</p>
						         <p>．參考資料：TN-008奈米表面處理抗污衛生陶瓷器驗證規範</p>
						       </td>
						     </tr>
						</table>
						
						<div class="form-actions">
						    <button name="back" class="btn btn-info">回委託單</button>
						</div>
					</div>
                  </div>	
               </div>
            </div>
            
            

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  


<!-- START FORM ORG MODAL-->
<div id="form_modal_specimen" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:640px;margin-left:-320px">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">表單</h3>
    </div>
    <div class="modal-body" style="max-height:none;">
        <form action="<?=site_url();?>/nanomark/update_specimen" class="form-horizontal" method="post">
			<div class="control-group">
               <label class="control-label">請選擇代工者</label>
               <div class="controls">
			      <input type="hidden" name="specimen_serial_no" value=""/>
                  <?php if(!empty($specimen_engineer_select)) echo $specimen_engineer_select; ?>
               </div>
            </div>
		</form>
    </div>
    <div class="modal-footer">
		<button name="form_modal_submit" class="btn btn-primary">送出</button>
    </div>
</div>
<!-- END FORM ORG MODAL-->

