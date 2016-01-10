<?php
namespace Exzcute\EzTool\models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $fillable = ['name','label','url','permissions','attr','icon','parent','order'];

	protected $with = ['childs'];

	function childs(){
		return $this->hasMany('Exzcute\EzTool\models\Menu','parent','id')->orderBy('order');
	}

	public function getPermissionsAttribute($permissions)
    {
        return $permissions ? json_decode($permissions, true) : [];
    }

    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = $permissions ? json_encode($permissions) : '';
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = str_slug($name, "-");
    }

	public function url_render()
    {
    	$url = $this->url;
    	if(substr($url, 0,1)=="@"){
    		$code = substr($url, 1); 		
    		eval('try {$url = '.$code.';} catch (\Exception $e) {$url=$e->getMessage();}');

    		return $url;
    	}
        return $url;
    }
}