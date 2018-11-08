<?php
/**
 * 前台单页管理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 1:52
 */

namespace app\index\controller;

use think\Controller;
class Single extends Controller
{
    /**
     * 单页index
     */
    public function index(){
        return $this->fetch('/single') ;
    }
}