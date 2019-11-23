<?php

namespace app\common\exceptions;

use think\Exception;
use Throwable;

class IllegalRequestException extends Exception
{
    public function __construct($message = "illegal request", $code = 402, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}