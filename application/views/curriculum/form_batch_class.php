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
					 <small>批次開課設置</small>
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
                        <h4><i class="icon-reorder"></i>儀器訓練開課 - 批次自動設定</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/class/add/batch" method="POST" class="form-horizontal">
                     		<div class="control-group">
					            <label class="control-label">複製來源</label>
					            <div class="controls">
					            	<input type="text" name="class_copy_src_month" value="<?=date("Y-m");?>" class="date-picker-mm" />
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">複製目標</label>
					            <div class="controls">
						            <input type="text" name="class_copy_dst_month" value="<?=date("Y-m",strtotime("+1month"));?>" class="date-picker-mm" />
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開放註冊時間</label>
					            <div class="controls">
						            <input type="text" name="class_reg_start_date" value="<?=isset($class_reg_start_time)?date("Y-m-d",strtotime($class_reg_start_time)):date("Y-m-d");?>" class="input-small date-picker"/>
						            <input type="text" name="class_reg_start_time" value="<?=isset($class_reg_start_time)?date("H:i",strtotime($class_reg_start_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">結束註冊時間</label>
					            <div class="controls">
					            	<label class="checkbox"><?=form_checkbox("class_reg_end_time_auto","1",isset($class_reg_end_time_auto)?$class_reg_end_time_auto:TRUE,"");?>自動</label>
						            <input type="text" name="class_reg_end_date" value="<?=isset($class_reg_end_time)?date("Y-m-d",strtotime($class_reg_end_time)):date("Y-m-d");?>" class="input-small date-picker"/>
						            <input type="text" name="class_reg_end_time" value="<?=isset($class_reg_end_time)?date("H:i",strtotime($class_reg_end_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" name="copy_month" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/class/list" class="btn btn-warning">取消</a>
							</div>
                     	</form>
                     </div>
                  </div>
               </div>
			</div>
            <!-- END PAGE CONTENT-->   
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>儀器訓練開課 - 批次手動設定</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/class/add/batch" method="POST" class="form-horizontal">
                     		<div class="control-group">
					            <label class="control-label">課程名稱</label>
					            <div class="controls">
					            	<label><input type="checkbox" data-model="select_all" data-target="course_select_box"/>全選</label>
									<?=form_multiselect("course_ID[]",empty($course_ID_select_options)?array():$course_ID_select_options,"","class='span12 chosen' id='course_select_box'");?>
									
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開課類別</label>
					            <div class="controls">
					            <?
					            	$class_type_select_options = array(""=>"","theory"=>"理論","training"=>"訓練","implement"=>"實作","certification"=>"認證");
					            	echo form_multiselect("class_type[]",$class_type_select_options,empty($class_type)?"":explode(",",$class_type),"class='chosen'");	
					            ?>
								</div>
							</div>
							<? $class_code = isset($class_code)?explode("-",$class_code):""; ?>
							<div class="control-group">
					            <label class="control-label">開課月份</label>
					            <div class="controls">
						            <input type="text" name="class_code[]" value="<?=empty($class_code)?date("Y-m",strtotime("+1month")):($class_code[0]."-".$class_code[1]);?>" class="date-picker-mm" />
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開課班別</label>
					            <div class="controls">
						            <?=form_dropdown("class_code[]",array("A"=>"A","B"=>"B","C"=>"C"),empty($class_code)?"":end($class_code),"class='input-mini'");?> 班
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開放註冊時間</label>
					            <div class="controls">
						            <input type="text" name="class_reg_start_date" value="<?=isset($class_reg_start_time)?date("Y-m-d",strtotime($class_reg_start_time)):date("Y-m-d");?>" class="input-small date-picker"/>
						            <input type="text" name="class_reg_start_time" value="<?=isset($class_reg_start_time)?date("H:i",strtotime($class_reg_start_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">結束註冊時間</label>
					            <div class="controls">
					            	<label class="checkbox"><?=form_checkbox("class_reg_end_time_auto","1",isset($class_reg_end_time_auto)?$class_reg_end_time_auto:TRUE,"");?>自動</label>
						            <input type="text" name="class_reg_end_date" value="<?=isset($class_reg_end_time)?date("Y-m-d",strtotime($class_reg_end_time)):date("Y-m-d");?>" class="input-small date-picker"/>
						            <input type="text" name="class_reg_end_time" value="<?=isset($class_reg_end_time)?date("H:i",strtotime($class_reg_end_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" name="default" class="btn btn-primary">送出</button>
								<a href="<?=site_url();?>/curriculum/class/list" class="btn btn-warning">取消</a>
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






