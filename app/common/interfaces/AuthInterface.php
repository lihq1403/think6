<?php

namespace app\common\interfaces;

interface AuthInterface
{
    /**
     * 注册
     * @return mixed
     */
    public function signUp();

    /**
     * 登录
     * @return mixed
     */
    public function login();

    /**
     * 注销
     * @return mixed
     */
    public function logout();

    /**
     * 密码重置
     * @return mixed
     */
    public function restPassword();
}