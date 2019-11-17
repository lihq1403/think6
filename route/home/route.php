<?php

use think\facade\Route;

Route::group('api', function () {
    Route::get('/index', 'index/index');
});