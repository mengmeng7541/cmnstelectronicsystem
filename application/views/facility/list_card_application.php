<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
		<div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->                          
              <h3 class="page-title">
                 儀器預約系統
                 <small>磁卡管理</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>

            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget ">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>磁卡申請列表</h4>
                        </div>
                        
                        <div class="widget-body form">
	                        <table id="table_list_card_application" class="table table-striped table-bordered">
								<thead>
									<th style="width:30px;">SN</th>
									<th style="width:45px;" >類別</th>
									<th>備註</th>
									<th style="width:85px;">申請日期</th>
									<th>帳號</th>
									<th style="width:60px;">姓名</th>
									<th >EMAIL</th>
									<th>電話</th>
									<th>卡號</th>
									<th style="width:85px;">處理日期</th>
									<th style="width:60px;">確認</th>
									<th style="width:55px;">動作</th>
								</thead>
							</table>
							<!--<div class="form-actions">
								<a href="" class="btn btn-warning">手動編輯</a>
							</div>-->
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>

<div id="form_facility_card_application_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:640px;margin-left:-320px">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">表單</h3>
    </div>
    <div class="modal-body form-horizontal" style="max-height:none;">
        <form action="<?=site_url();?>/facility/admin/card/update/" method="POST">
			<div class="control-group">
				<label class="control-label">卡號</label>
				<div class="controls">
					<input type="text" name="card_num" value="0000" maxlength="8"/>
				</div>
			</div>
			
		</form>
    </div>
    <div class="modal-footer">
		<button name="form_modal_submit" data-dismiss="modal" class="btn btn-primary">送出</button>
    </div>
</div>