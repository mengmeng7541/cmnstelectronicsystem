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
					 <small>開課設置</small>
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
                     	<form action="<?=site_url();?>/curriculum/class/update" method="POST" class="form-horizontal">
                     		<input type="hidden" name="class_ID" value="<?=empty($class_ID)?"":$class_ID;?>"/>
                     		<div class="control-group">
					            <label class="control-label">課程名稱</label>
					            <div class="controls">
									<?=form_dropdown("course_ID",empty($course_ID_select_options)?array():$course_ID_select_options,empty($course_ID)?"":$course_ID,"class='span12 chosen'");?>
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
							<div class="control-group">
					            <label class="control-label">最小開課人數</label>
					            <div class="controls">
						            <input type="number" name="class_min_participants" value="<?=isset($class_min_participants)?$class_min_participants:"2";?>"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">最大人數限制</label>
					            <div class="controls">
						            <input type="number" name="class_max_participants" value="<?=isset($class_max_participants)?$class_max_participants:"4";?>"/>
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
						            <?=form_dropdown("class_code[]",array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E"),empty($class_code)?"":end($class_code),"class='input-mini'");?> 班
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開課地點</label>
					            <div class="controls">
					            <?=form_dropdown("class_location",isset($location_name_select_options)?$location_name_select_options:array(),empty($class_location)?"":$class_location,"class='chosen'");?>
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
							<div class="control-group">
					            <label class="control-label">開課狀況</label>
					            <div class="controls">
						            <?=form_dropdown("class_state",array("normal"=>"正常","canceled"=>"停開","special"=>"加開"),isset($class_state)?$class_state:"");?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">開課註記</label>
					            <div class="controls">
						            <input type="text" name="class_remark" value="<?=isset($class_remark)?$class_remark:"";?>"/>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">送出</button>
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

