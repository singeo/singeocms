<?php
/**
 * 后台配置文件
 * User: Administrator
 * Date: 2018/4/25 0025
 * Time: 下午 2:49
 */

return [
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    //静态文件位置定义
    'system_static' => '/static/admin/',
    //后台存放登录用户的session名称
    'admin_login_info' => 'wqeudkicxcqe8i' ,
    //超级管理员ID
    'super_admin_uid' => 1 ,
    //不进行权限验证的菜单
    'white_menu_url' => [
        '/admin/Index/index',
        '/admin/Index/authclear' //清除权限缓存
    ],
    //后台登录地址
    'admin_login_url' => '/console/login',
    //后台登录地址token
    'login_token' => 'b4f0dd1c7a6370374f48d0859c3b654d',
    //数据库备份路径
    'data_backup_path' => './uploads/sqlbackup/',
    //数据库备份卷大小
    'data_backup_part_size' => 20971520 ,
    //数据库备份文件是否启用压缩 0不压缩 1 压缩
    'data_backup_compress'=> 0 ,
    //数据库备份文件压缩级别 1普通 4 一般  9最高
    'data_backup_compress_level'=> 9 ,

    'web_config_group' => [
        '1'=>'网站信息',
        '2'=>'基本信息',
        '3'=>'附件信息',
    ],
    'web_config_field_type' => [
        'input'=>'输入框',
        'textarea'=>'文本框',
        'file'=>'文件',
        'select'=>'下拉框',
        'checkbox'=>'复选框',
        'radio'=>'单选框',
    ],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH.'admin/view/common/dispatch_jump.html',
    'dispatch_error_tmpl'    => APP_PATH.'admin/view/common/dispatch_jump.html',
] ;