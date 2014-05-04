      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     儀器預約系統
					 <small>故障不計費管理系統</small>
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
                     	<h4><i class="icon-reorder"></i>申請表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=empty($action_url)?"":$action_url;?>" class="form-horizontal" method="POST">
                     		<div class="control-group">
                     			<label class="control-label">預約編號</label>
					            <div class="controls">
									<input type="text" name="booking_ID" value="<?=$booking_ID;?>" readonly="readonly"/>
								</div>
                     		</div>
                     		<div class="row-fluid">
	                     		<div class="control-group span6">
	                     			<label class="control-label">使用者帳號</label>
						            <div class="controls">
										<?=$user_ID;?>
									</div>
	                     		</div>
	                     		<div class="control-group span6">
	                     			<label class="control-label">使用者姓名</label>
						            <div class="controls">
										<?=$user_name;?>
									</div>
	                     		</div>
	                     	</div>
                     		<div class="control-group">
                     			<label class="control-label">預約儀器</label>
					            <div class="controls">
									<?=$facility_cht_name;?> (<?=$facility_eng_name;?>)
								</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">預約時段</label>
					            <div class="controls">
									<?=$start_time;?>~<?=$end_time;?>
								</div>
                     		</div>
                     		
                     		<div class="control-group">
                     			<label class="control-label">不計費申請日期</label>
					            <div class="controls">
									<?=empty($apply_date)?date("Y-m-d"):$apply_date;?>
								</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">不計費原因</label>
					            <div class="controls">
									<input name="reason" value="<?=empty($reason)?"":$reason;?>" type="text" class="span12" <?=empty($reason)?"":"disabled";?>/>
								</div>
                     		</div>
                     		
                     		<? if($page != "form"){ ?>
                     		<hr>
                 			<div class="control-group ">
                     			<label class="control-label">審查結果</label>
					            <div class="controls">
					            	<label class="radio"><input type="radio" name="result" value="1" class="radio" <?=empty($result)?"":"checked";?> <?=($page=="view")?"disabled='disabled'":""?>/>同意</label>
									<label class="radio"><input type="radio" name="result" value="0" class="radio" <?=empty($result)?"checked":"";?> <?=($page=="view")?"disabled='disabled'":""?>/>退件</label>
								</div>
                     		</div>
                     		<div class="control-group">
                     			<label class="control-label">核可時段</label>
                     			<div class="controls">
                     				<? if($page!="view"){ ?>
	                     				<input name="start_date" type="text" value="<?=empty($start_time)?"":date("Y-m-d",strtotime($start_time));?>" class="input-medium date-picker" />
	                     				<input name="start_time" type="text" value="<?=empty($start_time)?"":date("H:i:s",strtotime($start_time));?>" class="input-medium timepicker-24"/>
	                     				~
	                     				<input name="end_date" type="text" value="<?=empty($end_time)?"":date("Y-m-d",strtotime($end_time));?>" class="input-medium date-picker"/>
	                     				<input name="end_time" type="text" value="<?=empty($end_time)?"":date("H:i:s",strtotime($end_time));?>" class="input-medium timepicker-24"/>
                     				<? }else{ 
                     					echo $nocharge_start_time."~".$nocharge_end_time;
                     				   } ?>
                     			</div>
                     		</div>
                     		<div class="control-group ">
                     			<label class="control-label">備註</label>
					            <div class="controls">
									 <input type="text" name="comment" value="<?=$comment;?>" class="span12" <?=isset($result)?"readonly='readonly'":"";?>/>
								</div>
                     		</div>
                     		<? } ?>
                     		
                     		<div class="form-actions">
                     			<?php if($page!="view") { ?>
                     			<button type="submit" class="btn btn-primary">送出</button>
                     			<?php }else{ ?>
                     			<a href="<?=$this->agent->referrer();?>" class="btn btn-primary">回上頁</a>
                     			<?php } ?>
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

