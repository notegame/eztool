<?php
namespace Exzcute\EzTool\Controllers;

use App\Http\Controllers\Controller;
use Exzcute\EzTool\models\Menu;
use Illuminate\Http\Request;
use Sentinel;

class MenuManagerController extends Controller
{
    private $config_name = 'eztool.menu';

    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.view"))) abort(401,'no permissions to access');

        $result = Menu::whereNull('parent')->orderBy('order')->get();

        return response()->json($result); 
    }
    public function store(Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.create"))) abort(401,'no permissions to access');

        $max = Menu::whereNull('parent')->max('order');

        if($max==null){
            $max = 0;
        }

        $max++;

        $model = new Menu($request->all());
        $model->order = $max;
        $model->save();

        return response()->json($model); 
    }
    public function update_order(Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.update"))) abort(401,'no permissions to access');

        $lists = $request->get('lists');

        foreach ($lists as $value) {
            $model = Menu::find($value['id']);
            $model->order = @$value['order'];
            $model->parent = @$value['parent'];
            $model->save();
        }
    }
    public function update($id, Request $request)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.update"))) abort(401,'no permissions to access');

        $model = Menu::find($id);
        $model->name = $request->input('name');
        $model->label = $request->input('label');
        $model->url = $request->input('url');
        $model->attr = $request->input('attr');
        $model->icon = $request->input('icon');
        if($request->has('permissions')){
            $model->permissions = $request->input('permissions');
        }else{
            $model->permissions = [];
        }
        $model->save();

        return response()->json($model); 
    }

    public function destroy($id)
    {
        if(!Sentinel::hasAccess(config("{$this->config_name}.permissions.delete"))) abort(401,'no permissions to access');

        $lists = Menu::whereParent($id)->get();

        foreach ($lists as $list) {
            $this->destroy($list->id);
            $list->delete();
        }

        Menu::find($id)->delete();
        
        return response()->json(true); 
    }
}
