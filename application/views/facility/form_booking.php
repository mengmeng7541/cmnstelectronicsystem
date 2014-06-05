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
                        <h4><i class="icon-reorder"></i>請先選擇想要預約的時段</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url();?>/facility/user/booking/add" method="POST" class="form-horizontal" id="form_facility_booking">
                     		<div class="control-group">
                     			<label class="control-label">預約儀器</label>
                     			<div class="controls">
                     				<input type="hidden" name="facility_ID" value="<?=$ID;?>"/>
                     				<input type="text" value="<?=$cht_name;?> (<?=$eng_name;?>)" class="span12" disabled="disabled"/>
                     			</div>
                     		</div>
                     		<? if($this->session->userdata('status')=="admin"){ ?>
								<div class="control-group">
	                     			<label class="control-label">預約目的</label>
	                     			<div class="controls">
	                     				<label class="radio"><input type="radio" name="purpose" value="DIY" >自行操作</label>
	                     				<label class="radio"><input type="radio" name="purpose" value="OEM" checked="checked">客戶代工</label>
	                     				<?=anchor("/facility/admin/maintenance/form/{$ID}","維修調校","class='btn btn-warning'");?>
	                     			</div>
	                     		</div>
	                     		<div class="control-group">
	                     			<label class="control-label">備註</label>
	                     			<div class="controls">
	                     				<textarea name="note" rows="5" class="span12"></textarea>
	                     			</div>
	                     		</div>
	                     		<div class="control-group" id="user_selector">
	                     			<label class="control-label">操作者(可不選)</label>
	                     			<div class="controls">
	                     				<?=form_dropdown("user_ID",isset($user_ID_select_options)?$user_ID_select_options:array(),in_array($this->session->userdata('ID'),$user_ID_select_options)?$this->session->userdata('ID'):"","class='chosen-with-diselect'")?>
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
	                     		<button type="button" name="book" class="btn btn-primary">送出</button>
	                     		<a href="<?=site_url();?>/facility/<?=$this->session->userdata('status');?>/available/list" class="btn btn-warning">取消</a>
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

<div id="confirm_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-header'>
	    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
	    <h3 id='myModalLabel'>再次確認</h3>
	</div>
	<div class='modal-body'>
		<div class="alert alert-block alert-warning">
			<h4>Notice!預約機台取消相關規定如下：</h4>
			<p>
預約自行操作時段的前一小時無法取消預約<br>
若於預約自行操作時段的前1小時至前24小時取消，本中心酌收預約時段費用的1/3。<br>
預約時間的前一天取消預約，不需收費（一個月以內三次為限)
			</p>
			<p>請問您是否確定要預約？</p>
		</div>
	</div>
	<div class='modal-footer'>
		<button type='button' id="facility_booking_confirm" name="confirm" class='btn btn-warning' data-dismiss='modal'>確認</button>
		<button type='button' class='btn btn-primary' data-dismiss='modal'>取消</button>
	</div>
</div>