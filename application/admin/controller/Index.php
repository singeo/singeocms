<?php
/**
 * 后台首页
 * User: singeo
 * Date: 2018/4/25 0025
 * Time: 下午 2:00
 */
namespace app\admin\controller;

use think\Cache;

class Index extends Base {

    /**
     * 后台首页显示
     * @return mixed
     */
    public function index(){
        return $this->fetch() ;
    }

    /**
     * 清除数据缓存
     */
    public function authclear(){
        Cache::clear() ;
        $this->success('缓存清除成功') ;
    }

    /**
     * 修改登录密码
     */
    public function editPassword(){
        echo $this->fetch('edit_password') ;
    }

    /**
     * 提交修改密码
     */
    public function submitEditPassword(){
        $passwd = $this->request->param('password','') ;
        $repasswd = $this->request->param('repassword','') ;
        $passwd_len = count($passwd) ;
        if($passwd_len < 6 && $passwd_len > 24){
            $this->error('密码长度为6-24位字符') ;
        }
        if($passwd != $repasswd){
            $this->error('两次密码输入不一致') ;
        }
        $password_salt  = createSalt() ;
        $en_password = createPassword($passwd,$password_salt) ;
        $userData['user_pass_salt'] = $password_salt ;
        $userData['user_pass'] = $en_password ;
        $consoleInfo = cache(config('admin_login_info')) ;
        $result = \think\Db::name('console_user')->where(['id'=>$consoleInfo['id']])->update($userData) ;
        if($result){
            $this->success('密码修改成功') ;
        }else{
            $this->error('密码修改失败') ;
        }
    }
}