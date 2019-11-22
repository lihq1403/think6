<?php
// +----------------------------------------------------------------------
// | 多语言设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认语言
    'default_lang'    => Env::get('lang.default_lang', 'zh-cn'),
    // 允许的语言列表
    'allow_lang_list' => ['zh-cn', 'en-us'],
    // 多语言自动侦测变量名
    'detect_var'      => 'lang',
    // 是否使用Cookie记录
    'use_cookie'      => false,
    // 多语言cookie变量
    'cookie_var'      => 'think_lang',
    // 扩展语言包
    'extend_list'     => [
        'zh-cn' => [
            app()->getBasePath() . 'common/lang/zh-cn/app.php'
        ],
        'en-us' => [
            app()->getBasePath() . 'common/lang/n-us/app.php'
        ],
    ],
    // Accept-Language转义为对应语言包名称
    'accept_language' => [
        'zh-hans-cn' => 'zh-cn',
    ],
    // 是否支持语言分组
    'allow_group'     => false,
];
