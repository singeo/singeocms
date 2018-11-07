<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\exception\ErrorException;
use think\Request;

class Index extends Controller
{
    public function index()
    {
        $draw = new \app\common\library\DrawAward() ;
        $result = $draw->runDraw() ;
        print_r($result) ;
        //return $this->fetch() ;
    }

    public function testsubmit(){
//        print_r($_FILES) ;
        //查询最后一条记录
        header("Content-type:text/html;charset=utf-8");
        Db::startTrans() ;
        $goods = Db::name('goods')->order('id DESC')->find() ;
        if(empty($goods)){
            $version = 0 ;
        }else{
            $version = $goods['t_version'] ;
        }
        $new_version = 1 ;
        $goodsData['t_goods'] = getRandNum(10) ;
        $goodsData['t_version'] = $new_version ;
        $rst = Db::name('goods')->insert($goodsData) ;
        if(!$rst){
            Db::rollback() ;
            exit('goods写入失败') ;
        }
        $res = Db::name('version')->where(['g_version'=>$version,'id'=>1])->update(['g_version'=>$new_version]) ;
        if(!$res){
            Db::rollback() ;
            exit('版本写入失败') ;
        }

        $ret = Db::name('award_set')->where(['id'=>1])->update(['awardnum'=>['exp','awardnum - 1']]) ;
        if(!$ret){
            Db::rollback() ;
            exit('奖品已经发放完') ;
        }
        Db::commit() ;
        echo 'success' ;
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
