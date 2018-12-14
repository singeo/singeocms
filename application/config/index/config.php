<?php
/**
 * 前台配置管理
 * User: singeo
 * Date: 2018/11/7 0007
 * Time: 下午 5:49
 */

return [
    //前端模板css及css
    'theme_static' => '/static/default/' ,
    'template'               => [
        // 模板路径
        'view_path'    => '../resource/default/',
        //标签缓存
        'cache_time'    => -1,
        //Singeo标签库
        'taglib_pre_load' => 'app\common\taglib\Sg',
    ],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH.'admin/view/common/dispatch_jump.html',
    'dispatch_error_tmpl'    => APP_PATH.'admin/view/common/dispatch_jump.html',
] ;