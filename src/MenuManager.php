<?php
namespace Exzcute\EzTool;

use Exzcute\EzTool\models\Permission;
use Exzcute\EzTool\models\Menu;

class MenuManager
{
	public $permissions = [];
	public $config = [];
	public $active = null;
	public $actives = [];

	private $config_name = 'eztool.menu';

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

		return view('eztool::menu_manager.style'); 
	}

	public function script()
	{
		$this->_shareData();

		$permission_list = Permission::getList();

		return view('eztool::menu_manager.script')
		->with('permission_list',$permission_list);
	}

	public function loadStyle()
	{
		return '<link rel="stylesheet" href="'.url($this->config['style_url']).'">';
	}

	public function loadScript()
	{
		return '<script src="'.url($this->config['script_url']).'"></script>';
	}

	public function get()
	{
		return Menu::whereNull('parent')->orderBy('order')->get();
	}

	public function setActive($name)
	{
		$this->active = $name;
		return $name;
	}

	public function getActive($parent_id=null)
	{
		if($parent_id==null)
		{
			$menu = Menu::whereName($this->active)->first();
		}else{
			$menu = Menu::find($parent_id);
		}
		
		if($menu){
			$this->actives[] = $menu->name;
			if($menu->parent!=null){
				self::getActive($menu->parent);
			}
		}

		return $this->actives;
	}

	public function isActive($name)
	{
		if(!$this->actives)
		{
			self::getActive();
		}

		return in_array($name,$this->actives);
	}

	public function render()
	{
		$this->_shareData();

		if(!\Sentinel::hasAccess($this->permissions['view'])) return view('eztool::no_permission');

		return view('eztool::menu_manager.render'); 
	}


}