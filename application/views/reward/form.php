
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                   
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     論文獎勵申請系統
                  </h3>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
            	<div class="span12">
            		<div class="alert alert-info">
            			本中心會定期從申請補助儀器使用費中的論文，遴選出優良之論文置於中心網頁上供大家參考及展示用，同時也會通知被遴選出來之論文的老師！
            		</div>
            	</div>
            </div>
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN VALIDATION STATES-->
                  <div class="widget">
                     <div class="widget-title">
                        <h4><i class="icon-reorder"></i>(*者為必定填寫，否則不予以申請)</h4>
                     </div>
                     <div class="widget-body form">
                        
                        <!-- BEGIN FORM-->
                        <form action="<?=site_url();?>/reward/add" id="reward_application" class="form-horizontal" method=post enctype="multipart/form-data">
                        	<? $readonly = isset($readonly)?" disabled='disabled'":""; ?>
						   <? if($this->session->userdata('status')=="admin") { ?>
							   <div class="control-group">
	                              <label class="control-label">編號</label>
	                              <div class="controls">
	                                 <?=form_input("serial_no",isset($serial_no)?$serial_no:"","class='span6' readonly='readonly'");?>
	                                 <span class="help-inline" id="name"></span>
	                              </div>
	                           </div>	
							   <div class="control-group">
	                              <label class="control-label">申請日期</label>
	                              <div class="controls">
	                                 <input type="text" class="span6" value="<?=isset($application_date)?$application_date:"";?>" disabled="disabled"/>
	                                 <span class="help-inline" id="name"></span>
	                              </div>
	                           </div>				   
						   <? } ?>
                           <h4>申請人資料</h4>
                           <div class="control-group">
                              <label class="control-label">* 姓名</label>
                              <div class="controls">
                                 <?=form_input("applicant_name",isset($applicant_name)?$applicant_name:"","class='span6'".$readonly);?>
                                 <span class="help-inline" id="name"></span>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 服務單位</label>
                              <div class="controls">
							  	 <?=form_input("department",isset($department)?$department:"","class='span6'".$readonly);?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">例：成大航太系</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 聯絡電話</label>
                              <div class="controls">
                                 <?=form_input("tel",isset($tel)?$tel:"","class='span6'".$readonly);?>
                                 <span class="help-inline"></span>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* E-mail</label>
                              <div class="controls">
                                 <?=form_input("email",isset($email)?$email:"","class='span6'".$readonly);?>
                                 <span class="help-inline"></span>
                              </div>
                           </div>
                           
                           <h4>研究資料</h4>
						   <div class="control-group">
                              <label class="control-label">* 研究領域(可複選)</label>
                              <div class="controls">
							  	 <?
							  	 echo "<label class='checkbox'>".form_checkbox("research_field[]","奈米材料",isset($research_field)&&$research_field=="奈米材料",$readonly)."奈米材料</label>
                                 		 <label class='checkbox'>".form_checkbox("research_field[]","奈米檢測",isset($research_field)&&$research_field=="奈米檢測",$readonly)."奈米檢測</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","奈米精密加工",isset($research_field)&&$research_field=="奈米精密加工",$readonly)."奈米精密加工</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","分子診斷與治療",isset($research_field)&&$research_field=="分子診斷與治療",$readonly)."分子診斷與治療</label>
								 		 <label class='checkbox'>".form_checkbox("research_field[]","其它",isset($research_field)&&$research_field=="其它",$readonly)."其它</label>";
							  	 ?>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">* 篇名</label>
                              <div class="controls">
                                 <?=form_input("paper_title",isset($paper_title)?$paper_title:"","class='span6'".$readonly);?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須與期刊之篇名相同(含大小寫)</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 出處</label>
                              <div class="controls">
                                 <?=form_input("journal",isset($journal)?$journal:"","class='span6'".$readonly);?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須寫出期刊全名(不可使用縮寫)，並註明刊名，冊號，頁碼 例:Applied Physics Letters,vol.85,No.12,pp.2358-2360</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 出版年</label>
                              <div class="controls">
                                 <? $book_year_select_options = array('2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019'); ?>
                                 <?=form_dropdown("journal_year",$book_year_select_options,isset($journal_year)?$journal_year:"","class='span6'".$readonly);?>
                              </div>
                              
                           </div>
						   
                           <div class="control-group">
                              <label class="control-label">* 抵扣機台使用費姓名</label>
                              <div class="controls">
                                 <?=form_dropdown("awardees_no",isset($awardees_select_options)?$awardees_select_options:array(),isset($awardees_no)?$awardees_no:"","class = 'span6 chosen'".$readonly);?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須在中心設有抵扣帳戶者，若無，請聯絡本中心 林丹琪小姐 分機31382</p>
                              </div>
                           </div>
                           
						   <div class="control-group">
                              <label class="control-label">抵扣方式</label>
                              <div class="controls">
							  	 <?=form_dropdown("apply_plan_no",$plan_select_options,isset($apply_plan_no)?$apply_plan_no:"","class='span6'".$readonly);?>
                                 
                              </div>
                           </div>
						   
						  
						   
                           <div class="control-group">
                              <label class="control-label">論文上傳</label>
                              <div class="controls">
							  	  <?=isset($upload_file)?anchor(base_url("document/{$upload_file}"),$upload_file):form_upload("userfile","","");?>
                              </div>
                           </div>
						   
						   <? if($this->session->userdata('status')=="admin") { ?>
							   <h4>審查結果</h4>
							   <div class="control-group">
	                              <label class="control-label">日期</label>
	                              <div class="controls">
	                                 <?=isset($review_date)?$review_date:"";?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
							   <div class="control-group">
	                              <label class="control-label">審查者</label>
	                              <div class="controls">
	                                 <?=isset($reviewer_name)?$reviewer_name:"";?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
							   <div class="control-group">
	                              <label class="control-label">審查結果</label>
	                              <div class="controls">
	                              	 <label class='radio'><?=form_radio("result","1",isset($result)&&$result,"");?>符合</label>
	                              	 <?=form_dropdown("accept_plan_no",$plan_select_options,isset($accept_plan_no)?$accept_plan_no:"","class='span5'");?>
	                              	 <label class='radio'><?=form_radio("result","0",isset($result)&&!$result,"");?>不符合</label>
	                              	 <?=form_input("deny_reason",isset($deny_reason)?$deny_reason:"","class='span4'");?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
						   <? } ?>

                           <div class="form-actions">
                              <?=isset($action_btn)?implode(' ',(array)$action_btn):"";?>
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


