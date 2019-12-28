<?php

namespace app\common\models;

class LoginLog extends BaseModel
{
    public $auto = [
        'ip'
    ];

    public function setIpAttr($ip)
    {
        if (empty($ip)) {
            return 0;
        }
        return ip2long($ip);
    }

    public function getIpAttr($ip)
    {
        if (empty($ip)) {
            return '';
        }
        return long2ip($ip);
    }
}