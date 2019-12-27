<?php
declare (strict_types = 1);

namespace app\middleware;

class OpenAppUseLog
{
    /**
     * 接口请求日志
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        $output = $response->getData();

        \app\common\models\OpenAppUseLog::toWrite(app('global_params')->getGlobal('login_open_app_info')['id'], $output);

        return $response;
    }
}
