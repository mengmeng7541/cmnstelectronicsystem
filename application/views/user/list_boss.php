      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     老師主管資料維護
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
                        <h4><i class="icon-reorder"></i>老師/主管列表</h4>
                     </div>
                     <div class="widget-body">
					 	
                        <table class="table table-striped table-bordered" id="table_boss_list">
							<thead>
								<tr>
									<th>姓名</th>
									<th>所屬組織</th>
									<th>所屬單位</th>
									<th>電話</th>
									<th>EMAIL</th>
									<th width="100"></th>
								</tr>
							</thead>
							
						</table>
						<div class="row-fluid">
							<div class="span12 form-horizontal">
								<div class="form-actions">
									<?=anchor("/boss/form","新增","class='btn btn-warning'");?>
								</div>
							</div>
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

