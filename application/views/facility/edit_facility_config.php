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
                        <div class="widget-body form" data-ng-controller="facility_config_edit">
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
													<div class="control-group span6">
											           <label class="control-label">機台ID</label>
											           <div class="controls">
													      <input name="ID" type="text" class="input-small" value="<?=empty($ID)?"":$ID;?>" <?=(!empty($ID))?'readonly="readonly"':'';?>/>
											           </div>
											        </div>
											        <div class="control-group span6">
											           <label class="control-label">新ID</label>
											           <div class="controls">
													      <input name="new_ID" type="text" class="input-small" value="<?=empty($new_ID)?"":$new_ID;?>" />
											           </div>
											        </div>
												</div>
												<div class="row-fluid">
													<div class="control-group span6">
											           <label class="control-label">控制卡機編號</label>
											           <div class="controls">
													      <input name="ctrl_no" type="text" class="input-small" value="<?=isset($ctrl_no)?$ctrl_no:"";?>"/>
											           </div>
											        </div>
											        <div class="control-group span6">
											           <label class="control-label">儀器電話分機</label>
											           <div class="controls">
													      <input name="tel_ext" type="text" class="input-small" value="<?=isset($tel_ext)?$tel_ext:"";?>"/>
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
													  <label class="radio "><?=form_radio("status","migrated",isset($state)?($state=="migrated"):"");?>移轉</label>
													  
										           </div>
												</div>
												
												<div class="control-group">
										           <label class="control-label">停機時間</label>
										           <div class="controls">
													  <button type="button" id="open_facility_outage_modal" value="" class="btn btn-primary" data-toggle="modal" data-target="#modal_facility_outage" ng-click="outage = null">新增</button>
										           </div>
										           
												</div>
												<div class="control-group row-fluid">
												   <table my-datatable id="table_facility_outage_list" class="table table-striped table-bordered" >
										              <thead>
										                 <tr>
										                 	<th>停機起始時間</th>
										                 	<th>停機結束時間</th>
										                 	<th>停機原因</th>
										                 	<th>動作</th>
										                 </tr>
										              </thead>
										              <tbody>
										                 <!--<tr ng-repeat="outage in outages">
										                 	<td>{{outage.outage_start_time}}</td>
										                 	<td>{{outage.outage_end_time}}</td>
										                 	<td>{{outage.outage_remark}}</td>
										                 	<td>
										                 		<button type="button" class="btn btn-small btn-warning" ng-click="get_facility_outage(outage.outage_SN)" data-toggle="modal" data-target="#modal_facility_outage">編輯</button>
										                 	</td>
										                 </tr>-->
										              </tbody>
										           </table>
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
														  						   "3600"=>"1小時",
																				   "7200"=>"2小時",
																				   "10800"=>"3小時",
																				   "14400"=>"4小時",
																				   "28800"=>"8小時",
																				   "86400"=>"24小時");
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
														  						   "15768000"=>"6個月",
														  						   "31536000"=>"一年");
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
							<div id="modal_facility_outage" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							    <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							        <h3 id="myModalLabel">儀器停機</h3>
							    </div>
							    <div class="modal-body">
							        <form id="form_facility_outage" action="<?=site_url('facility/admin/outage/update');?>" method="POST" class="form-horizontal">
							        	<input type="hidden" name="outage_SN" value="{{outage.outage_SN}}"/>
							        	<input type="hidden" name="facility_SN" value="<?=empty($ID)?"":$ID;?>"/>
							        	<div class="control-group ">
								           <label class="control-label">停機起始時間</label>
								           <div class="controls ">
										      <input name="outage_start_date" type="text" value="{{outage.outage_start_time|date:'yyyy-MM-dd'}}" class="date-picker input-small" />
											  <input name="outage_start_time" type="text" value="{{outage.outage_start_time|date:'HH:mm'}}" class="timepicker-24-30m input-mini" />
								           </div>
										</div>
										<div class="control-group ">
								           <label class="control-label">停機結束時間(選填)</label>
								           <div class="controls ">
											  <input name="outage_end_date" type="text" value="{{outage.outage_end_time|date:'yyyy-MM-dd'}}" class="date-picker input-small" />
											  <input name="outage_end_time" type="text" value="{{outage.outage_end_time|date:'HH:mm'}}" class="timepicker-24-30m input-mini" />
								           </div>
										</div>
										<div class="control-group ">
								           <label class="control-label">停機原因</label>
								           <div class="controls ">
										      <input name="outage_remark" type="text" value="{{outage.outage_remark}}" class="span12" />
								           </div>
										</div>
							        </form>
							    </div>
							    <div class="modal-footer">
									<button type='submit' name="confirm_outage" class='btn btn-warning' data-dismiss='modal'>確認</button>
									<button type='button' class='btn btn-primary' data-dismiss='modal'>取消</button>
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
<script type="text/javascript">

	$(document).ready(function(){
		
	});
	$(document).ready(function(){
		cmnstApp
		.controller("facility_config_edit",function($scope,$http){
			$scope.get_facility_outage = function(SN)
			{
				$http
				.get('/index.php/facility/admin/outage/query',{params:{outage_SN:SN}})
				.success(function(data){
					if(data.aaData.length)
					{
						data.aaData[0].outage_start_time = Date.parse(data.aaData[0].outage_start_time);
						data.aaData[0].outage_end_time = Date.parse(data.aaData[0].outage_end_time);
						$scope.outage = data.aaData[0];
					}
					console.log($scope.outage);
				});
			}
		})
		.directive('myDatatable',function(){
		    return { 
				restrict: 'A', 
				link: function(scope,element,attrs){ 
					//jquery
					element.on('click', "button",function(){ 
						scope.$apply($(this).attr("ng-click"));
					}); 
				} 
		    }; 
		});
		
		
//		angular.module('compile', [], function($compileProvider) {
//		    // configure new 'compile' directive by passing a directive
//		    // factory function. The factory function injects the '$compile'
//		    $compileProvider.directive('my-table', function($compile) {
//			// directive factory creates a link function
//				return function(scope, element, attrs) {
//					scope.$watch(
//						function(scope) {
//							// watch the 'my-table' expression for changes
//							return scope.$eval(attrs.my-table);
//						},
//						function(value) {
//							// when the 'compile' expression changes
//							// assign it into the current DOM
////							element.html(value);
//
//							// compile the new DOM and link it to the current
//							// scope.
//							// NOTE: we only compile .childNodes so that
//							// we don't get into infinite loop compiling ourselves
//							$compile(value)(scope);
//						}
//					);
//				};
//			})
//		});
	});
</script>

