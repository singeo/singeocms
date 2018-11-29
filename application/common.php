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

if (!function_exists('is_serialized')){
    /**
     * 判断是否是序列化字符串
     * @param $data
     * @return bool
     */
    function is_serialized($data) {
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }
}

if(!function_exists('get_nav_link')){
    /**
     * 获取导航链接
     * @param $navlink
     * @return string
     */
    function get_nav_link($cid,$attr,$url,$tpl){
        if($attr == 1){
            return '/'.$tpl . '?cid=' .$cid ;
        }elseif($attr == 2){
            if(strpos($url,'http') === 0){
                return $url ;
            }else{
                return '/'.$url ;
            }
        }
    }
}

if(!function_exists('get_content_link')){
    /**
     * 获取内容页链接
     * @param $navlink
     * @return string
     */
    function get_content_link($id,$attr,$url,$tpl){
        if($attr == 1){
            return '/'.$tpl . '?id=' .$id ;
        }elseif($attr == 2){
            if(strpos($url,'http') === 0){
                return $url ;
            }else{
                return '/'.$url ;
            }
        }
    }
}

if(!function_exists('get_tags_link')){
    /**
     * 获取标签链接
     * @param $navlink
     * @return string
     */
    function get_tags_link($tagid){
        return '/search_tags.html?tagid=' .$tagid ;
    }
}

if(!function_exists('scan_files')) {
    /**
     * 获取path目录下的文件，不获取当前目录下文件夹的文件
     * @param $path
     * @return array
     */
    function scan_files($path)
    {
        $filelist = [] ;
        $resource = opendir($path);
        while ($rows = readdir($resource)) {
            if (!is_dir($path . '/' . $rows) && $rows != "." && $rows != "..") {
                $filelist[] = $rows ;
            }
        }
        return $filelist ;
    }
}

if(!function_exists('scan_all_files')) {
    /**
     * 获取当前path目录下的所有文件
     * @param $path
     * @param string $curdir
     * @return array
     */
    function scan_all_files($path, $curdir = '')
    {
        $filelist = [];
        $resource = opendir($path);
        while ($rows = readdir($resource)) {
            if (is_dir($path . '/' . $rows) && $rows != "." && $rows != "..") {
                $filelist = array_merge($filelist, scan_all_files($path . '/' . $rows, $rows));
            } elseif ($rows != "." && $rows != "..") {
                $filelist[] = $curdir !== '' ? $curdir . '/' . $rows : $rows;
            }
        }
        return $filelist;
    }
}

if(!function_exists('getSubstr')) {
    /**
     * 实现中文字串截取无乱码的方法
     *
     * @param string $string 字符串
     * @param intval $start 起始位置
     * @param intval $length 截取长度
     * @return string
     */
    function getSubstr($string, $start, $length)
    {
        if (mb_strlen($string, 'utf-8') > $length) {
            $str = msubstr($string, $start, $length, true, 'utf-8');
            return $str;
        } else {
            return $string;
        }
    }
}

if(!function_exists('msubstr')) {
    /**
     * 字符串截取，支持中文和其他编码
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string
     */
    function msubstr($str, $start = 0, $length, $suffix = false, $charset = "utf-8")
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }

        $str_len = strlen($str); // 原字符串长度
        $slice_len = strlen($slice); // 截取字符串的长度
        if ($slice_len < $str_len) {
            $slice = $suffix ? $slice . '...' : $slice;
        }

        return $slice;
    }
}

if(!function_exists('getPagelist')){
    /**
     * 获取列表分页代码
     */
    function getPagelist($pages, $listitem = '', $listsize = '')
    {
        if (empty($pages)) {
            echo '标签pagelist报错：只适用在标签list之后。';
            return false;
        }
        $listitem = !empty($listitem) ? $listitem : 'info,index,end,pre,next,pageno';
        $listsize = !empty($listsize) ? $listsize : '3';

        $value = $pages->render($listitem, $listsize);

        return $value;
    }
}

if(!function_exists('getAuthor')) {
    /**
     * 获取文章作者
     * @param $author_id 作者ID
     * @return mixed|string
     */
    function getAuthor($author_id)
    {
        if (empty($author_id)) {
            return '系统管理员';
        } else {
            $author_name = \think\Db::name('article_author')
                ->where(['id' => $author_id, 'status' => 1])
                ->value('author_name');
            if (empty($author_name)) {
                return '系统管理员';
            } else {
                return $author_name;
            }
        }
    }
}

if(!function_exists('getWebconfig')) {
    /**
     * 获取网站某个参数配置的值
     * @param $key
     * @return mixed|string
     */
    function getWebconfig($key){
        return cache(config('web_config_catch'))[$key] ;
    }
}
?>