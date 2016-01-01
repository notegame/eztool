angular.module('permissionEditorApp', [], function($interpolateProvider) {
	$interpolateProvider.startSymbol('{(');
    $interpolateProvider.endSymbol(')}');
})
.directive('permissionList', function($document, $templateCache) {
	return {
    	template: $('script[id="permission_list.html"]').html()
  	};
})
.controller('permissionEditorController', function($scope) {

	//Declare
	var _this = this;
	
	_this.permission_editor = $('#permission_editor');
	_this.permission_create_modal = $('#modal-create-permission');

	_this.create = {};
	_this.permissions = {};
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

	        $.ajax({
	        	url: '{{$config['url']['update_order']}}',
	        	type: 'PUT',
	        	data: {lists : _this.lists, _token : "{{csrf_token()}}"},
	        })
	        .done(function() {
	        	
	        })
	        
	    });

	//get data from ajax
	_this.loadData = function() {
		$.ajax({
			url: '{{$config['url']['list']}}',
			type: 'GET',
		})
		.done(function(data) {
			console.log(data);
			_this.permissions = data;
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
			_this.permissions.push(data);
			_this.create = {};
		})
		.always(function() {
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

            var content = parent.find('.permission-editor__list__content');

            $(content[0]).slideDown('fast');
        }else{
           
           	var content = parent.find('.permission-editor__list__content');

            $(content[0]).slideUp('fast',function() {
                parent.addClass('list-close');
                parent.removeClass('list-open');
            });
        }

	}
});