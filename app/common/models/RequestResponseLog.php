<?php

namespace app\common\models;


use think\facade\Request;

class RequestResponseLog extends BaseModel
{

    public function setResponseDataAttr($value)
    {
        return emoji_encode($value);
    }

    /**
     * 请求响应日志记录
     * @param string $uuid
     * @param array $request
     * @param array $return
     */
    public static function saveLog(string $uuid, array $request = [], array $return = [])
    {
        $ip = get_client_ip();

        if (empty($request)) {
            $request = [
                'method' => Request::method(),
                'host' => Request::host(),
                'domain' => Request::domain(),
                'url' => Request::url(),
                'header' => Request::header(),
                'params' => Request::param(),
                'get' => Request::get(),
                'post' => Request::post(),
                'ip' => $ip,
            ];
        }

        $data = [
            'uuid' => $uuid,
            'request_data' => json_encode($request, 256),
            'response_data' => json_encode($return, 256),
            'response_code' => $return['code'] ?? 0,
            'ip' => ip2long($ip),
        ];
        self::create($data);
    }

}