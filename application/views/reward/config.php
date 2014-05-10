
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     論文獎勵系統管理
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
                        <h4><i class="icon-reorder"></i>論文獎勵管理員設置</h4>
                     </div>
                     <div class="widget-body form">
					 	<form id="" action="<?=site_url();?>/nanomark/update_config" method="post" class="form-horizontal">

							<div class="control-group">
                            	<label class="control-label">超級管理員</label>
                            	<div class="controls">
									<?=form_multiselect("admin_ID[]",isset($admin_ID_select_options)?$admin_ID_select_options:array(),isset($admin_ID)?$admin_ID:"","class='chosen'")?>
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
			   <div class="span12">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>獎勵方案設置</h4>
                     </div>
                     <div class="widget-body form">
					 	<table id="table_reward_plan_list" class="table table-striped table-bordered">
					 		<thead>
					 			<th>方案名稱</th>
					 			<th>獎金</th>
					 			<th>開放</th>
					 			<th></th>
					 		</thead>
					 	</table>
					 </div>
                  </div>	
               </div>
			</div>
			
			
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  