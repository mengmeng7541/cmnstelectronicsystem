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
	//------------------APP----------------
	var set_app_SN = function(SN){
		
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
		},
		form: {
			header: {
				title: ''
			},
			body: {
				content: ''
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
	//-----------INFO MODAL---------------
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
	//------------FORM MODAL---------------
	var set_form_modal = function(data){
		$rootScope.modal.form = data;
	}
	return {
		reset_info_modal: reset_info_modal,
		set_info_modal: set_info_modal,
		get_info_modal: get_info_modal,
		set_form_modal: set_form_modal
	}
})
.factory("booking_service",function($http){
	return {
		booking_data: [],
		unit_sec: 0,
		user_SN: 0,
		facility_SN: 0,
		query_date: 0,
		
		book: function(){
			
		},
		set_facility: function(f_SN){
			this.facility_SN = f_SN;
			this.refresh();
		},
		set_query_date: function(q_date){
			this.query_date = q_date;
			this.refresh();
		},
		refresh: function(){
			var self = this;
			$http.get(site_url+'facility/time?'+$.param({query_date:this.query_date,facility_ID:this.facility_SN}))
			.success(function(data){
				self.unit_sec = parseInt(data.unit_sec);
				self.booking_data = data.aaData;
			});
		}
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
		scope.$watch(attrs.ngModel, function () {
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
		scope.$watch(attrs.modal, function (new_val,old_val) {
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
.directive('datepicker',function(){
	var linker = function(scope,ele,attrs){
		if(attrs.datepicker=="mm")
		{
			ele
			.datepicker({	format: 'yyyy-mm',
							viewMode: 'months',
							minViewMode: 'months'})
			.on("changeDate",function(){
				$(this).trigger('change');
			});
		}else{
			ele
			.datepicker({ format: 'yyyy-mm-dd'})
			.on("changeDate",function(){
				$(this).trigger('change');
			});
		}
	}
	return {
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
.directive('oemBookingListDatatable',function(){
	var control = function($scope,$element,booking_service){
		var d_table = $element.dataTable({
			"sAjaxSource": site_url+"oem/booking/query?app_SN="+$scope.app.app_SN,
			"sDom": "<'row-fluid'r>t<'row-fluid'>",
			"sPaginationType": "bootstrap",
			"aaSorting": [],
			"bPaginate": false,
			"aoColumnDefs": [ {
		      "aTargets": [ 0 ],
		      "mData": function ( source, type, val ) {
		      	
		        return source['start_time'];
		      }
		    },
		    {
		      "aTargets": [ 1 ],
		      "mData": function ( source, type, val ) {
		        return source['end_time'];
		      }
		    },
		    {
		      "aTargets": [ 2 ],
		      "mData": function ( source, type, val ) {
		        return source['user_name'];
		      }
		    },
		    {
		      "aTargets": [ 3 ],
		      "mData": function ( source, type, val ) {
		        return source['booking_state'];
		      }
		    },
		    {
		      "aTargets": [ 4 ],
		      "mData": function ( source, type, val ) {
		        return "";
		      }
		    }],
		});
		$scope.$watch("app.app_SN",function(new_val,old_val){
			if(new_val===old_val)
			{
				return;
			}
			d_table.fnReloadAjax();	
		});
		
		$scope.b_service = booking_service;
		$scope.$watch("b_service.booking_data",function(new_val,old_val){
			if(new_val===old_val)
			{
				return;
			}
			d_table.fnReloadAjax();	
		});
	}
	return {
		restrict: 'A',
		controller: control
	}
})
.directive('facilityTimeDatatable',function($http,$compile){
	var get_tpl = function(scope,data,query_date,unit_sec){
		if(!unit_sec)
		{
			return;
		}
		//initial
		scope.timetable = [];
		var start = parseInt(moment(query_date).add('days', -2).startOf('day').format('X'));
		var end = parseInt(moment(query_date).add('days', 3).startOf('day').format('X'));
		for(
			var i=start;
			i<end;
			i+=unit_sec
		)
		{
			scope.timetable.push({time:i,state:"available"});
		}
		
		angular.forEach(data,function(val,key){
			for(var i=val.start_time;i<val.end_time;i+=unit_sec)
			{
				scope.timetable.filter(function(ele){
					return ele.time==i;
				})[0].state='blocked';
			}
		});
		
		var body = [];
		for(var i=0;i<86400/unit_sec;i++)
		{
			var base_unixtime = parseInt(moment(query_date).add('days', -2).startOf('day').add('s',i*unit_sec).format('X'));
			body.push([
				moment().startOf('day').add('s',i*unit_sec).format('HH:mm')+'~'+moment().startOf('day').add('s',(i+1)*unit_sec).format('HH:mm'),
				scope.timetable[i].state=="blocked"?'X':"<input type='checkbox' uniform ng-model='timetable["+i+"].state' ng-true-value='selected' ng-false-value='available'/>",
				scope.timetable[i+86400/unit_sec*1].state=="blocked"?'X':"<input type='checkbox' ng-model='timetable["+(i+86400/unit_sec*1)+"].state' uniform ng-true-value='selected' ng-false-value='available'/>",
				scope.timetable[i+86400/unit_sec*2].state=="blocked"?'X':"<input type='checkbox' ng-model='timetable["+(i+86400/unit_sec*2)+"].state' uniform ng-true-value='selected' ng-false-value='available'/>",
				scope.timetable[i+86400/unit_sec*3].state=="blocked"?'X':"<input type='checkbox' ng-model='timetable["+(i+86400/unit_sec*3)+"].state' uniform ng-true-value='selected' ng-false-value='available'/>",
				scope.timetable[i+86400/unit_sec*4].state=="blocked"?'X':"<input type='checkbox' ng-model='timetable["+(i+86400/unit_sec*4)+"].state' uniform ng-true-value='selected' ng-false-value='available'/>"
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
		
		return header+body;
	}
	
	var control = function($scope,$element,$compile,booking_service){
		var datatable = $element.dataTable({
			"bPaginate": false,
			"bSort": false,
			"bFilter": false,
			"sDom": "<'row-fluid'r>t<'row-fluid'>",
			"sPaginationType": "bootstrap",
			"aaSorting": []
		});
		var fixedheader = new $.fn.dataTable.FixedHeader( datatable );//固定DATATABLE頭部
		
		
		$scope.b_service = booking_service;
		$scope.$watch("b_service.booking_data",function(){
			$element.html(get_tpl(
				$scope,
				$scope.b_service.booking_data,
				$scope.b_service.query_date,
				$scope.b_service.unit_sec
			));
			fixedheader._fnUpdateClones(true);
			fixedheader._fnUpdatePositions();
			$compile($element.contents())($scope);
		});
		
		$scope.$watch("timetable",function(new_val,old_val){
			if(new_val===old_val)
			{
				return;
			}
			var tmp = new_val.filter(function(ele){
				return ele.state=='selected';
			}).map(function(ele){
				return ele.time;
			});
			$scope.data_dst.booking_start_time = Math.min.apply(null,tmp);
			$scope.data_dst.booking_end_time = Math.max.apply(null,tmp)+$scope.b_service.unit_sec;
		},true);
		
	}
	return {
		restrict: 'A',
		scope:{
			data_dst: '=facilityTimeDatatable'
		},
		controller: control
	}
})
//---------------------BOOTSTRAP MODAL CONTROLLER--------------------------
.controller("bootstrap_modal_controller",function($scope,$http){
	
})
//-----------------------------OEM CONTROLLER------------------------------
	/*----------------APP EDIT----------------*/
.controller("oem_application_edit",function($scope,$http,$sce,user_service,oem_service,bootstrap_modal_service,booking_service){
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
		app_checkpoint: 'user_init',
		app_checkpoints: [],
		app_cols: []
	};
	$scope.booking = {
		query_date: moment().add('days', 2).format('YYYY-MM-DD'),
		app_SN: 0,
		booking_state: 'normal',
		booking_user_SN: 0,
		booking_facility_SN: [],
		booking_start_time: 0,
		booking_end_time: 0
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
		//set default booking_facility_SN = available_facility_SN
		$scope.booking.booking_facility_SN = $scope.available_facilities.map(function(ele){
			return ele.facility_SN;
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
		booking_service.set_facility(new_value);
	});
	$scope.$watch("booking.query_date",function(new_val,old_val){
		booking_service.set_query_date(new_val);
	})
	
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
//			//已有資料
//			$http.post(site_url+'oem/app/update',{data:$scope.app,action:'submit'})
//			.success(function(data){
//				bootstrap_modal_service.set_info_modal(data);
//			});
//		}else{
//			//未有資料
			$http.post(site_url+'oem/app/add',{data:$scope.app,action:'submit'})
			.success(function(data){
				bootstrap_modal_service.set_info_modal(data);
			});
		}
	}
	$scope.book = function(){
		bootstrap_modal_service.reset_info_modal();
		
		$scope.booking.app_SN = $scope.app.app_SN;//prepare for data
		
		$http.post(site_url+'oem/booking/add',{data:$scope.booking,action:'book'})
		.success(function(data){
			bootstrap_modal_service.set_info_modal(data);
			booking_service.refresh();
		});
	}
	$scope.accept = function(){
		bootstrap_modal_service.reset_info_modal();
		$scope.app.app_checkpoints.push($scope.app_checkpoint);
		$http.post(site_url+'oem/app/update',{data:$scope.app,action:'accept'})
		.success(function(data){console.log(data);
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
	$scope.apply_redo = function(){
		bootstrap_modal_service.reset_info_modal();
		$scope.app.app_checkpoints.push($scope.app_checkpoint);
		$http.post(site_url+'oem/app/update',{data:$scope.app,action:'apply_redo'})
		.success(function(data){
			bootstrap_modal_service.set_info_modal(data);
		});
	}
	$scope.is_redo_agreed = function(){
		var result = $scope.app.app_checkpoints.filter(function(ele){
			return ele.checkpoint_ID==='common_lab_section_chief';
		});
		
		if(result.length===0)
		{
			return false;
		}else{
			return true;
		}
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