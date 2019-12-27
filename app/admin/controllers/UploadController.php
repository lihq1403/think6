<?php

namespace app\admin\controllers;

use app\common\controllers\AdminBaseController;
use app\common\exceptions\DataValidateException;
use app\common\libs\CosStsLib;
use app\common\libs\OssPolicyLib;
use app\common\models\Media;
use app\common\repositories\MediaRepository;
use think\facade\Cache;
use think\Request;

/**
 * 上传相关接口
 * Class UploadController
 * @package app\admin\controllers
 */
class UploadController extends AdminBaseController
{
    /**
     * 本地上传
     * @param Request $request
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \app\common\exceptions\SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function local(Request $request)
    {
        $files = $request->file();

        if (empty($files)) {
            throw new DataValidateException('files require');
        }

        $save_name = [];
        foreach ($files as $name => $file) {
            if (empty($file)) {
                $save_name[$name] = [];
                continue;
            }

            // 如果历史记录有相同文件，则直接返回就好了
            if ($history_file = MediaRepository::instance()->historyFileHash($file)) {
                $save_name[$name] = $history_file;
                continue;
            }

            // 验证文件规则
            MediaRepository::instance()->fileValidate($file);

            // 上传并保存记录
            $file_info = MediaRepository::instance()->mediaUpload($file, '', 'public');

            $save_name[$name] = $file_info;
        }

        return $this->successResponse('upload success', $save_name);
    }

    /**
     * 腾讯云cos保存
     * @param Request $request
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \app\common\exceptions\SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function qCloud(Request $request)
    {
        $files = $request->file();

        if (empty($files)) {
            throw new DataValidateException('files require');
        }

        $save_name = [];
        foreach ($files as $name => $file) {
            if (empty($file)) {
                $save_name[$name] = [];
                continue;
            }

            // 如果历史记录有相同文件，则直接返回就好了
            if ($history_file = MediaRepository::instance()->historyFileHash($file)) {
                $save_name[$name] = $history_file;
                continue;
            }

            // 验证文件规则
            MediaRepository::instance()->fileValidate($file);

            // 上传并保存记录
            $file_info = MediaRepository::instance()->mediaUpload($file, '', 'qcloud');

            $save_name[$name] = $file_info;
        }

        return $this->successResponse('upload success', $save_name);
    }

    /**
     * 阿里云oss保存
     * @param Request $request
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \app\common\exceptions\SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function aLiYun(Request $request)
    {
        $files = $request->file();

        if (empty($files)) {
            throw new DataValidateException('files require');
        }

        $save_name = [];
        foreach ($files as $name => $file) {
            if (empty($file)) {
                $save_name[$name] = [];
                continue;
            }

            // 如果历史记录有相同文件，则直接返回就好了
            if ($history_file = MediaRepository::instance()->historyFileHash($file)) {
                $save_name[$name] = $history_file;
                continue;
            }

            // 验证文件规则
            MediaRepository::instance()->fileValidate($file);

            // 上传并保存记录
            $file_info = MediaRepository::instance()->mediaUpload($file, rtrim(MediaRepository::instance()->getPath('aliyun'), '/'), 'aliyun');

            $save_name[$name] = $file_info;
        }

        return $this->successResponse('upload success', $save_name);
    }

    /**
     * 获取临时密钥
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \Exception
     */
    public function getQCloudTempKeys()
    {
        // 有效期
        $durationSeconds = 1800;

        if (!$tempKeys = Cache::get('cos_temp_keys')) {
            $tempKeys = CosStsLib::getTempKeys($durationSeconds);

            // 防止请求过多，缓存起来
            Cache::set('cos_temp_keys', $tempKeys, $durationSeconds - 200);
        }

        return $this->successResponse('success', $tempKeys);
    }

    /**
     * 阿里云策略直传
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function getALiYunPolicy()
    {
        // https://help.aliyun.com/document_detail/31988.html

        // 有效期
        $durationSeconds = 1800;

        if (!$tempKeys = Cache::get('oss_temp_keys')) {
            $tempKeys = OssPolicyLib::instance()->init($durationSeconds);

            // 防止请求过多，缓存起来
            Cache::set('oss_temp_keys', $tempKeys, $durationSeconds - 200);
        }

        return $this->successResponse('success', $tempKeys);
    }

    /**
     * 通过文件hash值获取信息
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfoByHash()
    {
        $params = $this->apiParams(['md5', 'sha1'], ['md5']);

        return $this->successResponse('success', MediaRepository::instance()->getInfoByHash($params['md5'], $params['sha1'] ?? ''));
    }

    /**
     * 前端传值保存资源信息
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function info()
    {
        $params = $this->apiParams(['title', 'file_type', 'file_size', 'file_path', 'save_way', 'md5', 'sha1', 'category_id']);

        $this->validate($params, [
            'title' => 'require|max:255',
            'file_type' => 'max:255',
            'file_size' => 'max:255|number',
            'file_path' => 'require|max:255',
            'save_way' => 'require|max:255',
            'category_id' => 'array',
            'md5' => 'max:32',
            'sha1' => 'max:40'
        ]);

        if (!empty($params['md5'])) {
            // 进行验证
            $info = MediaRepository::instance()->getInfoByHash($params['md5'], $params['sha1'] ?? '');
            if (!empty($info)) {
                return $this->successResponse('success', $info);
            }
        }

        // 生成链接
        $params['file_url'] = MediaRepository::instance()->getDomain($params['save_way']) . '/' . $params['file_path'];

        self::beginTrans();

        $media = Media::create($params);

        if (!empty($params['category_id'])) {
            // 新增分类
            $media->category()->saveAll($params['category_id']);
        }

        self::commitTrans();

        // 保存图片信息
        return $this->successResponse('success', $media);
    }
}