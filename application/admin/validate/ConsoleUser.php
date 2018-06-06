<?php
/**
 * 后台用户的验证器类
 * User: 冯欣
 * Date: 2018/5/21 0021
 * Time: 下午 1:55
 */
namespace app\admin\validate ;

use think\Validate;

class ConsoleUser extends Validate
{
    //验证规则
    protected $rule = [
        'user_login'  => ['require','alphaDash','max' => 30,'token' => 'token_hash'],
        'user_email' => ['email'],
        'mobile' => ['checkMobile'=>'/^1[3|4|5|8][0-9]\d{8}$/'],
        'user_pass' => ['require','max'=>24,'min'=>6]
    ];

    //验证错误提示信息
    protected $message = [
        'user_login.require' => '用户名必须',
        'user_login.alphaDash'     => '用户名为小于30位的数字，字母，下划线组合',
        'user_login.max'     => '用户名为小于30位的数字，字母，下划线组合',
        'user_login.token'   => '不能重复提交',
        'user_email.email'        => '邮箱格式错误',
        'mobile.checkMobile' => '错误的手机号',
        'user_pass.require' => '登录密码必须',
        'user_pass.max' => '登录密码长度为6-24位字符',
        'user_pass.min' => '登录密码长度为6-24位字符',
    ];

    //验证场景
    protected $scene = [
        'add'   =>  ['user_login','user_email','mobile','user_pass'],
        'edit'  =>  ['user_login','user_email','mobile'],
    ];

    /**
     * 验证联系电话
     * @param $value
     * @param $rule
     * @param $data
     * @return mixed
     */
    protected function checkMobile($value,$rule,$data){
        return $this->regex($value,$rule) ;
    }
}