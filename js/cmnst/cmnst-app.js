var cmnstApp = angular.module('cmnstApp',['ngSanitize','mgcrea.ngStrap']);

cmnstApp
.factory("user_profile_service",function($http){
	return {
		get_my_profile: function(){
			return $http.get(site_url+'user/query',{params:{user_ID:''}});
		}
	}
})
.factory("bootstrap_modal_service",function($rootScope){
	$rootScope.modal = {
		info: {
			header: {
				title: ''
			},
			body: {
				message: '',
				state: ''
			},
			footer: {
				confirm: {
					text: '',
					link_url: ''
				},
				cancel: {
					text: '',
					link_url: ''
				}
			}
		}
	}
	var get_info_modal = function()
	{
		return $rootScope.modal.info;
	}
	var reset_info_modal = function(){
		$rootScope.modal = {
			info: {
				header: {
					title: '訊息'
				},
				body: {
					message: "上傳中...<img src='/assets/pre-loader/Atom.gif'>",
					state: 'info'
				},
				footer: null
			}
		}
	}
	var set_info_modal = function(data){
		$rootScope.modal.info = data;
	}
	
	return {
		reset_info_modal: reset_info_modal,
		set_info_modal: set_info_modal,
		get_info_modal: get_info_modal
	}
})
//-----------------------------DIRECTIVE-----------------------------------
.directive('chosen',function(){
	var linker = function(scope,element,attrs){
		scope.$watch(attrs.watch, function () {
            element.trigger('chosen:updated');
        });
		element.chosen();
	}
	return {
		restrict: 'A',
		link: linker
	}
})
.directive('uniform',function(){
	var linker = function(scope,element,attrs){

		element.uniform();
		scope.$watch(attrs.watch, function () {
    		jQuery.uniform.update(element);
        });
	}
	return {
		restrict: 'A',
		link: linker
	}
})
.directive('modal',function($rootScope){
	var linker = function(scope,element,attrs){
		element.modal({backdrop:'static',keyboard:true,show:false});
		element.modal('hide');
		scope.$watch(attrs.watch, function (new_val,old_val) {
			if(new_val===old_val)
			{
				//initialization
				return;
			}
			for(var key in new_val.footer)
			{
				if(!new_val.footer[key].link_url || !new_val.footer[key].link_url.length)
				{
					new_val.footer[key].dismiss = "modal";
				}
			}
    		element.modal('show');
        });
	}
	return {
		restrict: 'A',
		link: linker
	}
})
.directive('tab',function($timeout){
	var linker = function(scope,element,attrs){
//		if(scope.$last===true)
//		{
			$timeout(function(){
				element.tab('show');
			});
//		}
	}
	return {
		restrict: 'A',
		link: linker
	}
})
//---------------------BOOTSTRAP MODAL CONTROLLER--------------------------
.controller("bootstrap_modal_controller",function($scope,$http){
	
})
//-----------------------------OEM CONTROLLER------------------------------
	/*----------------APP EDIT----------------*/
.controller("oem_application_edit",function($scope,$http,user_profile_service,bootstrap_modal_service){
	//initial
	$scope.forms = [];
	$scope.app = {
		form_idx: 0,
		app_SN: 0,
		form_SN: 0,
		app_ID: 0,
		app_type: 'normal',
		app_user_ID: '',
		app_description: '',
		app_time: '',
		app_estimated_hour: '',
		app_checkpoint: '',
		app_checkpoints: [],
		app_cols: []
	};
	//WATCH
	$scope.$watch("app.form_idx",function(new_value,old_value){
		if(new_value===old_value){
			
		}else{
			//先移除舊的
			if(old_value>0)
			{
				$scope.app.app_cols.splice($scope.forms[0].form_cols.length);
			}
			if(new_value>0)
			{
				$scope.app.app_cols = $scope.app.app_cols.concat($scope.forms[new_value].form_cols);
			}
		}
	});
	
	$scope.get_app = function(SN){
		$http
		.get(site_url+'oem/app/query',{params:{app_SN:SN}})
		.success(function(data){
			$scope.app = data.aaData[0];
		});
	};
	$scope.get_form = function(SN){
		user_profile_service.get_my_profile()
		.success(function(data){
			$scope.user = data.aaData[0];
		});
		
		$http
		.get(site_url+'oem/form/query',{params:{form_SN:SN}})
		.success(function(data){
			$scope.forms[0] = data.aaData[0];
			$scope.get_sub_form(SN);
			
			$scope.app = {
				form_idx: 0,
				app_SN: 0,
				form_SN: $scope.forms[0].form_SN,
				app_ID: 0,
				app_type: 'normal',
				app_user_ID: '',
				app_description: '',
				app_time: '',
				app_estimated_hour: '',
				app_checkpoint: '',
				app_checkpoints: [],
				app_cols: $scope.forms[0].form_cols
			};
		});
	};
	$scope.get_sub_form = function(form_SN){
		$http.get(site_url+'oem/form/query',{params:{form_parent_SN:form_SN}})
		.success(function(data){
			for(var key in data.aaData)
			{
				$scope.forms.push(data.aaData[key]);	
			}
		});
	}
	
	$scope.submit = function()
	{
		bootstrap_modal_service.reset_info_modal();
		console.log($scope.app);
		$http.post(site_url+'oem/app/add',$scope.app)
		.success(function(data){
			console.log(data);
			bootstrap_modal_service.set_info_modal(data);
		});
	}
})
	/*----------------FORM EDIT----------------*/
.controller("oem_form_edit",function($scope,$http,bootstrap_modal_service){
	//data structure
	var col_data_structure = {
		form_col_SN: 0,
		form_SN: '',
		col_cht_name: '',
		col_eng_name: '',
		col_type: 'string',
		col_length: 256,
		col_rule: 'required',
		col_enable: 0
	};
	var form_data_structure = {
		form_facility_SN: [],
		form_cols: [],
		form_remark: ''
	};
	//initial
	$scope.forms = [angular.copy(form_data_structure)];
	
	$http.get(site_url+'facility/facility/query',{params:{type:'facility'}})
	.success(function(data){
		$scope.facilities = data.aaData;
	});
	
	$scope.get_form = function(SN){
		SN = SN || '';
		
		$http.get(site_url+'oem/form/query',{params:{form_SN:SN}})
		.success(function(data){
			if(data.aaData.length)
			{
				$scope.forms[0] = data.aaData[0];
				
				$http.get(site_url+'oem/form/query',{params:{form_parent_SN:SN}})
				.success(function(data){
					for(var key in data.aaData)
					{
						$scope.forms.push(data.aaData[key]);
					}
				});
			}
		});
	};
	
	$scope.del_form = function(form_idx){
		$scope.forms.splice(form_idx,1);
	}
	
	$scope.new_form = function(){
		$scope.forms.push(angular.copy(form_data_structure));
	}
	$scope.new_column = function(form_idx){
		$scope.forms[form_idx].form_cols.push(angular.copy(col_data_structure));
	}
	$scope.del_column = function(form_idx,col_idx){
		$scope.forms[form_idx].form_cols.splice(col_idx,1);
	}
	$scope.submit = function(){
		bootstrap_modal_service.reset_info_modal();
		if(!$scope.forms[0].form_SN)
		{
			$http.post(site_url+'oem/form/add',$scope.forms)
			.success(function(data){
				bootstrap_modal_service.set_info_modal(data);
			});
		}else{
			$http.post(site_url+'oem/form/update',$scope.forms)
			.success(function(data){
				bootstrap_modal_service.set_info_modal(data);
			});
		}
		
	}
});