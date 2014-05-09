<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
    	<!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
              <h3 class="page-title">
                 Automatic Booking System
				 <small>中心人員版</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN ADVANCED TABLE widget-->
        <? if($this->session->userdata('ID')){ ?>
	        <div class="row-fluid">
	        	<div class="widget">
	    			<div class="widget-title">
	                    <h4><i class="icon-reorder"></i>Manual Booking</h4>
	                </div>
	                <div class="widget-body form">
	                	<div class="row-fluid">
	                		<div class="span6">
	                			<form id="form_admin_manual_clock" action="<?=site_url();?>/admin/clock/add" method="post" class="form-horizontal">
	                				<? if($this->admin_clock_model->is_super_admin()){ ?>
										<div class="control-group">
				                			<label class="control-label">打卡人員</label>
				                			<div class="controls">
				                				<?=form_dropdown("clock_user_ID",isset($admin_ID_select_options)?$admin_ID_select_options:array(),$this->session->userdata('ID'),"class='chosen'");?>
				                			</div>
				                		</div>
									<? } ?>
			                		<div class="control-group">
			                			<label class="control-label">起始時間</label>
			                			<div class="controls">
			                				<input type="text" name="clock_start_date" value="<?=date("Y-m-d");?>" class="input-small date-picker"/>
			                				<input type="text" name="clock_start_time" value="<?=date("H:i");?>" class="input-mini timepicker-24-30m"/>
			                			</div>
			                		</div>
			                		<div class="control-group">
			                			<label class="control-label">預計回來時間(選填)</label>
			                			<div class="controls">
			                				<input type="text" name="clock_end_date" value="" class="input-small date-picker"/>
			                				<input type="text" name="clock_end_time" value="" class="input-mini timepicker-24-30m"/>
			                			</div>
			                		</div>
			                		<div class="control-group">
			                			<label class="control-label">地點</label>
			                			<div class="controls">
			                				<input type="text" name="clock_location" value=""/>
			                			</div>
			                		</div>
			                		<div class="control-group">
			                			<label class="control-label">事由</label>
			                			<div class="controls">
			                				<input type="text" name="clock_reason" value=""/>
			                			</div>
			                		</div>
			                		<div class="form-actions">
			                			<button type="submit" class="btn btn-primary">送出</button>
			                		</div>
			                	</form>
	                		</div>
	                		<div class="span6">
	                			<table id="table_admin_manual_clock_list" class="table table-striped table-bordered">
			                		<thead>
			                			<th width="50">人員</th>
			                			<th>地點或事由</th>
			                			<th width="70">起始時間</th>
			                			<th width="70">結束時間</th>
			                			<th width="50"></th>
			                		</thead>
			                	</table>
	                		</div>
	                	</div>
	                	
	                	
	                </div>
	    		</div>
	        </div>
		<? }else{ ?>
			
		<? } ?>
        <div class="hide">
        	<div class="widget">
    			<div class="widget-title">
                    <h4><i class="icon-reorder"></i></h4>
                </div>
                <div class="widget-body">
					<table class="table table-striped table-bordered">
						<thead>
							<th>時間</th>
							<th>姓名</th>
							<th>電話</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
                </div>
    		</div>
        </div>
        <div class="row-fluid">
        	
        </div>
        <div id="location_displayer">
        	
        </div>
        <!-- END ADVANCED TABLE widget-->
    </div>
