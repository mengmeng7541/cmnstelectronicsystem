      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     奈米標章
					 <small>奈米技術產品測試實驗室</small>
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
                        <h4><i class="icon-reorder"></i>委託檢測儀器預約預約表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/booking/update" method="POST" class="form-horizontal" id="form_curriculum_facility_booking">
                     		<input type="hidden" name="booking_ID" value="<?=empty($booking_ID)?"":$booking_ID;?>"/>
                     		<div class="control-group">
					            <label class="control-label">檢測件ID</label>
					            <div class="controls">
									<input type="text" name="lesson_ID" value="<?=empty($lesson_ID)?"":$lesson_ID;?>" readonly="readonly"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">使用儀器</label>
					            <div class="controls">
									<?
										if(empty($facility_ID)){
											echo form_dropdown("facility_ID",$facility_ID_select_options,"","class='span12 chosen'");
										}else{
											echo $facility_cht_name." (".$facility_eng_name.")";
										}
									?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">授課者</label>
					            <div class="controls">
									<?=empty($user_name)?"":$user_name;?>
								</div>
							</div>
							<input type="hidden" id="query_facility_booking_date" value="<?=empty($lesson_start_time)?"":date("Y-m-d",strtotime($lesson_start_time));?>"/>
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

