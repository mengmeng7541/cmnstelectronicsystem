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
					 <small>線上簽到</small>
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
                        <h4><i class="icon-reorder"></i>儀器訓練課程上課簽到列表</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/curriculum/signature/update" method="POST" class="form-horizontal" id="form_curriculum_signature">
	                     	<div class="control-group">
	                     		<label class="control-label">查詢時段</label>
	                     		<div class="controls">
	                     			<input type="text" id="lesson_start_date" value="<?=date("Y-m-d");?>" class="date-picker input-small"/>
	                     			~
	                     			<input type="text" id="lesson_end_date" value="<?=date("Y-m-d");?>" class="date-picker input-small"/>
	                     		</div>
	                     	</div>
							<table id="table_curriculum_signature_list" class="table table-striped table-bordered">
	                 			<thead>
	                 				<th>課程名稱</th>
	                 				<th width="40">類別</th>
	                 				<th width="50">授課者</th>
	                 				<th width="50">學員</th>
	                 				<th width="70">起始時間</th>
	                 				<th width="70">結束時間</th>
	                 				<th width="50">地點</th>
	                 				<th width="115"><input type="checkbox" data-model="check_all" data-target="signature_ID[]|reg_ID[]"/> 動作/狀態</th>
	                 			</thead>
	                 		</table>
	                 		<div class="form-actions">
	                 			<button type="button" id="query_curriculum_signature_list" class="btn btn-primary">查詢</button>
	                 			<button type="submit" id="confirm_curriculum_signature" class="btn btn-warning" name="confirm">確認已到</button>
	                 			<button type="submit" id="certify_curriculum_signature" class="btn btn-warning" name="certify">認證通過蓋章</button>
								<?=isset($action_btn)?implode(' ',$action_btn):"";?>
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

