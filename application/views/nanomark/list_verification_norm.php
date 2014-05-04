
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     奈米標章系統
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
                        <h4><i class="icon-reorder"></i>規範列表</h4>
						<span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                     </div>
                     <div class="widget-body">
						<?php echo $table_list_verification_norm; ?>
					 </div>
                  </div>	
               </div>
			</div>
			<!--<div class="row-fluid">
               <div class="span12">
			      <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>新增規範</h4>
						<span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                     </div>
                     <div class="widget-body form">
					 	<form action="/index.php/nanomark/add_verification_norm" method="post" class="form-horizontal">
							<div class="control-group">
	                           <label class="control-label">規範名稱</label>
	                           <div class="controls">
	                              <input type="text" class="span12" name="name" />
	                           </div>
	                        </div>
							<div class="form-actions">
                              <button type="submit" class="btn btn-primary" >送出</button>
                           </div>
						</form>
					 </div>
                  </div>	
               </div>
			</div>-->
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  