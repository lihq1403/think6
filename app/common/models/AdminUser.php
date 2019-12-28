<?php

namespace app\common\models;

use Lihq1403\ThinkRbac\traits\RBACUser;

/**
 * Class AdminUser
 * @package app\common\models
 */
class AdminUser extends BaseModel
{
    protected $hidden = [
        'delete_time', 'password'
    ];

    /**
     * 超级管理员id
     */
    const SUPER_ADMINISTRATOR_ID = 1;

    use RBACUser;

    public function getLastLoginTimeAttr($time)
    {
        return default_time_format($time);
    }

    public function setLastLoginIpAttr($ip)
    {
        if (empty($ip)) {
            return 0;
        }
        return ip2long($ip);
    }

    public function getLastLoginIpAttr($ip)
    {
        if (empty($ip)) {
            return '';
        }
        return long2ip($ip);
    }
}