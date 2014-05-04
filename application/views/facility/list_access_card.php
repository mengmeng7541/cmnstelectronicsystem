<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>刷卡進出紀錄列表</h4>
                        </div>
                        <div class="widget-body">
							<div class="row-fluid form-horizontal">
								<div class="span12">
									<div class="control-group">
		                            	<label class="control-label">選擇儀器</label>
		                            	<div class="controls">
		                                	<?=$facility_ctrl_no_select;?>
		                            	</div>
		                            </div>
								
									<div class="control-group">
		                            	<label class="control-label">設定日期</label>
		                            	<div class="controls">
		                                    <input id="start_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("-1 day"));?>"/>
											<input id="start_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
											~
											<input id="end_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d");?>"/>
											<input id="end_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
		                                </div>

		                            </div>
								</div>
							</div>
							<table id="table_list_access_card" class="table table-striped table-bordered">
								<thead>
									<th>日期</th>
									<th>時間</th>
									<th>卡號</th>
									<th>刷卡位置</th>
									<th>姓名</th>
									<th>狀態</th>
								</thead>
							</table>
							<div class="row-fluid">
								<div class="span12 form-horizontal">
									<div class="form-actions">
										<button id="synchronize_access_card" class="btn btn-primary">同步</button>
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
				