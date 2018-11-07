<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31 0031
 * Time: 下午 4:38
 */

namespace app\common\library;


use think\template\TagLib;

class Singeo extends  TagLib
{
    protected $tags = [
        'test' => ['attr' => 'name,value', 'close' => 0]
        // attr : 自定义标签的属性, close : 是否闭合标签,下面有说明
    ] ;

    public function tagTest($tags, $content)
    {
        $name  = $tags['name'];
        $value = $tags['value'];
        // 逻辑代码
        return 'ddddddddd';
    }
}