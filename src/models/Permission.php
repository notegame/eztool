<?php
namespace Exzcute\EzTool\models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $with = ['childs'];

	function childs(){
		return $this->hasMany('\Exzcute\EzTool\models\Permission','parent','id')->orderBy('order');
	}

	static $lists = [];

	public static function getList($parent_id=null,$parent_name=null)
	{
		if($parent_id==null){
			self::$lists = [];
		}

		$menus = self::where('parent',$parent_id)->with('childs')->orderBy('order')->get();

		foreach ($menus as $menu) {

			$_parent_name = !empty($parent_name) ? $parent_name.'.' : '';

			self::$lists[] = $_parent_name.$menu->name;

			self::getList($menu->id, $_parent_name.$menu->name);
		}

		return self::$lists;

	}

}