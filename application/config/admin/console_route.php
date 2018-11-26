<?php
/**
 * 后台路由配置
 * User: singeo
 * Date: 2018/4/25 0025
 * Time: 下午 2:02
 */

//后台路由配置
return [
    '__alias__' => [

    ],
    'console/login' => 'admin/Login/index',
    'login/verify' => 'admin/Login/getVerify',
    'console/login_ok' => 'admin/Login/login_ok'
];