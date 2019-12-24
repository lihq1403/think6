<?php

namespace app\common\libs;

use app\common\exceptions\CommonException;
use app\common\exceptions\DataValidateException;
use app\common\exceptions\SystemErrorException;
use think\facade\Cache;

class VerificationLib
{
    /**
     * 验证码生成
     * @param int $length
     * @param int $type
     * @return string
     */
    public static function generateCaptcha(int $length = 6, int $type = 0)
    {
        // 生成验证码code
        return random_string($length, $type);
    }

    /**
     * 生成随机code
     * @param string $prefix
     * @return string
     */
    public static function generateVerificationCode(string $prefix = 'none')
    {
        return $prefix . '_' . 'verificationCode_' . random_string(15, 5);
    }

    /**
     * 验证码验证
     * @param string $verification_key
     * @param string $verification_code
     * @param string $type
     * @param string $remark
     * @param bool $rm
     * @return mixed
     * @throws CommonException
     */
    public static function checkCaptcha(string $verification_key, string $verification_code, string $type = '', string $remark = '', bool $rm = true)
    {
        // 获取验证码
        $verifyData = self::getCache($verification_key);
        if (empty($verifyData)) {
            throw new CommonException('captcha expired');
        }
        if (!empty($verifyData['type']) && $verifyData['type'] != $type) {
            throw new CommonException('captcha type error');
        }
        if (!empty($verifyData['remark']) && $verifyData['remark'] != $remark) {
            throw new CommonException('captcha remark error');
        }
        // 防止时序攻击
        if (!hash_equals($verifyData['captcha'], $verification_code)) {
            throw new CommonException('captcha error');
        }

        if ($rm) {
            self::rmCache($verification_key);
        }

        return $verifyData;
    }

    /**
     * 设置缓存
     * @param string $key
     * @param string $captcha
     * @param int $expiredAt
     * @param string $type
     * @param string $remark
     * @return bool
     * @throws SystemErrorException
     */
    public static function setCache(string $key, string $captcha, int $expiredAt = 300, string $type = '', string $remark = '')
    {
        if (!Cache::set($key, compact('captcha', 'type', 'remark'), $expiredAt)) {
            throw new SystemErrorException('cache fail');
        }
        return true;
    }

    /**
     * 获取缓存
     * @param string $key
     * @return mixed
     */
    public static function getCache(string $key)
    {
        return Cache::get($key, []);
    }

    /**
     * 删除缓存
     * @param string $key
     * @return bool
     */
    public static function rmCache(string $key)
    {
        return Cache::delete($key);
    }
}