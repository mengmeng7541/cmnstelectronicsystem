var cmnstApp = angular.module('cmnstApp',[]);

cmnstApp
.factory("user_profile_service",function($http){
	return {
		get_my: function(){
			return $http.get(site_url+'user/query',{params:{user_ID:''}});
		}
	}
})
//-----------------------------OEM------------------------------
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
		user_profile_service.get_my()
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
});