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
        $params = $this->apiParams(['name'], ['name']);

        $this->validate($params, [
            'name' => 'require'
        ]);
        dump(config('jwt'));
//        halt(app()->getBasePath() . 'common/lang/zh-cn/app.php');

        $res = app('jwt_tool')->setScene('home')->jsonReturnToken(1);

        return $this->successResponse('success', $res);
    }

}