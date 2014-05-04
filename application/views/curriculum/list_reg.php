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
					 <small>學員選課</small>
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
                        <h4><i class="icon-reorder"></i>開課學員列表</h4>
                     </div>
                     <div class="widget-body form form-horizontal">
                     	<div class="row-fluid">
	                     	<div class="control-group span6">
					            <label class="control-label">開課月份</label>
					            <div class="controls">
									<input id="query_curriculum_reg_month" type="text" value="<?=date("Y-m");?>" class="date-picker-mm"/>
								</div>
							</div>
							<? if($this->session->userdata('status')=="admin"){ ?>
								<div class="control-group span6">
						            <label class="control-label">班別</label>
						            <div class="controls">
										<?=form_dropdown("",array(""=>"","A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E"),"","id='query_curriculum_reg_class' class='input-mini'");?>
									</div>
								</div>
							<? } ?>
						</div>
						<? if($this->session->userdata('status')=="admin"){ ?>
							<div class="control-group">
					            <label class="control-label">開課課程</label>
					            <div class="controls">
									<?=form_dropdown("",isset($course_ID_select_options)?$course_ID_select_options:array(),"","id='query_curriculum_reg_course_ID' class='input-xxlarge chosen-with-diselect'");?>
								</div>
							</div>
						<? } ?>
						<div class="row-fluid">
							<form action="<?=site_url();?>/curriculum/reg/update" method="POST" class="form-horizontal">
								<table id="table_curriculum_reg_list" class="table table-striped table-bordered">
		                 			<thead>
		                 				<th>課程名稱</th>
		                 				<th width="70">班別</th>
		                 				<th width="50">類別</th>
		                 				<th width="50">學員姓名</th>
		                 				<th width="90">單位/系所</th>
		                 				<th width="50">手機號碼</th>
		                 				<th width="100">email</th>
		                 				<th width="50">指導老師/主管</th>
		                 				<th width="100"><input type="checkbox" data-model="check_all" data-target="reg_ID[]"/>點名/認證/狀態/動作</th>
		                 			</thead>
		                 			
		                 		</table>
		                 		<div class="form-actions">
		             				<button type="button" id="confirm_curriculum_reg" name="confirm" class="btn btn-primary">確認已到</button>
		             				<button type="button" id="certify_curriculum_reg" name="certify" class="btn btn-warning">通過認證</button>
		             			</div>
							</form>
						</div>
                     </div>
                  </div>
               </div>
			</div>
			
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

