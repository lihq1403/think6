<?php

use think\facade\Route;

Route::group('api/admin', function () {

    Route::group('', function () {

        Route::post('login', 'Auth/login');

    });

    Route::group('', function () {

        // 登录用户管理
        Route::get('login-info', 'Auth/info');
        Route::post('change-password', 'Auth/changePassword');

        // 管理员管理
        Route::post('admin-user', 'AdminUser/store');
        Route::put('admin-user', 'AdminUser/update');
        Route::get('admin-users', 'AdminUser/index');
        Route::delete('admin-user', 'AdminUser/destroy');
        Route::post('admin-user/change-status', 'AdminUser/changeStatus');

        // 媒体资源上传
        Route::group('upload', function () {
            Route::post('local', 'Upload/local'); // 本地上传
            Route::post('q-cloud', 'Upload/qCloud'); // 腾讯云上传
            Route::get('q-cloud-temp-keys', 'Upload/getQCloudTempKeys'); // 腾讯云临时密钥
            Route::post('a-li-yun', 'Upload/aLiYun'); // 阿里云上传
            Route::post('a-li-yun-policy', 'Upload/getALiYunPolicy'); // 阿里云getALiYunPolicy
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

        // 开放商户管理
        Route::post('open-app', 'OpenApp/store');
        Route::put('open-app', 'OpenApp/update');
        Route::get('open-apps', 'OpenApp/index');
        Route::delete('open-app', 'OpenApp/destroy');
        Route::post('open-app/change-status', 'OpenApp/changeStatus');

        // rbac 管理
        Route::group('rbac', function () {

            // 角色管理
            Route::post('role', 'Lihq1403\ThinkRbac\controller\RBACController@addRole'); // 添加角色
            Route::put('role', 'Lihq1403\ThinkRbac\controller\RBACController@editRole'); // 修改角色
            Route::delete('role', 'Lihq1403\ThinkRbac\controller\RBACController@delRole'); // 删除角色
            Route::get('roles', 'Lihq1403\ThinkRbac\controller\RBACController@getRoles'); // 角色列表
            Route::get('role/permission-group', 'Lihq1403\ThinkRbac\controller\RBACController@roleHoldPermissionGroup'); // 角色拥有的权限列表
            Route::post('role/change-permission-group', 'Lihq1403\ThinkRbac\controller\RBACController@diffPermissionGroup'); // 角色更换的权限列表

            // 权限组管理
            Route::post('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@addPermissionGroup'); // 权限组新增
            Route::put('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@editPermissionGroup'); // 权限组编辑
            Route::delete('permission_group', 'Lihq1403\ThinkRbac\controller\RBACController@delPermissionGroup');// 权限组删除
            Route::get('permission_groups', 'Lihq1403\ThinkRbac\controller\RBACController@getPermissionGroups'); // 权限组列表

            // 权限管理
            Route::post('permission', 'Lihq1403\ThinkRbac\controller\RBACController@addPermission'); // 权限新增
            Route::put('permission', 'Lihq1403\ThinkRbac\controller\RBACController@editPermission'); // 权限编辑
            Route::delete('permission', 'Lihq1403\ThinkRbac\controller\RBACController@delPermission'); // 权限删除
            Route::get('permissions', 'Lihq1403\ThinkRbac\controller\RBACController@getPermissions'); // 权限列表

            // 管理员管理
            Route::post('admin-user/role', 'Lihq1403\ThinkRbac\controller\RBACController@userAssignRoles'); // 给管理员分配角色
            Route::delete('admin-user/role', 'Lihq1403\ThinkRbac\controller\RBACController@userCancelRoles'); // 给管理员删除角色
            Route::post('admin-user/sync-role', 'Lihq1403\ThinkRbac\controller\RBACController@userSyncRoles'); // 同步管理员角色

            // 日志管理
            Route::get('logs', 'Lihq1403\ThinkRbac\controller\RBACController@getLog'); // 获取日志
        });

    })->middleware('admin_auth');

    Route::get('/index', 'index/index');
})->allowCrossDomain();

