<?php

namespace app\common\models;

use app\common\repositories\MediaRepository;

class User extends BaseModel
{
    const PASSWORD_VALIDATE = 'min:6|max:16';

    protected $hidden = [
        'delete_time', 'password'
    ];

    /**
     * @param $avatar
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAvatarAttr($avatar)
    {
        return MediaRepository::instance()->getUrl($avatar);
    }

    /**
     * @param $value
     * @return false|int
     */
    public function setBirthdayAttr($value)
    {
        if (is_time($value)) {
            return strtotime($value);
        }
        return 0;
    }

    /**
     * @param $value
     * @return false|string
     */
    public function getBirthdayAttr($value)
    {
        return default_time_format($value, 'Y-m-d');
    }

    /**
     * @param $value
     * @return string
     */
    public function setNicknameAttr($value)
    {
        return emoji_encode($value);
    }

    /**
     * @param $value
     * @return string|string[]|null
     */
    public function getNicknameAttr($value)
    {
        return emoji_decode($value);
    }

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