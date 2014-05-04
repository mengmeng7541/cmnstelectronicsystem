      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器訓練課程系統
					 <small>開課課表設定</small>
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>開課課表排定表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/booking/update" method="POST" class="form-horizontal" id="form_curriculum_facility_booking">
                     		<input type="hidden" name="booking_ID" value="<?=isset($booking_ID)?$booking_ID:"";?>"/>
                     		<div class="control-group">
					            <label class="control-label">課堂ID</label>
					            <div class="controls">
									<input type="text" name="lesson_ID" value="<?=empty($lesson_ID)?"":$lesson_ID;?>" readonly="readonly"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">預約原因</label>
					            <div class="controls">
									<input type="text" name="booking_remark" value="<?=isset($booking_remark)?$booking_remark:"";?>"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">使用儀器</label>
					            <div class="controls">
									<?
										if(empty($facility_ID)){
											echo form_dropdown("facility_ID",isset($facility_ID_select_options)?$facility_ID_select_options:array(),"","class='span12 chosen'");
										}else{
											echo isset($facility_ID_select_options)?$facility_ID_select_options[$facility_ID]:"";
										}
									?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">授課者</label>
					            <div class="controls">
									<?=isset($user_name)?$user_name:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">授課日期</label>
					            <div class="controls">
									<input type="text" id="query_facility_booking_date" value="<?=empty($booking_start_time)?date("Y-m-d",strtotime("+2days")):date("Y-m-d",strtotime($booking_start_time));?>" class="date-picker"/>
								</div>
							</div>
							<table id="table_facility_booking_available_time" class="table table-striped table-bordered">
                     			<thead>
                     				<th></th>
                     				<th></th>
                     				<th></th>
                     				<th></th>
                     				<th></th>
                     				<th></th>
                     			</thead>
                     		</table>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/lesson/edit/<?=empty($lesson_ID)?"":$lesson_ID;?>" class="btn btn-warning">取消</a>
							</div>
                     	</form>
                 		
						
                     </div>
                  </div>
               </div>
			</div>
			
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

