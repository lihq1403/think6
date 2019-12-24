<?php

namespace app\common\models;

use think\model\Pivot;

class MediaCategoryPivot extends Pivot
{
    public $autoWriteTimestamp = true;

    protected $hidden = [
        'delete_time'
    ];
}