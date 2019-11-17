<?php

namespace app\home\controllers;

use app\common\controllers\IndexBaseController;
use app\common\exceptions\DataValidateException;

class IndexController extends IndexBaseController
{
    /**
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['name']);

        $this->validate($params, [
            'name' => 'require',
            'name2' => 'require'
        ]);

        return $this->successResponse('成功', $params);
    }

}