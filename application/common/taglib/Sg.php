<?php
/**
 * 自定义标签sg
 * User: Singeo
 * Date: 2018/11/16 0016
 * Time: 上午 10:12
 */
namespace app\common\taglib;

use think\template\TagLib;

class Sg extends TagLib
{
    protected $tags = [
        // attr : 自定义标签的属性, close : 是否闭合标签,下面有说明
        'article' => ['attr'=>'cid,notcid,addfields,order,row,key,id,is_head,is_recom,is_top','close'=>1] ,
        'articlelist' => ['attr'=>'cid,notcid,addfields,order,pagesize,flag,key,id,is_head,is_recom,is_top','close'=>1] ,
        'articletag' => ['attr'=>'aid','close' => 0] ,
        'prevornext' => ['attr'=>'aid,cid,flag','close' => 0] ,
        'tagsarticle' => ['attr'=>'aid,limit,empty','close'=>1] ,
        'taglist' => ['attr'=>'row,key,id,order,empty','close'=>1],
        'tagspage' => ['attr'=>'tagid,pagesize,id,key','close'=>1],
        'pagelist' => ['attr' => 'listitem,listsize', 'close' => 0],
        'breadcrumb'=> ['attr' => 'cid', 'close' => 0],
        'singleinfo'=> ['attr' => 'cid,id,empty','close'=>1],
        'advertlist'=> ['attr' => 'cid,id,empty,orderby,limit','close'=>1]
    ] ;

    /**
     * 文章列表
     * @param $tags
     * @param $content
     * @return string
     */
    public function tagArticle($tags, $content){
        $cid = empty($tags['cid']) ? '' : $tags['cid'] ;
        $notcid = empty($tags['notcid']) ? '' : $tags['notcid'] ;
        $addfields = empty($tags['addfields']) ? '' : $tags['addfields'] ;
        $id     = isset($tags['id']) ? $tags['id'] : 'field';
        $key    = !empty($tags['key']) ? $tags['key'] : 'i';
        $orderby    = isset($tags['order']) ? $tags['order'] : '';
        $flag    = isset($tags['flag']) ? $tags['flag'] : '';
        $is_head   = isset($tags['is_head']) ? $tags['is_head'] : 0 ;
        $is_recom    = isset($tags['is_recom']) ? $tags['is_recom'] : 0 ;
        $is_top    = isset($tags['is_top']) ? $tags['is_top'] : 0 ;
        $row = isset($tags['row']) ? $tags['row'] : 10 ;
        $parseStr = '<?php ' ;
        $parseStr .= '$param = [' ;
        $parseStr .= ' "cid"=> "'.$cid .'",';
        $parseStr .= ' "notcid"=> "'.$notcid .'",';
        $parseStr .= ' "flag"=> "'.$flag .'",';
        $parseStr .= ' "is_head"=> "'.$is_head .'",';
        $parseStr .= ' "is_top"=> "'.$is_top .'",';
        $parseStr .= ' "is_recom"=> "'.$is_recom.'"' ;
        $parseStr .= '] ;' ;
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;' ;
        $parseStr .= '$list = $articlemodel->getArclist($param,"'.$row.'","'.$orderby.'","'.$addfields.'") ;' ;
        $parseStr .= 'foreach($list as $'.$key.'=>$'.$id.'):  ' ;
        //$parseStr .= 'if(empty($'.$id.'["article_pic"]) || !file_exists($_SERVER["DOCUMENT_ROOT"].$'.$id.'["article_pic"])): $'.$id.'["article_pic"] = getWebconfig("web_no_pic") ;endif;' ;
        $parseStr .= ' ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ;?>' ;
        return $parseStr ;
    }

