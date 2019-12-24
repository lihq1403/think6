<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\exceptions\DataValidateException;
use app\common\libs\PageLib;
use app\common\models\MediaCategory;
use app\common\models\MediaCategoryPivot;

/**
 * 媒体资源分类管理
 * Class MediaCategoryController
 * @package app\admin\controllers
 */
class MediaCategoryController extends AdminBaseController
{
    /**
     * 媒体资源分类列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['page', 'page_rows', 'sort_type', 'pid', 'search']);

        $pagination = pagination($params);
        $pageLib = new PageLib(new MediaCategory());
        $fields = ['id', 'name', 'pid', 'sort', 'is_show', 'create_time', 'update_time'];

        $map = [];
        if (!empty($params['pid'])) {
            $map[] = ['pid', '=', $params['pid']];
        } else {
            $map[] = ['pid', '=', 0];
        }
        if (!empty($params['search'])) {
            $map[] = ['name', 'like', '%'.$params['search'].'%'];
        }

        $order = 'create_time desc';
        if (!empty($params['sort_type'])) {
            switch ($params['sort_type']) {
                case 'sort_asc':
                    $order = 'sort asc, create_time desc';
                    break;
                case 'sort_desc':
                    $order = 'sort desc, create_time desc';
                    break;
            }
        }

        return $this->successResponse('success', $pageLib->where($map)->setFields($fields)->order($order)->page($pagination['page'])->pageRows($pagination['page_rows'])->result());
    }

    /**
     * 媒体资源分类列表 - 树形结构
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function treeIndex()
    {
        $params = $this->apiParams(['pid']);
        // 数据验证
        $this->validate($params, [
            'pid' => 'integer',
        ]);
        if (empty($params['pid'])) {
            $params['pid'] = 0;
        }

        $fields = ['id', 'name', 'pid'];
        $list = MediaCategory::field($fields)->select()->toArray();

        if (!empty($list)) {
            $list = array_to_tree($list, $params['pid'], 'id', 'pid', 'child');
        }
        return $this->successResponse('success', $list);
    }

    /**
     * 资源分类新增
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function store()
    {
        $params = $this->apiParams(['name', 'pid', 'sort', 'is_show']);

        $this->validate($params, [
            'name' => 'require|max:255',
            'pid' => 'require|integer',
            'sort' => 'integer',
            'is_show' => 'in:1,2',
        ]);

        // 默认值
        $params['sort'] = $params['sort'] ?? 0;
        $params['is_show'] = $params['is_show'] ?? 1;

        return $this->successResponse('success', MediaCategory::create($params));
    }

    /**
     * 资源分类修改
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function update()
    {
        $params = $this->apiParams(['id', 'name', 'sort', 'is_show', 'pid']);

        if (count($params) <= 1) {
            throw new DataValidateException('no modification');
        }

        // 数据验证
        $this->validate($params, [
            'id' => 'require|integer',
            'name' => 'max:255',
            'pid' => 'integer',
            'sort' => 'integer',
            'is_show' => 'in:1,2',
        ]);

        return $this->successResponse('success', MediaCategory::update($params));
    }

    /**
     * 资源分类删除 - 支持批量
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     */
    public function destroy()
    {
        $params = $this->apiParams(['id']);

        // 数据验证
        $this->validate($params, [
            'id' => 'require|array'
        ]);

        // 获取是否具有下级
        if (MediaCategory::whereIn('pid', $params['id'])->count()) {
            throw new DataValidateException('please delete the child category');
        }

        self::beginTrans();

        // 删除自己
        MediaCategory::destroy($params['id']);
        // 删除媒体资源分类
        MediaCategoryPivot::destroy(function ($query) use ($params) {
            $query->whereIn('media_category_id', $params['id']);
        });

        self::commitTrans();

        return $this->successResponse('success');
    }
}