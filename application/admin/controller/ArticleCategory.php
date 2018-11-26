<?php
/**
 * 文章分类管理 ,后台管理文章分类的类型
 * User: singeo
 * Date: 2018/8/9 0009
 * Time: 上午 9:35
 */
namespace app\admin\controller;

use app\admin\library\TreeShape;
use think\Db;

class ArticleCategory extends Base
{
    /**
     * 文章分类管理页面
     * @return mixed
     */
    public function index(){
        $categorymodel = new \app\admin\model\ArticleCategory() ;
        $where['status'] = 1 ;
        $orderby = 'sort ASC,cid DESC' ;
        $feild = 'cid,pid,cate_title,cate_desc,status,sort' ;
        $catelist = $categorymodel->getList($where,$feild,null,$orderby) ;
        if($catelist === false){
            $this->error($categorymodel->getErrorMsg()) ;
        }else{
            $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
            $this->assign('catelist',$catelist) ;
        }
        return $this->fetch() ;
    }

    /**
     * 添加文章分类
     */
    public function cateadd(){
        $cate_id = $this->request->param('cate_id') ;
        $field = 'cid,pid,cate_title' ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = Db::name('article_category')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
        $this->assign('cateTree',$catelist) ;
        $this->assign('cate_id',$cate_id) ;
        echo $this->fetch('cateadd') ;
    }

    /**
     * 提交添加文章分类
     */
    public function submitCateAdd(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $categorymodel = new \app\admin\model\ArticleCategory() ;
        $result = $categorymodel->saveArticleCategory($param,$file) ;
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
        $cate_id = $this->request->param('cate_id') ;
        $field = 'cid,pid,cate_title' ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = Db::name('article_category')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
        $this->assign('cateTree',$catelist) ;
        $where = null ;
        $field = 'cid,pid,cate_title,cate_desc,seo_keywords,seo_desc,cate_pic,status,sort' ;
        $where['cid'] = $cate_id ;
        $cateinfo = Db::name('article_category')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('cateinfo',$cateinfo) ;
        $this->assign('cate_id',$cate_id) ;
        echo $this->fetch('cateedit') ;
    }

    /**
     * 提交添加文章分类
     */
    public function submitCateEdit(){
        $param = $this->request->param() ;
        $categorymodel = new \app\admin\model\ArticleCategory() ;
        $result = $categorymodel->updateArticleCategory($param) ;
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
        $cate_id = $this->request->param('cate_id') ;
        if(empty($cate_id)){
            $this->error('参数错误') ;
        }
        $where['cid'] = $cate_id ;
        $field = 'cid,cate_title' ;
        $cateInfo = Db::name('article_category')
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
        $cate_count = Db::name('article_category')->where(['pid'=>$cid])->count() ;
        if($cate_count > 0){
            $this->error('该分类下存在子菜单，不能删除') ;
        }
        $result = Db::name('article_category')->where(['cid'=>$cid])->delete() ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}
