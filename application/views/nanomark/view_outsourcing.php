
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
					 
						<form id="form_nanomark_outsourcing" method="post" action="<?=site_url();?>/nanomark/update_outsourcing/" class="form-horizontal">
						<input type="hidden" name="specimen_SN" value="<?php if(!empty($specimen_SN)) echo $specimen_SN; ?>" >

						<div class="print_area">
							
						  <table width="100%">
						  <tr>
						    <td><h2 align="center">國立成功大學 微奈米科技研究中心<br>奈米技術產品測試實驗室</h2></td>
						  </tr>
						  <tr>
						    <td align="center"><h1>委外檢測顧客同意書</h1></td>
						  </tr>
						  <tr>
						    <td><h3>根據「 <?=isset($verification_norm_name)?$verification_norm_select_options[$verification_norm_name]:"";?> 」對於 <?=isset($specimen_name)?$specimen_name:""; ?> 需符合下列 <?=isset($test_items_1_amount)?$test_items_1_amount:"";?> 項（ 
						    <?
						    	
						    	foreach($test_items_name_1 as $key => $val){
									$test_items_name_1[$key] = $test_item_select_options[$val];
								}
								echo implode('、',$test_items_name_1);
						   
						    ?>
						     ）要求水準，</h3></td>
						  </tr>
						  <tr>
						    <td><h3>貴 客戶 <?=isset($report_title)?$report_title:"";?> 委託國立成功大學奈米標章測試實驗室做產品檢測，並同意本單位將「 <?=isset($specimen_name)?$specimen_name:"";?> 」的試前樣本委託 <?=isset($outsourcing_organization)?$outsourcing_organization:"";?> 代做 
						    <?
						    	foreach($test_items_name_2 as $key => $val){
									$test_items_name_2[$key] = $test_item_select_options[$val];
								}
								echo implode('、',$test_items_name_2);
						    ?> 處理。</h3></td>
						  </tr>
						  <tr>
						    <td align="right"><h4>客戶簽名：<?=isset($client_signature)?$client_signature:"";?></h4></td>
						  </tr>
						  <tr>
						    <td align="right"><h5>日期：<?=isset($signature_date)?$signature_date:date("Y-m-d"); ?></h5></td>
						  </tr>
						  <tr>
						    <td align="right"><h6>DQP-23-01  V1.1</h6></td>
						  </tr>
						  </table>
						</div>  
							<div class="form-actions">
							   <?=isset($action_btn)?implode(' ',(array)$action_btn):"";?>
							   <?=anchor($this->agent->referrer(),"回上頁","class='btn btn-primary'");?>
							   <?=form_button("print_btn","列印","class='btn '");?>
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


