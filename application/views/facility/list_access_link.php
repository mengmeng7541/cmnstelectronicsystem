<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                	<h3 class="page-title">
						儀器預約系統
						<small>卡機連線資訊</small>
					</h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>卡機設定連線列表</h4>
                        </div>
                        <div class="widget-body form">
                        	<div class="alert alert-warning">
                        		<strong>WARNING！</strong>請注意，以下設定會影響卡機的連線運作，請務必知道您在做什麼，否則不建議更動。
                        	</div>
							<table id="table_list_access_link" class="table table-striped table-bordered">
								<thead>
									<th>卡機編號</th>
									<th>型態</th>
									<th>IP位置</th>
									<th width="100"></th>
								</thead>
							</table>
							<div class="form-actions">
								<a href="<?=site_url();?>/facility/admin/access/link/form" class="btn btn-warning">新增</a>
							</div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				