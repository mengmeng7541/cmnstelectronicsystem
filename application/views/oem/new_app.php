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
                     <div class="widget-body form" data-ng-controller="oem_application_edit">
                     	<form action="<?=isset($app_SN)?site_url('oem/app/update'):site_url('oem/app/add');?>" method="POST" id="" class="form-horizontal">
                     		<input type="text" name="app_SN" value="" data-ng-model="app.app_SN" data-ng-init="init(<?=isset($app_SN)?$app_SN:"";?>)"/>
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
							<h4>訪客卡申請(代工時)</h4>
							<div class="row-fluid ">
                     			<div class="control-group span6">
						            <label class="control-label">人員姓名</label>
						            <div class="controls">
						            	<input type="text" name="guest_name[]" value=""/>
									</div>
								</div>
								<div class="control-group span6">
						            <label class="control-label">聯絡手機</label>
						            <div class="controls">
						            	<input type="text" name="guest_mobile[]" value=""/>
									</div>
								</div>
                     		</div>
                     		<div class="control-group">
					            <label class="control-label"></label>
					            <div class="controls">
					            	<button type="button" class="btn btn-primary ">新增一筆</button>
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
<script>
	
	$(document).ready(function(){
		cmnstApp
		.controller("oem_application_edit",function($scope,$http){
			$scope.init = function(SN){
				$http
				.get(site_url+'oem/app/query',{params:{app_SN:SN}})
				.success(function(data){
					$scope.app = data.aaData[0];
					console.log($scope.app);
				});
			};
		});
	});
</script>
