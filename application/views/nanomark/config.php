
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     奈米標章系統管理
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span6">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>奈米標章超級管理員設置</h4>
						<span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                     </div>
                     <div class="widget-body form">
					 	<form id="" action="<?=site_url();?>/nanomark/update_config" method="post" class="form-horizontal">

							<div class="control-group">
                            	<label class="control-label">超級管理員</label>
                            	<div class="controls">
									<?php echo $select_super_admin; ?>
                            	</div>
                            </div>
						   
							<div class="form-actions">
						   		<input type="submit" value="更新" class="btn btn-warning"/>
							</div>

					 	</form>

					 </div>
                  </div>	
               </div>
			   
			   <div class="span6">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>委託單審核設置</h4>
						<span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                     </div>
                     <div class="widget-body form">
					 	<form id="" action="<?=site_url();?>/nanomark/update_config" method="post" class="form-horizontal">

							<div class="control-group">
                            	<label class="control-label">初始確認</label>
                            	<div class="controls">
									<?php echo $select_application_case_officer_1st; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">二度確認</label>
                            	<div class="controls">
									<?php echo $select_application_case_officer_2nd; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">完工確認</label>
                            	<div class="controls">
									<?php echo $select_application_case_officer_final; ?>
                            	</div>
                            </div>
						   
							<div class="form-actions">
						   		<input type="submit" value="更新" class="btn btn-warning"/>
							</div>

					 	</form>

					 </div>
                  </div>	
               </div>
			</div>
			
			<div class="row-fluid">
               <div class="span6">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>報告修改審核設置</h4>
						<span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                     </div>
                     <div class="widget-body form">
					 	<form id="" action="<?=site_url();?>/nanomark/update_config" method="post" class="form-horizontal">

							<div class="control-group">
                            	<label class="control-label">品質主管</label>
                            	<div class="controls">
									<?php echo $select_revision_quality_manager; ?>
                            	</div>
                            </div>
							<div class="control-group">
								<label class="control-label">技術主管(奈米尺度)</label>
								<div class="controls">
									<?php echo $select_revision_technical_manager_scale; ?>
                            	</div>
                            </div>
							<div class="control-group">
								<label class="control-label">技術主管(奈米功能)</label>
								<div class="controls">
									<?php echo $select_revision_technical_manager_functionality; ?>
                            	</div>
                            </div>
							<div class="control-group">
								<label class="control-label">技術主管(生物相容性)</label>
								<div class="controls">
									<?php echo $select_revision_technical_manager_biocompatibility; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">報告簽署人(奈米尺度)</label>
                            	<div class="controls">
									<?php echo $select_revision_report_signatory_scale; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">報告簽署人(奈米功能)</label>
                            	<div class="controls">
									<?php echo $select_revision_report_signatory_functionality; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">報告簽署人(生物相容性)</label>
                            	<div class="controls">
									<?php echo $select_revision_report_signatory_biocompatibility; ?>
                            	</div>
                            </div>
							<div class="control-group">
                            	<label class="control-label">實驗室主管</label>
                            	<div class="controls">
									<?php echo $select_revision_lab_manager; ?>
                            	</div>
                            </div>
						   
							<div class="form-actions">
						   		<input type="submit" value="更新" class="btn btn-warning"/>
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