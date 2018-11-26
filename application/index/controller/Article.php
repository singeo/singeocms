<?php
/**
 * 前台文章页面处理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 2:31
 */

namespace app\index\controller;

class Article extends Base
{
    /**
     * 列表页
     */
    public function index(){
        return $this->fetch('/lists') ;
    }

    /**
     * 瀑布流
     */
    public function water_flow(){
        return $this->fetch('/water_flow') ;
    }

    public function show(){
        return $this->fetch('/show') ;
    }
}