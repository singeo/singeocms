<?php
/**
 * 自定义标签
 * User: singeo
 * Date: 2018/10/31 0031
 * Time: 下午 4:38
 */

namespace app\common\library;


use think\template\TagLib;

class Singeo extends  TagLib
{
    protected $tags = [
        'testclose' => ['attr' => 'name,value', 'close' => 0],
        'testopen' => ['attr' => 'name,value,id', 'close' => 1]
        // attr : 自定义标签的属性, close : 是否闭合标签,下面有说明
    ] ;

    public function tagTestclose($tags, $content)
    {
        return $tags['name'] . ':' .$tags['value'] ;
    }

    public function tagTestopen($tags, $content){
        $id = $tags['id'] ;

        $parseStr = '<?php ' ;
        $parseStr .= '$param = ["cid"=>1] ;' ;
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;' ;
        $parseStr .= '$list = $articlemodel->getArclist($param) ;' ;
        $parseStr .= '$arr = [["name"=>"you","value"=>"mores"],["name"=>"we","value"=>"less"]] ;' ;
        $parseStr .= 'foreach($list as $key=>$'.$id.'): ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ;?>' ;

//        $parseStr='';
//        $parseStr.="<h1>{$tags['name']}</h1>";
//        $parseStr.="<br/>{$content}";
        return $parseStr;
    }
}