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
					 <small>代工申請</small>
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
                        <h4><i class="icon-reorder"></i>代工表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=isset($app_SN)?site_url('oem/app/update'):site_url('oem/app/add');?>" method="POST" id="" class="form-horizontal">
                     		<input type="hidden" name="app_SN" value="<?=isset($app_SN)?$app_SN:"";?>"/>
                     		<input type="hidden" name="form_SN" value="<?=isset($form_SN)?$form_SN:"";?>"/>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">代工單編號</label>
						            <div class="controls">
						            	<input type="text" name="form_cht_name" class="span12" value="<?=isset($form_cht_name)?$form_cht_name:"";?>" readonly="readonly"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">代工申請日期</label>
						            <div class="controls">
						            	<input type="text" name="form_eng_name" class="span12" value="<?=isset($form_eng_name)?$form_eng_name:"";?>" disabled="disabled"/>
									</div>
								</div>
                     		</div>
							<h4>基本資料</h4>
							<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">委託人姓名</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($user_name)?$user_name:"";?>" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">指導教授或主管姓名</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($boss_name)?$boss_name:"";?>" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">手機</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($user_mobile)?$user_mobile:"";?>" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">信箱</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($user_email)?$user_email:"";?>" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">單位</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($org_name)?$org_name:"";?>" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">系所或部門</label>
						            <div class="controls">
						            	<input type="text" value="<?=isset($user_department)?$user_department:"";?>" class="span12" disabled="disabled"/>  	
									</div>
								</div>
                     		</div>
							<h4>代工儀器</h4>
							<div class="control-group">
					            <label class="control-label">代工儀器</label>
					            <div class="controls">
					            	<input type="text" class="span12" value="<?=isset($form_cht_name)&&isset($form_eng_name)?"{$form_cht_name} ({$form_eng_name})":"";?>" disabled="disabled"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">注意事項</label>
					            <div class="controls">
					            	<?=isset($form_note)?nl2br($form_note):"";?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">代工需求</label>
					            <div class="controls">
					            	<textarea name="app_description" class="span12" rows="10"><?=isset($app_description)?$app_description:(isset($form_description)?$form_description:"");?></textarea>
								</div>
							</div>
							<h4>簽核流程</h4>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">儀器負責人簽章</label>
						            <div class="controls">
						            	<textarea name="checkpoint_comment" class="span12"></textarea>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">技術組副組長簽章</label>
						            <div class="controls">
						            	
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
								<div class="control-group span6">
						            <label class="control-label">老師/主管簽章：</label>
						            <div class="controls">
						            	
									</div>
								</div>
								<div class="control-group span6 hide">
						            <label class="control-label">共同實驗室組長簽章</label>
						            <div class="controls">
						            	
									</div>
								</div>
                     		</div>
	                     	<div class="form-actions">
	                     		<button type="submit" class="btn btn-warning">送出</button>
	                     		<button type="submit" name="accept" class="btn btn-warning">接受</button>
	                     		<button type="submit" name="reject" class="btn btn-danger">退件</button>
	                     		<a href="<?=site_url('oem/app/list');?>" class="btn btn-primary">取消</a>
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

