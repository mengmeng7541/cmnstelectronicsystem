      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                     門禁安全控管系統
					 <small>臨時磁卡申請</small>
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
                        <h4><i class="icon-reorder"></i>臨時磁卡申請表單</h4>
                     </div>
                     <div class="widget-body form">
                     	<form action="<?=site_url(isset($page)&&$page=='issue'?'/access/card/application/temp/update':'/access/card/application/temp/add');?>" method="POST" class="form-horizontal">
							<?=form_hidden("serial_no",isset($serial_no)?$serial_no:"");?>
							<div class="control-group">
					            <label class="control-label">申請磁卡類別</label>
					            <div class="controls">
									<?=form_dropdown("application_type_ID",array(),isset($application_type_ID)?$application_type_ID:"","");?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">申請磁卡目的</label>
					            <div class="controls">
									<?=form_dropdown("guest_purpose_ID",array(),isset($guest_purpose_ID)?$guest_purpose_ID:"","");?>
								</div>
							</div>
							<div class="row-fluid guest">
								<div class="control-group">
						            <label class="control-label">來賓姓名</label>
						            <div class="controls">
										<input type="text" name="guest_name" value="<?=isset($guest_name)?$guest_name:"";?>"/>
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">來賓聯絡手機(選填)</label>
						            <div class="controls">
										<input type="text" name="guest_mobile" value="<?=isset($guest_mobile)?$guest_mobile:"";?>"/>
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">磁卡使用時段</label>
						            <div class="controls">
										<input type="text" name="guest_access_start_date" value="<?=isset($guest_access_start_time)?date("Y-m-d",strtotime($guest_access_start_time)):date("Y-m-d");?>" class="input-medium date-picker"/>
										<input type="text" name="guest_access_start_time" value="<?=isset($guest_access_start_time)?date("H:i",strtotime($guest_access_start_time)):date("H:i");?>" class="input-small timepicker-24-mm"/>
										~
										<input type="text" name="guest_access_end_date" value="<?=isset($guest_access_end_time)?date("Y-m-d",strtotime($guest_access_end_time)):date("Y-m-d");?>" class="input-medium date-picker"/>
										<input type="text" name="guest_access_end_time" value="<?=isset($guest_access_end_time)?date("H:i",strtotime($guest_access_end_time)):date("H:i");?>" class="input-small timepicker-24-mm"/>
										
									</div>
								</div>
							</div>
							
							<? if(isset($page)&&$page=='issue'){ ?>					
							<hr>
							<div class="control-group">
					            <label class="control-label">核發卡號</label>
					            <div class="controls">
					            	<input type="checkbox" name="auto_issue" value="1"/>
									<input type="text" name="guest_access_card_num" value="0000"/>
								</div>
							</div>
							<? } ?>
							<div class="form-actions">
								<? if(isset($page)&&$page=='issue'){ ?>
									<?=form_submit("issue","核發","class='btn btn-warning'");?>
									<?=anchor("access/card/application/temp/list","回上頁","class='btn btn-primary'");?>
								<? }else{ ?>
									<?=form_submit("","申請","class='btn btn-primary'");?>
								<? } ?>
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
		var jdata;
		$("select[name='application_type_ID']").change(function(){
			var idx = $(this).find("option:selected").index();
			$("select[name='guest_purpose_ID']").empty();
			for(var i=0;i<jdata[idx].purpose.length;i++){
				$("select[name='guest_purpose_ID']").append('<option value='+jdata[idx].purpose[i].purpose_ID+'>'+jdata[idx].purpose[i].purpose_name+'</option>');
			}
			
			//
			if($(this).find("option:selected").val()=="guest")//訪客卡
			{
				$("form .guest").show();
			}else if($(this).find("option:selected").val()=="user"){//借用卡
				$("form .guest").hide();
			}
		});
		//取SELECT OPTION的資料
		$.ajax({
			url: site_url+'access/get_access_card_temp_application_type_purpose_json',
			type: 'GET',
			dataType: 'json',
			
		}).done(function(data){
			jdata = data;
			for(var i=0;i<jdata.length;i++){
				$("select[name='application_type_ID']").append('<option value='+jdata[i].type_ID+'>'+jdata[i].type_name+'</option>');
			}
			$("select[name='application_type_ID']").trigger("change");
			
			//取申請單的資料
			var serial_no = $("form input[name='serial_no']").val();
			if(serial_no != ''){
				$.ajax({
					url: site_url+'access/get_access_card_temp_application/'+serial_no,
					dataType: 'json',
				}).done(function(data){
					for(var key in data){
						$("input[name='"+key+"']").val(data[key]);
						$("select[name='"+key+"']").find("option[value='"+data[key]+"']").prop("selected",true);
					}
				});
			}
		});
		
	});
</script>

