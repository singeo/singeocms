<?php
/**
 * 前台文章分类管理模型
 * User: singeo
 * Date: 2018/11/12 0012
 * Time: 下午 5:19
 */

namespace app\common\model;


use think\Db;

class ArticleCategory extends Base
{
    /**
     * 获取分类信息
     * @param $cid 栏目ID
     * @return array|bool
     */
    public function getCateInfo($cid){
        if(empty($cid)){
            return false ;
        }
        $field = 'cid,pid,cate_title,seo_keywords,seo_desc,cate_desc,cate_pic' ;
        $where['status'] = 1 ;
        $where['cid'] = $cid ;
        $info = $this->find($where,$field) ;
        return $info ;
    }
}