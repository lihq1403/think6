<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\exceptions\CommonException;
use app\common\exceptions\DataValidateException;
use app\common\facades\AdminAuth;
use app\common\interfaces\AuthInterface;
use app\common\repositories\AdminUserRepository;
use app\common\repositories\LoginLogRepository;

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

        // 获取用户权限
        $res['admin_user_info']['permission'] = $admin_user_info->getUserPermission();

        // 记录最后登录时间和ip
        $admin_user_info->last_login_time = time();
        $admin_user_info->last_login_ip = get_client_ip();
        $admin_user_info->save();

        // 记录登录日志
        LoginLogRepository::instance()->write($admin_user_info['id'], app('http')->getName());

        return $this->successResponse('login success', $res);
    }

    /**
     * 注销
     * @return mixed|\think\response\Json|\think\response\Jsonp
     */
    public function logout()
    {
        // 注销本系统
        app('jwt_tool')->logout();
        return $this->successResponse('success');
    }

    /**
     * 密码重置
     */
    public function restPassword(){}

    /**
     * 获取用户信息
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function info()
    {
        $admin_user_info = AdminAuth::user();
        $admin_user_info['permission'] = $admin_user_info->getUserPermission();
        return $this->successResponse('获取成功', $admin_user_info);
    }

    /**
     * 修改密码
     * @return \think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     */
    public function changePassword()
    {
        $params = $this->apiParams(['old_password', 'password']);

        $this->validate($params, [
            'old_password' => 'require',
            'password' => 'require|'.AdminUserRepository::instance()->getPasswordValidate(),
        ]);

        $admin_user_info = AdminAuth::user();

        // 检验原密码
        if (!password_check($params['old_password'], $admin_user_info->password)) {
            throw new CommonException('原密码错误');
        }

        $admin_user_info->password = password_encrypt($params['password']);
        $admin_user_info->save();

        return $this->successResponse('修改成功', $admin_user_info);
    }
}