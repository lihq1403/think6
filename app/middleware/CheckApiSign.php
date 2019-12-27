<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\exceptions\IllegalRequestException;
use app\common\libs\ApiSignLib;
use app\common\repositories\OpenAppRepository;
use think\Request;

class CheckApiSign
{
    /**
     * 接口签名检测
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws IllegalRequestException
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \app\common\exceptions\CommonException
     * @throws \app\common\exceptions\SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(Request $request, \Closure $next)
    {
        $appid = $request->header('appid');
        if (empty($appid)) {
            throw new IllegalRequestException('illegal request, appid require');
        }

        // 获取app信息
        $openAppInfo = OpenAppRepository::instance()->getAppInfoByAppid($appid);
        // 全局存起来
        app('global_params')->setGlobal('login_open_app_info', $openAppInfo);
        if (!ApiSignLib::isCheckSign()) {
            return $next($request);
        }
        $header_sign = $request->header('sign');
        if (empty($header_sign)) {
            throw new IllegalRequestException('illegal request, sign require');
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
        $key = $openAppInfo['secret'];
        if (empty($key)) {
            throw new IllegalRequestException('illegal request, invalid appid');
        }

        // 生成签名
        $sign = ApiSignLib::createSign($timestamp, $random_str, $key);
        if ($sign != $header_sign) {
            throw new IllegalRequestException('illegal request, invalid sign');
        }

        return $next($request);
    }
}
