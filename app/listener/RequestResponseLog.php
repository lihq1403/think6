<?php
declare (strict_types = 1);

namespace app\listener;

class RequestResponseLog
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        \app\common\models\RequestResponseLog::saveLog($event['uuid'], [], $event);
        return true;
    }    
}
