<?php

namespace app\common\controllers;

use app\BaseController;
use app\common\traits\ApiRequestTrait;
use app\common\traits\ApiResponseTrait;

class CommonBaseController extends BaseController
{
    use ApiRequestTrait;
    use ApiResponseTrait;

    /**
     * 访问不存在的action
     * @param $method
     * @param $args
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function __call($method, $args)
    {
        return $this->emptyResponse('no found action');
    }
}