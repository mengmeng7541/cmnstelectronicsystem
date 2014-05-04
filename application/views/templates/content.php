      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid" >
               <div class="span12">
                   <div class="widget-body">
                   		<?
                   			if(isset($code)){
                   				if($code == SUCCESS_CODE)
									$code = "success";
								else if($code === WARNING_CODE) 
									$code = "warning";
								else if($code === ERROR_CODE) 
									$code = "error";
                   			}else{
                   				$code = "success";
                   			}
                   		?>
						<div class="alert alert-<?=$code;?>">
							<strong><?=strtoupper($code);?>!</strong> <?=isset($message)?$message:"";?>
						</div>
                   </div>
               </div>
            </div>

            

            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  

