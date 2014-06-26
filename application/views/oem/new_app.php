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
                     <div class="widget-body form" data-ng-controller="oem_application_edit" data-ng-init="<?=isset($app_SN)?"get_app($app_SN)":(isset($form_SN)?"get_form($form_SN)":"");?>">
                     	<form action="<?=isset($app_SN)?site_url('oem/app/update'):site_url('oem/app/add');?>" method="POST" class="form-horizontal">
                     		<input type="hidden" name="app_SN" value="{{app.app_SN}}" />
                     		<input type="hidden" name="form_SN" value="{{app.form_SN}}"/>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">代工單編號</label>
						            <div class="controls">
						            	<input type="text" name="app_ID" class="span12" value="{{app.app_ID}}" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">代工申請日期</label>
						            <div class="controls">
						            	<input type="text" name="app_time" class="span12" value="{{app.app_time}}" disabled="disabled"/>
									</div>
								</div>
                     		</div>
							<h4>基本資料</h4>
							<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">委託人姓名</label>
						            <div class="controls">
						            	<input type="text" value="{{app.user_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">指導教授或主管姓名</label>
						            <div class="controls">
						            	<input type="text" value="{{app.boss_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">手機</label>
						            <div class="controls">
						            	<input type="text" value="{{app.user_mobile}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">信箱</label>
						            <div class="controls">
						            	<input type="text" value="{{app.user_email}}" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">單位</label>
						            <div class="controls">
						            	<input type="text" value="{{app.org_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">系所或部門</label>
						            <div class="controls">
						            	<input type="text" value="{{app.user_department}}" class="span12" disabled="disabled"/>  	
									</div>
								</div>
                     		</div>
							<h4>代工儀器</h4>
							<div class="control-group">
					            <label class="control-label">代工儀器</label>
					            <div class="controls">
					            	<input type="text" class="span12" value="{{app.form_cht_name}} ({{app.form_eng_name}})" disabled="disabled"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">注意事項</label>
					            <div class="controls" >
					            	<pre>{{app.form_note}}</pre>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">代工需求</label>
					            <div class="controls">
					            	<textarea name="app_description" class="span12" rows="10">{{app.app_description||app.form_description}}</textarea>
								</div>
							</div>
							<h4>訪客卡申請(代工時)</h4>
							<div class="row-fluid " ng-repeat="guest in app.guests">
                     			<div class="control-group span6">
						            <label class="control-label">人員姓名</label>
						            <div class="controls">
						            	<input type="text" name="guest_name[]" value="{{guest.name}}"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">聯絡手機</label>
						            <div class="controls">
						            	<input type="text" name="guest_mobile[]" value="{{guest.mobile}}"/>
									</div>
								</div>
                     		</div>
                     		<div class="control-group">
					            <label class="control-label"></label>
					            <div class="controls">
					            	<button type="button" class="btn btn-primary" ng-click="new_guest(app.guests)">新增一筆</button>
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
	                     		<?
	                     		if(empty($app_checkpoint)||$app_checkpoint=='user_init')
	                     		{
									$display[] = form_submit("save","儲存","class='btn btn-primary'");
	                     			$display[] = form_submit("submit","送出","class='btn btn-warning'");
	                     			$display[] = anchor("oem/app/new","取消","class='btn btn-primary'");
								}else{
									$display[] = form_submit("accept","接受","class='btn btn-warning'");
	                     			$display[] = form_submit("reject","退件","class='btn btn-danger'");
	                     			$display[] = anchor("oem/app/list","取消","class='btn btn-primary'");
								}
								echo implode(' ',$display);
	                     		?>
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
