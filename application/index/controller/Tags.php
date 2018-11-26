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
        echo $this->column_id ;
        return $this->fetch('/tags') ;
    }
}