<?php
namespace Exzcute\EzTool\Controllers;

use App\Http\Controllers\Controller;
use Exzcute\EzTool\models\Permission;
use Illuminate\Http\Request;

class PermissionEditorController extends Controller
{
    public function index()
    {
        $result = Permission::whereNull('parent')->orderBy('order')->get();

        return response()->json($result); 
    }
    public function store(Request $request)
    {
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
        $model = Permission::find($id);
        $model->name = strtolower($request->input('name'));
        $model->desc = $request->input('desc');
        $model->save();

        return response()->json($model); 
    }

    public function destroy($id)
    {
        $lists = Permission::whereParent($id)->get();

        foreach ($lists as $list) {
            $this->destroy($list->id);
            $list->delete();
        }

        Permission::find($id)->delete();
        
        return response()->json(true); 
    }
}
