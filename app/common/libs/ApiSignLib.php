<?php

namespace app\common\libs;

use app\common\exceptions\IllegalRequestException;
use think\helper\Str;

class ApiSignLib
{
    /**
     * 是否验证签名
     * @return bool
     */
    public static function isCheckSign()
    {
        if (config('api_sign.check_sign')) {
            return true;
        }
        return false;
    }

    /**
     * 获取app_type的密钥key
     * @param string $app_type
     * @return mixed
     */
    public static function getAppTypeKey(string $app_type)
    {
        return config('api_sign.app_type.'.$app_type);
    }

    /**
     * 时效性检测
     * @param int $timestamp
     * @return int
     * @throws IllegalRequestException
     */
    public static function checkTimeStamp(int $timestamp)
    {
        if (empty($timestamp) || $timestamp <= 1 || Str::length($timestamp) !== 10) {
            throw new IllegalRequestException('illegal request, invalid timestamp');
        }
        $effective_time = config('api_sign.effective_time');

        if (abs(time() - $timestamp) > $effective_time) {
            throw new IllegalRequestException('request expire');
        }

        return $timestamp;
    }

    /**
     * 创建签名
     * @param int $timestamp
     * @param string $random_str
     * @param string $key
     * @return bool|string
     */
    public static function createSign(int $timestamp, string $random_str, string $key)
    {
        // 去空
        $params = [
            'timestamp' => $timestamp,
            'randomstr' => $random_str,
            'key' => $key
        ];
        // 字典升序
        $sign = self::formatParaMap($params);
        // md5
        $sign = md5($sign);
        // sha1
        $sign = sha1($sign);
        return $sign;
    }

    /**
     * 字典升序
     * @param array $paraMap
     * @return bool|string
     */
    private static function formatParaMap(array $paraMap)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';  //去掉最后一个字符 $
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
}