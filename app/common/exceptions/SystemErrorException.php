<?php
/**
 * Created by PhpStorm.
 * User: author lihq1403 <lihaiqing1994@163.com>
 * Date: 2018/10/12
 * Time: 14:15
 */
namespace app\common\exceptions;


use think\Exception;
use Throwable;

class SystemErrorException extends Exception
{
    public function __construct($message = "system error", $code = 501, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}