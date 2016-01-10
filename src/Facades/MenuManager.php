<?php
namespace Exzcute\EzTool\Facades;

use Illuminate\Support\Facades\Facade;

class MenuManager extends Facade{
    protected static function getFacadeAccessor() { return 'menu_manager'; }
}