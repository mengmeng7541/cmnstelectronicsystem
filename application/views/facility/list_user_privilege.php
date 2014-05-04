<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">
						儀器預約系統
						<small>使用者儀器權限控管</small>
					</h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>儀器權限列表</h4>
                        </div>						
						<div class="widget-body form">

							<table id="table_list_user_privilege" class="table table-striped table-bordered">
								<thead>
									<th width="85">ID</th>
									<th width="50">姓名</th>
									<th width="150">email</th>
									<th>儀器</th>
									<th width="60">權限</th>
									<th width="70">到期日</th>
									<th width="60">動作</th>
								</thead>
							</table>
							<div class="form-actions">
								<a href="<?=site_url();?>/facility/admin/privilege/form" class="btn btn-primary">新增</a>
								<a href="<?=site_url();?>/facility/admin/privilege/batch/form" class="btn btn-warning">批次變更</a>
							</div>
                        </div>
						
						
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				