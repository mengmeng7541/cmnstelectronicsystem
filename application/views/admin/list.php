      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     管理者資料維護
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN VALIDATION STATES-->
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>帳號列表</h4>
                     </div>
                     <div class="widget-body">
					 	
                        <table class="table table-striped table-bordered" id="admin_list_table">
							<thead>
								<tr>
									<th>ID</th>
									<th>姓名</th>
									<th>EMAIL</th>
									<th>電話</th>
									<th>卡號</th>
									<th>電子簽章</th>
									<th >權限</th>
									<th width="50"></th>
								</tr>
							</thead>
							
						</table>
						<div class="row-fluid">
							<div class="span12 form-horizontal">
								<div class="form-actions">
									<a href="<?=site_url();?>/admin/form" class="btn btn-primary">新增</a>
								</div>
							</div>
						</div>
                     </div>
                  </div>
                  <!-- END VALIDATION STATES-->
               </div>
            </div>
            
            

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

