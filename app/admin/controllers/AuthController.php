<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\exceptions\DataValidateException;
use app\common\interfaces\AuthInterface;
use app\common\repositories\AdminUserRepository;

class AuthController extends AdminBaseController implements AuthInterface
{
    /**
     * 注册
     */
    public function signUp(){}

    /**
     * 登录
     * @return mixed|\think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        $params = $this->apiParams(['username', 'password'], ['username', 'password']);

        // 获取用户资料
        $admin_user_info = AdminUserRepository::instance()->findByUserName($params['username']);

        // 检测密码是否正确
        if (empty($admin_user_info) || !password_check($params['password'], $admin_user_info['password'])) {
            throw new DataValidateException('wrong username or password');
        }

        // 检测用户状态
        if (empty($admin_user_info['status'])) {
            throw new DataValidateException('account is disabled');
        }

        // 生成token
        $res = app('jwt_tool')->setScene(AdminUserRepository::instance()->getLoginScene())->jsonReturnToken($admin_user_info['id']);
        $res['admin_user_info'] = $admin_user_info;

        return $this->successResponse('login success', $res);
    }

    /**
     * 注销
     */
    public function logout(){}

    /**
     * 密码重置
     */
    public function restPassword(){}
}