    /**
     * 文章列表分页
     * @param $tags
     * @param $content
     * @return string
     */
    public function tagArticlelist($tags, $content){
        $cid = empty($tags['cid']) ? request()->param('cid/d') : $tags['cid'] ;
        $notcid = empty($tags['notcid']) ? '' : $tags['notcid'] ;
        $addfields = empty($tags['addfields']) ? '' : $tags['addfields'] ;
        $id     = isset($tags['id']) ? $tags['id'] : 'field';
        $key    = !empty($tags['key']) ? $tags['key'] : 'i';
        $orderby    = isset($tags['order']) ? $tags['order'] : '';
        $is_head   = isset($tags['is_head']) ? $tags['is_head'] : 0 ;
        $is_recom    = isset($tags['is_recom']) ? $tags['is_recom'] : 0 ;
        $is_top    = isset($tags['is_top']) ? $tags['is_top'] : 0 ;
        $pagesize = isset($tags['pagesize']) ? $tags['pagesize'] : 10 ;
        $parseStr = '<?php ' ;
        $parseStr .= '$param = [' ;
        $parseStr .= ' "cid"=> "'.$cid .'",';
        $parseStr .= ' "notcid"=> "'.$notcid .'",';
        $parseStr .= ' "is_head"=> "'.$is_head .'",';
        $parseStr .= ' "is_top"=> "'.$is_top .'",';
        $parseStr .= ' "is_recom"=> "'.$is_recom.'"' ;
        $parseStr .= '] ;' ;
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;' ;
        $parseStr .= '$res = $articlemodel->getArticleList($param,"'.$pagesize.'","'.$orderby.'","'.$addfields.'") ;' ;
        $parseStr .= '$__LIST__ = $res["list"] ;' ;
        $parseStr .= '$__PAGES__ = $res["pages"] ;' ;
        $parseStr .= 'foreach($__LIST__ as $'.$key.'=>$'.$id.'):  ' ;
        //$parseStr .= 'if(empty($'.$id.'["article_pic"]) || !file_exists($_SERVER["DOCUMENT_ROOT"].$'.$id.'["article_pic"])): $'.$id.'["article_pic"] = cache(config("web_config_catch"))["web_no_pic"] ;endif;' ;
        $parseStr .= ' ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ;?>' ;
        return $parseStr ;
    }

    /**
     * 标签云获取
     * @param $tags
     * @param $content
     */
    public function tagTaglist($tags,$content){
        $row = isset($tags['row']) ? $tags['row'] : '' ;
        $key    = !empty($tags['key']) ? $tags['key'] : 'i';
        $id     = isset($tags['id']) ? $tags['id'] : 'field';
        $orderby    = isset($tags['orderby']) ? $tags['orderby'] : '';
        $empty    = isset($tags['empty']) ? $tags['empty'] : '没有数据';
        $parseStr = '<?php ' ;
        $parseStr .= '$tagmodel = new \app\common\model\Tags ;' ;
        $parseStr .= '$tags = $tagmodel->getTagslist(\''.$row.'\',"'.$orderby.'") ;' ;
        $parseStr .= 'if(empty($tags)): echo "'.$empty .'" ; else: ' ;
        $parseStr .= 'foreach($tags as $'.$key.'=>$'.$id.') : ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ; endif ; ?>' ;
        return $parseStr ;
    }

    /**
     * 文章标签
     * @param $tags
     * @param $content
     */
    public function tagArticletag($tags,$content){
        if(empty($tags['aid'])){
            echo '标签错误，当前文章ID不存在。' ;
        }
        $aid = $tags['aid'] ;
        $parseStr = ' <?php ';
        $parseStr .= '$tagsmodel = new \app\common\model\Tags() ;' ;
        $parseStr .= '$list = $tagsmodel->getArticleTags('.$aid.') ;' ;
        $parseStr .= '$taghtml = "" ;' ;
        $parseStr .= 'if(!empty($list)):' ;
        $parseStr .= '$taghtml .= \'<div class="tags">\' ;' ;
        $parseStr .= 'foreach($list as $item): ' ;
        $parseStr .= '$taghtml .=\'<a href="\'.get_tags_link($item[\'tags_id\']).\'" target="_blank">\'.$item[\'tags_name\'].\'</a>\' ;' ;
        $parseStr .= 'endforeach ;' ;
        $parseStr .= '$taghtml .= \'</div>\' ;' ;
        $parseStr .= 'endif ;' ;
        $parseStr .= 'echo $taghtml ;' ;
        $parseStr .= '?>' ;
        return $parseStr ;
    }

