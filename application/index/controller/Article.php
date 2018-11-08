<?php
/**
 * 前台文章页面处理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 2:31
 */

namespace app\index\controller;


use think\Controller;

class Article extends Controller
{
    /**
     * 列表页
     */
    public function lists(){
        return $this->fetch('/lists') ;
    }

    public function show(){
        return $this->fetch('/show') ;
    }
}