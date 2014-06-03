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
					 <small>收據處理</small>
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
                        <h4><i class="icon-reorder"></i>收據/發票列表</h4>
                     </div>
                     <div class="widget-body form">
                     	
						<div class="row-fluid">
	                     	<table id="table_cash_receipt_list" class="table table-striped table-bordered">
	                     		
	                     	</table>
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
<div id="modal_cash_receipt_form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 900px; margin-left: -450px; ">
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
							<label class="radio"><input name="receipt_type" type="radio" value="invoice"/>發票</label>
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
				<div class="row-fluid receipt-detail">
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
				<div class="row-fluid receipt-detail">
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
		$("#table_cash_receipt_list").dataTable({
        	"sAjaxSource": site_url+"cash/receipt/query",
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aoColumnDefs": [ {
		      "aTargets": [ 0 ],
		      "sTitle": "類別",
		      "mData": function ( source, type, val ) {
		      	if(source['receipt_type']=='receipt')
		      	{
					return '收據';
				}else if(source['receipt_type']=='invoice')
				{
					return '發票';
				}
		        return '';
		      }
		    },
		    {
		      "aTargets": [ 1 ],
		      "sTitle": "金額",
		      "mData": function ( source, type, val ) {
		        return source['receipt_amount'];
		      }
		    },
		    {
		      "aTargets": [ 2 ],
		      "sTitle": "編號",
		      "mData": function ( source, type, val ) {
		        return source['receipt_ID'];
		      }
		    },
		    {
		      "aTargets": [ 3 ],
		      "sTitle": "連絡人",
		      "mData": function ( source, type, val ) {
		        return source['receipt_contact_name'];
		      }
		    },
		    {
		      "aTargets": [ 4 ],
		      "sTitle": "遞送方式",
		      "mData": function ( source, type, val ) {
		      	if(source['receipt_delivery_way']=='pickup')
		      	{
					return '自取';
				}else if(source['receipt_delivery_way']=='post')
				{
					return '郵寄';
				}
		        return '';
		      }
		    },
		    {
		      "aTargets": [ 5 ],
		      "sTitle": "備註",
		      "mData": function ( source, type, val ) {
		        return source['receipt_remark'];
		      }
		    },
		    {
		      "aTargets": [ 6 ],
		      "sTitle": "動作",
		      "mData": function ( source, type, val ) {
		        return "";
		      }
		    } ],
            "fnDrawCallback": function (oSettings) {
			},
            "fnServerParams": function ( aoData ) {
//		      aoData.push(
//		      	{"name": "class_code", "value": $("#query_cash_curriculum_month").map(function(){return this.value}).get().join('-') }
//		      	
//		      );
		    },
        });
	});
</script>
