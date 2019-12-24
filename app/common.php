<?php
// 应用公共文件

/**
 * 数组转换成树
 * @param $array
 * @param int $root 根节点id
 * @param string $id 自身id名称
 * @param string $pid 父级id名称
 * @param string $child 子级元素名称
 * @return array
 */
function array_to_tree($array, $root = 0, $id = 'id', $pid = 'pid', $child = 'child')
{
    $tree = [];
    foreach ($array as $k => $v) {
        if ($v[$pid] == $root) {
            $v[$child] = array_to_tree($array, $v[$id], $id, $pid, $child);
            $tree[] = $v;
            unset($array[$k]);
        }
    }
    return $tree;
}

/**
 * 树转换成数组
 * @param $tree
 * @param string $id 自身id名称
 * @param string $child 子级元素名称
 * @return array
 */
function set_list($tree, $id = 'id', $child = 'child')
{
    $array = array();
    foreach ($tree as $k => $val) {
        $array[] = $val;
        if (isset($val[$child])) {
            $children = set_list($val[$child], $val[$id]);
            if ($children) {
                $array = array_merge($array, $children);
            }
        }
    }
    foreach ($array as $item) {
        unset($item[$child]);
    }
    return $array;
}

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

if (!function_exists('filter_check')) {
    /**
     * 使用filter_var方式验证
     * @access public
     * @param  mixed     $value  字段值
     * @param  mixed     $rule  验证规则
     * @return bool
     */
    function filter_check($value, $rule)
    {
        if (is_string($rule) && strpos($rule, ',')) {
            list($rule, $param) = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
            $rule  = $rule[0];
        } else {
            $param = null;
        }
        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }
}


/**
 * 是否邮箱
 * @param string $email
 * @return bool
 */
function is_email(string $email)
{
    return filter_check($email, FILTER_VALIDATE_EMAIL);
}

/**
 * 检测时间，如果是则返回时间戳，否则返回false
 * @param $value
 * @return bool|false|int
 */
function is_time($value)
{
    if (is_numeric($value)) {
        return intval($value);
    } else {
        if (strtotime($value)) {
            return strtotime($value);
        } else {
            return false;
        }
    }
}


/**
 * 返回一个默认时间格式
 * @param $value
 * @param string $format
 * @return false|string
 */
function default_time_format($value, $format = 'Y-m-d H:i:s')
{
    if (empty($value)) {
        return '';
    }
    if (!is_time($value)) {
        return '';
    }
    return date($format, $value);
}

/**
 * 生成随机字符串
 * @param int $length 生成长度
 * @param int $type 字符串类型 0-7 8种模式
 * @return string
 */
function random_string($length = 6, $type = 0): string
{
    $chars = [
        '0123456789',
        'abcdefghijklmnopqrstuvwxyz',
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        '!@#$%^&*()-_ []{}<>~`+=,.;:/?|'
    ];
    $char_seeder = '';
    switch ($type) {
        case 1:
            $char_seeder = $chars[1];
            break;
        case 2:
            $char_seeder = $chars[2];
            break;
        case 3:
            $char_seeder = $chars[3];
            break;
        case 4:
            $char_seeder = $chars[0] . $chars[1];
            break;
        case 5:
            $char_seeder = $chars[1] . $chars[2];
            break;
        case 6:
            $char_seeder = $chars[0] . $chars[1] . $chars[2];
            break;
        case 7:
            $char_seeder = $chars[0] . $chars[1] . $chars[2] . $chars[3];
            break;
        case 0:
        default:
            $char_seeder = $chars[0];
            break;
    }
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $random_string .= substr($char_seeder, mt_rand(0, strlen($char_seeder) - 1), 1);
        $random_string .= $char_seeder[mt_rand(0, strlen($char_seeder) - 1)];
    }

    return $random_string;
}