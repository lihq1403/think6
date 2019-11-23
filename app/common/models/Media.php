<?php

namespace app\common\models;

class Media extends BaseModel
{
    public $hidden = [
        'delete_time', 'save_way', 'md5', 'sha1', 'file_path', 'update_time', 'create_time', 'status', 'type'
    ];

    /**
     * 根据不同类型获取文件路径
     * @param $file_url
     * @param $data
     * @return string
     */
    public function getFileUrlAttr($file_url, $data)
    {
        switch ($data['save_way']) {
            case 'public':
                return '/storage/' . $file_url;
            case 'url':
                return $file_url;
            default:
                return $file_url;
        }
    }
}