<?php

namespace app\common\repositories;

use app\common\models\User;
use app\common\traits\SingletonTrait;

class UserRepository
{
    use SingletonTrait;

    protected $login_scene = 'home';

    protected $login_global_name = 'home_login_uid';

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
        return User::where($field, $value)->find();
    }

    /**
     * 获取初始化信息
     * @return array
     */
    public function getInitInfo()
    {
        return [
            'phone' => '',
            'email' => '',
            'nickname' => '',
            'sex' => 0,
            'area' => '',
            'birthday' => 0,
            'avatar' => 0,
            'status' => 1
        ];
    }
}