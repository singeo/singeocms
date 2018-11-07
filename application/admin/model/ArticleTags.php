<?php
/**
 * 文章标签关联tags
 * User: singeo
 * Date: 2018/10/30 0030
 * Time: 下午 3:27
 */

namespace app\admin\model;


use think\Db;

class ArticleTags extends Base
{

    /**
     * 保存文章标签信息
     * @param $article_tags
     * @param $art_id
     * @return bool
     */
    public function saveArticleTags($article_tags,$art_id){
        $tags = array_unique(explode(',',$article_tags)) ;
        $tagsmodel = new \app\admin\model\Tags() ;
        $tagsId = $tagsmodel->insTags($tags) ;
        if(is_array($tagsId)){
            $this->insArticleTags($tagsId,$art_id) ;
        }
        return true ;
    }
    /**
     * 向article_tags表中插入标签与文章的关联
     * @param $tagsId
     * @param $art_id
     * @return bool|int|string
     */
    public function insArticleTags($tagsId,$art_id){
        if(empty($tagsId)){
            return false ;
        }
        Db::name('article_tags')
            ->where(['aid'=>$art_id])
            ->delete() ;
        $artTags = [] ;
        foreach ($tagsId as $tag){
            $artTemptag['aid'] = $art_id ;
            $artTemptag['tags_id'] = $tag ;
            $artTags[] = $artTemptag ;
        }
        return Db::name('article_tags')->insertAll($artTags) ;
    }

    /**
     * 获取文章的标签
     * @param $art_id
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function getArticleTags($art_id){
        if(!$art_id){
            return false ;
        }
        $where['at.aid'] = $art_id ;
        $join[] = ['tags as t','t.tags_id = at.tags_id'] ;
        $field = 'tags_name' ;
        $tags = Db::name('article_tags')
            ->alias('at')
            ->join($join)
            ->where($where)
            ->field($field)
            ->select() ;
        return $tags ;
    }
}