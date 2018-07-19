<?php
/**
 * 角色管理
 * User: Administrator
 * Date: 2018/5/21 0021
 * Time: 下午 5:52
 */

namespace app\admin\controller;

use think\Db;

class Consolerole extends Base
{
    /**
     * 角色列表页
     */
    public function index(){
        $field = 'id,status,role_name,sort,create_time' ;
        $roleList = Db::name('console_role')
            ->field($field)
            ->order('sort ASC,id DESC')
            ->paginate() ;
        $this->assign('roleList',$roleList) ;
        return $this->fetch() ;
    }

    /**
     * 新增角色
     */
    public function roleAdd(){
        echo $this->fetch() ;
    }

    /**
     * 提交新增角色
     */
    public function submitAddRole(){
        $param = $this->request->param() ;
        $consolerolemodel = new \app\admin\model\ConsoleRole() ;
        $result = $consolerolemodel->saveRole($param) ;
        if(!$result){
            $this->error($consolerolemodel->getErrorMsg()) ;
        }else{
            $this->success('新增角色成功') ;
        }
    }

    /**
     * 修改角色
     */
    public function roleEdit(){
        $role_id = $this->request->param('role_id') ;
        if(empty($role_id)){
            $this->error('参数错误') ;
        }
        $consoleUser = new \app\admin\model\ConsoleRole() ;
        $where['id'] = $role_id ;
        //$where['user_status'] = 1 ;
        $field = 'id,status,role_name,sort' ;
        $roleInfo = $consoleUser->find($where,$field) ;
        $this->assign('info',$roleInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改角色
     */
    public function submitEditRole(){
        $param = $this->request->param() ;
        $consolerolemodel = new \app\admin\model\ConsoleRole() ;
        $result = $consolerolemodel->updateRole($param) ;
        if(!$result){
            $this->error($consolerolemodel->getErrorMsg()) ;
        }else{
            $this->success('修改角色成功') ;
        }
    }

    /**
     * 设置角色权限
     */
    public function roleRule(){
        $role_id = $this->request->param('role_id') ;
        if(empty($role_id)){
            $this->error('参数错误') ;
        }
        $where['id'] = $role_id ;// 正常
        $field = 'id,rules' ;
        $info = Db::name('console_role')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$info) ;
        //获取菜单
        $field = 'id,parent_id,menu_name' ;
        $where = null ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,id DESC' ;
        $menulist = Db::name('console_menu')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $menuTree = \app\admin\library\TreeShape::channelLevel($menulist,0,'','id','parent_id') ;
        $this->assign('menuTree',$menuTree) ;
        echo $this->fetch() ;
    }

    /**
     * 提交权限规则
     */
    public function submitRules(){
        $param = $this->request->param() ;
        $role_id = $param['role_id'] ;
        if(empty($role_id)){
            $this->error('参数错误') ;
        }
        sort($param['rules']) ;
        $data['rules'] = implode(',',$param['rules']) ;
        $result = Db::name('console_role')
            ->where(['id'=>$role_id])
            ->update($data) ;
        if(!$result){
            $this->error('授权操作失败') ;
        }else{
            $this->success('授权操作成功') ;
        }
    }
}