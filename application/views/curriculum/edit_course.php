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
					 <small>課程名稱設定</small>
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
                        <h4><i class="icon-reorder"></i>儀器訓練課程表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/course/update" method="POST" class="form-horizontal">
                     		<input type="hidden" name="course_ID" value="<?=empty($course_ID)?"":$course_ID;?>"/>
                     		<div class="control-group">
					            <label class="control-label">課程中文名稱</label>
					            <div class="controls">
									<input type="text" name="course_cht_name" value="<?=empty($course_cht_name)?"":$course_cht_name;?>" class="span12"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">課程英文名稱</label>
					            <div class="controls">
									<input type="text" name="course_eng_name" value="<?=empty($course_eng_name)?"":$course_eng_name;?>" class="span12"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">關連儀器</label>
					            <div class="controls">
									<?=form_multiselect("facility_ID[]",$facility_select_options,empty($facility_ID)?array():$facility_ID,"class='span12 chosen'");?>
								</div>
							</div>
							
							<div class="control-group">
					            <label class="control-label">擋修</label>
					            <div class="controls">
									<?=form_multiselect("pre_course_ID[]",isset($course_ID_select_options)?$course_ID_select_options:array(),isset($pre_course_ID)?$pre_course_ID:"","class='span12 chosen'");?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">預設最小開課人數</label>
					            <div class="controls">
									<input type="number" name="course_min_participants" value="<?=isset($course_min_participants)?$course_min_participants:"2";?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">預設最大開課人數</label>
					            <div class="controls">
									 <input type="number" name="course_max_participants" value="<?=isset($course_max_participants)?$course_max_participants:"4";?>"/>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/course/list" class="btn btn-warning">取消</a>
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

