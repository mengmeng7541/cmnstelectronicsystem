      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     現金帳務系統
					 <small>系統設置</small>
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
                        <h4><i class="icon-reorder"></i>系統管理者設置</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url('/cash/config/update');?>" method="POST" class="form-horizontal">
                     		
							<div class="control-group">
					            <label class="control-label">超級管理者</label>
					            <div class="controls">
									<?=form_multiselect("admin_ID[]",isset($admin_ID_select_options)?$admin_ID_select_options:array(),isset($admin_ID)?$admin_ID:array(),"class='span12 chosen'");?>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
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

