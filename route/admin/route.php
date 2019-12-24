<?php

use think\facade\Route;

Route::group('api/admin', function () {

    // 媒体资源上传
    Route::group('upload', function () {
        Route::post('local', 'Upload/local'); // 本地上传
        Route::post('q-cloud', 'Upload/qCloud'); // 腾讯云上传
        Route::get('q-cloud-temp-keys', 'Upload/getQCloudTempKeys'); // 腾讯云临时密钥
        Route::post('info', 'Upload/info'); // info上传
    });

    // 媒体资源分类管理
    Route::post('media-category', 'MediaCategory/store');
    Route::put('media-category', 'MediaCategory/update');
    Route::get('media-categories', 'MediaCategory/index');
    Route::get('media-categories-tree', 'MediaCategory/treeIndex');
    Route::delete('media-category', 'MediaCategory/destroy');

    // 媒体资源管理
    Route::get('medias', 'Media/index');
    Route::put('media', 'Media/update');
    Route::delete('media', 'Media/destroy');

    Route::get('/index', 'index/index');
})->allowCrossDomain();

