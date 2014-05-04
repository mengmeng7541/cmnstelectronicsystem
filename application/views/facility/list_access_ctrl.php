<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">儀器預約系統
					<small>卡機開關控管</small>
					</h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>卡機控管紀錄列表</h4>
                        </div>
                        <div class="widget-body form">
							<div class="row-fluid form-horizontal">
								
								<div class="control-group">
									<label class="control-label">選擇儀器</label>
									<div class="controls">
										<?=form_dropdown("facility_ctrl_no[]",$facility_ctrl_no_select_options,"","multiple='multiple' class='span12 chosen'")?>
									</div>
								</div>
								<div class="control-group">
	                            	<label class="control-label">設定日期</label>
	                            	<div class="controls">
	                                    <input id="start_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("-1 day"));?>"/>
										<input id="start_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
										~
										<input id="end_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("+2 day"));?>"/>
										<input id="end_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
	                                </div>
	                            </div>
								<div class="control-group">
								</div>
							</div>
							
							<table id="table_list_access_ctrl" class="table table-striped table-bordered">
								<thead>
									<th>日期時間</th>
									<th>動作</th>
									<th>卡號</th>
									<th>姓名</th>
									<th>卡機位置</th>
									<th>旗標</th>
									<th>狀態</th>
								</thead>
								
							</table>
							<div class="row-fluid">
								<div class="span12 form-horizontal">
									<div class="form-actions">
										<button id="query_access_ctrl" class="btn btn-primary" >查詢</button>
										<a href="<?=site_url()?>/facility/admin/access/ctrl/form" role="button" class="btn btn-warning" data-toggle="modal">新增</a>
										<!--<a href="#form_facility_access_ctrl_modal" role="button" class="btn btn-warning" data-toggle="modal">新增</a>-->
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
