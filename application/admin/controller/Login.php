<?php
/**
 * 后台登录控制类
 * User: 冯欣
 * Date: 2018/4/25 0025
 * Time: 下午 2:25
 */

namespace app\admin\controller;

use app\admin\traits\LoginTrait;
use app\common\traits\VerifyCodeTrait;
use think\Config;
use think\Controller;

class Login extends Controller
{
    use LoginTrait , VerifyCodeTrait;
    /**
     * 登录页面
     */
    public function index(){
        $loginToken = $this->request->get('token') ;
        if(empty($loginToken)){
            $this->error('地址不存在','/') ;
        }
        if($loginToken != config('login_token')){
            $this->error('地址不存在','/') ;
        }
        return $this->fetch() ;
    }
}