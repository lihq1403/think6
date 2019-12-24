<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\libs\PageLib;
use app\common\models\Media;
use app\common\models\MediaCategoryPivot;
use app\common\repositories\MediaRepository;

/**
 * 资源管理
 * Class MediaController
 * @package app\admin\controllers
 */
class MediaController extends AdminBaseController
{
    /**
     * 资源列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function index()
    {
        $params = $this->apiParams(['page', 'page_rows', 'category_id']);

        $pagination = pagination($params);
        $pageLib = new PageLib(new Media());
        $map = [];
        $with = [
            'category' => function ($query) {
                $query->field(['name', 'is_show', 'sort']);
            }
        ];
        $fields = [];
        $order = 'create_time desc';

        if (!empty($params['category_id'])) {
            // 先获取当前分类的所有下级分类
            $all_category_id = MediaRepository::instance()->getAllChildIdByPid([(int)$params['category_id']]);

            // 取出分类下的所有资源id
            $media_ids = MediaCategoryPivot::whereIn('media_category_id', $all_category_id)->column('media_id');
            // 获取分类下的所有资源id
            $map[] = ['id', 'in', $media_ids];
        }

        return $this->successResponse('success', $pageLib->where($map)->page($pagination['page'])->pageRows($pagination['page_rows'])->with($with)->setFields($fields)->order($order)->result());
    }

    /**
     * 资源修改
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function update()
    {
        $params = $this->apiParams(['id', 'title', 'md5', 'sha1', 'category_id']);

        $this->validate($params, [
            'id' => 'require',
            'title' => 'max:255',
            'category_id' => 'array',
            'md5' => 'max:32',
            'sha1' => 'max:40'
        ]);

        self::beginTrans();

        $media = Media::update($params);

        if (isset($params['category_id'])) {
            // 先删除原来的分类
            MediaCategoryPivot::destroy(function ($query) use ($params) {
                $query->where('media_id', '=', $params['id']);
            });
            if (!empty($params['category_id'])) {
                // 新增分类
                $media->category()->saveAll($params['category_id']);
            }
        }

        self::commitTrans();

        // 保存图片信息
        return $this->successResponse('success', $media);
    }

    /**
     * 资源删除
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \app\common\exceptions\DataValidateException
     */
    public function destroy()
    {
        $params = $this->apiParams(['id']);

        $this->validate($params, [
            'id' => 'require|array'
        ]);

        Media::destroy($params['id']);

        return $this->successResponse('success');
    }
}