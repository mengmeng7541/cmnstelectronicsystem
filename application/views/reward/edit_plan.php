
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     論文獎勵申請系統
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
                        <h4><i class="icon-reorder"></i>獎勵方案設定表單</h4>
                     </div>
                     <div class="widget-body form">
                        <!-- BEGIN FORM-->
                        <form action="<?=site_url("/reward/plan/update");?>" id="" class="form-horizontal" method="post">
                        	<? if(isset($serial_no)){ ?>
								<div class="control-group">
								  <label class="control-label">編號</label>
								  <div class="controls">
								     <?=form_input("serial_no",$serial_no,"class='span2' readonly='readonly'");?>
								  </div>
								</div>	
                        	<? } ?>
							   
							   <div class="control-group">
	                              <label class="control-label">方案名稱</label>
	                              <div class="controls">
	                                 <input type="text" name="name" class="span6" value="<?=isset($name)?$name:"";?>" />
	                              </div>
	                           </div>	
                           <div class="control-group">
                              <label class="control-label">獎金</label>
                              <div class="controls">
                                 <?=form_input("points",isset($points)?$points:"","class='span6'");?>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">開放選擇</label>
                              <div class="controls">
							  	 <?=form_checkbox("available",1,isset($available)&&$available);?>
                              </div>
                           </div>
                           <div class="form-actions">
                              <input type="submit" value="送出" class="btn btn-warning"/>
                              <?=anchor($this->whence->pop(),"取消","class='btn btn-primary'");?>
                           </div>
                        </form>
                        <!-- END FORM-->						
						
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


