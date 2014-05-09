      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     組織單位資料維護
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN VALIDATION STATES-->
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>組織列表</h4>
                     </div>
                     <div class="widget-body form">
                        <table class="table table-striped table-bordered" id="table_org_list">
                        	<thead>
                        		<th>名稱</th>
                        		<th>統編</th>
                        		<th>地址</th>
                        		<th>電話</th>
                        		<th>地位</th>
                        		<th>聯盟</th>
                        		<th width="100"></th>
                        	</thead>
                        </table>
                        <div class="form-actions form-horizontal">
                        	<?=anchor("org/form","新增","class='btn btn-warning'");?>
                        </div>
                     </div>
                  </div>
                  <!-- END VALIDATION STATES-->
               </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

