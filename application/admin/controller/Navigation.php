<?php
/**
 * 前端导航管理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 4:48
 */

namespace app\admin\controller;


use app\admin\library\TreeShape;

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
        $this->assign('catelist',$list) ;
        $this->assign('navcate_id',$navcate_id) ;
        return $this->fetch() ;
    }

    /**
     * 新增导航
     */
    public function navAdd(){
        $navcate_id = $this->request->param('cate_id/d',1) ;
        //导航分类
        $categorymodel = new \app\admin\model\NavigationCategory() ;
        $where['status'] = 1 ;
        $field = 'cate_id,cate_name' ;
        $orderby = 'cate_id DESC' ;
        $catelist = $categorymodel->getList($where,$field,null,$orderby) ;
        $this->assign('catelist',$catelist) ;
        //当前分类下的导航
        $navigationmodel = new \app\admin\model\Navigation() ;
        $where['status'] = 1 ;
        $where['nav_cate_id'] = $navcate_id ;
        $orderby = 'sort ASC,nav_id DESC' ;
        $feild = 'nav_id,nav_cate_id,pid,nav_name,nav_link,sort' ;
        $navlist = $navigationmodel->getList($where,$feild,null,$orderby) ;
        $navlist = TreeShape::tree($navlist,'nav_name','nav_id', 'pid') ;
        $this->assign('cateTree',$navlist) ;
        $this->assign('navcate_id',$navcate_id) ;
        echo $this->fetch() ;
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
}