<?php

namespace app\common\libs;


use app\common\exceptions\SystemErrorException;

class OssPolicyLib
{
    private static $instance;

    protected static $oss_config = [];

    public static function instance()
    {
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * AliOssHelper constructor.
     * @throws SystemErrorException
     */
    private function __construct()
    {
        $disk_config = config('filesystem.disks.aliyun');

        self::$oss_config = [
            'id' => $disk_config['accessId'],
            'key' => $disk_config['accessSecret'],
            'host' => $disk_config['url'],
            'dir' => $disk_config['dir'],
            'bucket_name' => $disk_config['bucket'],
            'end_point' => $disk_config['endpoint'],
        ];
        if (count(array_filter(self::$oss_config)) != count(self::$oss_config)) {
            throw new SystemErrorException('oss配置错误');
        }
    }

    private function __clone()
    {
    }

    public function init(int $expire = 30)
    {
        $id = self::$oss_config['id'];
        $key = self::$oss_config['key'];
        $host = self::$oss_config['host'];

        $now = time();
//        $expire = 30;
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        $dir = self::$oss_config['dir'].date('Ym').'/'.date('d').'/';

        //最大文件大小.用户可以自己设置
        $condition = [
            'content-length-range', 0, 1048576000
        ];
        $conditions[] = $condition;

        $start = ['starts-with', '$key', $dir];
        $conditions[] = $start;

        $arr = [
            'expiration' => $expiration,
            'conditions' => $conditions
        ];

        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $signature = $this->generateSignature($base64_policy, $key);

        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;

        return $response;
    }

    /**
     * 生成签名
     * @param $base64_policy
     * @param $accessKeySecret
     * @return string
     */
    protected function generateSignature($base64_policy, $accessKeySecret)
    {
        return base64_encode(hash_hmac('sha1', $base64_policy, $accessKeySecret, true));
    }

    /**
     * 生成时间格式
     * @param $time
     * @return string
     */
    protected function gmt_iso8601($time)
    {
        $dStr = date('Y-m-d H:i:s', $time);
        $expiration = str_replace(" ","T",$dStr);
        return $expiration.".000Z";
    }
}