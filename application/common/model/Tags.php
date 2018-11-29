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
     * 获取标签信息
     * @param $tags_id
     * @return array|false|
     */
    public function getTagsInfo($tags_id){
        $where['tags_id'] = $tags_id ;
        $result = Db::name('tags')
            ->field('tags_id,tags_name,hits_total_num,hits_day_num,hits_week_num,hits_month_num')
            ->where($where)
            ->find() ;
        return $result ;
    }

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

    /**
     * 更新标签点击量
     * @param $tags_id
     * @return int|string
     */
    public function updateHitNum($tags_id){
        $where['tags_id'] = $tags_id ;
        $tagsData['hits_total_num'] = ['exp','hits_total_num + 1'] ;
        $tagsData['hits_day_num'] = ['exp','hits_total_num + 1'] ;
        $tagsData['hits_week_num'] = ['exp','hits_total_num + 1'] ;
        $tagsData['hits_month_num'] = ['exp','hits_total_num + 1'] ;
        $result = Db::name('tags')->where($where)->update($tagsData) ;
        return $result ;
    }

}