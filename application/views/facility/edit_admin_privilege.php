<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>機台列表</h4>
                        </div>
                        <div class="widget-body">
							<form id="form_facility_admin_privilege" action="<?=site_url();?>/facility/admin/privilege/update" method="post" class="form-horizontal">
								<?php echo $table_list_privilege; ?>
								<div class="form-actions">
									<input type="submit" name="update" value="更新" class="btn btn-success">
								</div>
							</form>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				