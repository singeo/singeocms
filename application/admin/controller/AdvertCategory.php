<?php
/**
 * 广告分类管理
 * User: singeo
 * Date: 2018/11/2 0002
 * Time: 上午 9:58
 */

namespace app\admin\controller;


use think\Db;

class AdvertCategory extends Base
{
    /**
     * 分类列表
     */
    public function index(){
        $categorymodel = new \app\admin\model\AdvertCategory() ;
        $where['status'] = 1 ;
        $orderby = 'sort ASC,cid DESC' ;
        $feild = 'cid,c_name,c_desc,status,sort,create_time' ;
        $catelist = $categorymodel->getList($where,$feild,null,$orderby) ;
        if($catelist === false){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $this->assign('catelist',$catelist) ;
        }
        return $this->fetch() ;
    }

    /**
     * 添加广告分类
     */
    public function cateadd(){
        echo $this->fetch('cateadd') ;
    }

    /**
     * 提交添加广告
     */
    public function submitCateAdd(){
        $param = $this->request->param() ;
        $categorymodel = new \app\admin\model\AdvertCategory() ;
        $result = $categorymodel->saveAdvertCategory($param) ;
        if(!$result){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 修改文章分类
     */
    public function cateedit(){
        $cate_id = $this->request->param('cid') ;
        $where = null ;
        $field = 'cid,c_name,c_desc,status,sort' ;
        $where['cid'] = $cate_id ;
        $cateinfo = Db::name('advert_category')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('cateinfo',$cateinfo) ;
        $this->assign('cid',$cate_id) ;
        echo $this->fetch('cateedit') ;
    }

    /**
     * 提交添加文章分类
     */
    public function submitCateEdit(){
        $param = $this->request->param() ;
        $categorymodel = new \app\admin\model\AdvertCategory() ;
        $result = $categorymodel->updateAdvertCategory($param) ;
        if(!$result){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $this->success('修改成功') ;
        }
    }

    /**
     * 删除菜单
     */
    public function cateDel(){
        $cate_id = $this->request->param('cid') ;
        if(empty($cate_id)){
            $this->error('参数错误') ;
        }
        $where['cid'] = $cate_id ;
        $field = 'cid,c_name' ;
        $cateInfo = Db::name('advert_category')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$cateInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交删除分类
     */
    public function submitCateDel(){
        $cid = $this->request->param('cid') ;
        if(empty($cid)){
            $this->error('参数错误') ;
        }
        $upData['status'] = -1 ;
        $upData['update_time'] = time() ;
        $result = Db::name('advert_category')
            ->where(['cid'=>$cid])
            ->update($upData) ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}