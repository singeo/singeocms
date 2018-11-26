<?php
/**
 * 网站栏目分类模型
 * User: singeo
 * Date: 2018/11/15 0015
 * Time: 下午 3:45
 */

namespace app\common\model;


use app\admin\library\TreeShape;
use think\Db;

class Arctype extends Base
{
    /**
     * 获取所有栏目
     */
    public function getAllColumu(){
        $cacheKey = 'arctype_get_all_column' ;
        $result = cache($cacheKey) ;
        if(empty($result)){
            $orderby = 'pid ASC,sort ASC,cid' ;
            $result = Db::name('arctype')
                ->where(null)
                ->order($orderby)
                ->cache(true,SINGEO_CACHE_TIME,'arctype')
                ->select() ;
            cache($cacheKey, $result, null, 'arctype');
        }
        return $result ;
    }

    /**
     * 获取当前栏目及所有子栏目
     * @param $cid
     * @param bool $self 包括自己本身
     */
    public function getHasChildren($cid,$self = true){
        $cacheKey = 'arctype_get_has_children_'.$cid.'_'.$self ;
        $result = cache($cacheKey) ;
        if(empty($result)){
            $all_column = $this->getAllColumu() ;
            $result = TreeShape::childList($all_column,$cid,'cid','pid',1,$self) ;
            cache($cacheKey, $result, null, 'arctype');
        }
        return $result ;
    }
}