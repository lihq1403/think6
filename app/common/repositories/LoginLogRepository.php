<?php

namespace app\common\repositories;

use app\common\models\LoginLog;
use app\common\traits\SingletonTrait;
use think\facade\Request;

class LoginLogRepository
{
    use SingletonTrait;

    /**
     * 记录登录日志
     * @param int $uid
     * @param string $type
     * @return LoginLog|\think\Model
     */
    public function write(int $uid, string $type)
    {
        $ip = get_client_ip();
        $data = [
            'type' => $type,
            'uid' => $uid,
            'ip' => $ip,
            'ip_show' => $ip,
            'agent' => \request()->header('user-agent') ?? '',
        ];
        return LoginLog::create($data);
    }
}