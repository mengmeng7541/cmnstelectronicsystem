
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
                        <h4><i class="icon-reorder"></i>委外檢測顧客同意書</h4>
                    </div>
                     <div class="widget-body form">
					 
						<form id="form_nanomark_outsourcing" method="post" action="<?=isset($action_url)?$action_url:site_url().'/nanomark/add_outsourcing/';?>" class="form-horizontal">
							<input type="hidden" name="specimen_SN" value="<?php if(!empty($specimen_SN)) echo $specimen_SN; ?>"/>
							<div class="control-group">
								<label class="control-label"></label>
								<div class="controls">
									根據「 <?=isset($verification_norm_select_options)?form_dropdown("verification_norm_no", $verification_norm_select_options, isset($verification_norm_no)?$verification_norm_no:"", "class='input-xxlarge chosen'"):"";?> 」
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"></label>
								<div class="controls">
									對於 <?=isset($specimen_name)?$specimen_name:""; ?> 需符合下列 <?=form_input("test_items_1_amount",isset($test_items_1_amount)?$test_items_1_amount:"0","class='input-mini' readonly='readonly'");?> 項（ <?=form_dropdown("test_items_name_1[]", isset($test_item_select_options)?$test_item_select_options:array(), isset($test_items_name_1)?$test_items_name_1:" ", "id='test_items_name_1' class='input-xlarge chosen' multiple='multiple'");?> ）要求水準，
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"></label>
								<div class="controls">
									貴 客戶 <?=isset($report_title)?$report_title:"";?> 委託國立成功大學奈米標章測試實驗室做產品檢測，並同意本單位將「 <?=isset($specimen_name)?$specimen_name:"";?> 」的試前樣本
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"></label>
								<div class="controls">
									委託 <?=form_input("outsourcing_organization",isset($outsourcing_organization)?$outsourcing_organization:"","class='input-xlarge'");?> 代做 <?=form_dropdown("test_items_name_2[]", isset($test_item_select_options)?$test_item_select_options:array(),isset($test_items_name_2)?$test_items_name_2:" ", "class='input-xlarge chosen' multiple='multiple'");?> 處理。
								</div>
							</div>
						 
							<div class="form-actions">
							   <?=isset($action_btn)?implode(' ',(array)$action_btn):"";?>
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


