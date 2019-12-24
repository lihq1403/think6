<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\repositories\UserRepository;
use think\Request;
use think\Response;

class CheckAuthHome
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $version = $request->param('version', 'v1');

        // 后期可能会需要版本区分，现在没有用
        switch ($version) {
            case 'v1':
                $scene = UserRepository::instance()->getLoginScene();
                break;
            default:
                $scene = UserRepository::instance()->getLoginScene();
        }

        try {
            $user_info = app('jwt_tool')->setScene($scene)->authenticate();

            // 记录登录信息
            app('global_params')->setGlobal(UserRepository::instance()->getLoginGlobalName(), $user_info->uid);
        } catch (\Exception $e) {

        }

        return $next($request);
    }
}
