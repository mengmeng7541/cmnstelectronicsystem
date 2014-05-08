      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     老師/主管資料維護
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
                        <h4><i class="icon-reorder"></i>老師/主管資料表單</h4>
                     </div>
                     <div class="widget-body form">
					 	<form action="<?=site_url('admin/boss/update');?>" id="" class="form-horizontal" method="post">
							<div class="control-group">
	                           <label class="control-label">編號</label>
	                           <div class="controls">
	                              <input name="serial_no" type="text" class="span2" value="<?=isset($serial_no)?$serial_no:"";?>" <?=isset($serial_no)?'readonly="readonly"':"";?>/>
	                           </div>
	                        </div>
	                        <h4>基本資料</h4>
							<div class="control-group">
	                           <label class="control-label">姓名</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="name" value="<?=isset($name)?$name:""?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">組織</label>
	                           <div class="controls">
	                              <?=form_dropdown("organization",isset($org_ID_select_options)?$org_ID_select_options:array(),isset($organization)?$organization:"","class='chosen'");?>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">單位</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="department" value="<?=isset($department)?$department:"";?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">電話</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="tel" value="<?=isset($tel)?$tel:"";?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">E-Mail</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="email" value="<?=isset($email)?$email:"";?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
	                        
						   <div class="form-actions">
							  <input type="submit" class="btn btn-primary" value="送出" />
						   </div>
						</form>
								
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

