<?php
if(!function_exists('get_client_ip')){
    /**
     * 获取客户端ip
     */
    function get_client_ip() {
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
        return $ip;
    }
}

if(!function_exists('array_sort')){
    /**
     * 二维数组排序
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
    function array_sort($arr, $keys, $type = 'desc')
    {
        $key_value = $new_array = array();
        foreach ($arr as $k => $v) {
            $key_value[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($key_value);
        } else {
            arsort($key_value);
        }
        reset($key_value);
        foreach ($key_value as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}


if(!function_exists('array_multi2single')){
    /**
     * 多维数组转化为一维数组
     *
     * @param 多维数组
     * @return array 一维数组
     */
    function array_multi2single($array)
    {
        static $result_array = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                array_multi2single($value);
            } else
                $result_array [] = $value;
        }
        return $result_array;
    }
}



if(!function_exists('getRandNum')) {
    /**
     * 获取随机数
     * @param int $len
     * @return string
     */
    function getRandNum($len = 6)
    {
        return \think\helper\Str::random($len);
    }
}

if(!function_exists('combineArray')) {
    /**
     * 两个数组的笛卡尔积
     * @param unknown_type $arr1
     * @param unknown_type $arr2
     */
    function combineArray($arr1, $arr2)
    {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }
}

if(!function_exists('get_nav_link')){
    /**
     * 获取导航链接
     * @param $navlink
     * @return string
     */
    function get_nav_link($navlink){
        $link = unserialize($navlink) ;
        if($link == false){
            return $navlink ;
        }else{
            return  $link['tpl'] . '?cid=' . $link['cid'] ;
        }
    }
}
?>