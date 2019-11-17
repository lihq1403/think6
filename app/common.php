<?php
// 应用公共文件

/**
 * 数组递归格式化
 * @param $array
 * @param string|array $function
 * @return mixed
 */
function array_map_function($array, $function)
{
    foreach ($array as &$item) {
        if (is_array($item)) {
            $item = array_map_function($item, $function);
        } else {
            if (is_array($function)) {
                foreach ($function as $func) {
                    $item = $func($item);
                }
            } else {
                $item = $function($item);
            }
        }
    }
    return $array;
}