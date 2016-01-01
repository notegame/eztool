<?php
namespace Exzcute\EzTool\models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $with = ['childs'];

	function childs(){
		return $this->hasMany('\Exzcute\EzTool\models\Permission','parent','id')->orderBy('order');
	}
}