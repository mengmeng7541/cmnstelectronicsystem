<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">儀器預約系統<small>儀器與門禁管理</small></h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>批次儀器設定</h4>
                        </div>
                        <div class="widget-body form">
							<form action="<?=site_url();?>/facility/admin/facility/update/batch" id="form_facility_config" class="form-horizontal" method="POST">
								
								
								<div class="control-group ">
						           <label class="control-label">選擇儀器</label>
						           <div class="controls">
								      <?=form_multiselect("ID[]",$facility_ID_select_options,"","class='chosen span12'");?>
						           </div>
						        </div>
								
								
								<div class="control-group">
						           <label class="control-label">停機時間</label>
						           <div class="controls">
								      <input name="pause_start_date" type="text" value="<?=empty($pause_start_date)?"":$pause_start_date;?>" class="date-picker input-medium" />
									  <input name="pause_start_time" type="text" value="<?=empty($pause_start_time)?"":$pause_start_time;?>" class="timepicker-24 input-medium" />
									  ~
									  <input name="pause_end_date" type="text" value="<?=empty($pause_end_date)?"":$pause_end_date;?>" class="date-picker input-medium" />
									  <input name="pause_end_time" type="text" value="<?=empty($pause_end_time)?"":$pause_end_time;?>" class="timepicker-24 input-medium" />
						           </div>
								</div>
								
								<div class="form-actions">
									<button type="submit" class="btn btn-primary">送出</button>
									<button type="reset" class="btn" >重填</button>
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
			



