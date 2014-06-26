var cmnstApp = angular.module('cmnstApp',[]);

cmnstApp
.controller("oem_application_edit",function($scope,$http){
	//initial
	$scope.get_app = function(SN){
		$http
		.get(site_url+'oem/app/query',{params:{app_SN:SN}})
		.success(function(data){
			var output = data.aaData[0];
			output.guests = [];
			$scope.app = output;
		});
	};
	$scope.get_form = function(SN){
		$http
		.get(site_url+'user/query',{params:{user_ID:''}})
		.success(function(data){
			var output = data.aaData[0];
			$scope.user = output;
			console.log($scope.user);
		});
		$http
		.get(site_url+'oem/form/query',{params:{form_SN:SN}})
		.success(function(data){
			var output = data.aaData[0];
			output.guests = [];
			$scope.app = output;
		});
	};
	
	$scope.new_guest = function(container){
		container.push({name:'',mobile:''});
	}
});