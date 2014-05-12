
	$(document).ready(function(){
		$.ajax({
			url: 'index.php/access/card/application/temp/option',
			type: 'GET',
			dataType: 'json',
			success: function(data){
				console.log(data);
				for(var i=0;i<data.length;i++){
					$("select[name='application_type']").append('<option value'+data[i].type_no+'>'+data[i].type_name+'</option>');
				}
			}
		});
		$("select[name='application_type']").change(function(){
			var type_no = $(this).find("option:selected").val();
			$("select[name='guest_purpose']").remove("option");
			for(var i=0;i<data[type_no].purpose.length;i++){
				$("select[name='guest_purpose']").append('<option value'+data[type_no].purpose[i].purpose_no+'>'+data[type_no].purpose[i].purpose_name+'</option>');
			}
		});
	});