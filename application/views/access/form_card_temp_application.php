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
									<?=form_dropdown("application_type_ID",array(),"","");?>
								</div>
							</div>
							<div class="control-group">
					            <label class="control-label">申請磁卡目的</label>
					            <div class="controls">
									<?=form_dropdown("guest_purpose_ID",array(),"","");?>
								</div>
							</div>
							<div class="row-fluid user">
								<div class="control-group">
						            <label class="control-label">借卡者</label>
						            <div class="controls">
										<?=form_dropdown("used_by",isset($used_by_array)?$used_by_array:array(),isset($used_by)?$used_by:"","class='chosen'");?>
									</div>
								</div>
							</div>
							<div class="row-fluid guest">
								<div class="control-group">
						            <label class="control-label">磁卡使用時段</label>
						            <div class="controls">
										<input type="text" name="guest_access_start_date" value="<?=isset($guest_access_start_time)?date("Y-m-d",strtotime($guest_access_start_time)):date("Y-m-d");?>" class="input-small date-picker"/>
										<input type="text" name="guest_access_start_time" value="<?=isset($guest_access_start_time)?date("H:i",strtotime($guest_access_start_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
										~
										<input type="text" name="guest_access_end_date" value="<?=isset($guest_access_end_time)?date("Y-m-d",strtotime($guest_access_end_time)):date("Y-m-d");?>" class="input-small date-picker"/>
										<input type="text" name="guest_access_end_time" value="<?=isset($guest_access_end_time)?date("H:i",strtotime($guest_access_end_time)):date("H:i");?>" class="input-mini timepicker-24-30m"/>
										
									</div>
								</div>
								<div class="control-group">
						            <label class="control-label">需求門禁</label>
						            <div class="controls">
										<?=form_multiselect("facility_SN[]",isset($facility_SN_select_options)?$facility_SN_select_options:array(),isset($facility_SN)?$facility_SN:array(),"class='span12 chosen'");?>
									</div>
								</div>
								<div class="row-fluid batch-input-area">
									<div class="row-fluid">
										<div class="control-group span6">
								            <label class="control-label">訪客姓名</label>
								            <div class="controls">
												<input type="text" name="guest_name[]" value="<?=isset($guest_name)?$guest_name:"";?>"/>
											</div>
										</div>
										<div class="control-group span6">
								            <label class="control-label">訪客聯絡手機(選填)</label>
								            <div class="controls">
												<input type="text" name="guest_mobile[]" value="<?=isset($guest_mobile)?$guest_mobile:"";?>"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<? if(isset($page)&&$page=='issue'){ ?>					
							<hr>
							<div class="control-group">
					            <label class="control-label">核發卡號</label>
					            <div class="controls">
					            	<input type="checkbox" name="auto_issue" value="1"/>自動
									<input type="text" name="guest_access_card_num" value="0000"/>
								</div>
							</div>
							<? } ?>
							<div class="form-actions">
								<? if(isset($page)&&$page=='issue'){ ?>
									<?=form_submit("issue","核發","class='btn btn-warning'");?>
									<?=anchor("access/card/application/temp/list","回上頁","class='btn btn-primary'");?>
								<? }else{ ?>
									<?=form_button("add","新增一筆","class='btn btn-primary'");?>
									<?=form_submit("","申請送出","class='btn btn-warning'");?>
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
				$("form .user").hide();
			}else if($(this).find("option:selected").val()=="user"){//借用卡
				$("form .guest").hide();
				$("form .user").show();
			}
		});
		//取SELECT OPTION的資料
		var application_type_ID = '<?=isset($application_type_ID)?$application_type_ID:"";?>';
		var guest_purpose_ID = '<?=isset($guest_purpose_ID)?$guest_purpose_ID:"";?>';
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
			$("select[name='application_type_ID'] option").filter(function(){return $(this).val()==application_type_ID}).prop("selected",true).trigger("change");
			$("select[name='guest_purpose_ID'] option").filter(function(){return $(this).val()==guest_purpose_ID}).prop("selected",true);

		});
		//自動開關
		$("input[type='checkbox'][name='auto_issue']").change(function(){
			$("input[name='guest_access_card_num']").prop("readonly",$(this).prop("checked"));
		})
		//新增一列
		var row_new = $("div.batch-input-area").html();
		$("button[name='add']").click(function(){
			$("div.batch-input-area").append(row_new);
		});
	});
</script>

