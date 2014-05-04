      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     新使用者申請
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
                        <h4><i class="icon-reorder"></i>註冊表單(*者為必定填寫)</h4>
                     </div>
                     <div class="widget-body form">
                        
                        <!-- BEGIN FORM-->
                        <form action="<?=site_url();?>/user/add" id="form_user_register" class="form-horizontal" method="post">
						
						<h3>帳號設定</h3>
						<div class="control-group">
                           <label class="control-label">* 身分證字號</label>
                           <div class="controls">
                              <input type="text" class="span2" name="ID" maxlength="10"/>
                              <p class="help-block">（此為您登入之使用者帳號）</p>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label">* 自訂密碼</label>
                           <div class="controls">
                              <input type="password" class="span6" name="passwd"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 密碼確認</label>
                           <div class="controls">
                              <input type="password" class="span6" name="passwd2"/>
							  <p class="help-block">請再輸入一次自訂的密碼</p>
                           </div>
                        </div>
						
						<h4>基本資料</h4>
						<div class="control-group">
                           <label class="control-label">* 姓名</label>
                           <div class="controls">
                              <input type="text" class="span6" name="name"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label">* 性別</label>
                           <div class="controls">
                              <label class="radio"><input type="radio" name="sex" value="男"/>男</label>
							  <label class="radio"><input type="radio" name="sex" value="女"/>女</label>
                              <span class="help-inline"></span>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label">* 服務單位</label>
                           <div class="controls">
                              <?=form_dropdown("organization",isset($org_select_options)?$org_select_options:array(),"","class='span6 chosen'");?>
							  <a href="#form_modal_org" role="button" class="btn btn-link" data-toggle="modal" data-backdrop="static">若貴單位不在列表中請點此新增</a>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 系所或部門</label>
                           <div class="controls">
                              <input type="text" class="span6" name="department"/>
                              <p class="help-block">( 學校系所或公司服務部門 )</p>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 聯絡電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="tel"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 行動電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="mobile"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">通訊地址</label>
                           <div class="controls">
                              <input type="text" class="span6" name="address"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* E-Mail</label>
                           <div class="controls">
                              <input type="text" class="span6" name="email"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 身份別</label>
                           <div class="controls">
                              <?=form_dropdown("status",isset($status_select_options)?$status_select_options:array(),"");?>
                              <p class="help-block">（如為公司行號之人員，請選"其他"）</p>
                           </div>
                        </div>
						
						<h4>指導老師</h4>
                        <div class="control-group">
                           <label class="control-label">* 指導老師姓名</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_name"/>
                              <p class="help-block">(填姓名即可;若無請填入自己的姓名)</p>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 服務單位</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_department"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* Email</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_email"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 聯絡電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_tel"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>

                           <div class="form-actions">
                              <button type="submit" class="btn btn-success" >送出</button>
							  <a href="#output_model" role="button" class="hide" data-toggle="modal" id="output_model_trigger" data-backdrop="static" data-keyboard="true"></a>
                              <button type="reset" class="btn" >重填</button>
                           </div>
                        </form>
                        <!-- END FORM-->
						<!-- START FORM ORG MODAL-->
						
						<div id="form_modal_org" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:640px;margin-left:-320px">
						    <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						        <h3 id="myModalLabel">表單</h3>
						    </div>
						    <div class="modal-body" style="max-height:none;">
						        <form action="<?=site_url();?>/org/add" class="form-horizontal" method="post">
									<div class="control-group">
			                           <label class="control-label">組織單位</label>
			                           <div class="controls">
			                              <input type="text" class="span12" name="org"/>
			                              <span class="help-inline"></span>
			                           </div>
			                        </div>
								</form>
						    </div>
						    <div class="modal-footer">
								<button name="form_modal_submit" class="btn btn-primary">送出</button>
						    </div>
						</div>
							
						
						
						<!-- END FORM ORG MODAL-->
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

