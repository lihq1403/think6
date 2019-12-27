<?php

use think\facade\Env;

return [
    // 默认磁盘
    'default' => Env::get('filesystem.driver', 'local'),
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],
        'public' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/storage',
            // 磁盘路径对应的外部URL路径
            'url'        => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
        'qcloud' => [
            'type'       => 'qcloud',
            'region'      => env('qcloud.REGION'),
            'appId'      => env('qcloud.APPID'), // 域名中数字部分
            'secretId'   => env('qcloud.SECRETID'),
            'secretKey'  => env('qcloud.SECRETKEY'),
            'bucket'          => env('qcloud.BUCKET'),
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
        ],
        'aliyun' => [
            'type'         => 'aliyun',
            'accessId'     => env('aliyun_oss.OSS_ACCESS_KEY_ID'),
            'accessSecret' => env('aliyun_oss.OSS_ACCESS_KEY_SECRET'),
            'bucket'       => env('aliyun_oss.OSS_BUCKET_NAME'),
            'endpoint'     => env('aliyun_oss.OSS_END_POINT'),
            'url'          => env('aliyun_oss.OSS_HOST'),
            'dir'          => env('aliyun_oss.OSS_DIR'),
        ],
    ],
];
