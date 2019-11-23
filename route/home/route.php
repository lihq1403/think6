<?php

use think\facade\Route;

Route::group('api/:version', function () {
    Route::get('index', ':version.Index/index');

    // 上传相关
    Route::group('upload', function () {
        Route::post('local', ':version.Media/localMediaUpload'); // 本地磁盘保存
    });

})->allowCrossDomain();