<?php
namespace Exzcute\EzTool;

class PermissionEditor
{
	public $permissions = [];
	public $config = [];

	public function __construct()
	{
		$this->config = config('eztool.permission');
	}

	private function _shareData()
	{
		view()->share('config', $this->config);
		view()->share('permissions', $this->permissions);
	}

	public function setPermission($permissions=[])
	{
		$this->permissions = $permissions;
	}

	public function config($config=[])
	{
		foreach ($config as $key => $value) {
			$this->config[$key] = $value;
		}
		return $this->config;
	}

	public function style()
	{
		$this->_shareData();
		return view('eztool::permission_editor_style'); 
	}

	public function script()
	{
		$this->_shareData();
		return view('eztool::permission_editor_script');
	}

	public function render()
	{
		return view('eztool::permission_editor'); 
		//dd($this->permissions);
	}


}