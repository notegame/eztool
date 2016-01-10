<?php
namespace Exzcute\EzTool;

use Exzcute\EzTool\models\Permission;

class ACLManager
{
	public $permissions = [];
	public $config = [];

	public function __construct()
	{
		$this->config = config('eztool.acl');
		$this->permissions = $this->config['permissions'];
	}

	private function _shareData()
	{
		view()->share('config', $this->config);
		view()->share('permissions', $this->permissions);
	}

	public function setPermission($permissions=[])
	{
		foreach ($permissions as $key => $value) {
			$this->permissions[$key] = $value;
			\Config::set("eztool.acl.permissions.$key", $value);
		}
		return $this->permissions;
	}

	public function getPermission()
	{
		return $this->permissions;
	}

	public function config($config=[])
	{
		foreach ($config as $key => $value) {
			$this->config[$key] = $value;
			\Config::set("eztool.acl.$key", $value);
		}
		return $this->config;
	}

	public function style()
	{
		$this->_shareData();
		return view('eztool::acl_manager.style'); 
	}

	public function script()
	{
		$this->_shareData();
		return view('eztool::acl_manager.script');
	}


	public function loadStyle()
	{
		return '<link rel="stylesheet" href="'.url($this->config['style_url']).'">';
	}

	public function loadScript()
	{
		return '<script src="'.url($this->config['script_url']).'"></script>';
	}

	public function render()
	{
		$this->_shareData();

		if(!\Sentinel::hasAccess($this->permissions['view'])) return view('eztool::no_permission');

		$roles = \Sentinel::getRoleRepository()->all();
		$permissions = Permission::whereNull('parent')->orderBy('order')->get();

		return view('eztool::acl_manager.render')
		->with('roles',$roles)
		->with('permission_list',$permissions)
		;
	}


}