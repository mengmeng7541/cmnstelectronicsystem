      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     使用者維護
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
                        <form action="<?=site_url();?>/user/update" id="form_user_register" class="form-horizontal" method="post">
						<div class="control-group">
                           <label class="control-label">身分證字號<br>(或統一編號)</label>
                           <div class="controls">
                              <input name="ID" type="text" class="span2" value="<?=$ID?>" readonly="readonly"/>
                           </div>
                        </div>
                        
						<h4>基本資料</h4>
						<div class="control-group">
                           <label class="control-label">* 姓名</label>
                           <div class="controls">
                              <input type="text" class="span6" name="name"value="<?=$name?>"/>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label">* 性別</label>
                           <div class="controls">
                              <label class="radio"><input type="radio" name="sex" value="男" <?php if($sex=="男") echo "checked='checked'"; ?>/>男</label>
							  <label class="radio"><input type="radio" name="sex" value="女" <?php if($sex=="女") echo "checked='checked'"; ?>/>女</label>
                              <span class="help-inline"></span>
                           </div>
                        </div>
                        <div class="control-group">
                           <label class="control-label">* 服務單位</label>
                           <div class="controls">
                              <?=form_dropdown("organization",isset($org_select_options)?$org_select_options:array(),isset($organization)?$organization:"","class='span6 chosen'");?>
							  <!--<a href="#form_modal_org" role="button" class="btn btn-link" data-toggle="modal" data-backdrop="static">若貴單位不在列表中請點此新增</a>-->
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 系所或部門</label>
                           <div class="controls">
                              <input type="text" class="span6" name="department" value="<?=$department?>"/>
                              <p class="help-block">( 學校系所或公司服務部門 )</p>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 聯絡電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="tel" value="<?=$tel?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 行動電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="mobile" value="<?=$mobile?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 通訊地址</label>
                           <div class="controls">
                              <input type="text" class="span6" name="address" value="<?=$address?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* E-Mail</label>
                           <div class="controls">
                              <input type="text" class="span6" name="email" value="<?=$email?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 身份別</label>
                           <div class="controls">
                              <?=form_dropdown("status",isset($status_select_options)?$status_select_options:array(),isset($status)?$status:"");?>
                              <p class="help-block">（如為公司行號之人員，請選"其他"）</p>
                           </div>
                        </div>
						<? if($this->session->userdata('status')=="admin"){ ?>
						<div class="control-group">
                           <label class="control-label">卡號</label>
                           <div class="controls">
                              <input type="text" class="span6" name="card_num" value="<?=empty($card_num)?"":$card_num;?>"/>
                           </div>
                        </div>
						<? } ?>
						<h4>指導老師</h4>
                        <div class="control-group">
                           <label class="control-label">* 指導老師姓名</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_name" value="<?=isset($boss['name'])?$boss['name']:"";?>"/>
                              <p class="help-block">(填姓名即可;若無請填入自己的姓名)</p>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 服務單位</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_department" value="<?=isset($boss['department'])?$boss['department']:"";?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* Email</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_email" value="<?=isset($boss['email'])?$boss['email']:"";?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>
						<div class="control-group">
                           <label class="control-label">* 聯絡電話</label>
                           <div class="controls">
                              <input type="text" class="span6" name="boss_tel" value="<?=isset($boss['tel'])?$boss['tel']:"";?>"/>
                              <span class="help-inline"></span>
                           </div>
                        </div>

                           <div class="form-actions">
                              <button type="submit" class="btn btn-primary" >更新</button>
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

