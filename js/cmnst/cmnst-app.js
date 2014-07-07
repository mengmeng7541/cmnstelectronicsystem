var cmnstApp = angular.module('cmnstApp',['ngSanitize']);

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
//---------------------BOOTSTRAP MODAL CONTROLLER--------------------------
.controller("bootstrap_modal_controller",function($scope,$http){
	
})
//-----------------------------OEM CONTROLLER------------------------------
.controller("oem_application_edit",function($scope,$http,user_profile_service){
	//initial
	$scope.get_app = function(SN){
		$http
		.get(site_url+'oem/app/query',{params:{app_SN:SN}})
		.success(function(data){
			data.aaData[0].guests = [];
			$scope.app = data.aaData[0];
		});
	};
	$scope.get_form = function(SN){
		$scope.app = [];
		user_profile_service.get_my_profile()
		.success(function(data){
			$.extend($scope.app,data.aaData[0]);
		});
		
		$http
		.get(site_url+'oem/form/query',{params:{form_SN:SN}})
		.success(function(data){
			data.aaData[0].guests = [];
			$.extend($scope.app,data.aaData[0]);
		});
	};
	
	$scope.new_guest = function(){
		$scope.app.guests.push({name:'',mobile:''});
	}
	
})
.controller("oem_form_edit",function($scope,$http,bootstrap_modal_service){
	//initial
	$scope.forms = [{form_facility_SN:[]}];
	
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
	
	$scope.del_form = function(SN){
		$scope.forms.splice(SN,1);
	}
	
	$scope.new_form = function(){
		$scope.forms.push({form_facility_SN:[]});
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