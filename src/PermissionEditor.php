<?php
namespace Exzcute\EzTool;

class PermissionEditor
{
	public $permissions = [];
	public $config = [];

	private $config_name = 'eztool.permission';

	public function __construct()
	{
		$this->config = config($this->config_name);
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
			\Config::set("{$this->config_name}.permissions.$key", $value);
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
			\Config::set("{$this->config_name}.$key", $value);
		}
		return $this->config;
	}

	public function style()
	{
		$this->_shareData();

		return view('eztool::permission_editor.style'); 
	}

	public function script()
	{
		$this->_shareData();

		return view('eztool::permission_editor.script');
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

		return view('eztool::permission_editor.render'); 
	}


}