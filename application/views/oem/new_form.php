      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器代工系統
					 <small>代工表單維護</small>
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
                        <h4><i class="icon-reorder"></i>表單維護</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=isset($form_SN)?site_url('oem/form/update'):site_url('oem/form/add');?>" method="POST" id="" class="form-horizontal">
                     		<input type="hidden" name="form_SN" value="<?=isset($form_SN)?$form_SN:"";?>"/>
                     		<div class="control-group">
					            <label class="control-label">代工單中文名稱</label>
					            <div class="controls">
					            	<input type="text" name="form_cht_name" class="span12" value="<?=isset($form_cht_name)?$form_cht_name:"";?>"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">代工單英文名稱</label>
					            <div class="controls">
					            	<input type="text" name="form_eng_name" class="span12" value="<?=isset($form_eng_name)?$form_eng_name:"";?>"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">代工單關連儀器</label>
					            <div class="controls">
					            	<?=form_multiselect("facility_SN[]",isset($facility_SN_select_options)?$facility_SN_select_options:array(),isset($facility_SN)?$facility_SN:"","class='span12 chosen'");?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">注意事項</label>
					            <div class="controls">
					            	<textarea name="form_note" class="span12" rows="10"><?=isset($form_note)?$form_note:"";?></textarea>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">預設描述(客戶填寫)</label>
					            <div class="controls">
					            	<textarea name="form_description" class="span12" rows="10"><?=isset($form_description)?$form_description:"";?></textarea>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">備註</label>
					            <div class="controls">
					            	<input type="text" name="form_remark" class="span12" value="<?=isset($form_remark)?$form_remark:"";?>"/>
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group span6">
						            <label class="control-label">代工單管理員</label>
						            <div class="controls">
						            	<?=form_dropdown("form_admin_ID",isset($admin_ID_select_options)?$admin_ID_select_options:array(),isset($form_admin_ID)?$form_admin_ID:$this->session->userdata('ID'),"class='span12 chosen'");?>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">開放代工</label>
						            <div class="controls">
						            	<label class="radio"><?=form_radio("form_enable",1,isset($form_enable)&&$form_enable);?>開放</label>
						            	<label class="radio"><?=form_radio("form_enable",0,isset($form_enable)&&!$form_enable);?>關閉</label>
									</div>
								</div>
							</div>
                     	
	                     	<div class="form-actions">
	                     		<button type="submit" class="btn btn-warning">儲存</button>
	                     		<a href="<?=site_url('oem/form/list');?>" class="btn btn-primary">取消</a>
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

