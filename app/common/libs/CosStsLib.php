<?php

namespace app\common\libs;

use app\common\exceptions\SystemErrorException;

include app()->getRootPath().'vendor/qcloud_sts/qcloud-sts-sdk/sts/sts.php';

class CosStsLib
{
    /**
     * 获取临时密钥，计算签名
     * @return array|bool|mixed|string|null
     * @throws \Exception
     */
    public static function getTempKeys(int $durationSeconds = 1800)
    {
        // 获取qcloud配置
        $qcould = config('filesystem.disks.qcloud');
        if (empty($qcould)) {
            throw new SystemErrorException('empty filesystem');
        }

        // 配置
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            //'proxy' => null,  //设置网络请求代理,若不需要设置，则为null
            'secretId' => $qcould['secretId'], // 云 API 密钥 secretId
            'secretKey' => $qcould['secretKey'], // 云 API 密钥 secretKey
            'bucket' => $qcould['bucket'] . '-' . $qcould['appId'], // 换成你的 bucket
            'region' => $qcould['region'], // 换成 bucket 所在地区
            'durationSeconds' => $durationSeconds, // 密钥有效期
            'allowPrefix' => '*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 简单上传
                'name/cos:PutObject',
                // 表单上传
                'name/cos:PostObject',
                // 分片上传： 初始化分片
                'name/cos:InitiateMultipartUpload',
                // 分片上传： 查询 bucket 中未完成分片上传的UploadId
                "name/cos:ListMultipartUploads",
                // 分片上传： 查询已上传的分片
                "name/cos:ListParts",
                // 分片上传： 上传分片块
                "name/cos:UploadPart",
                // 分片上传： 完成分片上传
                "name/cos:CompleteMultipartUpload"
            )
        );

        $sts = new \STS();

        $tempKeys = $sts->getTempKeys($config);

        return $tempKeys;
    }
}