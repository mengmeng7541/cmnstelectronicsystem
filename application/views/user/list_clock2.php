<link rel="stylesheet" href="<?=base_url('assets/blueimp-Gallery/css/blueimp-gallery.min.css');?>">
<div id="main-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
    	<!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
              <h3 class="page-title">
                 廣告輪播系統
				 <small>carousel版</small>
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
	                           <label class="control-label">展示地點</label>
	                           <div class="controls">
	                              <?=form_dropdown("location_ID",isset($location_ID_select_options)?$location_ID_select_options:array(),isset($location_ID)?$location_ID:"","");?>
	                           </div>
	                        </div>
	                        <div class="row-fluid" id="fullscreen_area">
			                	<div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
								    <div class="slides"></div>
								    <h3 class="title"></h3>
								    <a class="prev">‹</a>
								    <a class="next">›</a>
								    <a class="play-pause"></a>
								    <ol class="indicator"></ol>
								</div>
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
<script src="<?=base_url('assets/blueimp-Gallery/js/blueimp-gallery.min.js');?>"></script>
<script>
blueimp.Gallery.prototype.textFactory = function (obj, callback) {
    var $element = $('<div>')
            .addClass('text-content')
            .attr('title', obj.title);
    $.get(obj.href)
        .done(function (result) {
            $element.html(result);
            callback({
                type: 'load',
                target: $element[0]
            });
        })
        .fail(function () {
            callback({
                type: 'error',
                target: $element[0]
            });
        });
    return $element[0];
};
</script>
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
		line-height: 100%;
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
			var columns_per_row = Math.max(Math.ceil(Math.sqrt(data.length)),5);
			var rows_per_column = Math.max(Math.ceil(data.length/columns_per_row),5);
			var output_array = [];
			//準備資料
			for(var idx=0,row=[];idx<data.length;idx++){
				if(idx!=0 && idx%columns_per_row==0){
					output_array.push(row);
					row = [];
				}
				row.push(data[idx]);
			}
			if(idx!=0){//把一列後面補滿
				for(;idx%columns_per_row!=0;idx++){
					row.push('');
				}
				output_array.push(row);
			}
			//把排補滿
			for(;output_array.length<rows_per_column;)
			{
				for(row = [];row.length<columns_per_row;){
					row.push('');
				}
				output_array.push(row);
			}
			//顯示
			$("#table_user_clock_list").html('<tr><th colspan='+columns_per_row+'>'+moment().format('LLLL分')+'</th></tr>');
			$.each(output_array,function(idx,value){
				output_array[idx] = '<td>'+value.join('</td><td>')+'</td>';	
			});
			$("#table_user_clock_list").append(
				'<tr>'+output_array.join('</tr><tr>')+'</tr>'
			);
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
			$("#blueimp-gallery-carousel").fullscreen();
		});
		
		
		var carouselLinks = [];
		
		carouselLinks.push({
		    href: base_url+'gallery/clock.html',
		    title: '',
		    type: 'text/html',
		});
		carouselLinks.push({
		    href: base_url+'gallery/ad1.jpg',
		    title: '',
		});
		
		var gallery = blueimp.Gallery(
		    carouselLinks,
		    {
		        container: '#blueimp-gallery-carousel',
		        carousel: true
		    }
		);
	});
</script>
				