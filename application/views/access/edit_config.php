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
                     	<form action="<?=site_url('/access/config/update');?>" method="POST" class="form-horizontal">
                     		
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
               <div class="span6">
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>磁卡批次新增</h4>
                     </div>
                     <div class="widget-body form">
                     	<form id="form_access_card_pool_add_batch" action="<?=site_url('/access/card/pool/add/batch');?>" method="POST" class="form-horizontal">
                     		
							<div class="control-group">
					            <label class="control-label">啟始流水號</label>
					            <div class="controls">
									<input type="text" name="serial_no_start" value="0000"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">結束流水號</label>
					            <div class="controls">
									<input type="text" name="serial_no_end" value="0000"/>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" name="add" class="btn btn-primary">送出</button>
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
                        <h4><i class="icon-reorder"></i>磁卡游泳池</h4>
                     </div>
                     <div class="widget-body form">
                     	<form id="form_access_card_pool_list" action="<?=site_url();?>/access/card/pool/del" method="POST" >
	                     	<table id="table_access_card_pool_list" class="table table-striped table-bordered">
	                     		<thead>
	                     			<th>卡號</th>
	                     			<th></th>
	                     		</thead>
	                     	</table>
							<div class="form-actions">
								<button type="submit" name="del" class="btn btn-danger">刪除</button>
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

