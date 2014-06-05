      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     現金帳務系統
					 <small>儀器訓練課程</small>
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
                        <h4><i class="icon-reorder"></i>帳單列表</h4>
                     </div>
                     <div class="widget-body form">
                     	<form id="form_curriculum_bill_list_table" class="form-horizontal">
                     		<div class="row-fluid">
	                     		<div class="control-group span12">
						            <label class="control-label">開課月份</label>
						            <div class="controls">
										<input id="query_cash_bill_curriculum_month" type="text" value="<?=date("Y-m");?>" class="date-picker-mm"/>
									</div>
								</div>
	                     	</div>
	                     	
							<div class="row-fluid">
		                     	<table id="table_cash_bill_curriculum_list" class="table table-striped table-bordered">
		                     		<thead>
		                     			<tr>
		                     				<th>課程</th>
		                     				<th>班別</th>
		                     				<th>類別</th>
		                     				<th>學員</th>
		                     				<th>組織</th>
		                     				<th>費用/折扣</th>
		                     				<th>收據/發票</th>
		                     				<th>備註</th>
		                     			</tr>
		                     		</thead>
		                     	</table>
	                     	</div>
	                     	<div class="form-actions">
	                     		<button name="open_receipt" type="button" class="btn btn-primary">開立收據</button>
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
<?=$this->load->view('cash/form_receipt',true);?>
