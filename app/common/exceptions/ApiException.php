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
            return $this->emptyResponse($e->getMessage());
        }

        // 401 未登录
        if ($e instanceof UnauthorizedHttpException){
            return $this->customCodeResponse($e->getCode(), $e->getMessage());
        }

        // 402 非法请求
        if ($e instanceof IllegalRequestException) {
            return $this->customCodeResponse($e->getCode(), $e->getMessage());
        }

        // 500 系统错误
        if ($e instanceof SystemErrorException) {
            return $this->customCodeResponse($e->getCode(), $e->getMessage(), []);
        }

        // 全局异常
        if ($e instanceof \Exception) {
            return $this->customCodeResponse($e->getCode(), $e->getMessage());
        }

        return parent::render($request, $e);
    }
}