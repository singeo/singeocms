<?php
namespace app\index\controller;

use think\exception\ErrorException;
use think\Request;

class Index
{
    public function index()
    {
//        header("Content-type:text/html;charset=utf-8");
        // 1. 初始化
//        $ch = curl_init();
//        // 2. 设置选项，包括URL
//        curl_setopt($ch,CURLOPT_URL,"http://swoole.singeo.com/aassaa");
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//        curl_setopt($ch,CURLOPT_HEADER,0);
//        // 3. 执行并获取HTML文档内容
//        $output = curl_exec($ch);
//        if($output === FALSE ){
//            echo "CURL Error:".curl_error($ch);
//        }
//        // 4. 释放curl句柄
//        curl_close($ch);
    }


    public function testCallback($a,$cb){
        echo 'test callback' ;
        //$cb($a) ;
        call_user_func($cb,$a) ;
        echo 'test callback 3' ;
    }

    public function getAgeByBirth($date,$type = 1){
        $nowYear = date("Y",time());
        $nowMonth = date("m",time());
        $nowDay = date("d",time());
        $birthYear = date("Y",$date);
        $birthMonth = date("m",$date);
        $birthDay = date("d",$date);
        if($type == 1){
            $age = $nowYear - ($birthYear - 1);
        }elseif($type == 2){
            if($nowMonth<$birthMonth){
                $age = $nowYear - $birthYear - 1;
            }elseif($nowMonth==$birthMonth){
                if($nowDay<$birthDay){
                    $age = $nowYear - $birthYear - 1;
                }else{
                    $age = $nowYear - $birthYear;
                }
            }else{
                $age = $nowYear - $birthYear;
            }
        }
        return $age;
    }
}
