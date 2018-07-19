<?php
namespace app\index\controller;

use think\exception\ErrorException;
use think\Request;

class Index
{
    public function index()
    {
        echo 0.2 / 100 ;

//        $str = '{\"batchNo\":\"usihd2018071216303278108bb74714a\",\"bizDetails\":[{\"requestNo\":\"usihd20180712163032781261bc1666a\",\"tradeType\":\"TENDER\",\"projectNo\":\"crbif20180712160130bb8893c1eb35d\",\"details\":[{\"bizType\":\"TENDER\",\"freezeRequestNo\":\"usrhd2018071216014293901e9369f8d\",\"sourcePlatformUserNo\":\"201807111449496005\",\"targetPlatformUserNo\":\"20180608121317\",\"amount\":\"94.50\",\"remark\":\"\标\号[4068]\放\款\"},{\"bizType\":\"MARKETING\",\"freezeRequestNo\":\"usrhd2018071216014293901e9369f8d\",\"sourcePlatformUserNo\":\"SYS_GENERATE_002\",\"targetPlatformUserNo\":\"20180608121317\",\"amount\":\"5.50\",\"remark\":\"\标\号[4068]\放\款,\用\户[4573]\使\用\红\包[0.50\元]\和\抵\现\券[5.00\元]\"}]},{\"requestNo\":\"usihd20180712163032781ced84d85e7\",\"tradeType\":\"TENDER\",\"projectNo\":\"crbif20180712160130bb8893c1eb35d\",\"details\":[{\"bizType\":\"TENDER\",\"freezeRequestNo\":\"usrhd20180712160154961381a86a333\",\"sourcePlatformUserNo\":\"201807111449496005\",\"targetPlatformUserNo\":\"20180608121317\",\"amount\":\"94.50\",\"remark\":\"\标\号[4068]\放\款\"},{\"bizType\":\"MARKETING\",\"freezeRequestNo\":\"usrhd20180712160154961381a86a333\",\"sourcePlatformUserNo\":\"SYS_GENERATE_002\",\"targetPlatformUserNo\":\"20180608121317\",\"amount\":\"5.50\",\"remark\":\"\标\号[4068]\放\款,\用\户[4573]\使\用\红\包[0.50\元]\和\抵\现\券[5.00\元]\"}]}],\"timestamp\":\"20180712163032\"}' ;
//        $str = str_replace('\\','',$str) ;
//        print_r($str) ;
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
