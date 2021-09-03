<?php
/**
 * 文章列表模型
 * User: singeo
 * Date: 2018/11/16 0016
 * Time: 上午 10:39
 */

namespace app\common\model;


use think\Db;

class Article extends Base
{

    /**
     * 获取文章信息
     * @param $article_id
     * @return array|bool|false
     */
    public function articleInfo($article_id){
        if(empty($article_id)){
            return false ;
        }
        $info = Db::name('article')
            ->where(['id'=>$article_id,'status'=>1])
            ->find() ;
        if(empty($info)){
            return false ;
        }
        return $info ;
    }

    /**
     * 更新点击量
     * @param $article_id
     * @return bool|int|string
     */
    public function updateViewnum($article_id){
        if(empty($article_id)){
            return false ;
        }
        $result = Db::name('article')
            ->where(['id'=>$article_id])
            ->update(['view_num'=>['exp','view_num + 1']]) ;
        return $result ;
    }

    /**
     * 获取上一篇
     * @param $article_id
     * @param $cid
     * @return array|bool|false
     */
    public function getPrevArticle($article_id,$cid){
        if(empty($article_id)){
            return false ;
        }
        $where['id'] = ['lt',$article_id] ;
        if(!empty($cid)){
            $where['cid'] = $cid ;
        }
        $orderby = 'id DESC' ;
        $field = 'id,cid,article_title,link_attr,link_url,template_view' ;
        $result = Db::name('article')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->find() ;
        return $result ;
    }

    /**
     * 获取上一篇
     * @param $article_id
     * @param $cid
     * @return array|bool|false
     */
    public function getNextArticle($article_id,$cid){
        if(empty($article_id)){
            return false ;
        }
        $where['id'] = ['gt',$article_id] ;
        if(!empty($cid)){
            $where['cid'] = $cid ;
        }
        $orderby = 'id ASC' ;
        $field = 'id,cid,article_title,link_attr,link_url,template_view' ;
        $result = Db::name('article')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->find() ;
        return $result ;
    }

    /**
     * 获取文章列表
     * @param array $param 参数
     * @param string $limit
     * @param string $orderby
     * @param string $addfields
     * @return array
     */
    public function getArclist($param = [],$row = 5 ,$orderby = '',$addfields = ''){
        $arcWhere = [] ;
        $arcWhere['a.status'] = 1;
        //栏目ID
        if(!empty($param['cid'])){
            $arctypemodel = new \app\common\model\Arctype() ;
            $column = $arctypemodel->getHasChildren($param['cid']) ;
            $column_ids = array_column($column,'cid') ;
            $arcWhere['a.cid'] = ['in',$column_ids] ;
        }
        //文档不在栏目中
        if(!empty($param['notcid'])){
            $notcids = explode('|',$param['notcid']) ;
            $arcWhere['a.cid'] = ['notin',$notcids] ;
        }
        //是否头条
        if(!empty($param['is_head'])){
            $arcWhere['is_head'] = $param['is_head'] ;
        }
        //是否推荐
        if(!empty($param['is_recom'])){
            $arcWhere['is_recom'] = $param['is_recom'] ;
        }
        //是否置顶
        if(!empty($param['is_top'])){
            $arcWhere['is_top'] = $param['is_top'] ;
        }

        if(empty($addfields)){
            $addfields = 'a.id,ar.cid,article_title,article_desc,article_pic,a.seo_keywords,a.seo_desc,view_num,a.link_attr,'
                        .'a.link_url,a.template_view,publish_time,is_head,is_recom,is_top,ar.c_name,ar.link_attr as lm_link_attr,'
                        .'ar.link_url as lm_link_url,ar.template_list' ;
        }
        if($orderby == ""){
            $orderby = 'publish_time DESC,a.sort ASC,a.id DESC' ;
        }
        $join[] = ['arctype as ar','a.cid = ar.cid'] ;
        $list = Db::name('article')
            ->alias('a')
            ->join($join)
            ->where($arcWhere)
            ->field($addfields)
            ->order($orderby)
            ->limit($row)
            ->select() ;
        return $list ;
    }

