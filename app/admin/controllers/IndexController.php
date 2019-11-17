<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;

class IndexController extends AdminBaseController
{
    public function index()
    {
        return $this->successResponse('admin-index-index');
    }
}