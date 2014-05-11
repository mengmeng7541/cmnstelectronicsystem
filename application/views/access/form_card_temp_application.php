      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     門禁安全控管系統
					 <small>臨時磁卡申請</small>
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
                        <h4><i class="icon-reorder"></i>臨時磁卡申請表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url('/access/card/application/temp/add');?>" method="POST" class="form-horizontal">
							<div class="control-group">
					            <label class="control-label">申請人姓名</label>
					            <div class="controls">
									<input type="text" value="<?=isset()?>"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">申請磁卡類別</label>
					            <div class="controls">
									
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">申請磁卡目的</label>
					            <div class="controls">
									
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">來賓姓名</label>
					            <div class="controls">
									
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">來賓聯絡手機</label>
					            <div class="controls">
									
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">磁卡使用時段</label>
					            <div class="controls">
									
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
<script type="text/javascript" src="<?=base_url();?>assets/ckeditor/ckeditor.js"></script>

