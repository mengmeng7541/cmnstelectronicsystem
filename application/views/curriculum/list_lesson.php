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
					 <small>課程時間排定</small>
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
                        <h4><i class="icon-reorder"></i>儀器訓練開課時間排定列表</h4>
                     </div>
                     <div class="widget-body form">
                     	<input type="hidden" name="class_ID" value="<?=isset($class_ID)?$class_ID:"";?>" />
						<table id="table_curriculum_lesson_list" class="table table-striped table-bordered">
                 			<thead>
                 				<th width="40">類別</th>
                 				<th>授課者</th>
                 				<th>起始時間</th>
                 				<th>結束時間</th>
                 				<th>地點</th>
                 				<th width="115">動作/狀態</th>
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

