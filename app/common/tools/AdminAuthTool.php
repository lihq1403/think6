<?php

namespace app\common\tools;

use app\common\exceptions\CommonException;
use app\common\exceptions\UnauthorizedHttpException;
use app\common\repositories\AdminUserRepository;

class AdminAuthTool
{
    protected $user = null;

    /**
     * AdminAuthTool constructor.
     * @throws CommonException
     * @throws UnauthorizedHttpException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function __construct()
    {
        if (empty($this->user)) {
            $this->user = AdminUserRepository::instance()->getInfoByFiled('id', app('global_params')->getGlobal('admin_login_uid', 0));
            if (!$this->user) {
                throw new UnauthorizedHttpException();
            }
        }
        if ($this->user()->status != 1) {
            throw new CommonException('account is disabled', 471);
        }
    }

    /**
     * @return array|\think\Model|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function uid()
    {
        return $this->user->getAttr('id');
    }
}