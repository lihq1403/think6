<?php

namespace app\common\facades;

use app\common\tools\HomeAuthTool;
use think\Facade;

/**
 * Class HomeAuth
 * @package app\common\facades
 * @see HomeAuthTool
 * @mixin HomeAuthTool
 */
class HomeAuth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\common\tools\HomeAuthTool';
    }
}