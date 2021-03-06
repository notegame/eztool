<?php
namespace Exzcute\EzTool\Controllers;

use App\Http\Controllers\Controller;
use Exzcute\EzTool\models\Permission;
use Illuminate\Http\Request;
use Sentinel;

class PermissionEditorController extends Controller
{
    private $config_name = 'eztool.permission';

    public function index()
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.view"))) abort(401,'no permissions to access');

        $result = Permission::whereNull('parent')->orderBy('order')->get();

        return response()->json($result); 
    }
    public function store(Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.create"))) abort(401,'no permissions to access');

        $max = Permission::whereNull('parent')->max('order');

        if($max==null){
            $max = 0;
        }

        $max++;

        $model = new Permission;
        $model->name = strtolower($request->input('name'));
        $model->desc = $request->input('desc');
        $model->order = $max;
        $model->save();

        return response()->json($model); 
    }
    public function update_order(Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.update"))) abort(401,'no permissions to access');

        $lists = $request->get('lists');

        foreach ($lists as $value) {
            $model = Permission::find($value['id']);
            $model->order = @$value['order'];
            $model->parent = @$value['parent'];
            $model->save();
        }
    }
    public function update($id, Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.update"))) abort(401,'no permissions to access');

        $model = Permission::find($id);
        $model->name = strtolower($request->input('name'));
        $model->desc = $request->input('desc');
        $model->save();

        return response()->json($model); 
    }

    public function destroy($id)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.delete"))) abort(401,'no permissions to access');

        $lists = Permission::whereParent($id)->get();

        foreach ($lists as $list) {
            $this->destroy($list->id);
            $list->delete();
        }

        Permission::find($id)->delete();
        
        return response()->json(true); 
    }
}
