<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
    	<!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
              <h3 class="page-title">
                 自動打卡系統
				 <small>使用者版</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN ADVANCED TABLE widget-->
		<div class="row-fluid">
			<div class="span12">
	        	<div class="widget">
	    			<div class="widget-title">
	                    <h4><i class="icon-reorder"></i></h4>
	                </div>
	                <div class="widget-body">
	                	<input type="hidden" id="location_ID" value="<?=isset($location_ID)?$location_ID:"";?>" />
						<table id="table_user_clock_list" class="table table-striped table-bordered">
							
							<thead>
								<th width=""></th>
								<th width="50">姓名</th>
								<th width="50">電話</th>
								<th width="100">進入時間</th>
								<th width="100">最後反應時間</th>
							</thead>
							
						</table>
	                </div>
	    		</div>
    		</div>
        </div>
        
        <!-- END ADVANCED TABLE widget-->
    </div>
</div>
				