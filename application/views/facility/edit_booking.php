      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器預約系統
					 <small>儀器預約</small>
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
                        <h4><i class="icon-reorder"></i>請選擇想要預約的時段</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/facility/user/booking/update/<?=$serial_no;?>" method="POST" class="form-horizontal" id="form_facility_booking">
                     		<div class="control-group">
                     			<label class="control-label">預約儀器</label>
                     			<div class="controls">
                     				<input type="hidden" name="booking_ID" value="<?=$serial_no;?>"/>
                     				<input type="text" value="<?=$facility_cht_name;?> (<?=$facility_eng_name;?>)" class="span12" disabled="disabled"/>
                     			</div>
                     		</div>
                     		<? if($this->session->userdata('status')=="admin"){	?>
                     			<div class="control-group">
	                     			<label class="control-label">備註</label>
	                     			<div class="controls">
	                     				<textarea name="note" rows="5" class="span12" readonly="readonly"><?=empty($note)?"":$note;?></textarea>
	                     			</div>
	                     		</div>
	                     		<div class="control-group">
	                     			<label class="control-label">時段查詢</label>
	                     			<div class="controls">
	                     				<input id="query_facility_booking_date" type="text" class="date-picker" value="<?=date("Y-m-d",strtotime("+2 days"));?>"/>
	                     			</div>
	                     		</div>
							<? } ?>
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
	                     		<a href="<?=site_url();?>/facility/<?=$this->session->userdata('status');?>/booking/list" class="btn btn-warning">取消</a>
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

