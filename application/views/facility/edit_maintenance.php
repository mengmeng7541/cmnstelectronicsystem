<?
$manager = $this->facility_model->get_admin_privilege_list(
			array("admin_ID"=>$this->session->userdata('ID'),"privilege"=>"facility_maintenance_manager")
			)->row_array();
?>
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
					 <small>儀器維修調教</small>
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
                     	<h4><i class="icon-reorder"></i>申請表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=empty($action_url)?"":$action_url;?>" class="form-horizontal" method="POST" id="form_facility_maintenance">
                     		<input type="hidden" name="facility_ID" value="<?=empty($ID)?empty($facility_ID)?"":$facility_ID:$ID;?>"/>
                     		<input type="hidden" name="booking_ID" value="<?=empty($booking_ID)?"":$booking_ID;?>"/>
                     		<input type="hidden" name="serial_no" value="<?=empty($serial_no)?"":$serial_no;?>"/>
                     		<div class="control-group">
                     			<label class="control-label">儀器</label>
                     			<div class="controls">
                     				
                     				<input type="text" value="<?=empty($facility_cht_name)?"":$facility_cht_name;?>(<?=empty($facility_eng_name)?"":$facility_eng_name;?>)" class="span12" disabled="disabled"/>
                     			</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">事由</label>
					            <div class="controls">
									<label class="radio "><input type="radio" name="subject" value="參數測試" <?=empty($subject)?"":$subject=="參數測試"?"checked='checked'":"";?>/>參數測試</label>
									<label class="radio "><input type="radio" name="subject" value="維護保養" <?=empty($subject)?"":$subject=="維護保養"?"checked='checked'":"";?>/>維護保養</label>
									<label class="radio "><input type="radio" name="subject" value="儀器復機" <?=empty($subject)?"":$subject=="儀器復機"?"checked='checked'":"";?>/>儀器復機</label>
									<label class="radio "><input type="radio" name="subject" value="內部維修" <?=empty($subject)?"":$subject=="內部維修"?"checked='checked'":"";?>/>內部維修</label>
									<label class="radio "><input type="radio" name="subject" value="外部維修" <?=empty($subject)?"":$subject=="外部維修"?"checked='checked'":"";?> id="facility_maintenance_outsourcing"/>外部維修</label>
									<label class="radio "><input type="radio" name="subject" value="其他" />其他 </label>
								</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">描述</label>
					            <div class="controls">
									<textarea name="content" class="span12" rows="5"><?=empty($content)?"":$content;?></textarea>
								</div>
                     		</div>
                     		<div class="control-group" id="booking_time_table">
                     			<label class="control-label">時段</label>
					            <div class="controls">
					            	<input type="text" id="query_facility_booking_date" class="date-picker" value="<?=date("Y-m-d",strtotime("+2 days"));?>"/>
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
                     		</div>
	                     	
                     		<!--<div class="control-group advanced_data">
                     			<label class="control-label">費用</label>
					            <div class="controls">
									<input type="text" name="fees" value="<?=empty($fees)?"":$fees;?>">
								</div>
                     		</div>
                     		<div class="control-group advanced_data">
                     			<label class="control-label">廠商</label>
					            <div class="controls">
									<input type="text" name="maintainer_name" value="<?=empty($maintainer_name)?"":$maintainer_name;?>">
								</div>
                     		</div>-->
	                     	
	                     	<? if($manager){ ?>
                     		<!--<div class="control-group">
                     			<label class="control-label">審核</label>
					            <div class="controls">
									<label class="radio"><input type="radio" name="result" value="1"/>同意</label>
									<label class="radio"><input type="radio" name="result" value="0"/>不同意</label>
								</div>
                     		</div>-->
                     		<? } ?>
                     		<div class="form-actions">
                     			<button type="submit" class="btn btn-primary">送出</button>
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

