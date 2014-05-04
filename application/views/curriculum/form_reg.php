      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器訓練課程系統
					 <small>儀器訓練課程選課系統</small>
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
                        <h4><i class="icon-reorder"></i>加選表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/reg/add" method="POST" class="form-horizontal">
                     		<input type="hidden" name="class_ID" value="<?=empty($class_ID)?"":$class_ID;?>"/>
                     		<div class="control-group">
					            <label class="control-label">課程</label>
					            <div class="controls">
									<?=form_dropdown("class_ID[]",isset($class_ID_select_options)?$class_ID_select_options:array(),isset($class_ID)?$class_ID:"","class='chosen'");?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">學員</label>
					            <div class="controls">
					            	<?=form_dropdown("user_ID[]",isset($user_ID_select_options)?$user_ID_select_options:array(),"","class='chosen'");?>
								</div>
							</div>
							
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/class/list" class="btn btn-warning">取消</a>
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

