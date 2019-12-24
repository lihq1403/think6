<?php

namespace app\home\controllers\v1;

use app\common\controllers\HomeBaseController;
use app\common\exceptions\DataValidateException;
use app\common\libs\VerificationLib;

class CaptchaController extends HomeBaseController
{
    /**
     * 发送验证码
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     * @throws \app\common\exceptions\SystemErrorException
     */
    public function send()
    {
        $params = $this->apiParams(['type', 'email', 'phone', 'scene']);

        // 先根据type区分
        $this->validate($params, [
            'type' => 'require|in:email,phone',
            'scene' => 'require|in:sign-up,rest-password',
        ]);

        switch ($params['type']) {
            case 'phone':
                $this->validate($params, [
                    'phone' => 'require'
                ]);
                $field = 'phone';

                // todo 判断手机号格式，有可能是国外的，所以先没做验证，发送短信
                break;
            case 'email':
                $this->validate($params, [
                    'email' => 'require|email'
                ]);
                $field = 'email';

                // todo 发送邮件
                break;
            default:
                throw new DataValidateException('error type');
        }


        // 生成码
        $captcha = VerificationLib::generateCaptcha();

        $show_code = '';
        if (app()->isDebug()) {
            $show_code = $captcha;
        }

        // 10分钟有效
        $expiredAt = 10 * 60;
        $verification_code = VerificationLib::generateVerificationCode($params['type']);
        VerificationLib::setCache($verification_code, $captcha, $expiredAt, $params['type'], $params[$field].'_'.$params['scene']);

        $res = [
            'verification_key' => $verification_code,
            'expired_at' => $expiredAt,
            'verification_code' => $show_code
        ];

        return $this->successResponse('success', $res);
    }
}