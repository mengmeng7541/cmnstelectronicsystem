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
	                        			<?=$facility_ID_select;?>
	                        		</div>
	                        	</div>
								<div class="control-group">
	                            	<label class="control-label">設定日期</label>
	                            	<div class="controls">
	                                    <input id="start_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("-1 days"));?>"/>
										<input id="start_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
										~
										<input id="end_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("+5 days"));?>"/>
										<input id="end_time" type="text" class="input-small timepicker-24" value="<?=date("H:i:s");?>"/>
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
							<div class="form-actions">
	                        	<button id="query_facility_booking" class="btn btn-primary">查詢</button>
	                        </div>
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
		<div class="alert alert-block alert-warning"><h4>Warning!</h4><p>自行操作者，若離預約使用時段開始前24小時內取消，<strong>將收取1/3費用</strong></p><p>請問您是否確定要執行此動作？</p></div>
	</div>
	<div class='modal-footer'>
		<button type='button' id="facility_booking_cancel_confirm" name="confirm" class='btn btn-warning' data-dismiss='modal'>確認</button>
		<button type='button' class='btn btn-primary' data-dismiss='modal'>取消</button>
	</div>
</div>
	