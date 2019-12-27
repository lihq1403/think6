<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\exceptions\UnauthorizedHttpException;
use app\common\models\AdminUser;
use app\common\repositories\AdminUserRepository;
use app\common\repositories\UserRepository;
use think\Request;
use think\Response;

class AuthAdmin
{
    /**
     * 后台登录中间件
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $admin_user_info = app('jwt_tool')->setScene(AdminUserRepository::instance()->getLoginScene())->authenticate();
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException();
        }

        // 记录登录信息
        app('global_params')->setGlobal(AdminUserRepository::instance()->getLoginGlobalName(), $admin_user_info->uid);
//        app('global_params')->setGlobal(AdminUserRepository::instance()->getLoginGlobalName(), 1);

        return $next($request);
    }
}
