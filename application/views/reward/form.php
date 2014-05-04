
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
                     <p><small>本中心會定期從申請補助儀器使用費中的論文，遴選出優良之論文置於中心網頁上供大家參考及展示用，同時也會通知被遴選出來之論文的老師！</small></p>
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
                        <h4><i class="icon-reorder"></i>(*者為必定填寫，否則不予以申請)</h4>
                     </div>
                     <div class="widget-body form">
                        
                        <!-- BEGIN FORM-->
                        <form action="<?=site_url();?>/reward/add" id="reward_application" class="form-horizontal" method=post enctype="multipart/form-data">
						   <? if($this->session->userdata('status')=="admin") { ?>
							   <div class="control-group">
	                              <label class="control-label">編號</label>
	                              <div class="controls">
	                                 <?php echo $serial_no; ?>
	                                 <span class="help-inline" id="name"></span>
	                              </div>
	                           </div>	
							   <div class="control-group">
	                              <label class="control-label">申請日期</label>
	                              <div class="controls">
	                                 <input type="text" class="span6" value="<?=empty($application_date)?"":$application_date;?>" disabled="disabled"/>
	                                 <span class="help-inline" id="name"></span>
	                              </div>
	                           </div>				   
						   <? } ?>
                           <h4>申請人資料</h4>
                           <div class="control-group">
                              <label class="control-label">* 姓名</label>
                              <div class="controls">
                                 <?php echo $applicant_name; ?>
                                 <span class="help-inline" id="name"></span>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 服務單位</label>
                              <div class="controls">
							  	 <?php echo $department; ?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">例：成大航太系</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 聯絡電話</label>
                              <div class="controls">
                                 <?php echo $tel; ?>
                                 <span class="help-inline"></span>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* E-mail</label>
                              <div class="controls">
                                 <?php echo $email; ?>
                                 <span class="help-inline"></span>
                              </div>
                           </div>
                           
                           <h4>研究資料</h4>
						   <div class="control-group">
                              <label class="control-label">* 研究領域(可複選)</label>
                              <div class="controls">
							  	 <?php echo $research_field; ?>
                              </div>
                           </div>
                           
                           <div class="control-group">
                              <label class="control-label">* 篇名</label>
                              <div class="controls">
                                 <?php echo $paper_title; ?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須與期刊之篇名相同(含大小寫)</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 出處</label>
                              <div class="controls">
                                 <?php echo $journal; ?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須寫出期刊全名(不可使用縮寫)，並註明刊名，冊號，頁碼 例:Applied Physics Letters,vol.85,No.12,pp.2358-2360</p>
                              </div>
                           </div>
                           <div class="control-group">
                              <label class="control-label">* 出版年</label>
                              <div class="controls">
                                 <?php echo $journal_year; ?>
                              </div>
                              
                           </div>
						   
                           <div class="control-group">
                              <label class="control-label">* 抵扣機台使用費姓名</label>
                              <div class="controls">
                                 <?php echo $awardees; ?>
                                 <span class="help-inline"></span>
                                 <p class="help-block">須在中心設有抵扣帳戶者，若無，請聯絡本中心 林丹琪小姐 分機31382</p>
                              </div>
                           </div>
                           
						   <div class="control-group">
                              <label class="control-label">抵扣方式</label>
                              <div class="controls">
							  	 <?php echo $apply_plan; ?>
                                 
                              </div>
                           </div>
						   
						  
						   
                           <div class="control-group">
                              <label class="control-label">論文上傳</label>
                              <div class="controls">
							  	  <?php echo $file_name; ?>
                              </div>
                           </div>
						   
						   <? if($this->session->userdata('status')=="admin") { ?>
							   <h4>審查結果</h4>
							   <div class="control-group">
	                              <label class="control-label">日期</label>
	                              <div class="controls">
	                                 <?php echo $review_date; ?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
							   <div class="control-group">
	                              <label class="control-label">審查者</label>
	                              <div class="controls">
	                                 <?php echo $reviewer; ?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
							   <div class="control-group">
	                              <label class="control-label">審查結果</label>
	                              <div class="controls">
								  	 <?php echo $result; ?>
	                                 <span class="help-inline"></span>
	                              </div>
	                           </div>
						   <? } ?>

                           <div class="form-actions">
                              <?php echo $action_btn; ?>
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


