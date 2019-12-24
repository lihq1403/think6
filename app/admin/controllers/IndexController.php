<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\facades\AdminAuth;

class IndexController extends AdminBaseController
{
    public function index()
    {
        return $this->successResponse('admin-index-index');
    }

    public function userInfo()
    {
        return $this->successResponse('success', AdminAuth::user());
    }
}