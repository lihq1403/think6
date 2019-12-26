<?php

namespace app\middleware;

use app\common\facades\AdminAuth;
use app\common\models\AdminUser;
use Lihq1403\ThinkRbac\exception\ForbiddenException;
use Lihq1403\ThinkRbac\facade\RBAC;
use think\Request;

class AdminUserRbac
{
    /**
     * rbac中间件
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ForbiddenException
     */
    public function handle(Request $request, \Closure $next)
    {
        $uid = AdminAuth::uid();

        // 超级管理员不需要进行权限控制
        if ($uid !== AdminUser::SUPER_ADMINISTRATOR_ID) {
            // 检查权限
            try {
                RBAC::can($uid);
            } catch (\Lihq1403\ThinkRbac\exception\ForbiddenException $exception){
                throw new ForbiddenException($exception->getMessage());
            }
        }

        // 记录日志
        RBAC::log($uid);

        return $next($request);
    }
}