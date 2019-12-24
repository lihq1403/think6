<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\exceptions\UnauthorizedHttpException;
use app\common\repositories\UserRepository;
use think\Request;

class AuthHome
{
    /**
     * 前台登录中间件
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws UnauthorizedHttpException
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
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException();
        }

        // 记录登录信息
        app('global_params')->setGlobal(UserRepository::instance()->getLoginGlobalName(), $user_info->uid);

        return $next($request);
    }
}
