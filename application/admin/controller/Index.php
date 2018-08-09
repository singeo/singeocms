<?php
/**
 * 后台首页
 * User: singeo
 * Date: 2018/4/25 0025
 * Time: 下午 2:00
 */
namespace app\admin\controller;

class Index extends Base {

    /**
     * 后台首页显示
     * @return mixed
     */
    public function index(){
        return $this->fetch() ;
    }

    /**
     * 清除数据缓存
     */
    public function authclear(){
        session(config('admin_auth_menu'), null) ;
        return $this->success('缓存清除成功') ;
    }
}