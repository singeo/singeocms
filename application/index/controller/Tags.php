<?php
/**
 * 标签展示页
 * User: singeo
 * Date: 2018/11/12 0012
 * Time: 下午 3:45
 */

namespace app\index\controller;


class Tags extends Base
{
    /**
     * 标签展示
     */
    public function index(){
        return $this->fetch('/tags') ;
    }

    public function search(){
        return $this->fetch('/search_tags') ;
    }
}