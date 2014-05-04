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
                            <h4><i class="icon-reorder"></i>表單</h4>
                        </div>
                        <div class="widget-body form">
							<form action="<?=$action;?>" class="form-horizontal" method="POST">
								<div class="control-group ">
							            <!--<label class="control-label">卡號</label>
							            <div class="controls">
											<input type="text" name="card_num" value="0000"/>
										</div>-->
										<label class="control-label">帳號、姓名或卡號</label>
							            <div class="controls">
											<!--<input type="text" name="user_ID" value=""/>-->
											<?=form_dropdown("user_ID",isset($user_ID_select_options)?$user_ID_select_options:array(),"","class='input-xlarge chosen'");?>
										</div>
									</div>
								<div class="control-group">
						            <label class="control-label">儀器</label>
						            <div class="controls">
						            	<span class="help-block">
											<label class="checkbox inline"><input id="select_all_facility" name="all_facility" value="1" type="checkbox" />全部儀器</label>
											<label class="checkbox inline"><input id="select_all_door" name="all_door" value="1" type="checkbox" />全部門禁</label>
										</span>
										<?=form_dropdown("facility_ID[]",$facility_ID_select_options,"","multiple='multiple' class='span12 chosen'");?>
										
										
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">起始時間</label>
						            <div class="controls">
										<input type="text" name="start_date" class="date-picker" value="<?=date("Y-m-d");?>"/>
										<input type="text" name="start_time" class="timepicker-24" value="<?=date("H:i:s");?>"/>
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">結束時間</label>
						            <div class="controls">
										<input type="text" name="end_date" class="date-picker" value="<?=date("Y-m-d");?>"/>
										<input type="text" name="end_time" class="timepicker-24" value="<?=date("H:i:s",strtotime("+1 hour"));?>"/>
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn btn-warning">送出</button>
									<a href="<?=site_url();?>/facility/admin/access/ctrl/list" class="btn btn-primary">取消</a>
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



