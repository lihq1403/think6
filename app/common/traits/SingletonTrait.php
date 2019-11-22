<?php

namespace app\common\traits;

/**
 * 启用单例
 * Trait SingletonTrait
 * @package app\common\traits
 */
trait SingletonTrait
{
    private static $instance;

    public static function instance()
    {
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}