    /**
     * 获取文章列表分页
     * @param array $param 参数
     * @param string $limit
     * @param string $orderby
     * @param string $addfields
     * @return array
     */
    public function getArticleList($param = [],$pagesize = 5 ,$orderby = '',$addfields = ''){
        $arcWhere = [] ;
        //栏目ID
        if(!empty($param['cid'])){
            $arctypemodel = new \app\common\model\Arctype() ;
            $column = $arctypemodel->getHasChildren($param['cid']) ;
            $column_ids = array_column($column,'cid') ;
            $arcWhere['a.cid'] = ['in',$column_ids] ;
        }
        //文档不在栏目中
        if(!empty($param['notcid'])){
            $notcids = explode('|',$param['notcid']) ;
            $arcWhere['a.cid'] = ['notin',$notcids] ;
        }
        //是否头条
        if(!empty($param['is_head'])){
            $arcWhere['is_head'] = $param['is_head'] ;
        }
        //是否推荐
        if(!empty($param['is_recom'])){
            $arcWhere['is_recom'] = $param['is_recom'] ;
        }
        //是否置顶
        if(!empty($param['is_top'])){
            $arcWhere['is_top'] = $param['is_top'] ;
        }

        if(empty($addfields)){
            $addfields = 'a.id,ar.cid,article_title,article_desc,article_pic,a.seo_keywords,a.seo_desc,view_num,a.link_attr,'
                .'a.link_url,a.template_view,publish_time,is_head,is_recom,is_top,ar.c_name,ar.link_attr as lm_link_attr,'
                .'ar.link_url as lm_link_url,ar.template_list' ;
        }
        if($orderby == ""){
            $orderby = 'publish_time DESC,a.sort ASC,a.id DESC' ;
        }
        $join[] = ['arctype as ar','a.cid = ar.cid'] ;
        $list = Db::name('article')
            ->alias('a')
            ->join($join)
            ->where($arcWhere)
            ->field($addfields)
            ->order($orderby)
            ->paginate($pagesize,false,['query'=>request()->param()]) ;
        $result['list'] = $list->toArray()['data'] ;
        $result['pages'] = $list ;
        return $result ;
    }

    /**
     * 获取相关文章
     * @param $aid
     * @return array
     */
    public function getRelativeList($aid,$limit = 10){
        //获取该文章的tags_id
        $tags = Db::name('article_tags')->where(['aid'=>$aid])->select() ;
        if(empty($tags)){
            return [] ;
        }
        $tags_ids = array_column($tags,'tags_id') ;
        $where['a.id'] = ['neq',$aid] ;
        $where['at.tags_id'] = ['in',$tags_ids] ;
        $join[] = ['article_tags as at','at.aid = a.id'] ;
        $field = 'distinct a.id,a.article_title,a.link_attr,a.link_url,a.template_view' ;
        $list = Db::name('article')
            ->alias('a')
            ->join($join)
            ->where($where)
            ->field($field)
            ->order('sort ASC ,a.id DESC')
            ->limit($limit)
            ->select() ;
        return $list ;
    }

    /**
     * 标签文章列表
     * @param $tags_id
     * @param int $pagesize
     * @return array
     */
    public function getTagsPageList($tags_id, $pagesize = 10){
        $where['at.tags_id'] = $tags_id ;
        $field = 'a.id,article_title,article_desc,article_pic,a.seo_keywords,a.seo_desc,view_num,a.link_attr,'
                .'a.link_url,a.template_view,publish_time,is_head,is_recom,is_top,ar.cid,ar.c_name,ar.link_attr as lm_link_attr,'
                .'ar.link_url as lm_link_url,ar.template_list' ;
        $join[] = ['article as a','a.id = at.aid'] ;
        $join[] = ['arctype as ar','a.cid = ar.cid'] ;
        $list = Db::name('article_tags')
            ->alias('at')
            ->join($join)
            ->where($where)
            ->field($field)
            ->order('a.sort ASC ,a.id DESC')
            ->paginate($pagesize,false,['query'=>request()->param()]) ;
        $result['list'] = $list->toArray()['data'] ;
        $result['pages'] = $list ;
        return $result ;
    }
}