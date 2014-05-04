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
                        <h4><i class="icon-reorder"></i>委託檢測件確認</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/nanomark/update_specimen" method="POST" class="form-horizontal" id="form_nanomark_specimen">
                     		<div class="control-group">
					            <label class="control-label">檢測件ID</label>
					            <div class="controls">
									<input type="text" name="specimen_SN" value="<?=isset($specimen_SN)?$specimen_SN:"";?>" readonly="readonly"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">檢測項目</label>
					            <div class="controls">
									<?=isset($test_item_name)?$test_item_name:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">樣品名稱</label>
					            <div class="controls">
									<?=isset($name)?$name:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">廠商名稱</label>
					            <div class="controls">
									<?=isset($company_name)?$company_name:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">產品廠牌</label>
					            <div class="controls">
									<?=isset($brand)?$brand:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">產品型號</label>
					            <div class="controls">
									<?=isset($model)?$model:"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">使用儀器</label>
					            <div class="controls">
					            	<?=isset($facility_ID)?form_hidden("facility_ID",$facility_ID):"";?>
									<?=isset($facility_cht_name)?$facility_cht_name:"";?> (<?=isset($facility_eng_name)?$facility_eng_name:"";?>)
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">檢測人員</label>
					            <div class="controls">
					            	<?
					            		if(isset($facility_engineer_ID))
					            		{
									?>
											<input type="hidden" name="engineer_ID" value="<?=$facility_engineer_ID;?>" /><?=isset($engineer_ID_select_options)?$engineer_ID_select_options[$facility_engineer_ID]:"";?>
									<?
										}else{
											echo form_dropdown("engineer_ID",isset($engineer_ID_select_options)?$engineer_ID_select_options:array(),"","");
										}
					            	?>
								</div>
								<table id="table_specimen_facility_booking_list" class="table table-striped table-bordered">
									<thead>
										<th>起始時間</th>
										<th>結束時間</th>
										<th></th>
									</thead>
									<input type="hidden" id="specimen_ID" value="<?=isset($specimen_SN)?$specimen_SN:"";?>" />
								</table>
							</div>
							
							<div class="control-group">
					            <label class="control-label">新增預約</label>
					            <div class="controls">
									<input type="text" id="query_facility_booking_date" value="<?=date("Y-m-d",strtotime("+2days"));?>" class="date-picker"/>
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
							
							
							<div class="form-actions">
								<button type="submit" name="booking" class="btn btn-primary">預約</button>
								<button type="submit" name="confirm" class="btn btn-danger">蓋章</button>
								<a href="<?=site_url();?>/nanomark/edit_application/<?=isset($application_SN)?$application_SN:"";?>" class="btn btn-warning">取消</a>
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

