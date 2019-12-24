<?php

namespace app\home\controllers\v1;

use app\common\controllers\HomeBaseController;
use app\common\facades\HomeAuth;

class UserController extends HomeBaseController
{
    public function userInfo()
    {
        return $this->successResponse('success', HomeAuth::user());
    }
}