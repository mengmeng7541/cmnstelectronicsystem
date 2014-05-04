<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
					<h3 class="page-title">儀器預約系統
					<small>門禁管理</small>
					</h3>
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>門禁設定</h4>
                        </div>
                        <div class="widget-body form">
							<form action="<?=$action;?>" id="form_facility_config" class="form-horizontal" method="POST">
								<div class="row-fluid">
									<div class="control-group span6">
							           <label class="control-label">門禁ID</label>
							           <div class="controls">
									      <input name="ID" type="text" class="input-small" value="<?=empty($ID)?"":$ID;?>" <?=(!empty($ID))?'readonly="readonly"':'';?>/>
							           </div>
							        </div>
							        <div class="control-group span6">
							           <label class="control-label">控制卡機編號</label>
							           <div class="controls">
									      <input name="ctrl_no" type="text" class="input-mini" value="<?=empty($ctrl_no)?"":$ctrl_no;?>"/>
							           </div>
							        </div>
								</div>
								
								<div class="row-fluid">
									
									<div class="control-group span6">
									   <label class="control-label">預先開放</label>
									   <div class="controls">
										  <?=form_dropdown("pre_open_sec",array("0"=>"不提前","60"=>"1分鐘","1800"=>"30分鐘","3600"=>"1小時"),empty($pre_open_sec)?0:$pre_open_sec,"class='input-small'");?>
							           </div>
									</div>
									<div class="control-group span6">
									   <label class="control-label">門禁位置</label>
									   <div class="controls">
										  <?=form_dropdown("location_ID",isset($facility_location_ID_select_options)?$facility_location_ID_select_options:array(),isset($location_ID)?$location_ID:"","class='span12' ");?>
							           </div>
									</div>
								</div>
								
								<div class="control-group">
						           <label class="control-label">父門禁</label>
						           <div class="controls">
								      <?=$parent_ID;?>
						           </div>
						        </div>
								<div class="control-group">
						           <label class="control-label">名稱</label>
						           <div class="controls">
								      <input name="name" type="text" class="span12" value="<?=empty($cht_name)?"":$cht_name;?>"/>
						           </div>
						        </div>
								<div class="control-group">
						           <label class="control-label">備註</label>
						           <div class="controls">
								      <textarea name="note" rows="5" class="span12"><?=empty($note)?"":$note;?></textarea>
						           </div>
						        </div>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary">送出</button>
									<button type="reset" class="btn" >重填</button>
									<a href="<?=site_url();?>/facility/admin/facility/list" class="btn btn-warning">取消</a>
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
			



