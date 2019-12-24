<?php

namespace app\openapi\controllers\v1;

use app\common\controllers\OpenApiBaseController;
use app\common\exceptions\IllegalRequestException;
use app\common\libs\ApiSignLib;

class TestController extends OpenApiBaseController
{
    /**
     * 测试创建签名
     * @return \think\response\Json|\think\response\Jsonp
     * @throws IllegalRequestException
     * @throws \app\common\exceptions\DataValidateException
     */
    public function storeSign()
    {
        $params = $this->apiParams(['apptype', 'timestamp', 'randomstr'], ['apptype', 'timestamp', 'randomstr']);

        $timestamp = $params['timestamp'];

        // 检测app_type
        $key = ApiSignLib::getAppTypeKey($params['apptype']);
        if (empty($key)) {
            throw new IllegalRequestException('invalid apptype');
        }

        // 生成签名
        $params['sign'] = ApiSignLib::createSign($timestamp, $params['randomstr'], $key);

        return $this->successResponse('生成成功', $params);
    }
}