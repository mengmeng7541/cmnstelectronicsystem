
	$(document).ready(function(){
		var jdata;
		$.ajax({
			url: 'http://140.116.176.44/cmnst_trunk/index.php/access/card/application/temp/option',
			type: 'GET',
			dataType: 'json',
			
		}).always(function(data){
			jdata = data;
			for(var i=0;i<jdata.length;i++){
				$("select[name='application_type']").append('<option value='+jdata[i].type_no+'>'+jdata[i].type_name+'</option>');
			}
		});
		$("select[name='application_type']").change(function(){
			var idx = $(this).find("option:selected").index();
			$("select[name='guest_purpose']").empty();
			for(var i=0;i<jdata[idx].purpose.length;i++){
				$("select[name='guest_purpose']").append('<option value'+jdata[idx].purpose[i].purpose_no+'>'+jdata[idx].purpose[i].purpose_name+'</option>');
			}
		});
	});