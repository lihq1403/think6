<?php

namespace app\common\facades;

use app\common\tools\AdminAuthTool;
use think\Facade;

/**
 * Class HomeAuth
 * @package app\common\facades
 * @see AdminAuthTool
 * @mixin AdminAuthTool
 */
class AdminAuth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\common\tools\AdminAuthTool';
    }
}