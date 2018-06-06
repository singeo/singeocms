<?php
/**
 * 图形验证码traits类
 * User: Administrator
 * Date: 2018/4/25 0025
 * Time: 下午 3:28
 */
namespace app\common\traits ;

trait VerifyCodeTrait
{
    /**
     * 生成图形验证码
     * @return \think\Response
     */
    public function getVerify(){
        $captcha = new \think\captcha\Captcha() ;
        $captcha->length = 4 ;
        return $captcha->entry() ;
    }
}