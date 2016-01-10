<?php

Route::group(['middleware' => ['web']], function () {

	Route::get(config("eztool.permission.script_url"),function () {
	    return EzPermissionEditor::script();
	});

	Route::get(config("eztool.permission.style_url"),function () {
	    return response(EzPermissionEditor::style())
	    ->header('Content-Type', 'text/css');
	});

	Route::get(config("eztool.acl.script_url"),function () {
	    return EzACLManager::script();
	});

	Route::get(config("eztool.acl.style_url"),function () {
	    return response(EzACLManager::style())
	    ->header('Content-Type', 'text/css');
	});

	Route::get(config("eztool.menu.script_url"),function () {
	    return EzMenuManager::script();
	});

	Route::get(config("eztool.menu.style_url"),function () {
	    return response(EzMenuManager::style())
	    ->header('Content-Type', 'text/css');
	});

});