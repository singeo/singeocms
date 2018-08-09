<?php
/**
 * 分类管理模型
 * User: singeo
 * Date: 2018/8/9 0009
 * Time: 上午 9:48
 */

namespace app\admin\model;


use think\Db;
use think\Exception;

class Category extends Base
{

    /**
     * 获取分类类型模型
     * @return false
     */
    public function getCatelist(){
        try{
            $feild = 'c.cid,c.pid,c.cate_title,c.cate_desc,c.status,c.sort,cm.mode_title' ;
            $where['c.status'] = 1 ;
            $orderby = 'c.sort ASC,c.cid DESC' ;
            $join[] = ['category_mode as cm','cm.id = c.cate_mode'] ;
            $list = Db::name('category')
                ->alias('c')
                ->join($join)
                ->where($where)
                ->field($feild)
                ->order($orderby)
                ->select() ;
            return $list ;
        }catch (Exception $e){
            $this->setErrorMsg('没有数据') ;
            return false ;
        }
    }
}