<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
		<div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->                          
              <h3 class="page-title">
                 儀器預約系統
                 <small>故障不計費管理系統</small>
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
	                        <!--<div class="row-fluid form-horizontal">
	                        	<div class="control-group">
	                        		<label class="control-label">選擇儀器</label>
	                        		<div class="controls">
	                        			
	                        		</div>
	                        	</div>
								<div class="control-group">
	                            	<label class="control-label">設定日期</label>
	                            	<div class="controls">
	                                    <input id="start_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d",strtotime("-1 day"));?>"/>
										<input id="start_time" type="text" class="input-small timepicker-24" value="<?=date("h:i:s");?>"/>
										~
										<input id="end_date" type="text" class="input-small date-picker" value="<?=date("Y-m-d");?>"/>
										<input id="end_time" type="text" class="input-small timepicker-24" value="<?=date("h:i:s");?>"/>
	                                </div>
	                            </div>
	                        	<div class="control-group"></div>
	                        </div>-->
							<table id="table_list_nocharge" class="table table-bordered table-striped">
								<thead>
									<th>編號</th>
									<th>申請日期</th>
									<th>帳號</th>
									<th>姓名</th>
									<th>儀器名稱</th>
									<th>預約區間</th>
									<th>動作</th>
								</thead>
							</table>
							
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				