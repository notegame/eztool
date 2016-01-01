<?php
Route::group(['middleware' => ['web']], function () {
	
	Route::get(config("eztool.permission.script_url"),function () {
	    return EzPermissionEditor::script();
	});

	Route::get(config("eztool.permission.style_url"),function () {
	    return response(EzPermissionEditor::style())
	    ->header('Content-Type', 'text/css');
	});

});