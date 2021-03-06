<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
    	<!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
              <h3 class="page-title">
                 自動打卡系統
				 <small>使用者版</small>
              </h3>
              <!-- END PAGE TITLE & BREADCRUMB-->
           </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN ADVANCED TABLE widget-->
		<div class="row-fluid">
			<div class="span12">
	        	<div class="widget">
	    			<div class="widget-title">
	                    <h4><i class="icon-reorder"></i></h4>
	                </div>
	                <div class="widget-body form">
	                	<form id="form_user_clock" class="form-horizontal" action="" method="GET">
	                		<div class="control-group">
	                           <label class="control-label">地點</label>
	                           <div class="controls">
	                              <?=form_dropdown("location_ID",isset($location_ID_select_options)?$location_ID_select_options:array(),isset($location_ID)?$location_ID:"","");?>
	                           </div>
	                        </div>
	                        <div class="row-fluid" id="fullscreen_area">
		                		<table id="table_user_clock_list" class="table table-striped table-bordered">
									<!--<thead>
										<th width="100">更新</th>
										<th width="60">姓名</th>
										<th width="150">進入時間</th>
										<th >所在位置</th>
									</thead>-->
								</table>
		                	</div>
	                		<div class="form-actions">
	                			<button id="to_fullscreen" type="button" class="btn btn-primary">全螢幕</button>
	                		</div>
	                	</form>
	                </div>
	                
	    		</div>
    		</div>
        </div>
        
        <!-- END ADVANCED TABLE widget-->
    </div>
</div>
<style type="text/css">
	#fullscreen_area{
		height: 100vh;
	}
	#table_user_clock_list{
		height: 100%;
		table-layout: fixed;
	}
	#table_user_clock_list th,#table_user_clock_list td{
		text-align: center;
		vertical-align: middle;
	}
</style>
<script type="text/javascript">
	var ajax_displayer = function(){
		$.ajax({
			url: site_url+"user/clock/query",
			type: "GET",
			dataType: "json",
			data: $("#form_user_clock").serialize()
		}).done(function(data){
			data = data.aaData;
			console.log(data);
			
			//參數設定
			var columns_per_row = Math.ceil(Math.sqrt(data.length));
			var output_array = [];
			//準備資料
			for(var idx=0,row=[];idx<data.length;idx++){
				if(idx!=0 && idx%columns_per_row==0){
					output_array.push(row);
					row = [];
				}
				row.push(data[idx]);
			}
			if(idx!=0){
				for(;idx%columns_per_row!=0;idx++){
					row.push('');
				}
				output_array.push(row);
			}
			//顯示
			$("#table_user_clock_list").html('<tr><th colspan='+columns_per_row+'>'+moment().format('LLLL')+'</th></tr>');
			if(idx!=0){
				$.each(output_array,function(idx,value){
					output_array[idx] = '<td>'+value.join('</td><td>')+'</td>';	
				});
				$("#table_user_clock_list").append(
					'<tr>'+output_array.join('</tr><tr>')+'</tr>'
				);
			}
			$("#table_user_clock_list th").fitText(2);
			$("#table_user_clock_list td").css("width",100/columns_per_row+"%");
			$("#table_user_clock_list td").fitText(0.5);
		});
	};
	$(document).ready(function(){
		ajax_displayer();
		setInterval(function(){
			ajax_displayer();
		},10000);
		
		//全螢幕按鈕
		$("#to_fullscreen").click(function(){
			$("#fullscreen_area").fullscreen();
		});
	});
</script>
				