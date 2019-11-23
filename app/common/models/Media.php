<?php

namespace app\common\models;

class Media extends BaseModel
{
    public $hidden = [
        'delete_time', 'save_way', 'md5', 'sha1', 'file_url', 'update_time', 'create_time', 'status', 'type'
    ];

    /**
     * 根据不同类型获取文件路径
     * @param $file_path
     * @param $data
     * @return string
     */
    public function getFilePathAttr($file_path, $data)
    {
        switch ($data['save_way']) {
            case 'public':
                return '/storage/' . $file_path;
            default:
                return $file_path;
        }
    }
}