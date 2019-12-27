<?php

namespace app\common\models;

use think\facade\Request;

class OpenAppUseLog extends BaseModel
{
    public $json = [
        'header', 'input', 'output'
    ];

    public $auto = [
        'ip'
    ];

    public function openApp()
    {
        return $this->belongsTo(OpenApp::class, 'open_app_id', 'id');
    }

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

    /**
     * 添加日志记录
     * @param int $open_app_id
     * @return OpenAppUseLog|\think\Model
     */
    public static function toWrite(int $open_app_id, array $output)
    {
        $data = [
            'open_app_id' => $open_app_id,
            'method' => strtoupper(Request::method()),
            'path' => Request::pathinfo(),
            'ip' => get_client_ip(),
            'header' => Request::header(),
            'input' => Request::param(),
            'output' => $output,
        ];

        return self::create($data);
    }
}