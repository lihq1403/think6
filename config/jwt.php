<?php

return [

    //jwt密钥，一定要配置
    'jwt_secret' => env('jwt.JWT_SECRET'),

    //认证有效期，单位 秒
    'auth_expires' => env('jwt.AUTH_EXPIRES'),
    // 刷新token有效期 30天
    'refresh_expires' => env('jwt.REFRESH_EXPIRES'),
];