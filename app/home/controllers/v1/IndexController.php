<?php

namespace app\home\controllers\v1;

use app\common\controllers\HomeBaseController;
use app\common\exceptions\DataValidateException;

class IndexController extends HomeBaseController
{
    /**
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['name'], ['name']);

        $this->validate($params, [
            'name' => 'require'
        ]);

        return $this->successResponse('success', $params);
    }

}