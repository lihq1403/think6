<?php

namespace app\common\exceptions;

use think\Exception;
use Throwable;

class DataValidateException extends Exception
{
    public function __construct($message = "error params", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}