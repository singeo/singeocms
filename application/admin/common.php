<?php
/**
 * 后台使用的一些公共函数
 * User: singeo
 * Date: 2018/4/27 0027
 * Time: 上午 11:52
 */

if(!function_exists('checkAuth')){
    /**
     * 检查权限
     * @param $url
     * @return bool
     */
    function checkAuth($url){
        $consoleInfo = cache(config('admin_login_info')) ;
        if($consoleInfo['id']  === config('super_admin_uid')){
            return true ;
        }else{
            $rules = cache(config('admin_login_info')) ;
            if(!empty($rules)){
                $rules = array_column($rules,'menu_url') ;
                if(is_array($rules)){
                    if(in_array($url,$rules)){
                        return true ;
                    }
                }
            }
            return false ;
        }
    }
}

if(!function_exists('createPassword')){
    /**
     * 加密密码
     * @param $password 密码
     * @param $salt 盐
     * @return string
     */
    function createPassword($password,$salt){
        return md5(md5($password).$salt) ;
    }
}

if(!function_exists('createSalt')){
    /**
     * @return string
     */
    function createSalt($len = 6){
        return \think\helper\Str::random($len) ;
    }
}

if(!function_exists('format_bytes')){
    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}

