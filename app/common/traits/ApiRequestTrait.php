<?php

namespace app\common\traits;

use app\common\exceptions\DataValidateException;
use think\facade\Request;

trait ApiRequestTrait
{
    /**
     * api 接口专用参数处理
     * @param array $only
     * @param array $must
     * @param string $type
     * @param bool $trim
     * @return array|mixed
     * @throws DataValidateException
     */
    protected function apiParams(array $only = [], array $must = [], string $type = 'param', bool $trim = true)
    {
        // todo 接口安全性设置

        $params = Request::only($only, $type);

        if ($trim) {
            // 参数去空
            $params = array_map_function($params, 'trim');
        }

        if (!empty($must)) {
            foreach ($must as $item) {
                if (empty($params[$item])) {
                    throw new DataValidateException($item . '不能为空');
                }
            }
        }
        return $params;
    }
}