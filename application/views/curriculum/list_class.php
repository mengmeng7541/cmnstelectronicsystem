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
					 <small></small>
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
                        <h4><i class="icon-reorder"></i>儀器訓練開課列表</h4>
                     </div>
                     <div class="widget-body form form-horizontal">
                     	<div class="alert alert-block alert-info">
                     		<?=isset($bulletin['bulletin_content'])?$bulletin['bulletin_content']:"";?>
                     	</div>
                     	<div class="control-group">
				            <label class="control-label">開課月份</label>
				            <div class="controls">
								<input id="query_curriculum_class_month" type="text" value="<?=date("Y-m");?>" class="date-picker-mm"/>
							</div>
						</div>
                 		<table id="table_curriculum_class_list" class="table table-striped table-bordered">
                 			<thead>
                 				<? if($this->session->userdata('status')=='admin'){ ?>
									<th>課程名稱</th>
	                 				<th width="30">班別</th>
	                 				<th width="40">類別</th>
	                 				<th width="50">地點</th>
	                 				<th width="70">上課時間</th>
	                 				<th width="70">結束時間</th>
	                 				<th width="30">授課時數</th>
	                 				<th width="40">授課者</th>
	                 				<th width="30">人數</th>
	                 				<th width="40">狀況</th>
	                 				<th width="40">註記</th>
	                 				<th width="100"></th>
								<? } else { ?>
									<th>課程名稱</th>
	                 				<th width="30">班別</th>
	                 				<th width="70">上課時間</th>
	                 				<th width="30">人數</th>
	                 				<th width="40">狀況</th>
	                 				<th width="40">註記</th>
	                 				<th width="100"></th>
								<? } ?>
                 				
                 			</thead>
                 			
                 		</table>
                 		<div class="form-actions">
                 			<?=isset($action_btn)?implode(' ',$action_btn):"";?>
                 			

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

