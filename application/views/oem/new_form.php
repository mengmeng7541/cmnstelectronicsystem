      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid" ng-controller="oem_form_edit" ng-init="get_form(<?=isset($form_SN)?$form_SN:"";?>)">
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
                     <div class="widget-body form" >
                     	<form method="POST" class="form-horizontal" ng-submit="submit()">
<!-- BEGIN ACCORDION PORTLET-->
<div class="accordion" id="accordion1" >
  <div class="accordion-group" ng-repeat="(form_idx,form) in forms">
     <div class="accordion-heading">
     	
     	
        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" ng-href="#collapse_{{form_idx}}">
        <i class=" icon-plus"></i>
        {{form.form_cht_name + ' (' + form.form_eng_name + ')'}} 
        
        </a>
        
     </div>
     <div id="collapse_{{form_idx}}" class="accordion-body collapse in">
        <div class="accordion-inner">
        	<div class="row-fluid">
				<div class="control-group span6">
		            <label class="control-label">代工單管理員</label>
		            <div class="controls">
		            	<?=form_dropdown("form_admin_ID",isset($admin_ID_select_options)?$admin_ID_select_options:array(),"","ng-model='form.form_admin_ID' chosen class='span12'");?>
					</div>
				</div>
				<div class="control-group span6">
		            <label class="control-label">開放代工服務</label>
		            <div class="controls">
		            	<label class="radio"><input uniform type="radio" name="form_enable[{{form_idx}}]" ng-model="form.form_enable" value="1"/>開放</label>
		            	<label class="radio"><input uniform type="radio" name="form_enable[{{form_idx}}]" ng-model="form.form_enable" value="0"/>關閉</label>
					</div>
				</div>
			</div>
            <div class="control-group">
	            <label class="control-label">代工單中文名稱</label>
	            <div class="controls">
	            	<input type="text" name="form_cht_name" class="span12" value="" ng-model="form.form_cht_name"/>
				</div>
			</div>
			<div class="control-group">
	            <label class="control-label">代工單英文名稱</label>
	            <div class="controls">
	            	<input type="text" name="form_eng_name" class="span12" value="" ng-model="form.form_eng_name"/>
				</div>
			</div>
			<div class="control-group">
	            <label class="control-label">代工單關連儀器</label>
	            <div class="controls">
	            	<!--<select chosen watch="facilities" ng-model="form.form_facility_SN" ng-options="facility.facility_SN as (facility.facility_cht_name + ' (' + facility.facility_eng_name + ')') for facility in facilities" class="span12" multiple="multiple">
	            	</select>-->
	            	<?=form_multiselect("form_facility_SN[]",isset($facility_SN_select_options)?$facility_SN_select_options:array(),"","ng-model='form.form_facility_SN' chosen class='span12'")?>
				</div>
			</div>
			<div class="control-group">
	            <label class="control-label">注意事項</label>
	            <div class="controls">
	            	<textarea name="form_note" class="span12" rows="10" ng-model="form.form_note"></textarea>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label">表單欄位 <button type="button" class="btn btn-small btn-primary" ng-click="new_column(form_idx)">新增</button></label>
				<div class="controls">
					<!--<div class="row-fluid" ng-repeat="(col_idx,col) in form.form_cols">
						<div class="control-group span6">
							<label class="control-label">中文名稱</label>
							<div class="controls">
								<input type="text" ng-model="col.col_cht_name" class="input-medium"/>
							</div>
						</div>
						<div class="control-group span6">
							<label class="control-label">英文名稱</label>
							<div class="controls">
								<input type="text" ng-model="col.col_eng_name" class="input-medium"/>
							</div>
						</div>
					</div>-->
<!--BEGIN TABS-->
<div class="tabbable tabbable-custom" >
		<ul class="nav nav-tabs">
		   <li class="" ng-repeat="(col_idx,col) in form.form_cols"><a tab data-toggle="tab" ng-href="#tab_{{form_idx}}_{{col_idx}}">{{col.col_cht_name}} <i class="icon-remove" ng-click="del_column(form_idx,col_idx)" ng-show="!col.form_col_SN"></i></a></li>
		</ul>
		<div class="tab-content">
		   <div ng-repeat="(col_idx,col) in form.form_cols" class="tab-pane active" id="tab_{{form_idx}}_{{col_idx}}">
				<div class="row-fluid">
					<div class="control-group span6">
						<label class="control-label">中文名稱</label>
						<div class="controls">
							<input type="text" ng-model="col.col_cht_name" class="input-medium"/>
						</div>
					</div>
					<div class="control-group span6">
						<label class="control-label">英文名稱</label>
						<div class="controls">
							<input type="text" ng-model="col.col_eng_name" class="input-medium"/>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="control-group span6">
						<label class="control-label">規則</label>
						<div class="controls">
							<select ng-model="col.col_rule" class="input-small">
								<option value="optional">選填</option>
								<option value="required">必填</option>
							</select>
						</div>
					</div>
					<div class="control-group span6">
						<label class="control-label">是否啟用</label>
						<div class="controls">
							<label class="radio"><input uniform type="radio" name="form_col_enable[{{form_idx}}]" ng-model="col.col_enable" value="1"/>開放</label>
		            		<label class="radio"><input uniform type="radio" name="form_col_enable[{{form_idx}}]" ng-model="col.col_enable" value="0"/>關閉</label>
						</div>
					</div>
				</div>
		   </div>
		</div>
</div>
<!--<div bs-tabs="form.form_cols">
</div>-->
<!--END TABS-->
				</div>
			</div>
			
			<div class="control-group">
	            <label class="control-label">預設描述(客戶填寫)</label>
	            <div class="controls">
	            	<textarea name="form_description" class="span12" rows="10" ng-model="form.form_description"></textarea>
				</div>
			</div>
			<div class="control-group">
	            <label class="control-label">備註</label>
	            <div class="controls">
	            	<input type="text" name="form_remark" class="span12" value="" ng-model="form.form_remark"/>
				</div>
			</div>
			
        </div>
     </div>
  </div>
</div>
<!-- END ACCORDION PORTLET-->      

                     		
                     	
	                     	<div class="form-actions">
	                     		<button type="button" class="btn btn-danger" ng-click="new_form()">新增服務</button>
	                     		<button type="button" ng-click="submit()" class="btn btn-primary">儲存</button>
	                     		<a href="<?=site_url('oem/form/list');?>" class="btn btn-warning">取消</a>
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