    /**
     * 获取文章上一篇和下一篇
     * @param $tags
     * @param $content
     */
    public function tagPrevornext($tags,$content){
        if(empty($tags['aid'])){
            echo '标签错误，当前文章ID不存在。' ;
        }
        $aid = $tags['aid'] ;
        $cid = $tags['cid'] ;
        $flag = empty($tags['flag']) ? 'prev' : $tags['flag'] ;
        $parseStr = '<?php ';
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;';
        $parseStr .= '$flag = "'.$flag.'" ;' ;
        $parseStr .= '$resinfo = [] ;' ;
        $parseStr .= 'if(\'prev\' == $flag): ' ;
        $parseStr .= '$resinfo = $articlemodel->getPrevArticle('.$aid.','.$cid.') ;' ;
        $parseStr .= 'else : ' ;
        $parseStr .= '$resinfo = $articlemodel->getNextArticle('.$aid.','.$cid.') ;' ;
        $parseStr .= 'endif ;' ;
        $parseStr .= 'if(empty($resinfo)) : ' ;
        $parseStr .= '$taghtml = \'<a href="javascript:void(0);">没有了</a>\' ;' ;
        $parseStr .= 'else : ' ;
        $parseStr .= '$taghtml = \'<a href="\'.get_content_link($resinfo[\'id\'],$resinfo[\'link_attr\'],$resinfo[\'link_url\'],$resinfo[\'template_view\']).\'">\'.$resinfo[\'article_title\'].\'</a>\' ;' ;
        $parseStr .= 'endif ;' ;
        $parseStr .= 'echo $taghtml ;?>' ;
        return $parseStr ;
    }

    /**
     * 获取文章内容相关内容
     * @param $tags
     * @param $content
     */
    public function tagTagsarticle($tags,$content){
        if(empty($tags['aid'])){
            echo '标签错误，当前文章ID不存在。' ;
        }
        $aid = $tags['aid'] ;
        $limit = empty($tags['limit']) ? 10 : $tags['limit'] ;
        $empty = empty($tags['empty']) ? '没有数据' : $tags['empty'] ;
        $parseStr = '<?php ';
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;';
        $parseStr .= '$list = $articlemodel->getRelativeList('.$aid.','.$limit.') ;' ;
        $parseStr .= 'if(empty($list)): echo \''.$empty.'\' ;' ;
        $parseStr .= 'else: ' ;
        $parseStr .= 'foreach($list as $item): ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ;endif ;?>' ;
        return $parseStr ;
    }

    /**
     * 获取含有tags标签的列表
     * @param $tags
     * @param $content
     */
    public function tagTagspage($tags,$content){
        if(empty($tags['tagid'])){
            echo '标签错误，当前标签ID不存在。' ;
        }
        $tagid = $tags['tagid'] ;
        $pagesize = empty($tags['pagesize']) ? 10 : $tags['pagesize'] ;
        $id     = isset($tags['id']) ? $tags['id'] : 'field';
        $key    = !empty($tags['key']) ? $tags['key'] : 'i';
        $empty = empty($tags['empty']) ? '没有数据' : $tags['empty'] ;
        $parseStr = '<?php ';
        $parseStr .= '$articlemodel = new \app\common\model\Article() ;';
        $parseStr .= '$res = $articlemodel->getTagsPageList('.$tagid.','.$pagesize.') ;' ;
        $parseStr .= '$__LIST__ = $res["list"] ;' ;
        $parseStr .= '$__PAGES__ = $res["pages"] ;' ;
        $parseStr .= 'foreach($__LIST__ as $'.$key.'=>$'.$id.'):  ' ;
        $parseStr .= 'if(empty($'.$id.'["article_pic"]) || !file_exists($_SERVER["DOCUMENT_ROOT"].$'.$id.'["article_pic"])): $'.$id.'["article_pic"] = cache(config("web_config_catch"))["web_no_pic"] ;endif;' ;
        $parseStr .= ' ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ;?>' ;
        return $parseStr ;
    }

    /**
     * pagelist 标签解析
     * 在模板中获取列表的分页
     * 格式： {sg:pagelist listitem='info,index,end,pre,next,pageno' listsize='2'/}
     * @access public
     * @param array $tag 标签属性
     * @return string
     */
    public function tagPagelist($tags,$content)
    {
        $listitem = !empty($tags['listitem']) ? $tags['listitem'] : '';
        $listsize   = !empty($tags['listsize']) ? intval($tags['listsize']) : '';

        $parseStr = ' <?php ';
        $parseStr .= ' $__PAGES__ = isset($__PAGES__) ? $__PAGES__ : "";';
        $parseStr .= ' $_value = getPagelist($__PAGES__, "'.$listitem.'", "'.$listsize.'");';
        $parseStr .= ' echo $_value;';
        $parseStr .= ' ?>';

        return $parseStr;
    }

