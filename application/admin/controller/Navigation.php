<?php
/**
 * 前端导航管理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 4:48
 */

namespace app\admin\controller;


use app\admin\library\TreeShape;
use think\Db;

class Navigation extends Base
{
    /**
     * 导航列表
     * @return mixed
     */
    public function index(){
        $navcate_id = $this->request->param('cate_id/d',1) ;
        $navigationmodel = new \app\admin\model\Navigation() ;
        $where['status'] = 1 ;
        $where['nav_cate_id'] = $navcate_id ;
        $orderby = 'sort ASC,nav_id DESC' ;
        $feild = 'nav_id,nav_cate_id,pid,nav_name,nav_link,sort' ;
        $list = $navigationmodel->getList($where,$feild,null,$orderby) ;
        if($list === false){
            $this->error($navigationmodel->getErrorMsg()) ;
        }
        $list = TreeShape::tree($list,'nav_name','nav_id', 'pid') ;
        $this->assign('list',$list) ;
        $this->assign('navcate_id',$navcate_id) ;

        //导航分类
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $where = [] ;
        $where['status'] = 1 ;
        $field = 'cate_id,cate_name' ;
        $orderby = 'cate_id DESC' ;
        $catelist = $categorymodel->getList($where,$field,null,$orderby) ;
        $this->assign('catelist',$catelist) ;
        return $this->fetch() ;
    }

    /**
     * 新增导航
     */
    public function navAdd(){
        $navcate_id = $this->request->param('cate_id/d',1) ;
        $nav_id = $this->request->param('nav_id/d',1) ;
        //导航分类
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $where['status'] = 1 ;
        $field = 'cate_id,cate_name' ;
        $orderby = 'cate_id DESC' ;
        $catelist = $categorymodel->getList($where,$field,null,$orderby) ;
        $this->assign('catelist',$catelist) ;
        //当前分类下的导航
        $navigationmodel = new \app\admin\model\Navigation() ;
        $where = [] ;
        $where['status'] = 1 ;
        $where['nav_cate_id'] = $navcate_id ;
        $orderby = 'sort ASC,nav_id DESC' ;
        $feild = 'nav_id,nav_cate_id,pid,nav_name,nav_link,sort' ;
        $navlist = $navigationmodel->getList($where,$feild,null,$orderby) ;
        $navlist = TreeShape::tree($navlist,'nav_name','nav_id', 'pid') ;
        $this->assign('cateTree',$navlist) ;
        $this->assign('navcate_id',$navcate_id) ;
        $this->assign('nav_id',$nav_id) ;
        echo $this->fetch('navadd') ;
    }

    /**
     * 提交新增导航
     */
    public function submitNavAdd(){
        $param = $this->request->param() ;
        $navmodel = new \app\admin\model\Navigation() ;
        $result = $navmodel->saveNavigation($param) ;
        if(!$result){
            $this->error($navmodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 编辑导航
     * @return mixed
     */
    public function navEdit(){
        $nav_id = $this->request->param('nav_id/d',1) ;
        //获取导航信息
        $navigationmodel = new \app\admin\model\Navigation() ;
        $where['nav_id'] = $nav_id ;
        $field = 'nav_id,nav_cate_id,pid,nav_name,nav_link,sort,status' ;
        $orderby = 'nav_id DESC' ;
        $navi_info = $navigationmodel->find($where,$field,$orderby) ;
        //获取导航分类列表
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $where = [] ;
        $where['status'] = 1 ;
        $field = 'cate_id,cate_name' ;
        $orderby = 'cate_id DESC' ;
        $catelist = $categorymodel->getList($where,$field,null,$orderby) ;
        $this->assign('catelist',$catelist) ;
        //当前分类下的导航
        $where = [] ;
        $where['status'] = 1 ;
        $where['nav_cate_id'] = $navi_info['nav_cate_id'] ;
        $orderby = 'sort ASC,nav_id DESC' ;
        $feild = 'nav_id,nav_cate_id,pid,nav_name,nav_link,sort' ;
        $navlist = $navigationmodel->getList($where,$feild,null,$orderby) ;
        $navlist = TreeShape::tree($navlist,'nav_name','nav_id', 'pid') ;
        $this->assign('cateTree',$navlist) ;
        $is_serialize = is_serialized($navi_info['nav_link']) ;
        if($is_serialize){
            $link_attr = 1 ;
            $tpl = unserialize($navi_info['nav_link'])['tpl'] ;
        }else{
            $link_attr = 2 ;
            $tpl = '' ;
        }
        $this->assign('tpl',$tpl) ;
        $this->assign('link_attr',$link_attr) ;
        $this->assign('info',$navi_info) ;
        $this->assign('navcate_id',$navi_info['nav_cate_id']) ;
        $this->assign('nav_id',$nav_id) ;
        echo $this->fetch('navedit') ;
    }

    /**
     * 提交修改导航
     */
    public function submitNavEdit(){
        $param = $this->request->param() ;
        $navmodel = new \app\admin\model\Navigation() ;
        $result = $navmodel->updateNavigation($param) ;
        if(!$result){
            $this->error($navmodel->getErrorMsg()) ;
        }else{
            $this->success('编辑成功') ;
        }
    }

    /**
     * 删除导航
     */
    public function navDel(){
        $nav_id = $this->request->param('nav_id') ;
        if(empty($nav_id)){
            $this->error('参数错误') ;
        }
        $where['nav_id'] = $nav_id ;
        $field = 'nav_id,nav_name' ;
        $navInfo = Db::name('navigation')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$navInfo) ;
        echo $this->fetch('navdel') ;
    }

    /**
     * 提交删除导航
     */
    public function submitNavDel(){
        $nav_id = $this->request->param('nav_id') ;
        if(empty($nav_id)){
            $this->error('参数错误') ;
        }
        $nav_count = Db::name('navigation')->where(['pid'=>$nav_id])->count() ;
        if($nav_count > 0){
            $this->error('该导航下存在子导航，不能删除') ;
        }
        $result = Db::name('navigation')->where(['pid'=>$nav_id])->delete() ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}