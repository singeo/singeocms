<?php
/**
 * 导航分类管理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 4:16
 */

namespace app\admin\controller;


use think\Db;

class NavigationCategory extends Base
{
    /**
     * 导航分类列表
     */
    public function index(){
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $where['status'] = 1 ;
        $field = 'cate_id,cate_name,remark,create_time' ;
        $orderby = 'cate_id DESC' ;
        $list = $categorymodel->getList($where,$field,null,$orderby) ;
        $this->assign('list',$list) ;
        return $this->fetch() ;
    }

    /**
     * 添加导航分类
     */
    public function cateadd(){
        echo $this->fetch('cateadd') ;
    }

    /**
     * 提交添加导航分类
     */
    public function submitCateAdd(){
        $param = $this->request->param() ;
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $result = $categorymodel->saveNavigationCategory($param) ;
        if(!$result){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 修改导航分类
     */
    public function cateedit(){
        $cate_id = $this->request->param('cid') ;
        $where = null ;
        $field = 'cate_id,cate_name,remark,status' ;
        $where['cate_id'] = $cate_id ;
        $cateinfo = Db::name('navigation_category')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('cateinfo',$cateinfo) ;
        $this->assign('cid',$cate_id) ;
        echo $this->fetch('cateedit') ;
    }

    /**
     * 提交修改导航分类
     */
    public function submitCateEdit(){
        $param = $this->request->param() ;
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $result = $categorymodel->updateNavigationCategory($param) ;
        if(!$result){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $this->success('修改成功') ;
        }
    }

    /**
     * 删除导航分类
     */
    public function cateDel(){
        $cate_id = $this->request->param('cid') ;
        if(empty($cate_id)){
            $this->error('参数错误') ;
        }
        $where['cate_id'] = $cate_id ;
        $field = 'cate_id,cate_name' ;
        $cateInfo = Db::name('navigation_category')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$cateInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交删除导航分类
     */
    public function submitCateDel(){
        $cid = $this->request->param('cid') ;
        if(empty($cid)){
            $this->error('参数错误') ;
        }
        $upData['status'] = -1 ;
        $result = Db::name('navigation_category')
            ->where(['cate_id'=>$cid])
            ->update($upData) ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}