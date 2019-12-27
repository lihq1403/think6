<?php


namespace app\common\models;

class OpenApp extends BaseModel
{
    /**
     * 生成secret
     * @return string
     */
    public static function generateAppSecret()
    {
        while (1) {
            $secret = md5(random_string(128, 6));
            if (!self::where('secret', $secret)->value('id')) {
                break;
            }
        }
        return $secret;
    }
}