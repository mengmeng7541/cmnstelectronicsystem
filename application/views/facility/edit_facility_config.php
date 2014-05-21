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
                            <h4><i class="icon-reorder"></i>儀器設定</h4>
                        </div>
                        <div class="widget-body form">
							<form action="<?=$action;?>" id="form_facility_config" class="form-horizontal" method="POST">
								<div class="accordion" id="accordion1">
									<div class="accordion-group">
		                                 <div class="accordion-heading">
		                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">
		                                    <i class=" icon-plus"></i>
		                                    儀器基本資料
		                                    </a>
		                                 </div>
		                                 <div id="collapse_1" class="accordion-body collapse in">
		                                    <div class="accordion-inner">
		                                        <div class="row-fluid">
													<div class="control-group span4">
											           <label class="control-label">機台ID</label>
											           <div class="controls">
													      <input name="ID" type="text" class="input-small" value="<?=empty($ID)?"":$ID;?>" <?=(!empty($ID))?'readonly="readonly"':'';?>/>
											           </div>
											        </div>
											        <div class="control-group span4">
											           <label class="control-label">新ID</label>
											           <div class="controls">
													      <input name="new_ID" type="text" class="input-small" value="<?=empty($new_ID)?"":$new_ID;?>" />
											           </div>
											        </div>
													<div class="control-group span4">
											           <label class="control-label">控制卡機編號</label>
											           <div class="controls">
													      <input name="ctrl_no" type="text" class="input-small" value="<?=isset($ctrl_no)?$ctrl_no:"";?>"/>
											           </div>
											        </div>
												</div>
												<div class="row-fluid">
													<div class="control-group span8">
											           <label class="control-label">上層儀器</label>
											           <div class="controls">
													      <?=form_dropdown("parent_ID",$facility_ID_select_options,empty($parent_ID)?"":$parent_ID,"class='span12 chosen-with-diselect'")?>
											           </div>
													</div>
													<div class="control-group span4">
											           <label class="control-label">平行群組</label>
											           <div class="controls">
													      <input type="text" name="horizontal_group_ID" value="<?=isset($horizontal_group_ID)?$horizontal_group_ID:"";?>" class="input-small"/>
											           </div>
													</div>
												</div>
												<div class="row-fluid">
													<div class="control-group span6">
														<label class="control-label">儀器類別</label>
														<div class="controls">
															<?=form_dropdown("facility_category_ID",$facility_category_options,(isset($Facility_Tech)&&isset($Facility_Class))?($Facility_Tech.",".$Facility_Class):"","class='span12' ");?>
														</div>
													</div>
													<div class="control-group span6">
														<label class="control-label">儀器位置</label>
														<div class="controls">
															<?=form_dropdown("location_ID",isset($facility_location_ID_select_options)?$facility_location_ID_select_options:array(),isset($location_ID)?$location_ID:"","class='span12' ");?>
														</div>
													</div>
												</div>
												
												<div class="control-group">
										           <label class="control-label">中文名稱</label>
										           <div class="controls">
												      <input name="cht_name" type="text" class="span12" value="<?=empty($cht_name)?"":$cht_name;?>"/>
										           </div>
										        </div>
												<div class="control-group">
										           <label class="control-label">英文名稱</label>
										           <div class="controls">
												      <input name="eng_name" type="text" class="span12" value="<?=empty($eng_name)?"":$eng_name;?>"/>
										           </div>
										        </div>
												<div class="row-fluid">
												    <div class="control-group span6">
											           <label class="control-label">* 管理員一</label>
											           <div class="controls">
											              <?=form_dropdown("admin_ID[]",$admin_ID_select_options,empty($admin_ID[0])?"":$admin_ID[0],"class='span12 chosen'");?>
											           </div>
											        </div>
													<div class="control-group span6">
											           <label class="control-label">管理員二</label>
											           <div class="controls">
											              <?=form_dropdown("admin_ID[]",$admin_ID_select_options,empty($admin_ID[1])?"":$admin_ID[1],"class='span12 chosen-with-diselect'");?>
											           </div>
											        </div>
												</div>
												
												<div class="control-group">
										           <label class="control-label">備註</label>
										           <div class="controls">
												      <textarea name="note" rows="10" class="span12"><?=empty($note)?"":$note;?></textarea>
										           </div>
										        </div>
												
												
												
												
		                                    </div>
		                                 </div>
									</div>
									<div class="accordion-group">
		                                 <div class="accordion-heading">
		                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2">
		                                    <i class=" icon-plus"></i>
		                                    儀器進階設定
		                                    </a>
		                                 </div>
		                                 <div id="collapse_2" class="accordion-body collapse">
		                                    <div class="accordion-inner">
												
		                                        <div class="control-group">
										           <label class="control-label">儀器狀況</label>
										           <div class="controls ">
												      <label class="radio "><?=form_radio("status","normal",isset($state)?($state=="normal"):"");?>正常</label>
													  <label class="radio ">
													  <?=form_radio("status","fault",isset($state)?($state=="fault"):"");?>異常
													  <input name="error_comment" type="text" value="<?=empty($error_comment)?"":$error_comment;?>"/>
													  </label>
													  <label class="radio "><?=form_radio("status","migrated",isset($state)?($state=="migrated"):"");?>移轉</label>
													  
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
												<div class="row-fluid">
													<div class="control-group span5">
											           <label class="control-label">開放預約使用</label>
											           <div class="controls ">
													      <label class="radio "><?=form_radio("enable_booking","1",!empty($enable_booking));?>是</label>
														  <label class="radio "><?=form_radio("enable_booking","0",empty($enable_booking));?>否</label>
											           </div>
													</div>
													<div class="control-group span7">
											           <label class="control-label">最低預約時間</label>
											           <div class="controls">
													      <?php
														  $min_sec_options = array("1800"=>"30分鐘",
														  						   "3600"=>"1小時",
																				   "7200"=>"2小時",
																				   "10800"=>"3小時",
																				   "14400"=>"4小時",
																				   "28800"=>"8小時",
																				   "86400"=>"24小時");
														  echo form_dropdown("min_sec",$min_sec_options,empty($min_sec)?"3600":$min_sec);
														  ?>
													      
											           </div>
													</div>
												</div>
												<div class="row-fluid">
													<div class="control-group span5">
											           <label class="control-label">檢查預約權限</label>
											           <div class="controls ">
													      <label class="radio "><?=form_radio("enable_privilege","1",!isset($enable_occupation)||!empty($enable_privilege));?>是</label>
														  <label class="radio "><?=form_radio("enable_privilege","0",isset($enable_occupation)&&empty($enable_privilege));?>否</label>
											           </div>
													</div>
													<div class="control-group span7">
											           <label class="control-label">單位預約時間</label>
											           <div class="controls ">
													      <?php
														  $unit_sec_options = array("1800"=>"30分鐘",
														  						   "3600"=>"1小時");
														  echo form_dropdown("unit_sec",$unit_sec_options,empty($unit_sec)?"3600":$unit_sec);
														  ?>
											           </div>
													</div>
												</div>
												<div class="row-fluid">
													<div class="control-group span5">
											           <label class="control-label">僅可單人預約</label>
											           <div class="controls ">
													      <label class="radio "><?=form_radio("enable_occupation","1",!isset($enable_occupation)||!empty($enable_occupation));?>是</label>
														  <label class="radio "><?=form_radio("enable_occupation","0",isset($enable_occupation)&&empty($enable_occupation));?>否</label>
											           </div>
													</div>
													<div class="control-group span7">
											           <label class="control-label">權限延長時間</label>
											           <div class="controls ">
													      <?php
														  $extension_sec_options = array(
														  						   "0"=>"不設限",
														  						   "7776000"=>"3個月",
														  						   "31536000"=>"1年");
														  echo form_dropdown("extension_sec",$extension_sec_options,isset($extension_sec)?$extension_sec:"7776000");
														  ?>
											           </div>
													</div>
												</div>
		                                    </div>
		                                 </div>
									</div>
		                              
								</div>

								<div class="form-actions">
									<button type="submit" class="btn btn-warning">送出</button>
									<button type="reset" class="btn" >重填</button>
									<a href="<?=site_url();?>/facility/admin/facility/list" class="btn btn-primary">取消</a>
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
			



