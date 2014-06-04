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
					 <small>奈米標章</small>
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
                     	<form id="form_nanomark_bill_list_table" class="form-horizontal">
                     		
							<div class="row-fluid">
		                     	<table id="table_cash_bill_nanomark_list" class="table table-striped table-bordered">
		                     		<thead>
		                     			<tr>
		                     				<th>廠商</th>
		                     				<th>委測者</th>
		                     				<th>檢測項目</th>
		                     				<th>申請日</th>
		                     				<th>委託單號</th>
		                     				<th>費用</th>
		                     				<th>發票</th>
		                     				<th>基金會申請單號</th>
		                     				<th>備註</th>
		                     			</tr>
		                     		</thead>
		                     	</table>
	                     	</div>
	                     	<div class="form-actions">
	                     		<button name="open_receipt" type="button" class="btn btn-primary">開立發票</button>
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
<div id="form_cash_receipt_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 900px; margin-left: -450px; ">
		<div class='modal-header'>
		    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
		    <h3 id='myModalLabel'>收據/發票</h3>
		</div>
		<div class='modal-body'>
			<form id="form_cash_receipt" action="<?=site_url('cash/receipt/add');?>" method="POST" class="form form-horizontal">
				<div class="row-fluid">
					<table id="table_cash_bill_list" class="table table-striped table-bordered">
					</table>
				</div>
				<input type="hidden" name="account_boss" value=""/>
				<div class="row-fluid">
					<div class="control-group span6">
						<label class="control-label">收據類別</label>
						<div class="controls">
							<label class="radio"><input name="receipt_type" type="radio" value="receipt"/>收據</label>
							<label class="radio"><input name="receipt_type" type="radio" value="invoice" checked="checked"/>發票</label>
						</div>
					</div>
					<div class="control-group span6">
						<label class="control-label">收據編號</label>
						<div class="controls">
							<input name="receipt_ID" type="text" value=""/>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="control-group span6">
						<label class="control-label">收據抬頭</label>
						<div class="controls">
							<input name="receipt_title" type="text" value=""/>
						</div>
					</div>
					<div class="control-group span6">
						<label class="control-label">收據金額</label>
						<div class="controls">
							<input name="account_amount" type="text" value=""/>
						</div>
					</div>
				</div>
				<div class="row-fluid receipt-detail hide">
					<div class="control-group span6">
						<label class="control-label">收據作業</label>
						<div class="controls">
							<label class="radio"><input name="receipt_delivery_way" type="radio" value="pickup"/>自取</label>
							<label class="radio"><input name="receipt_delivery_way" type="radio" value="post"/>郵寄</label>
						</div>
					</div>
					<div class="control-group span6 receipt-email">
						<label class="control-label">電子郵件</label>
						<div class="controls">
							<input name="receipt_contact_email" type="text" value=""/>
						</div>
					</div>
					<div class="control-group span6 receipt-address hide">
						<label class="control-label">郵寄地址</label>
						<div class="controls">
							<input name="receipt_contact_address" type="text" value=""/>
						</div>
					</div>
				</div>
				<div class="row-fluid receipt-detail hide">
					<div class="control-group span6">
						<label class="control-label">連絡人姓名</label>
						<div class="controls">
							<input name="receipt_contact_name" type="text" value=""/>
						</div>
					</div>
					<div class="control-group span6">
						<label class="control-label">連絡電話</label>
						<div class="controls">
							<input name="receipt_contact_tel" type="text" value=""/>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class='modal-footer'>
			<button type='button' id="open_receipt" name="confirm_receipt" class='btn btn-warning' data-dismiss='modal'>確認</button>
			<button type='button' class='btn btn-primary' data-dismiss='modal'>取消</button>
		</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#table_cash_bill_nanomark_list").dataTable({
        	"sAjaxSource": site_url+"cash/nanomark/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "fnDrawCallback": function (oSettings) {
//				handleUniform();	
			},
            "fnServerParams": function ( aoData ) {
//		      aoData.push(
//		      	{"name": "class_code", "value": $("#query_cash_bill_curriculum_month").map(function(){return this.value}).get().join('-') }
//		      	
//		      );
		    },
        });
	});
</script>