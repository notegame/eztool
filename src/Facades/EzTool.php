<?php
namespace Exzcute\EzTool\Facades;

use Illuminate\Support\Facades\Facade;

class EzTool extends Facade{
    protected static function getFacadeAccessor() { return 'eztool'; }
}