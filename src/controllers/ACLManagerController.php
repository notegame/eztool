<?php
namespace Exzcute\EzTool\Controllers;

use App\Http\Controllers\Controller;
use Exzcute\EzTool\models\Permission;
use Illuminate\Http\Request;
use Sentinel;

class ACLManagerController extends Controller
{

    public function store(Request $request)
    {

        if(!Sentinel::hasAccess(config('eztool.acl.permissions.update'))) abort(401,'no permissions to access');

        $roles = $request->get('role');

        if($roles)
        foreach ($roles as $role_id => $permissions) {

            foreach ($permissions as $permission => $value) {
               $permissions[$permission] = (boolean)$value;
            }

            $role = \Sentinel::findRoleById($role_id);
            $role->permissions = $permissions;
            $role->save();
        }

        return response()->json(true); 

    }

}