    /**
     * 面包屑导航
     * @param array $tag 标签属性
     */
    public function tagBreadcrumb($tags){
        if(empty($tags['cid'])){
            echo '标签错误，当前栏目ID不存在。' ;
        }
        $cid = $tags['cid'] ;
        $parseStr = ' <?php ';
        $parseStr .= '$arctypemodel = new \app\common\model\Arctype() ;';
        $parseStr .= '$columnAll = $arctypemodel->getAllColumu() ;' ;
        $parseStr .= '$list = \app\admin\library\TreeShape::parentChannel($columnAll,'.$cid.') ; ' ;
        $parseStr .= '$reslist = array_reverse($list) ;' ;
        $parseStr .= '$crumb_str = "" ;' ;
        $parseStr .= 'foreach($reslist as $crumb) : ' ;
        $parseStr .= 'if($crumb["cid"] == '.$cid.'): ' ;
        $parseStr .= '$crumb_str .= "<a href=\"".get_nav_link($crumb["cid"],$crumb["link_attr"],$crumb["link_url"],$crumb["template_list"])."\" class=\"n2\">".$crumb["c_name"]."</a>" ;' ;
        $parseStr .= 'else :' ;
        $parseStr .= '$crumb_str .= "<a href=\"".get_nav_link($crumb["cid"],$crumb["link_attr"],$crumb["link_url"],$crumb["template_list"])."\" class=\"n1\">".$crumb["c_name"]."</a>" ;' ;
        $parseStr .= 'endif ; endforeach ;' ;
        $parseStr .= 'echo "<a href=\"/index.html\" class=\"n1\">网站首页</a>" . $crumb_str ;';
        $parseStr .= '?>' ;
        return $parseStr ;
    }

    /**
     * 获取单页内容
     * @param $tags
     */
    public function tagSingleinfo($tags,$content){
        if(empty($tags['cid'])){
            echo '标签错误，当前栏目ID不存在。' ;
        }
        $cid = $tags['cid'] ;
        $idname = empty($tags['id']) ? 'info' : $tags['id'] ;
        $empty = empty($tags['empty']) ? '没有数据' : $tags['empty'] ;
        $parseStr = ' <?php ';
        $parseStr .= '$singlepagemodel = new \app\common\model\SinglePage() ;' ;
        $parseStr .= '$'.$idname.' = $singlepagemodel->getSingleInfo('.$cid.') ;' ;
        $parseStr .= 'if(empty($info)): echo \''.$empty.'\' ;' ;
        $parseStr .= 'else: ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endif ;?>' ;
        return $parseStr ;
    }

    /**
     * 获取广告列表内容
     * @param $tags
     */
    public function tagAdvertlist($tags,$content){
        if(empty($tags['cid'])){
            echo '标签错误，广告栏目ID不存在。' ;
        }
        $cid = $tags['cid'] ;
        $idname = empty($tags['id']) ? 'item' : $tags['id'] ;
        $empty = empty($tags['empty']) ? '' : $tags['empty'] ;
        $orderby = empty($tags['orderby']) ? '' : $tags['orderby'] ;
        $limit = empty($tags['limit']) ? '' : $tags['limit'] ;
        $parseStr = ' <?php ';
        $parseStr .= '$advertmodel = new \app\common\model\Advert() ;' ;
        $parseStr .= '$list = $advertmodel->getAdvertList("'.$cid.'","'.$orderby.'","'.$limit.'") ;' ;
        $parseStr .= 'if(empty($list)): echo \''.$empty.'\' ;' ;
        $parseStr .= 'else: ' ;
        $parseStr .= 'foreach($list as $'.$idname.') : ?>' ;
        $parseStr .= $content ;
        $parseStr .= '<?php endforeach ; endif ;?>' ;
        return $parseStr ;
    }
}