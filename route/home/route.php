<?php

use think\facade\Route;

Route::group('api', function () {
    Route::get('index', 'index/index');

    // 上传相关
    Route::group('upload', function () {
        Route::post('local', 'media/localMediaUpload'); // 本地磁盘保存
    });

});