</div>
<script>
	var ajax_displayer = function(){
		$.ajax({
			dataType: "json",
			url:"/index.php/admin/clock/query/auto",
		}).always(function(data){
			var block_num_per_row = 3;
			var block_obj = [];
			$("#location_displayer").empty();
			$.each(data.aaData,function(index,value){
				//取得手動打卡註記
				var clock_remark = value.pop();
				//取得手動打卡事由
				var clock_reason = value.pop();
				//取得手動打卡位置
				var clock_location = value.pop();
				//取得手動打卡起始時間
				var clock_end_time = value.pop();
				//取得手動打卡起始時間
				var clock_start_time = value.pop();
				//取得地點的電話
				var location_tel = value.pop();
				//取得location
				var location_name = value.pop();
				//取得手機
				var user_mobile = value.pop();
				//取得電話
				var user_tel = value.pop();
				//取得身分
				var user_status = value.pop();
				//取得姓名
				var user_name = value.pop();
				//取得刷卡狀態
				var access_state = value.pop();
				//取得刷卡時間
				var access_time = value.pop();
				//取得刷卡日期
				var access_date = value.pop();
				
				
				if(	
					(clock_reason==null && clock_location==null) || 
					(
						access_state!=null &&
						Date.parse(access_date+' '+access_time) >= Date.parse(clock_start_time)
					)
				)
				{
					if(user_status=='professor')
					{
						if(location_name==null)
						{
							location_name = '老師辦公室';//把老師的位置送回自己的辦公室
							location_tel ='';
						}else{
							if(access_state=="01"){
								if(Date.parse("-10minutes") > Date.parse(access_date+' '+access_time)){
									//已離開太久，判定已回預設地點
									location_name='老師辦公室';
									location_tel ='';
								}
							}
						}
					}else{
						if(location_name==null)
						{
							location_name='科技5F辦公室';
							location_tel ='31380';
							
						}else{
							if(access_state=="01"){
								if(Date.parse("-10minutes") > Date.parse(access_date+' '+access_time)){
									//已離開太久，判定已回預設地點
									location_name='科技5F辦公室';
									location_tel ='31380';
									
								}
							}
						}
					}
				}else{
					//外出
					location_name = '其它';
					location_tel = '';
				}
				
				var show_array = ['---','---','---'];
				//根據所在地不同show出不同的資訊格式
				if(location_name == '科技5F辦公室' || location_name == '老師辦公室')
				{
					show_array = [access_time,user_name,user_tel+'<br>['+user_mobile+']'];
				}else if(location_name == '其它')
				{
					show_array = [clock_reason+'@'+clock_location+'<br>預計直到：<br>'+(clock_end_time==null?'未知':moment(clock_end_time).format('MM-DD HH:mm')),user_name,user_mobile];
				}else{
					show_array = [access_time,user_name,user_mobile];
				}
				
				//function，匹配location是否吻合，回傳index，皆否則回傳-1
				function get_index(block_obj,location_name){
					for(var idx=0;idx<block_obj.length;idx++)
					{
						if(block_obj[idx].title==location_name)
						{
							return idx;
						}
					}
					return -1;
				};
				var i = get_index(block_obj,location_name);
				if(i==-1)
				{
					//CLASS，定義BLOCK的成員
					function Block(location_name,location_tel){
						this.title = location_name;
						this.location_tel = location_tel;
						this.content = [];
					}
					var j = block_obj.push(new Block(location_name,location_tel==null?'':location_tel))-1;
//					block_obj[j].content.push([access_time,user_name,user_mobile]);
					block_obj[j].content.push(show_array);
				}else{
//					block_obj[i].content.push([access_time,user_name,user_mobile]);
					block_obj[i].content.push(show_array);
				}
			});
			//SORTING，根據資料長度由少到多
			block_obj.sort(function(a,b){
				return (a.title=='其它')?100:(a.content.length - b.content.length);
			});
			//資料整理完畢，開始產生內容
			$.each(block_obj,function(idx,i_val){
				//先根據時間由晚到早SORTING
				block_obj[idx].content.sort().reverse();
				//產生內容
				$.each(block_obj[idx].content,function(jdx,j_val){
					block_obj[idx].content[jdx] = '<td>'+block_obj[idx].content[jdx].join('</td><td>')+'</td>';
				});
				block_obj[idx].content = '<tr>'+block_obj[idx].content.join('</tr><tr>')+'</tr>';
			});
			//顯示
			$.each(block_obj,function(idx,i_val){
				if((idx%block_num_per_row)==0){
					$("#location_displayer").append('<div class="row_fluid" id="row_'+parseInt(idx/block_num_per_row)+'"></div>');
				}
				$("#row_"+parseInt(idx/block_num_per_row)).append('<div class="span'+(12/block_num_per_row)+'" id="block_'+idx+'"></div>');
				$("#block_"+idx).html($("div.hide").html());
				if(block_obj[idx].title=='其它')
				{
					var head_array = ['訊息','姓名','電話'];
					$("#block_"+idx).find("thead").html('<tr><th width="100">'+head_array.join('</th><th>')+'</th></tr>');
				}
				$("#block_"+idx).find("tbody").html(block_obj[idx].content);
				$("#block_"+idx).find("h4").append(block_obj[idx].title+' '+block_obj[idx].location_tel);
			});
			console.log(block_obj);
		});
	}
	jQuery(document).ready(function(){
		ajax_displayer();
		setInterval(function(){
			ajax_displayer();
		},60000);//一分鐘更新一次
	});
</script>
				