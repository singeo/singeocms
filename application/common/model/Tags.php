<?php
/**
 * 标签
 * User: singeo
 * Date: 2018/11/19 0019
 * Time: 下午 3:23
 */

namespace app\common\model;

use think\Db ;
class Tags extends Base
{
    /**
     * 标签列表
     * @param $row
     * @param $order
     * @return false
     */
    public function getTagslist($row, $order){
        $where['status'] = 1 ;
        $result = Db::name('tags')
            ->field('tags_id,tags_name,hits_total_num')
            ->where($where)
            ->order($order)
            ->limit($row)
            ->select() ;
        return $result ;
    }

    /**
     * 获取文章标签
     * @param $article_id
     * @return $this
     */
    public function getArticleTags($article_id){
        $where['at.aid'] = $article_id ;
        $where['tg.status'] = 1 ;
        $field = 'tg.tags_id,tg.tags_name' ;
        $orderby = 'at.tags_id ASC' ;
        $join[] = ['tags as tg','at.tags_id = tg.tags_id'] ;
        $list = Db::name('article_tags')
            ->alias('at')
            ->join($join)
            ->field($field)
            ->where($where)
            ->order($orderby)
            ->select() ;
        return $list ;
    }
}