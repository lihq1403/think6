<?php
// 中间件配置
return [
    // 别名或分组
    'alias'    => [
        'api_sign' => [
            \app\middleware\CheckApiSign::class
        ],
        'home_auth' => [
            \app\middleware\AuthHome::class,
        ],
        'check_home_auth' => [
            \app\middleware\CheckAuthHome::class,
        ],
        'admin_auth' => [
            \app\middleware\AuthAdmin::class,
            \app\middleware\AdminUserRbac::class,
        ],
    ],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [],
];
