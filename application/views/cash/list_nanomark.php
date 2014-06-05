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
<?=$this->load->view('cash/form_receipt',array("bill_type"=>"nanomark"),true);?>
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