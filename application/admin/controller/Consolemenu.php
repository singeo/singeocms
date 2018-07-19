<?php
/**
 * 后台菜单
 * User: 冯欣
 * Date: 2018/5/24 0024
 * Time: 上午 10:26
 */

namespace app\admin\controller;


use app\admin\library\TreeShape;
use think\Db;

class Consolemenu extends Base
{
    /**
     * 列表
     */
    public function index(){
        $field = 'id,parent_id,type,status,menu_name,menu_url,sort' ;
        $orderby = 'sort ASC,id DESC' ;
        $menulist = Db::name('console_menu')
            ->field($field)
            ->order($orderby)
            ->select() ;
        $menulist = TreeShape::tree($menulist,'menu_name','id', 'parent_id') ;
        $this->assign('menulist',$menulist) ;
        return $this->fetch() ;
    }


    /**
     * 新增菜单列表
     */
    public function menuAdd(){
        $menu_id = $this->request->param('menu_id') ;
        $field = 'id,parent_id,menu_name' ;
        $where['type'] = 1 ;// 菜单
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,id DESC' ;
        $menulist = Db::name('console_menu')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $menulist = TreeShape::tree($menulist,'menu_name','id', 'parent_id') ;
        $this->assign('menuTree',$menulist) ;
        $this->assign('curMenuID',$menu_id) ;
        echo $this->fetch() ;
    }

    /**
     * 提交新增菜单
     */
    public function submitMenuAdd(){
        $param = $this->request->param() ;
        $consolemenumodel = new \app\admin\model\ConsoleMenu() ;
        $result = $consolemenumodel->saveMenu($param) ;
        if(!$result){
            $this->error($consolemenumodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 修改菜单
     */
    public function menuEdit(){
        $menu_id = $this->request->param('menu_id') ;
        if(empty($menu_id)){
            $this->error('参数错误') ;
        }
        //获取菜单
        $field = 'id,parent_id,menu_name' ;
        $where['type'] = 1 ;// 菜单
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,id DESC' ;
        $menulist = Db::name('console_menu')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $menuTree = TreeShape::tree($menulist,'menu_name','id', 'parent_id') ;
        $this->assign('menuTree',$menuTree) ;
        //获取规则信息
        $where = null ;
        $where['id'] = $menu_id ;
        $field = 'id,parent_id,type,status,menu_name,menu_icon,menu_url,sort' ;
        $menuInfo = Db::name('console_menu')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$menuInfo) ;
        $this->assign('curMenuID',$menuInfo['parent_id']) ;
        echo $this->fetch() ;
    }

    /**
     * 修改菜单
     */
    public function submitMenuEdit(){
        $param = $this->request->param() ;
        $consolemenumodel = new \app\admin\model\ConsoleMenu() ;
        $result = $consolemenumodel->updateMenu($param) ;
        if(!$result){
            $this->error($consolemenumodel->getErrorMsg()) ;
        }else{
            $this->success('修改成功') ;
        }
    }

    /**
     * 删除菜单
     */
    public function menuDel(){
        $menu_id = $this->request->param('menu_id') ;
        if(empty($menu_id)){
            $this->error('参数错误') ;
        }
        $where['id'] = $menu_id ;
        $field = 'id,menu_name' ;
        $menuInfo = Db::name('console_menu')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$menuInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交删除菜单
     */
    public function submitMenuDel(){
        $menu_id = $this->request->param('id') ;
        if(empty($menu_id)){
            $this->error('参数错误') ;
        }
        $result = Db::name('console_menu')->where(['id'=>$menu_id])->delete() ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}