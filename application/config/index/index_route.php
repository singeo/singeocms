<?php
/**
 * 前台路由配置
 * User: singeo
 * Date: 2018/11/12 0012
 * Time: 下午 3:33
 */

return [
    '/' => 'index/Home/index',//首页路由
    'index' =>  'index/Home/index', //首页路由
    'lists' =>  'index/Article/index', //文章列表路由
    'water_flow' =>  'index/Article/water_flow', //文章列表路由
    'single' =>  'index/Single/index', //单页路由
    'show'  =>  'index/Article/show', //文章内容页
    'tags_cloud' =>  'index/Tags/index?cid=4', //标签页
] ;