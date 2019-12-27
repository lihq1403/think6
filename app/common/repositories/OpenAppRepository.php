<?php

namespace app\common\repositories;

use app\common\exceptions\CommonException;
use app\common\exceptions\SystemErrorException;
use app\common\models\OpenApp;
use app\common\traits\SingletonTrait;
use Lihq1403\ThinkRbac\exception\DataValidationException;

class OpenAppRepository
{
    use SingletonTrait;

    /**
     * @param string $appid
     * @return array
     * @throws CommonException
     * @throws DataValidationException
     * @throws SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAppInfoByAppid(string $appid)
    {
        // 数据库获取
        $map = [
            'appid' => $appid
        ];
        $openApp = OpenApp::where($map)->find();
        if (empty($openApp)) {
            throw new DataValidationException('无效appid');
        }
        if ($openApp['status'] != 1) {
            throw new CommonException('当前appid已被禁用');
        }
        if (empty($openApp['secret'])) {
            throw new SystemErrorException('系统配置错误');
        }

        return $openApp->toArray();
    }

    /**
     * 获取secret
     * @param string $appid
     * @return mixed
     * @throws CommonException
     * @throws DataValidationException
     * @throws SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAppKeyByAppid(string $appid)
    {
        return $this->getAppInfoByAppid($appid)['secret'];
    }
}