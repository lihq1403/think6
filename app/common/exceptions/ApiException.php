<?php

namespace app\common\exceptions;

use app\common\traits\ApiResponseTrait;
use think\exception\Handle;
use think\exception\RouteNotFoundException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class ApiException extends Handle
{
    use ApiResponseTrait;

    public function render($request, Throwable $e): Response
    {
        // 422 错误参数
        if ($e instanceof DataValidateException || $e instanceof ValidateException) {
            return $this->errorParamResponse($e->getMessage());
        }

        // 404 路由未找到
        if ($e instanceof RouteNotFoundException) {
//            return $this->emptyResponse($e->getMessage());
        }

        return parent::render($request, $e);
    }
}