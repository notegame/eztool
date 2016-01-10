angular.module('menuManagerApp', [], function($interpolateProvider) {
	$interpolateProvider.startSymbol('{(');
    $interpolateProvider.endSymbol(')}');
})
.directive('menuList', function($document, $templateCache) {
	return {
    	template: $('script[id="menu_list.html"]').html()
  	};
})
.directive('onPostRepeatDirective', function($timeout) {
  return function(scope, element, attrs) {
    if (scope.$last){
    	$timeout(function() { 
      		$('.select-chosen').chosen({width: "100%"});
      	});
    }
  };
})
.controller('menuManagerController', function($scope) {

	//Declare
	var _this = this;
	
	_this.permission_editor = $('#menu_manager');
	_this.permission_create_modal = $('#modal-create-menu');

	_this.create = {};
	_this.menus = {};
	_this.permissions = {!!json_encode($permission_list)!!};
	_this.lists = [];

	//get List Recursive
	_this.getChilds = function(data,parent) {
		var order = 1;
		$.each(data, function(index, val) {
			if(val.children != undefined)
			{
				val.children = _this.getChilds(val.children,val.id);
			}
			_this.lists.push({
				id : val.id,
				parent : parent,
				order : order
			});
			order++;
		});
	}

	@if(\Sentinel::hasAccess($permissions['update']))
	//run jquery nestable
	_this.permission_editor
	    .nestable({
	        group: 1
	    }).on('change', function(e) {

	    	//onchange update parent and order 

	        var list   = e.length ? e : $(e.target),
	            output = list.data('output');

	        _this.lists = [];

	        _this.getChilds(list.nestable('serialize'));

	        if(list.attr('id')=="menu_manager")
	        {
		        $.ajax({
		        	url: '{{$config['url']['update_order']}}',
		        	type: 'PUT',
		        	data: {lists : _this.lists, _token : "{{csrf_token()}}"},
		        })
		        .done(function() {
		        	
		        })
		    }
	        
	    });
	@endif

	//get data from ajax
	_this.loadData = function() {
		$.ajax({
			url: '{{$config['url']['list']}}',
			type: 'GET',
		})
		.done(function(data) {
			_this.menus = data;
			$scope.$apply();
		});
	}

	_this.loadData();

	//on click create button 
	_this.createSubmit = function($event) {

		var parent = $($event.currentTarget).parent().parent();

		$(parent).waitMe();

		_this.create._token ="{{csrf_token()}}";
		
		$.ajax({
			url: '{{$config['url']['create']}}',
			type: 'POST',
			data: _this.create
		})
		.done(function(data) {
			_this.menus.push(data);
			_this.create = {};
		})
		.fail(function(data) {
			bootbox.alert(data.responseJSON.msg);
		})
		.always(function() {
			$('#create_permissions').val('').trigger('chosen:updated');
			_this.permission_create_modal.modal('hide');
			$(parent).waitMe('hide');
			_this.loadData();
		});

		console.log(parent);

	}

	//on click close button
	_this.closeSubmit = function() {
		_this.create = {};
	}

	//on click save button
	//update permission
	_this.save = function(object,$event) {

		var parent = $($event.currentTarget).parent().parent();

		$(parent).waitMe();

		object._token ="{{csrf_token()}}";

		$.ajax({
			url: '{{$config['url']['update']}}/'+object.id,
			type: 'PUT',
			data: object,
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function(data) {
			bootbox.alert(data.responseJSON.msg);
		})
		.always(function() {
			$(parent).waitMe('hide');
		});
		
	}

	//on click delete button
	//delete permission and childs
	_this.delete = function(object,$event) {

		bootbox.confirm("Confirm Delete?",function(confirm) {

			if(!confirm) return;

			var parent = $($event.currentTarget).parent().parent();

			$(parent).waitMe();

			object._token ="{{csrf_token()}}";

			$.ajax({
				url: '{{$config['url']['delete']}}/'+object.id,
				type: 'DELETE',
				data: object,
			})
			.done(function(data) {
				console.log(data);
			})
			.fail(function(data) {
				bootbox.alert(data.responseJSON.msg);
			})
			.always(function() {
				_this.loadData();
				$(parent).waitMe('hide');
			});

		})
	}

	//on click collapse button
	_this.collapse = function(obj,$event) {

		var parent = $($event.currentTarget).parent();
		
        if(parent.hasClass('list-close')){
            parent.removeClass('list-close');
            parent.addClass('list-open');

            var content = parent.find('.menu-manager__list__content');

            $(content[0]).slideDown('fast');
        }else{
           
           	var content = parent.find('.menu-manager__list__content');

            $(content[0]).slideUp('fast',function() {
                parent.addClass('list-close');
                parent.removeClass('list-open');
            });
        }
	}
});