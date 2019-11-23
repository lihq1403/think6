<?php

return [
    // 是否验证签名
    'check_sign' => env('api.check_sign', true),

    // 时效性，有效时间，秒
    'effective_time' => 60,

    // 允许的请求key
    'app_type' => [
        'home-api' => 'wf6OvQt6',
        'admin-api' => 'igUxPjZw'
    ],
];