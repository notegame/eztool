angular.module('ACLManagerApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('{(');
    $interpolateProvider.endSymbol(')}');
})
.controller('ACLManagerController', function($scope) {
    var _this = this;
    _this.permission = {};

    _this.ok = function() {
        console.log(_this.permission);
    }

    $(function(){
    	$.each($('input[data-role-id]:not([data-permission-id]):not([data-permission-main-id])'), function(index, val) {
    		_this.checkSelectAll(null,$(val).attr("data-role-id"));
    	});
    })

    

    _this.saveSubmit = function() {
    	//$('#ez_acl_manager')
    	$('#ACLManagerApp').waitMe();
    	console.log($('#ez_acl_manager').serialize());

    	$.ajax({
    		url: '{{$config['url']['update']}}',
    		type: 'POST',
    		dataType: 'json',
    		data: $('#ez_acl_manager').serialize(),
    	})
    	.done(function() {
    		console.log("success");
    	})
    	.always(function() {
    		$('#ACLManagerApp').waitMe('hide');
    	});
    	
    }

    _this.permissionClick = function($event) {
    	
    	var target = $($event.currentTarget);

    	var permission_id = target.attr('data-permission-id');
    	var permission_main_id = target.attr('data-permission-main-id');
    	var role_id = target.attr('data-role-id');

    	if(permission_main_id==null && permission_id==null){
    		
    		$('input[data-role-id='+role_id+']').prop('checked',target.is(":checked"));
    	
    	}else
    	if(permission_main_id==null){
    		
    		$('input[data-permission-main-id='+permission_id+'][data-role-id='+role_id+']').prop('checked',target.is(":checked"));
    	
    	}

    	_this.checkSelectAll(permission_main_id,role_id);
    }

    _this.checkSelectAll = function(permission_main_id,role_id){

    	var total = $('input[data-permission-main-id='+permission_main_id+'][data-role-id='+role_id+']').length;
    	var checked = $('input[data-permission-main-id='+permission_main_id+'][data-role-id='+role_id+']:checked').length;

    	$('input[data-permission-id='+permission_main_id+'][data-role-id='+role_id+']').prop('checked',total==checked);

    	total = $('input[data-role-id='+role_id+'][data-permission-main-id]').length;
    	checked = $('input[data-role-id='+role_id+'][data-permission-main-id]:checked').length;

    	$('input[data-role-id='+role_id+']:not([data-permission-id]):not([data-permission-main-id])').prop('checked',total==checked)
    }

});