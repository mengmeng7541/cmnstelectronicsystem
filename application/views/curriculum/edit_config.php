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
                     	<form action="<?=site_url();?>/curriculum/config/update" method="POST" class="form-horizontal">
                     		
							<div class="control-group">
					            <label class="control-label">超級管理者</label>
					            <div class="controls">
									<?=form_multiselect("curriculum_super_admin_ID[]",isset($admin_ID_select_options)?$admin_ID_select_options:array(),isset($super_admin_IDs)?$super_admin_IDs:array(),"class='span12 chosen'");?>
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
			<div class="row-fluid">
               <div class="span12">
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>選課注意事項編輯</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/bulletin/update" method="POST" >
                     		<textarea class="span12 ckeditor" name="reg_warning_bulletin_content" rows="6"><?=isset($bulletin['bulletin_content'])?$bulletin['bulletin_content']:"";?></textarea>

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
<script type="text/javascript" src="/assets/ckeditor/ckeditor.js"></script>

