<?php

namespace app\common\repositories;

use app\common\exceptions\DataValidateException;
use app\common\exceptions\SystemErrorException;
use app\common\models\Media;
use app\common\traits\SingletonTrait;
use think\facade\Filesystem;
use think\file\UploadedFile;

class MediaRepository
{
    use SingletonTrait;

    /**
     * 检查文件hash散列值
     * @param UploadedFile $file
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function historyFileHash(UploadedFile $file)
    {
        $map = [
            'md5' => $file->md5(),
            'sha1' => $file->sha1()
        ];
        $info = Media::where($map)->order('create_time', 'desc')->find();
        if (empty($info)) {
            return false;
        }
        return $info->toArray();
    }

    /**
     * 文件上传验证
     * @param UploadedFile $file
     * @param array $validate
     * @return bool
     * @throws DataValidateException
     * @throws SystemErrorException
     */
    public function fileValidate(UploadedFile $file, $validate = [])
    {
        // 检查服务器上传配置
        $is_open = get_cfg_var('file_uploads') ?? 'on';
        if (!in_array($is_open, [1, 'on'])) {
            throw new SystemErrorException('upload not allowed on current server');
        }
        // 检查是否超过最大上限
        $upload_max_filesize = rtrim(get_cfg_var("upload_max_filesize"), 'M') * 1024 * 1024;
        $max_size = max($upload_max_filesize, $validate['size'] ?? 0);
        if ($file->getSize() > $max_size) {
            throw new DataValidateException('file size is too large');
        }

        // 文件后缀验证
        if (!empty($validate['ext'])) {
            $allow_ext = explode(',', $validate['ext']);
            if (!in_array($file->getOriginalExtension(), $allow_ext)) {
                throw new DataValidateException('file format not allowed');
            }
        }

        return true;
    }

    public function mediaUpload(UploadedFile $file, string $path = '', string $disk = 'public')
    {
        // 保存文件
        $save_path = Filesystem::disk($disk)->putFile($path, $file);
        app()->getBasePath();
        // 记录数据库
        $data = [
            'title' => $file->getOriginalName(),
            'file_type' => $file->getOriginalMime(),
            'file_size' => $file->getSize(),
            'file_url' => $save_path,
            'file_path' => $save_path,
            'md5' => $file->md5(),
            'sha1' => $file->sha1(),
            'save_way' => $disk
        ];

        halt($data);
    }

}