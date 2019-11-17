<?php

use app\common\exceptions\ApiException;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ApiException::class,
];
