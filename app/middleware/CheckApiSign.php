<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\exceptions\IllegalRequestException;
use app\common\libs\ApiSignLib;
use think\Request;

class CheckApiSign
{
    /**
     * 接口签名检测
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws IllegalRequestException
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!ApiSignLib::isCheckSign()) {
            return $next($request);
        }

        $header_sign = $request->header('sign');
        if (empty($header_sign)) {
            throw new IllegalRequestException('illegal request, sign require');
        }
        $app_type = $request->header('apptype');
        if (empty($app_type)) {
            throw new IllegalRequestException('illegal request, apptype require');
        }
        $timestamp = (int)$request->header('timestamp');
        if (empty($timestamp)) {
            throw new IllegalRequestException('illegal request, timestamp require');
        }

        // 时效性检测
        ApiSignLib::checkTimeStamp($timestamp);

        $random_str = $request->header('randomstr');
        if (empty($random_str)) {
            throw new IllegalRequestException('illegal request, randomstr require');
        }

        // 检测app_type
        $key = ApiSignLib::getAppTypeKey($app_type);
        if (empty($key)) {
            throw new IllegalRequestException('illegal request, invalid apptype');
        }

        // 生成签名
        $sign = ApiSignLib::createSign($timestamp, $random_str, $key);
        if ($sign != $header_sign) {
            throw new IllegalRequestException('illegal request, invalid sign');
        }

        return $next($request);
    }
}
