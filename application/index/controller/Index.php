<?php
namespace app\index\controller;

use think\exception\ErrorException;
use think\Request;

class Index
{
    public function index()
    {

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
