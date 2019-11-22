<?php

use app\common\exceptions\ApiException;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ApiException::class,

    // 模拟全局变量
    'global_params' => app\common\containers\GlobalParams::class,
    // jwt 工具类
    'jwt_tool' => app\common\containers\JwtTool::class,
];
