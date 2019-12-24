<?php

namespace app\common\models;

/**
 * Class AdminUser
 * @package app\common\models
 */
class AdminUser extends BaseModel
{
    protected $hidden = [
        'delete_time', 'password'
    ];
}