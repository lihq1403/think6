<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\libs\PageLib;
use app\common\models\OpenApp;

class OpenAppController extends AdminBaseController
{
    /**
     * 开放商户添加
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function store()
    {
        $params = $this->apiParams(['appid', 'name', 'status']);

        $this->validate($params, [
            'appid|商户id' => 'require|max:255|unique:app\common\models\OpenApp,appid',
            'name|商户名称' => 'require|max:255|unique:app\common\models\OpenApp,name',
            'status|状态' => 'in:1,2'
        ]);

        // 生成唯一密钥
        $params['secret'] = OpenApp::generateAppSecret();

        $params['status'] = $params['status'] ?? 1;

        return $this->successResponse('添加成功', OpenApp::create($params));
    }

    /**
     * 开放商户编辑
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function update()
    {
        $params = $this->apiParams(['id', 'appid', 'name', 'status'], ['id']);

        $this->validate($params, [
            'appid|商户id' => 'require|max:255|unique:app\common\models\OpenApp,appid,'.$params['id'],
            'name|商户名称' => 'require|max:255|unique:app\common\models\OpenApp,name,'.$params['id'],
            'status|状态' => 'in:1,2'
        ]);

        return $this->successResponse('编辑成功', OpenApp::update($params));
    }

    /**
     * 开放商户列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['page', 'page_rows', 'search', 'status']);

        $pagination = pagination($params);
        $pageLib = new PageLib(new OpenApp());
        $map = [];
        if (!empty($params['search'])) {
            $map[] = ['appid|name', 'like', '%'.$params['search'].'%'];
        }
        if (!empty($params['status'])) {
            $map[] = ['status', '=', $params['status']];
        }
        $order = 'id desc';
        $fields = ['id', 'appid', 'secret', 'name', 'status', 'create_time'];

        return $this->successResponse('获取成功', $pageLib->where($map)->order($order)->setFields($fields)->page($pagination['page'])->pageRows($pagination['page_rows'])->result());
    }

    /**
     * 开放商户删除
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function destroy()
    {
        $params = $this->apiParams(['id']);

        $this->validate($params, [
            'id' => 'require|array'
        ]);

        OpenApp::destroy($params['id']);

        return $this->successResponse('删除成功');
    }

    /**
     * 开放商户修改状态
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function changeStatus()
    {
        $params = $this->apiParams(['id', 'status']);

        $this->validate($params, [
            'id' => 'require|array',
            'status|状态' => 'require|in:1,2'
        ]);

        OpenApp::whereIn('id', $params['id'])->update(['status' => $params['status']]);

        return $this->successResponse('操作成功');
    }
}