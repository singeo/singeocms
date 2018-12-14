<?php
/**
 * 后台首页
 * User: singeo
 * Date: 2018/4/25 0025
 * Time: 下午 2:00
 */
namespace app\admin\controller;

use think\Cache;

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
        Cache::clear() ;
        $this->success('缓存清除成功') ;
    }
}