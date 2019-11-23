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

/**
 * 对emoji表情转义
 * @param $str
 * @return string
 */
function emoji_encode($str){
    $strEncode = '';

    $length = mb_strlen($str,'utf-8');

    for ($i=0; $i < $length; $i++) {
        $_tmpStr = mb_substr($str,$i,1,'utf-8');
        if(strlen($_tmpStr) >= 4){
            $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
        }else{
            $strEncode .= $_tmpStr;
        }
    }

    return $strEncode;
}

/**
 * 对emoji表情转反义
 * @param $str
 * @return string|string[]|null
 */
function emoji_decode($str){
    $strDecode = preg_replace_callback('|\[\[EMOJI:(.*?)\]\]|', function($matches){
        return rawurldecode($matches[1]);
    }, $str);

    return $strDecode;
}

/**
 * 获取真实ip
 * @return array|false|string
 */
function get_client_ip()
{
    //判断服务器是否允许$_SERVER
    if(isset($_SERVER)){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
            $real_ip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $real_ip = $_SERVER['REMOTE_ADDR'];
        }
    }else{
        //不允许就使用getenv获取
        if(getenv("HTTP_X_FORWARDED_FOR")){
            $real_ip = getenv( "HTTP_X_FORWARDED_FOR");
        }elseif(getenv("HTTP_CLIENT_IP")) {
            $real_ip = getenv("HTTP_CLIENT_IP");
        }else{
            $real_ip = getenv("REMOTE_ADDR");
        }
    }
    return $real_ip;
}

/**
 * 生成uuid
 * @param string $prefix
 * @return string
 */
function uuid($prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid  = substr($chars,0,8) . '-';
    $uuid .= substr($chars,8,4) . '-';
    $uuid .= substr($chars,12,4) . '-';
    $uuid .= substr($chars,16,4) . '-';
    $uuid .= substr($chars,20,12);
    return $prefix . $uuid;
}

/**
 * 验证url
 * @param $str
 * @return bool
 */
function is_url($str)
{
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $str)) {
        return false;
    }
    return $str;
}

/**
 * 限制字符串长度，超过的部分用$replace替换
 * @param $str
 * @param $limit
 * @param string $replace
 * @return string
 */
function limit_str($str, $limit, $replace = '...')
{
    if (strlen($str) <= $limit) {
        return $str;
    } else {
        return mb_substr($str, 0, $limit) . $replace;
    }
}

/**
 * 判断是否是json格式
 * @param $string
 * @return bool
 */
function is_json($string)
{
    if (!is_string($string)) {
        return false;
    }
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * 密码加密
 * @param $password
 * @return bool|string
 */
function password_encrypt($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * 密码验证
 * @param $password
 * @param $hash
 * @return mixed
 */
function password_check($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * 获取分页配置
 * @param $params
 * @return array
 */
function pagination($params)
{
    if (empty($params['page']) || !is_numeric($params['page']) || $params['page'] <= 0) {
        $page = 1;
    } else {
        $page = (int)$params['page'];
    }
    if (empty($params['page_rows']) || !is_numeric($params['page_rows']) || $params['page_rows'] <= 0) {
        $page_rows = 10;
    } else {
        $page_rows = (int)$params['page_rows'];
    }
    return compact('page', 'page_rows');
}