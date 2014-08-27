var cmnstApp = angular.module('cmnstApp',['ngSanitize','mgcrea.ngStrap']);

cmnstApp
.factory("user_service",function($http){
	var get_profiles = function(user_ID){
		user_ID = user_ID || '';
		return $http.get(site_url+'user/query',{params:{user_ID:user_ID}});
	}
	return {
		get_profiles: get_profiles
	}
})
.factory("facility_service",function($http){
	var get_facilities = function(facility_SN)
	{
		return $http.get(site_url+'facility/facility/query',{params:{facility_SN:facility_SN}});
	}
	return {
		get_facilities : get_facilities
	}
})
.factory("oem_service",function($http,$q){
	var get_form = function(SN){
//		var deferred = $q.defer();
		
		return $http.get(site_url+'oem/form/query',{params:{form_SN:SN}})
//		.success(function(data){
//			deferred.resolve(data);
//		});
		
//		return deferred.promise;
	}
	var get_sub_form = function(SN){
//		var deferred = $q.defer();
		return $http.get(site_url+'oem/form/query',{params:{form_parent_SN:SN}})
//		.success(function(data){
//			deferred.resolve(data);
//		});
//		
//		return deferred.promise;
	}
	var get_forms = function(SN){
		var deferred = $q.defer();
		var forms = [];
		get_form(SN)
		.success(function(data){
			forms[0] = data.aaData[0];
			if(forms[0].form_parent_SN)
			{
				get_form(forms[0].form_parent_SN)
				.success(function(data2){
					forms[0] = data2.aaData[0];
					get_sub_form(forms[0].form_SN)
					.success(function(data){
						for(var key in data.aaData)
						{
							forms.push(data.aaData[key]);	
						}
						deferred.resolve(forms);
					});
				})
			}else{
				get_sub_form(forms[0].form_SN)
				.success(function(data){
					for(var key in data.aaData)
					{
						forms.push(data.aaData[key]);	
					}
					deferred.resolve(forms);
				});
			}
		});
		return deferred.promise;
	}
	return {
		//---------------get------------------
		get_form: get_form,
		get_sub_form: get_sub_form,
		get_forms: get_forms
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
		scope.$watch(attrs.ngModel, function () {
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
.directive('facilityTimeDatatable',function($http,$sce,$compile){
	var get_data = function(scope,ele,attrs){
		return $http.get(site_url+'facility/time',{params:{query_date:scope.data_dst.query_date,facility_ID:scope.data_dst.booking_facility_SN}});
	}
	var get_tpl = function(scope,data,query_date,unit_sec){
		//initial
		unit_sec = parseInt(unit_sec);
		var timetable = [];
		var start = parseInt(moment(query_date).add('days', -2).startOf('day').format('X'));
		var end = parseInt(moment(query_date).add('days', 3).startOf('day').format('X'));
		for(
			var i=start;
			i<end;
			i+=unit_sec
		)
		{
			timetable[i] = {
				blocked:false,
				checked:false
			};
		}
		
		angular.forEach(data,function(val,key){
			for(var i=val.start_time;i<val.end_time;i+=unit_sec)
			{
				timetable[i].blocked = true;
			}
		});
		
		var body = [];
		for(var i=0;i<86400/unit_sec;i++)
		{
			var base_unixtime = parseInt(moment(query_date).add('days', -2).startOf('day').add('s',i*unit_sec).format('X'));
			body.push([
				moment().startOf('day').add('s',i*unit_sec).format('HH:mm')+'~'+moment().startOf('day').add('s',(i+1)*unit_sec).format('HH:mm'),
				timetable[base_unixtime].blocked?'X':"<input type='checkbox' uniform ng-model='scope.timetable["+base_unixtime+"].checked' value='"+base_unixtime+"'/>",
				timetable[base_unixtime=base_unixtime+86400].blocked?'X':"<input type='checkbox' ng-model='scope.timetable["+base_unixtime+"].checked' uniform value='"+base_unixtime+"'/>",
				timetable[base_unixtime=base_unixtime+86400].blocked?'X':"<input type='checkbox' ng-model='scope.timetable["+base_unixtime+"].checked' uniform value='"+base_unixtime+"'/>",
				timetable[base_unixtime=base_unixtime+86400].blocked?'X':"<input type='checkbox' ng-model='scope.timetable["+base_unixtime+"].checked' uniform value='"+base_unixtime+"'/>",
				timetable[base_unixtime=base_unixtime+86400].blocked?'X':"<input type='checkbox' ng-model='scope.timetable["+base_unixtime+"].checked' uniform value='"+base_unixtime+"'/>"
			]);
		}
		
		var header = [
			'',
			moment(query_date).add('days', -2).format("YYYY年MM月Dodddd"),
			moment(query_date).add('days', -1).format("YYYY年MM月Dodddd"),
			moment(query_date).add('days', 0).format("YYYY年MM月Dodddd"),
			moment(query_date).add('days', 1).format("YYYY年MM月Dodddd"),
			moment(query_date).add('days', 2).format("YYYY年MM月Dodddd"),
		];
		header = '<thead><tr><td>'+header.join('</td><td>')+'</td></tr></thead>';
		
		for(var key in body){
			body[key] = '<td>'+body[key].join('</td><td>')+'</td>';
		}
		body = '<tbody><tr>'+body.join('</tr><tr>')+'</tr></tbody>';
		
		return $sce.trustAsHtml(header+body);
	}
	var linker = function(scope,ele,attrs){
		//initial
		scope.data_dst.query_date = moment().add('days', 2).format('YYYY-MM-DD');
		
		var datatable = ele.dataTable({
			"bPaginate": false,
			"bSort": false,
			"bFilter": false,
			"sDom": "<'row-fluid'r>t<'row-fluid'>",
			"sPaginationType": "bootstrap",
			"aaSorting": []
		});
		var fixedheader = new $.fn.dataTable.FixedHeader( datatable );//固定DATATABLE頭部
		
		//watch
		scope.$watch("data_dst.booking_facility_SN",function(new_val,old_val){
			get_data(scope,ele,attrs)
			.success(function(data){
				scope.data_dst.tpl = get_tpl(scope,data.aaData,scope.data_dst.query_date,data.unit_sec);
				
			});
		});
		scope.$watch("data_dst.query_date",function(new_val,old_val){
			get_data(scope,ele,attrs)
			.success(function(data){
				scope.data_dst.tpl = get_tpl(scope,data.aaData,scope.data_dst.query_date,data.unit_sec);
			});
		});
		scope.$watch("data_dst.tpl",function(new_val,old_val){
//			if(typeof fixedHeader != "undefined")
//		    {
		    	//for temporary fixed!!
				fixedheader._fnUpdateClones(true);
				fixedheader._fnUpdatePositions();
				$compile(ele.contents())(scope);
//			}
		});
        
	}
	return {
		restrict: 'A',
		scope:{
			data_dst: '=facilityTimeDatatable'
		},
//		template: function(tElement, tAttrs) {
//		    return get_tpl(scope,data.aaData,scope.data_dst.query_date,data.unit_sec);
//		},
		link: linker
	}
})
//---------------------BOOTSTRAP MODAL CONTROLLER--------------------------
.controller("bootstrap_modal_controller",function($scope,$http){
	
})
//-----------------------------OEM CONTROLLER------------------------------
	/*----------------APP EDIT----------------*/
.controller("oem_application_edit",function($scope,$http,user_service,oem_service,bootstrap_modal_service){
//	$scope.test = {query_date:"123"};//預設為今天
	//initial variable
	var app_col = {
		app_col_SN: 0,
		app_SN: 0,
		form_col_SN: 0,
		form_SN: 0,
		col_value: '',
	};
	$scope.app_checkpoint = {
		checkpoint_SN: 0,
		app_SN: 0,
		checkpoint_ID: 0,
		checkpoint_admin_ID: '',
		checkpoint_comment: '',
		checkpoint_timestamp: '',
	}
//	$scope.form_idx = 0;
	$scope.forms = [];
	$scope.app = {
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
	$scope.booking = {
		query_date: '',
		booking_user_SN: 0,
		booking_facility_SN: []	
	};
	//---------common function-------------------
	var get_form_cols = function(form_idx){
		var cols = [];
		for(var key in $scope.forms[form_idx].form_cols)
		{
			if($scope.forms[form_idx].form_cols[key].col_enable==1)
			{
				var tmp = angular.copy(app_col);
				angular.extend(tmp,$scope.forms[form_idx].form_cols[key]);
				cols.push(tmp);
			}
		}
		return cols;
	}
	//-------------------WATCH--------------------
	$scope.$watch("form_idx",function(new_value,old_value){
		if(new_value===old_value){
			return;
		}
		if(old_value!==undefined){
			//先移除舊的
			if(old_value>0)
			{
				$scope.app.app_cols = $scope.app.app_cols.filter(function(app_col){
					return app_col.form_SN == $scope.forms[0].form_SN;
				});
			}
			if(new_value>0)
			{
				$scope.app.app_cols = $scope.app.app_cols.concat(get_form_cols(new_value));
			}
			$scope.app.form_SN = $scope.forms[new_value].form_SN;
		}
		$scope.available_facilities = (new_value>0)?$scope.forms[0].form_facilities.concat($scope.forms[new_value].form_facilities):$scope.forms[0].form_facilities;
		$scope.available_facilities = $scope.available_facilities.filter(function(ele,idx,arr){
			for(var idx2 in arr){
				if(arr[idx2].facility_SN == ele.facility_SN){
					return idx2 == idx;
				}
			}
		});
	});
	$scope.$watch("booking.booking_facility_SN",function(new_value,old_value){
		if(new_value===old_value){
			return;
		}
		$scope.available_users = [];
		$scope.available_facilities.filter(function(ele){
			return $scope.booking.booking_facility_SN.indexOf(ele.facility_SN)!==-1; 
		}).forEach(function(ele,idx){
			$scope.available_users = $scope.available_users.concat(ele.engineers);
		});
		$scope.available_users = $scope.available_users.filter(function(ele,idx,arr){
			for(var idx2 in arr){
				if(arr[idx2].user_ID == ele.user_ID){
					return idx2 == idx;
				}
			}
		});
	});
	
	//initial function
	$scope.init_app = function(SN,token){
		token = token || '';
		
		if(token)
		{
			$http.get(site_url+'oem/app/query',{params:{app_SN:SN,app_token:token}})
			.success(function(data){
				angular.extend($scope,data.aaData);
				
				for(var idx in data.aaData.forms){
					if(data.aaData.forms[idx].form_SN == data.aaData.app.form_SN)
					{
						$scope.form_idx = idx;
					}
				}
			});
		}else{
			//initial
			$http.get(site_url+'oem/app/query',{params:{app_SN:SN}})
			.success(function(data){
				$scope.app = data.aaData[0];
				oem_service.get_forms(data.aaData[0].form_SN)
				.then(function(data2){
					$scope.forms = data2;
					for(var idx in data2){
						if(data2[idx].form_SN == data.aaData[0].form_SN)
						{
							$scope.form_idx = idx;
						}
					}
				});
				
			});
			
			user_service.get_profiles($scope.app.app_user_ID)
			.success(function(data){
				$scope.user = data.aaData[0];
			});
		}
	};
	
	$scope.init_new_app = function(form_SN){
		user_service.get_profiles()
		.success(function(data){
			$scope.user = data.aaData[0];
		});
		
		oem_service.get_forms(form_SN).then(function(data){
			$scope.forms = data;
			//initial
			angular.extend($scope.app,$scope.forms[0]);
			$scope.app.app_cols = get_form_cols(0);
			$scope.form_idx = 0;
		});

	}
	
	//---------------post-----------------
	$scope.save = function()
	{
		bootstrap_modal_service.reset_info_modal();
		$http.post(site_url+'oem/app/add',{data:$scope.app,action:'save'})
		.success(function(data){
			bootstrap_modal_service.set_info_modal(data);
		});
	}
	
	$scope.submit = function()
	{
		bootstrap_modal_service.reset_info_modal();
		if($scope.app.app_SN){
			//已有資料
			$http.post(site_url+'oem/app/update',{data:$scope.app,action:'submit'})
			.success(function(data){
				bootstrap_modal_service.set_info_modal(data);
			});
		}else{
			//未有資料
			$http.post(site_url+'oem/app/add',{data:$scope.app,action:'submit'})
			.success(function(data){
				bootstrap_modal_service.set_info_modal(data);
			});
		}
	}
	
	$scope.accept = function(){
		bootstrap_modal_service.reset_info_modal();
		$scope.app.app_checkpoints.push($scope.app_checkpoint);
		$http.post(site_url+'oem/app/update',{data:$scope.app,action:'accept'})
		.success(function(data){
			bootstrap_modal_service.set_info_modal(data);
		});
	}
	$scope.reject = function(){
		bootstrap_modal_service.reset_info_modal();
		$scope.app.app_checkpoints.push($scope.app_checkpoint);
		$http.post(site_url+'oem/app/update',{data:$scope.app,action:'reject'})
		.success(function(data){
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