<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
		<div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->                          
              <h3 class="page-title">
                 儀器預約系統
                 <small>儀器與門禁管理設定</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>

            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget widget-tabs">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>設備列表</h4>
                        </div>
                        <div class="widget-body form">
							<div class="tabbable portlet-tabs">
								<ul class="nav nav-tabs">
									<li><a href="#portlet_tab2" data-toggle="tab">門禁</a></li>
									<li class="active"><a href="#portlet_tab1" data-toggle="tab">儀器</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="portlet_tab1">
										<table id="table_list_facility" class="table table-bordered table-striped">
											<thead>
												<th width="40">編號</th>
												<th >儀器名稱</th>
												<th width="40">卡機編號</th>
												<!--<th width="100">技術</th>
												<th width="100">類別</th>-->
												<th width="50">狀態</th>
												<th width="50">管理者</th>
												<th width="60">動作</th>
											</thead>
										</table>
										<div class="row-fluid form-horizontal">
											<div class="form-actions">
												<a href="<?=site_url();?>/facility/admin/facility/form" class="btn btn-primary">新增</a>
												<a href="<?=site_url();?>/facility/admin/facility/edit/batch" class="btn btn-warning">批次更新</a>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="portlet_tab2">
										<table id="table_list_door" class="table table-bordered table-striped">
											<thead>
												<th>ID</th>
												<th>父門禁</th>
												<th>名稱</th>
												<th>控制卡機</th>
												<th>狀態</th>
											</thead>
										</table>
										<div class="row-fluid form-horizontal">
											<div class="form-actions">
												<a href="<?=site_url();?>/facility/admin/door/form" class="btn btn-primary">新增</a>
												
											</div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				