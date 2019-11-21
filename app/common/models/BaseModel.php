<?php

namespace app\common\models;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = 'int';

    use SoftDelete;

    protected $hidden = [
        'delete_time'
    ];

}