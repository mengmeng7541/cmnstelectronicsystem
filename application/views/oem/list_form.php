      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器代工系統
					 <small></small>
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
                        <h4><i class="icon-reorder"></i>表單列表</h4>
                     </div>
                     <div class="widget-body form">
                     	<!--<div class="control-group">
				            <label class="control-label">儀器</label>
				            <div class="controls">
				            	
							</div>
						</div>-->
                     	<table id="table_oem_form_list" class="table table-striped table-bordered" data-mode="<?=isset($mode)?$mode:'form';?>">
                     		<thead>
                     			<tr>
                     				<th>代工表單名稱</th>
                     				<th width="100">開放申請</th>
                     				<th width="100">備註</th>
                     				<th width="100">動作</th>
                     			</tr>
                     		</thead>
                     	</table>
                     	<div class="form-actions">
                     		<a href="<?=site_url('oem/form/new');?>" class="btn btn-primary">新增</a>
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

