<?php

namespace app\common\repositories;

use app\common\models\AdminUser;
use app\common\traits\SingletonTrait;

class AdminUserRepository
{
    use SingletonTrait;

    protected $login_scene = 'admin';

    protected $login_global_name = 'admin_login_uid';

    /**
     * @return string
     */
    public function getLoginScene()
    {
        return $this->login_scene;
    }

    /**
     * @return string
     */
    public function getLoginGlobalName()
    {
        return $this->login_global_name;
    }

    /**
     * 通过用户名查找信息
     * @param string $username
     * @param array $fields
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function findByUserName(string $username, array $fields = [])
    {
        $map = [
            ['username', '=', $username]
        ];
        return AdminUser::where($map)->field($fields)->find();
    }

    /**
     * 获取用户信息
     * @param string $field
     * @param string $value
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfoByFiled(string $field, string $value)
    {
        return AdminUser::where($field, $value)->find();
    }

    /**
     * 密码规则
     * @return string
     */
    public function getPasswordValidate()
    {
        return 'min:6|max:16';
    }
}