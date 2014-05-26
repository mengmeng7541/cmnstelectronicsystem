      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
			    
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     管理者資料維護
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
                        <h4><i class="icon-reorder"></i>管理者帳號表單</h4>
                     </div>
                     <div class="widget-body form">
					 	<form action=<?=$action;?> id="form_admin_register" class="form-horizontal" method="post" enctype="multipart/form-data">
							<div class="control-group">
	                           <label class="control-label">帳號</label>
	                           <div class="controls">
	                              <input name="ID" type="text" class="span2" value="<?=empty($ID)?"":$ID;?>" <?=empty($ID)?"":'readonly="readonly"';?>/>
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
	                              <input type="text" class="span6" name="name" value="<?=empty($name)?"":$name;?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">* E-Mail</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="email" value="<?=empty($email)?"":$email;?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
							<div class="control-group">
	                           <label class="control-label">* 行動電話</label>
	                           <div class="controls">
	                              <input type="text" class="span6" name="mobile" value="<?=empty($mobile)?"":$mobile;?>"/>
	                              <span class="help-inline"></span>
	                           </div>
	                        </div>
	                        <? if(!empty($ID)) { ?>
								<h4>進階資料</h4>
								<div class="control-group">
		                           <label class="control-label">卡號</label>
		                           <div class="controls">
		                              <input type="text" class="span6" name="card_num" value="<?=empty($card_num)?"":$card_num;?>"/>
		                              <span class="help-inline"></span>
		                           </div>
		                        </div>
								<div class="control-group">
	                              <label class="control-label">電子印章</label>
	                              <div class="controls">
								      <?=empty($stamp)?'':img($stamp);?>
								  	  <input type="file" name="userfile" class="default" />
	                              </div>
	                            </div>
	                            <div class="control-group">
	                            	<label class="control-label">人員權限</label>
	                            	<div class="controls">
	                            		<?=form_dropdown("group_no",isset($org_chart_group_no_name_array)?$org_chart_group_no_name_array:array(),"","");?>
	                            		<?=form_dropdown("team_no",isset($org_chart_team_no_name_array)?$org_chart_team_no_name_array:array(),"","");?>
	                            		<?=form_dropdown("status_no",isset($org_chart_status_no_name_array)?$org_chart_status_no_name_array:array(),"","");?>
	                            		<button name="add" type="button" class="btn btn-warning btn-small">新增</button>
	                            	</div>
	                            	
	                            </div>
	                            <table id="table_admin_org_chart" class="table table-striped table-bordered">
                            		<thead>
                            			<th>群組</th>
                            			<th>團隊</th>
                            			<th>身分</th>
                            			<th width="100"></th>
                            		</thead>
                            		<tbody>
                            			
                            		</tbody>
                            	</table>
                            <? } ?>
						   <div class="form-actions">
							  <input type="submit" class="btn btn-primary" value="送出" />
						   </div>
						</form>
								
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
<script type="text/javascript">
	
			
	$(document).ready(function(){
		$.ajax({
			url: site_url+'admin/get_org_chart_group_team_status_json',
			type: "GET",
			dataType: "json"
		}).done(function(jdata){
			$("select[name='group_no']").empty();
			for(var gIdx in jdata){
				$("select[name='group_no']").append('<option value='+jdata[gIdx].group_no+'>'+jdata[gIdx].group_name+'</option>');
			}
			
			$("select[name='group_no']").change(jdata,function(event){
				console.log(event.data);
				var gIdx = $(this).find(":selected").val();
				$("select[name='team_no']").empty();
				for(var tIdx in event.data[gIdx].team){
					$("select[name='team_no']").append('<option value='+event.data[gIdx].team[tIdx].team_no+'>'+event.data[gIdx].team[tIdx].team_name+'</option>');
				}
				$("select[name='status_no']").empty();
				for(var sIdx in event.data[gIdx].status){
					$("select[name='status_no']").append('<option value='+event.data[gIdx].status[sIdx].status_no+'>'+event.data[gIdx].status[sIdx].status_name+'</option>');
				}
			});
			
			$("select[name='group_no']").trigger("change");
		});
	});
</script>
