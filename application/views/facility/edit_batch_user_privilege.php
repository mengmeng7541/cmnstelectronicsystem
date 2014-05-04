<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">儀器預約系統<small>使用者權限頁面</small></h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>批次設定</h4>
                        </div>
                        <div class="widget-body form">
							<form action="<?=site_url();?>/facility/user/privilege/update/batch" id="form_facility_config" class="form-horizontal" method="POST">
								<div class="control-group ">
						           <label class="control-label">選擇儀器</label>
						           <div class="controls">
								      <?=form_multiselect("facility_ID[]",$facility_ID_select_options,"","class='chosen span12'");?>
						           </div>
						        </div>
								<div class="control-group">
						           <label class="control-label">開關</label>
						           <div class="controls">
								    	<label class="radio"><input name="suspended" type="radio" value="1" <?=empty($suspended)?"":"checked='checked'";?>/>開</label>
										<label class="radio"><input name="suspended" type="radio" value="0" <?=empty($suspended)?"checked='checked'":"";?>/>關</label>
						           </div>
								</div>
								<div>
									<label class="control-label">效期</label>
									<input name="suspended_expiration_date" type="text" value="<?=isset($suspended_expiration_date)?$suspended_expiration_date:"";?>" class="date-picker input-small" />
									<input name="suspended_expiration_time" type="text" value="<?=isset($suspended_expiration_time)?$suspended_expiration_time:"";?>" class="timepicker-24-30m input-mini" />
								</div>
								<div class="control-group">
						           <label class="control-label">權限</label>
						           <div class="controls">
										<option value="normal" <?=empty($privilege)?"":($privilege=='normal')?"selected='selected'":"";?>>一般使用者</option>
										<option value="super" <?=empty($privilege)?"":($privilege=='super')?"selected='selected'":"";?>>超級使用者</option>
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
			



