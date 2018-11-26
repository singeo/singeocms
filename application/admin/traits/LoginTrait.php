<?php
/**
 * 后台登录提交验证类
 * User: Administrator
 * Date: 2018/4/25 0025
 * Time: 下午 2:35
 */
namespace app\admin\traits;
use app\library\Lib;
use think\captcha\Captcha;
use think\Db;
use think\Request;

trait LoginTrait
{
    /**
     * 执行登录
     * @return mixed
     */
    public function login_ok(){
        //也可以使用助手函数
        $arr['status'] = 0 ;
        $request = new Request() ;
        $param = $request->param() ;
        $captcha = new Captcha() ;
        if(!$captcha->check($param['user_verify'])){
            $arr['msg'] = '图形验证码错误' ;
            return $arr ;
        }
        $user_name = $param['user_name'] ;
        $where['user_login'] = $user_name ;
        $field = 'id,user_login,user_pass,user_pass_salt,user_nickname,avatar,user_status' ;
        $user = Db::name('console_user')
            ->where($where)
            ->field($field)
            ->find() ;
        if(empty($user)){
            $arr['msg'] = '用户不存在' ;
            return $arr ;
        }
        if($user['user_status'] == 0){
            $arr['msg'] = '该用户已被禁用' ;
            return $arr ;
        }
        $password = $param['user_password'] ;
        $enpassword = createPassword($password, $user['user_pass_salt']) ;
        if($enpassword != $user['user_pass']){
            $arr['msg'] = '用户登录密码错误' ;
            return $arr ;
        }
        //更新登录次数和时间
        $loginData['login_times'] = ['exp','login_times + 1'] ;
        $loginData['last_login_time'] = time() ;
        $loginData['last_login_ip'] = get_client_ip() ;
        Db::name('console_user')
            ->where(['id'=>$user['id']])
            ->update($loginData) ;
        unset($user['user_pass']) ;
        unset($user['user_pass_salt']) ;
        cache(config('admin_login_info'),$user) ;
        $arr['status'] = 1 ;
        $arr['msg'] = '登录成功' ;
        return $arr ;
    }

    /**
     * 退出登录
     */
    public function logout(){
        cache(config('admin_login_info'),null) ;
        cache(config('app_config.catch_keys_all')['admin_auth_menu'],null) ;
        cache(config('app_config.catch_keys_all')['admin_auth_rules'],null) ;
        $this->success('成功退出',config('app_config.website')) ;
    }
}