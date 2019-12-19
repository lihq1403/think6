<?php

namespace app\openapi\controllers\v1;

use app\common\controllers\OpenApiBaseController;

class IndexController extends OpenApiBaseController
{
    public function index()
    {
        return $this->successResponse('获取成功');
    }
}