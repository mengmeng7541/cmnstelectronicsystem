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
                     <div class="widget-body form" ng-controller="oem_application_edit" ng-init="<?=isset($app_SN)?"get_app($app_SN)":(isset($form_SN)?"get_form($form_SN)":"");?>">
                     	<form action="<?=isset($app_SN)?site_url('oem/app/update'):site_url('oem/app/add');?>" method="POST" class="form-horizontal">
                     		<!--<input type="hidden" name="app_SN" value="{{app.app_SN}}" />
                     		<input type="hidden" name="form_SN" value="{{app.form_SN}}"/>-->
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
						            	<input type="text" value="{{user.user_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">指導教授或主管姓名</label>
						            <div class="controls">
						            	<input type="text" value="{{user.user_boss_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">手機</label>
						            <div class="controls">
						            	<input type="text" value="{{user.user_mobile}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">信箱</label>
						            <div class="controls">
						            	<input type="text" value="{{user.user_email}}" class="span12" disabled="disabled"/>
									</div>
								</div>
                     		</div>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">單位</label>
						            <div class="controls">
						            	<input type="text" value="{{user.user_org_name}}" class="span12" disabled="disabled"/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">系所或部門</label>
						            <div class="controls">
						            	<input type="text" value="{{user.user_department}}" class="span12" disabled="disabled"/>  	
									</div>
								</div>
                     		</div>
							<h4>代工服務</h4>
							<div class="control-group">
					            <label class="control-label">代工服務名稱</label>
					            <div class="controls">
					            	<input type="text" class="span12" value="{{forms[0].form_cht_name}} ({{forms[0].form_eng_name}})" disabled="disabled"/>
								</div>
							</div>
							<div class="control-group" ng-show="forms.length > 1">
					            <label class="control-label">需要額外服務嗎?</label>
					            <div class="controls">
					            	<label class="radio" ng-repeat="form in forms" ng-init="app.form_idx=0"><input uniform type="radio" name="form_SN" ng-model="app.form_idx" value="{{$index}}"/>{{form.form_cht_name}}({{form.form_eng_name}})</label>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">注意事項</label>
					            <div class="controls" >
					            	<pre>{{forms[0].form_note}}</pre>
					            	<pre ng-show="app.form_idx">{{forms[app.form_idx].form_note}}</pre>
								</div>
							</div>
							<div class="control-group" ng-repeat="col in app.app_cols" ng-show="col.col_enable">
								<label class="control-label">{{col.col_cht_name}}</label>
								<div class="controls">
									<input type="text" ng-model="col.col_value" value="" class="span12"/>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">其它事宜</label>
					            <div class="controls" >
					            	<textarea ng-model="app.app_description" rows="5" class="span12"></textarea>
								</div>
							</div>
							<h4>簽核流程</h4>
                     		<div class="row-fluid">
                     			<div class="control-group span6">
						            <label class="control-label">儀器負責人簽章</label>
						            <div class="controls">
						            	<!--<textarea name="checkpoint_comment" class="span12"></textarea>-->
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
									$display[] = form_button("save","儲存","class='btn btn-primary' ng-click='submit()'");
	                     			$display[] = form_button("submit","送出","class='btn btn-warning'");
	                     			$display[] = anchor("oem/app/new","取消","class='btn btn-primary'");
								}else{
									$display[] = form_button("accept","接受","class='btn btn-warning'");
	                     			$display[] = form_button("reject","退件","class='btn btn-danger'");
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
