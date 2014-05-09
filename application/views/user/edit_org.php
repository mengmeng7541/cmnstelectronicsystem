      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     組織維護
                  </h3>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN VALIDATION STATES-->
                  <div class="widget box blue">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>資料表單</h4>
                     </div>
                     <div class="widget-body form">
                        
                        <!-- BEGIN FORM-->
                        <form action="<?=site_url();?>/org/update" id="" class="form-horizontal" method="post">
                        	<? if(isset($serial_no)){ ?>
	                        	<div class="control-group">
									<label class="control-label">組織編號</label>
									<div class="controls">
										<input name="serial_no" type="text" class="span2" value="<?=$serial_no;?>" readonly="readonly"/>
									</div>
								</div>
                        	<? } ?>
							

							<h4>基本資料</h4>
							<div class="control-group">
								<label class="control-label">名稱</label>
								<div class="controls">
									<input type="text" class="span6" name="name" value="<?=isset($name)?$name:"";?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">統編</label>
								<div class="controls">
									<input type="text" class="span6" name="VAT" value="<?=isset($VAT)?$VAT:"";?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">地址</label>
								<div class="controls">
									<input type="text" class="span6" name="address" value="<?=isset($address)?$address:"";?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">電話</label>
								<div class="controls">
									<input type="text" class="span6" name="tel" value="<?=isset($tel)?$tel:""?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">地位</label>
								<div class="controls">
									<?=form_dropdown("status_ID",isset($status_ID_select_options)?$status_ID_select_options:array(),isset($status_ID)?$status_ID:"");?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">聯盟</label>
								<div class="controls">
									<?=form_dropdown("aliance_no",isset($aliance_no_select_options)?$aliance_no_select_options:array(),isset($aliance_no)?$aliance_no:"","");?>
									
								</div>
							</div>						

							<div class="form-actions">
								<button type="submit" class="btn btn-primary" >更新</button>
								<button type="reset" class="btn" >重填</button>
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

