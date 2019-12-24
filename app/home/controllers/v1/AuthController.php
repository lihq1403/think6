<?php

namespace app\home\controllers\v1;

use app\common\controllers\HomeBaseController;
use app\common\exceptions\CommonException;
use app\common\exceptions\DataValidateException;
use app\common\facades\HomeAuth;
use app\common\interfaces\AuthInterface;
use app\common\libs\VerificationLib;
use app\common\models\User;
use app\common\repositories\UserRepository;

class AuthController extends HomeBaseController implements AuthInterface
{
    /**
     * 注册
     * @return mixed|\think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function signUp()
    {
        $params = $this->apiParams(['type', 'phone', 'email', 'verification_key', 'verification_code', 'password'], ['type', 'verification_key', 'verification_code']);

        // 先根据type区分
        $this->validate($params, [
            'type' => 'require|in:email,phone',
            'verification_key' => 'require|max:255',
            'verification_code' => 'require|max:255',
            'password' => 'require|'.User::PASSWORD_VALIDATE
        ]);

        switch ($params['type']) {
            case 'phone':
                $this->validate($params, [
                    'phone' => 'require'
                ]);

                // todo 判断手机号格式，有可能是国外的，所以先没做验证

                $field = 'phone';
                break;
            case 'email':
                $this->validate($params, [
                    'email' => 'require|email'
                ]);
                $field = 'email';
                break;
            default:
                throw new DataValidateException('error type');
        }

        // 验证验证码
        VerificationLib::checkCaptcha($params['verification_key'], $params['verification_code'], $params['type'], $params[$field].'_sign-up');

        // 检验账号是否已经存在
        if (!empty(UserRepository::instance()->getInfoByFiled($field, $params[$field]))) {
            throw new CommonException('account exist');
        }

        // 创建账号
        $data = array_merge(UserRepository::instance()->getInitInfo(), [
            $field => $params[$field],
            'password' => password_encrypt($params['password']),
        ]);
        $user_info = User::create($data);

        // 登录token生成
        $jwt = app('jwt_tool')->setScene(UserRepository::instance()->getLoginScene())->jsonReturnToken($user_info['id']);

        return $this->successResponse('success', compact('user_info', 'jwt'));
    }

    /**
     * 登录
     * @return mixed|\think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws CommonException
     */
    public function login()
    {
        $params = $this->apiParams(['username', 'password']);

        $this->validate($params, [
            'username' => 'require',
            'password' => 'require|'.User::PASSWORD_VALIDATE
        ]);

        if (is_email($params['username'])) {
            $field = 'email';
        } else {
            $field = 'phone';
        }

        $user_info = UserRepository::instance()->getInfoByFiled($field, $params['username']);

        // 检验密码
        if (!password_check($params['password'], $user_info->password)) {
            throw new CommonException('wrong username or password');
        }

        if ($user_info->status != 1) {
            throw new CommonException('account is disabled', 471);
        }

        // 登录token生成
        $jwt = app('jwt_tool')->setScene(UserRepository::instance()->getLoginScene())->jsonReturnToken($user_info['id']);

        return $this->successResponse('success', compact('user_info', 'jwt'));
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
     * @return mixed|\think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function restPassword()
    {
        $params = $this->apiParams(['type', 'phone', 'email', 'verification_key', 'verification_code', 'password']);

        // 先根据type区分
        $this->validate($params, [
            'type' => 'require|in:email,phone',
            'verification_key' => 'require|max:255',
            'verification_code' => 'require|max:255',
            'password' => 'require|'.User::PASSWORD_VALIDATE
        ]);

        switch ($params['type']) {
            case 'phone':
                $this->validate($params, [
                    'phone' => 'require'
                ]);
                $field = 'phone';
                break;
            case 'email':
                $this->validate($params, [
                    'email' => 'require|email'
                ]);
                $field = 'email';
                break;
            default:
                throw new DataValidateException('error type');
        }

        // 验证码检验
        VerificationLib::checkCaptcha($params['verification_key'], $params['verification_code'], $params['type'], $params[$field].'_rest-password');

        // 查找用户信息
        $user_info = UserRepository::instance()->getInfoByFiled($field, $params[$field]);
        if (empty($user_info)) {
            throw new DataValidateException('the account has not been registered');
        }

        $user_info->password = password_encrypt($params['password']);
        $user_info->save();

        $jwt = app('jwt_tool')->setScene(UserRepository::instance()->getLoginScene())->jsonReturnToken($user_info['id']);

        return $this->successResponse('success', compact('user_info', 'jwt'));
    }

    /**
     * 刷新token
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function refreshToken()
    {
        $user_info = HomeAuth::user();

        $jwt = app('jwt_tool')->setScene(UserRepository::instance()->getLoginScene())->jsonReturnToken($user_info['id']);

        return $this->successResponse('success', compact('user_info', 'jwt'));
    }
}