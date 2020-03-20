<?php

use think\facade\Route;

Route::group('api/home/:version', function () {
    Route::get('index', ':version.Index/index');

    // 认证相关
    Route::group('auth', function () {
        Route::post('sign-up', ':version.Auth/signUp'); // 注册
        Route::post('login', ':version.Auth/login'); // 登录
        Route::post('rest-password', ':version.Auth/restPassword'); // 重置密码
    });

    Route::post('captcha', ':version.Captcha/send'); // 验证码发送

    // 需要登录的路由
    Route::group('', function () {
        Route::get('auth/refresh-token', ':version.Auth/refreshToken'); // 刷新token
        Route::get('auth/logout', ':version.Auth/logout'); // 注销

        Route::get('user', ':version.User/userInfo');
    })->middleware('home_auth');

})->allowCrossDomain();