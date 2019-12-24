<?php

namespace app\common\tools;

use app\common\exceptions\CommonException;
use app\common\exceptions\UnauthorizedHttpException;
use app\common\repositories\UserRepository;

class HomeAuthTool
{
    /**
     * @var array|\think\Model|null
     */
    protected $user = null;

    /**
     * HomeAuthTool constructor.
     * @throws CommonException
     * @throws UnauthorizedHttpException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function __construct()
    {
        if (empty($this->user)) {
            $this->user = UserRepository::instance()->getInfoByFiled('id', app('global_params')->getGlobal(UserRepository::instance()->getLoginGlobalName(), 0));
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

    /**
     * @return mixed
     */
    public function phone()
    {
        return $this->user->getAttr('phone');
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->user->getAttr('email');
    }
}