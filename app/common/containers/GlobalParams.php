<?php

namespace app\common\containers;

/**
 * 模拟全局变量
 * Class GlobalParams
 * @package app\common\containers
 */
class GlobalParams
{
    /**
     * @var array
     */
    protected $value = [];

    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    public function setGlobal(string $key, $value)
    {
        $this->value[$key] = $value;
        return true;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function getGlobal(string $key, $default = null)
    {
        return $this->value[$key] ?? $default;
    }
}