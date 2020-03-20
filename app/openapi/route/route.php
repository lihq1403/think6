<?php

use think\facade\Route;

Route::group('openapi/:version', function () {

    Route::group('test', function () {

        Route::post('sign', ':version.Test/storeSign'); // 创建比对签名

    });

    // 签名访问
   Route::group('', function () {

        Route::get('index', ':version.Index/index');

    })->middleware('api_sign');



})->allowCrossDomain();