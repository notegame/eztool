<?php
namespace Exzcute\EzTool\Facades;

use Illuminate\Support\Facades\Facade;

class PermissionEditor extends Facade{
    protected static function getFacadeAccessor() { return 'permission_editor'; }
}