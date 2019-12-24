<?php

namespace app\common\exceptions;

use think\Exception;
use Throwable;

class CommonException extends Exception
{
    public function __construct($message = "common error", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}