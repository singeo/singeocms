<?php
/**
 * 分类管理 ,后台管理分类的类型
 * User: singeo
 * Date: 2018/8/9 0009
 * Time: 上午 9:35
 */
namespace app\admin\controller;

use app\admin\library\TreeShape;
use think\Db;

class Category extends Base
{
    /**
     * 分类管理页面
     * @return mixed
     */
    public function index(){
        $categorymodel = new \app\admin\model\Category() ;
        $catelist = $categorymodel->getCatelist() ;
        if($catelist === false){
            return $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
            $this->assign('catelist',$catelist) ;
        }
        return $this->fetch() ;
    }

    /**
     * 添加分类
     */
    public function cateadd(){
        $cate_id = $this->request->param('cate_id') ;
        $field = 'cid,pid,cate_title' ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = Db::name('category')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
        $where = null ;
        $where['status'] = 1 ;
        $field = 'id,mode_title' ;
        $orderby = 'sort ASC,id DESC' ;
        $catemode = Db::name('category_mode')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $this->assign('catemode',$catemode) ;
        $this->assign('cateTree',$catelist) ;
        $this->assign('cate_id',$cate_id) ;
        echo $this->fetch('cateadd') ;
    }
}
