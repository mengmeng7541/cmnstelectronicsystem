<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
		<div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->                          
              <h3 class="page-title">
                 儀器預約系統
                 <small>儀器預約紀錄管理</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>

            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>預約列表</h4>
                        </div>
                        
                        <div class="widget-body form">
	                        <div class="row-fluid form-horizontal">
	                        	<div class="control-group">
	                        		<label class="control-label">選擇儀器</label>
	                        		<div class="controls">
	                        			<?=form_dropdown("facility_ID[]",isset($facility_ID_select_options)?$facility_ID_select_options:array(),"","multiple='multiple' class='span12 chosen' id='query_booking_list_facility_ID'");?>
	                        		</div>
	                        	</div>
								<div class="control-group">
	                            	<label class="control-label">設定日期</label>
	                            	<div class="controls">
	                                    <input id="query_booking_list_start_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("-1 days"));?>"/>
										<input id="query_booking_list_start_time" type="text" class="input-small timepicker-24-30m" value="<?=date("H:i");?>"/>
										~
										<input id="query_booking_list_end_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("+5 days"));?>"/>
										<input id="query_booking_list_end_time" type="text" class="input-small timepicker-24-30m" value="<?=date("H:i");?>"/>
	                                </div>
	                            </div>
	                        	<div class="control-group"></div>
	                        </div>
							<table id="table_list_booking" class="table table-bordered table-striped">
								<thead>
									<th width="70">帳號</th>
									<th width="50">姓名</th>
									<th>儀器名稱</th>
									<th width="70">目的</th>
									<th width="70">起始時間</th>
									<th width="70">結束時間</th>
									<th width="100">動作/狀態</th>
								</thead>
							</table>
							<!--<div class="form-actions">
	                        	<button id="query_facility_booking" class="btn btn-primary">查詢</button>
	                        </div>-->
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>

<div id="confirm_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-header'>
	    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
	    <h3 id='myModalLabel'>再次確認</h3>
	</div>
	<div class='modal-body'>
		<div class="alert alert-block alert-warning">
			<h4>Notice!預約機台取消相關規定如下：</h4>
			<p>
預約自行操作時段的前一小時無法取消預約<br>
若於預約自行操作時段的前1小時至前24小時取消，本中心酌收預約時段費用的1/3。<br>
預約時間的前一天取消預約，不需收費（一個月以內三次為限)
			</p>
			<p>請問您是否確定要取消預約？</p>
		</div>
	</div>
	<div class='modal-footer'>
		<button type='button' id="facility_booking_cancel_confirm" name="confirm" class='btn btn-warning' data-dismiss='modal'>確認</button>
		<button type='button' class='btn btn-primary' data-dismiss='modal'>取消</button>
	</div>
</div>
	