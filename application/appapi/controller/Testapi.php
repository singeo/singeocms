<?php
/**
 * 测试接口
 * User: singeo
 * Date: 2018/11/20 0020
 * Time: 下午 3:49
 */
namespace app\appapi\controller ;

use think\Log;

class Testapi
{
    public function tapi(){
        $url = 'http://wechat.liantoujinrong.com/Userlogin/doLogin' ;
        $cryptapi = new \app\appapi\library\ApiEncrypt() ;
        $data['appid'] = 2 ;
        $data['device'] = 'ios' ;
        $data['imgcode'] = '2r76c4' ;
        $data['member_type'] = 'INVESTOR' ;
        $data['passwd'] = $cryptapi->encryptRSA('12345a') ;
        $data['timestamp'] = '1543226965684' ;
        $data['user_name'] = '15828698525' ;
        $sign = $cryptapi->makeSign($data) ;
        $data['sign'] = $sign ;
        print_r($data) ;
        $resdata = $this->request_post($url,$data) ;
        print_r(json_decode($resdata,true)) ;
    }

    public function testgetimgcode(){
        $url = 'http://wechat.liantoujinrong.com/Verify/getImgcode' ;
        $data['appid'] = 2 ;
        $data['device'] = 'ios' ;
        $data['timestamp'] = '1543226965684' ;
        $cryptapi = new \app\appapi\library\ApiEncrypt() ;
        $sign = $cryptapi->makeSign($data) ;
        $data['sign'] = $sign ;
        $resdata = $this->request_post($url,$data) ;
        $resdata = json_decode($resdata,true) ;
        echo '<img src="'.$resdata['data']['imgcode'].'" />' ;
    }

    public function testsendcode(){
        $url = 'http://wechat.liantoujinrong.com/Verify/sendEditPasswordCode' ;
        $data['appid'] = 2 ;
        $data['device'] = 'ios' ;
        $data['timestamp'] = time() ;
        $data['user_phone'] = '18030577175' ;
        $cryptapi = new \app\appapi\library\ApiEncrypt() ;
        $sign = $cryptapi->makeSign($data) ;
        $data['sign'] = $sign ;
        $resdata = $this->request_post($url,$data) ;
        $resdata = json_decode($resdata,true) ;
        print_r($resdata) ;
    }

    function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return $data;
    }
}