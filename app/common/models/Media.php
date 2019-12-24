<?php

namespace app\common\models;

use app\common\repositories\MediaRepository;

class Media extends BaseModel
{
    public $hidden = [
        'delete_time', 'md5', 'sha1', 'update_time', 'create_time', 'status', 'type'
    ];

    public function category()
    {
        return $this->belongsToMany(MediaCategory::class, MediaCategoryPivot::class, 'media_category_id', 'media_id');
    }

    /**
     * 根据不同类型获取文件路径
     * @param $file_url
     * @param $data
     * @return string
     */
    public function getFileUrlAttr($file_url, $data)
    {
        return MediaRepository::instance()->getDomain($data['save_way']) . '/'. $data['file_path'];
    }
}