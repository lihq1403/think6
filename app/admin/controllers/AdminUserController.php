<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\exceptions\CommonException;
use app\common\exceptions\DataValidateException;
use app\common\libs\PageLib;
use app\common\models\AdminUser;
use app\common\repositories\AdminUserRepository;

class AdminUserController extends AdminBaseController
{
    /**
     * 管理员添加
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function store()
    {
        $params = $this->apiParams(['username', 'password', 'status']);

        $this->validate($params, [
            'username|用户名' => 'require|max:255|unique:app\common\models\AdminUser,username',
            'password|密码' => 'require|'.AdminUserRepository::instance()->getPasswordValidate(),
            'status|状态' => 'in:1,2'
        ]);

        $params['status'] = $params['status'] ?? 1;
        $params['password'] = password_encrypt($params['password']);

        return $this->successResponse('添加成功', AdminUser::create($params));
    }

    /**
     * 管理员编辑
     * @return \think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     */
    public function update()
    {
        $params = $this->apiParams(['id', 'username', 'password', 'status'], ['id']);

        if (count($params) < 2) {
            throw new DataValidateException('无修改');
        }

        if ($params['id'] == AdminUser::SUPER_ADMINISTRATOR_ID) {
            throw new CommonException('无权修改');
        }

        $this->validate($params, [
            'username|用户名' => 'max:255|unique:app\common\models\AdminUser,username,'.$params['id'],
            'password|密码' => AdminUserRepository::instance()->getPasswordValidate(),
            'status|状态' => 'in:1,2'
        ]);

        if (!empty($params['password'])) {
            $params['password'] = password_encrypt($params['password']);
        }

        return $this->successResponse('编辑成功', AdminUser::update($params));
    }

    /**
     * 管理员列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['page', 'page_rows', 'status', 'search']);

        $pagination = pagination($params);
        $pageLib = new PageLib(new AdminUser());
        $map = [];
        if (!empty($params['status'])) {
            $map[] = ['status', '=', $params['status']];
        }
        if (!empty($params['search'])) {
            $map[] = ['username', 'like', '%'.$params['search'].'%'];
        }
        $order = 'id desc';
        $field = [];

        $res = $pageLib->where($map)->setFields($field)->order($order)->page($pagination['page'])->pageRows($pagination['page_rows'])->result();

        // 角色处理
        if (!empty($res['list'])) {
            foreach ($res['list'] as &$user_info) {
                $user_info['all_roles'] = $user_info->allRoles();
                unset($user_info->roles);
            }
            unset($user_info);
        }

        return $this->successResponse('获取成功', $res);
    }

    /**
     * 管理员删除
     * @return \think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     */
    public function destroy()
    {
        $params = $this->apiParams(['id']);

        $this->validate($params, [
            'id' => 'require|array'
        ]);

        if (in_array(AdminUser::SUPER_ADMINISTRATOR_ID, $params['id'])) {
            throw new CommonException('无权修改');
        }

        AdminUser::destroy($params['id']);

        return $this->successResponse('删除成功');
    }

    /**
     * 管理员修改状态
     * @return \think\response\Json|\think\response\Jsonp
     * @throws CommonException
     * @throws DataValidateException
     */
    public function changeStatus()
    {
        $params = $this->apiParams(['id', 'status']);

        $this->validate($params, [
            'id' => 'require|array',
            'status|状态' => 'require|in:1,2'
        ]);

        if (in_array(AdminUser::SUPER_ADMINISTRATOR_ID, $params['id'])) {
            throw new CommonException('无权修改');
        }

        AdminUser::whereIn('id', $params['id'])->update(['status' => $params['status']]);

        return $this->successResponse('操作成功');
    }
}