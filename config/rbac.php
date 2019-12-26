<?php


return [

    /**
     * 用户表
     */
    'user_model' => \app\common\models\AdminUser::class,

    /**
     * 是否忽略未定义的权限
     */
    'skip_undefined_permission' => false,

    /**
     * 可以跳过权限验证的方法
     */
    'continue_list' => [
        'module' => ['admin'],
        'controller' => ['test'],
        'action' => ['test']
    ],

    /**
     * 权限组配置，很重要！！！
     */
    'permission_group_list' => [
        ['name' => 'rbac管理', 'code' => 'rbac'],
        // todo 自己手动配置权限组
    ],

    /**
     * 权限配置
     * $behavior => ['list', 'add', 'edit', 'show', 'delete', 'import', 'export', 'download'];
     */
    'permission_list' => [
        // 角色管理
        ['name' => '角色新增', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'addRole', 'behavior' => 'add', 'permission_group_code' => 'rbac'],
        ['name' => '角色编辑', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'editRole', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        ['name' => '角色删除', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'delRole', 'behavior' => 'delete', 'permission_group_code' => 'rbac'],
        ['name' => '角色列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'getRoles', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        ['name' => '角色拥有的权限列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'roleHoldPermissionGroup', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        ['name' => '角色更换的权限列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'diffPermissionGroup', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],

        // 权限组管理
        ['name' => '权限组新增', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'addPermissionGroup', 'behavior' => 'add', 'permission_group_code' => 'rbac'],

        // todo 手动配置权限
    ],
];