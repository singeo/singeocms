<?php
/**
 * 首页
 * User: singeo
 * Date: 2018/11/15 0015
 * Time: 下午 4:01
 */

namespace app\index\controller;


use think\Db;

class Home extends Base
{
    /**
     * 首页
     */
    public function index(){
        return $this->fetch() ;
    }
}