<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">
						儀器預約系統
						<small>使用者儀器權限編輯</small>
					</h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>權限編輯表單</h4>
                        </div>
                        <div class="widget-body form">
							<form id="form_facility_user_privilege" action="<?=$action;?>" method="post" class="form-horizontal">
								<div class="control-group">
									<label class="control-label">使用者帳號</label>
									<div class="controls">
										<?=empty($user_ID)?form_dropdown("user_ID",$user_ID_select_options,"","class='chosen'"):$user_ID;?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">儀器名稱</label>
									<div class="controls">
										<?=$facility_ID_select;?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">權限</label>
									<div class="controls">
										<select name="privilege">
											<option value="normal" <?=empty($privilege)?"":($privilege=='normal')?"selected='selected'":"";?>>一般使用者</option>
											<option value="super" <?=empty($privilege)?"":($privilege=='super')?"selected='selected'":"";?>>超級使用者</option>
										</select>
									</div>
								</div>
								<? if(!empty($user_ID)) { ?>
									<div class="control-group">
										<label class="control-label">到期日</label>
										<div class="controls">
											<input name="expiration_date" type="text" class="date-picker" value="<?=empty($expiration_date)?date("Y-m-d",strtotime("+3months")):$expiration_date;?>"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">立即停權</label>
										<div class="controls">
											<label class="radio"><input name="suspended" type="radio" value="1" <?=empty($suspended)?"":"checked='checked'";?>/>是</label>
											<label class="radio"><input name="suspended" type="radio" value="0" <?=empty($suspended)?"checked='checked'":"";?>/>否</label>
										</div>
									</div>
								<? } ?>
								<div class="form-actions">
									<input type="submit" value="送出" class="btn btn-primary">
									<a href="<?=site_url();?>/facility/admin/privilege/list" class="btn btn-warning">取消</a>
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


				