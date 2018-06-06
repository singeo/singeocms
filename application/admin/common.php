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
        $consoleInfo = session(config('admin_login_info')) ;
        if($consoleInfo['id']  === config('super_admin_uid')){
            return true ;
        }else{
            $rules = session(config('admin_auth_rules')) ;
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
