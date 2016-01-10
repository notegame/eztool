<?php
namespace Exzcute\EzTool\Facades;

use Illuminate\Support\Facades\Facade;

class ACLManager extends Facade{
    protected static function getFacadeAccessor() { return 'acl_manager'; }
}