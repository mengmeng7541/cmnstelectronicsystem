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
                     	<form action="<?=site_url();?>/curriculum/lesson/update" method="POST" class="form-horizontal">
                     		<input type="hidden" name="lesson_ID" value="<?=isset($lesson_ID)?$lesson_ID:"";?>"/>
                     		<?
                     		if(isset($booking_ID)){
                     			foreach($booking_ID as $b_ID)
                     			{
									echo form_hidden("booking_ID[]",$b_ID);
								}
							}
                     		?>
                     		
                     		<div class="control-group">
					            <label class="control-label">開課名稱</label>
					            <div class="controls">
									<input type="hidden" name="class_ID" value="<?=empty($class_ID)?"":$class_ID;?>"/>
									<?=empty($course_cht_name)?"":$course_cht_name;?> (<?=empty($course_eng_name)?"":$course_eng_name;?>) 第 <?=empty($class_code)?"":$class_code;?> 期 
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">授課者</label>
					            <div class="controls">
									<?=form_dropdown("user_ID",$user_ID_select_options,empty($lesson_prof_ID)?"":$lesson_prof_ID,"class='chosen'");?>
								</div>
							</div>
							<? if(empty($facility_ID)){ ?>
								<div class="control-group">
						            <label class="control-label">授課時段</label>
						            <div class="controls">
										<input type="text" name="lesson_start_date" value="<?=date("Y-m-d",empty($lesson_start_time)?time():strtotime($lesson_start_time));?>" class="date-picker input-small"/>
										<input type="text" name="lesson_start_time" value="<?=date("H:i",empty($lesson_start_time)?time():strtotime($lesson_start_time));?>" class="timepicker-24-30m input-mini"/>
										~
										<input type="text" name="lesson_end_date" value="<?=date("Y-m-d",empty($lesson_end_time)?time():strtotime($lesson_end_time));?>" class="date-picker input-small"/>
										<input type="text" name="lesson_end_time" value="<?=date("H:i",empty($lesson_end_time)?time():strtotime($lesson_end_time));?>" class="timepicker-24-30m input-mini"/>
									</div>
								</div>
							<? }else{ ?>
								
								<div class="control-group">
						            <label class="control-label">使用儀器</label>
						            <div class="controls">
										<?=form_multiselect("facility_ID[]",$facility_ID_select_options,$facility_ID,"class='chosen input-xxlarge' disabled='disabled'");?>
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">授課時段</label>
						            <div class="controls">
										<input type="text" id="query_facility_booking_date" value="<?=isset($query_date)?$query_date:date("Y-m-d",strtotime("+2days"));?>" class="date-picker"/>
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
								</div>
								
							<? } ?>
							
							
							
                     		
                     		
							<div class="control-group">
					            <label class="control-label">備註</label>
					            <div class="controls">
									<textarea name="lesson_comment" class="span12"><?=empty($lesson_comment)?"":$lesson_comment;?></textarea>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/lesson/list/<?=empty($class_ID)?"":$class_ID;?>" class="btn btn-warning">取消</a>
							</div>
                     	</form>
                 		
						
                     </div>
                  </div>
               </div>
			</div>
			<? if(!empty($lesson_ID)){ ?>
				<div class="row-fluid">
	               <div class="span12">
	                  <div class="widget">
	                     <div class="widget-title">
	                        <h4><i class="icon-reorder"></i>課程預約儀器列表</h4>
	                     </div>
	                     <div class="widget-body form">
							<table id="table_curriculum_booking_list" class="table table-striped table-bordered">
	                 			<thead>
	                 				<th width="50">預約ID</th>
	                 				<th width="50">授課教師</th>
	                 				<th>儀器名稱</th>
	                 				<th width="75">起始時間</th>
	                 				<th width="75">結束時間</th>
	                 				<th width="75">預約狀況</th>
	                 				<th width="115">動作/狀態</th>
	                 			</thead>
	                 		</table>
	                 		<div class="form-actions">
								<a href="<?=site_url();?>/curriculum/booking/form/<?=isset($lesson_ID)?$lesson_ID:"";?>" class="btn btn-primary">補約</a>
							</div>
	                     </div>
	                  </div>
	               </div>
				</div>
			<? } ?>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

