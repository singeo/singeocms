<?php
/**
 * 后台管理员管理
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 上午 11:25
 */

namespace app\admin\controller;


use think\Config;
use think\Db;

class Consoleuser extends Base
{
    /**
     * 管理员列表
     */
    public function index(){
        $consoleUser = new \app\admin\model\ConsoleUser() ;
        $where['id'] = ['neq',config('super_admin_uid')] ;
        $field = 'id,user_login,user_nickname,user_email,mobile,user_status,login_times,last_login_time,last_login_ip' ;
        $userList = $consoleUser
            ->where($where)
            ->field($field)
            ->paginate() ;
        $this->assign('userList',$userList) ;
        return $this->fetch() ;
    }

    /**
     * 新增管理员
     */
    public function userAdd(){
        echo $this->fetch() ;
    }

    /**
     * 提交新增用户
     */
    public function submitAddUser(){
        $param = $this->request->param() ;
        $consoleusermodel = new \app\admin\model\ConsoleUser() ;
        $result = $consoleusermodel->saveUser($param) ;
        if($result['status'] == 0){
            $this->error($result['msg']) ;
        }else{
            $this->success($result['msg']) ;
        }
    }

    /**
     * 修改管理员
     */
    public function userEdit(){
        $user_id = $this->request->param('user_id') ;
        if(empty($user_id)){
            $this->error('参数错误') ;
        }
        $consoleUser = new \app\admin\model\ConsoleUser() ;
        $where['id'] = $user_id ;
        //$where['user_status'] = 1 ;
        $field = 'id,user_login,user_nickname,user_email,mobile,user_status' ;
        $userInfo = $consoleUser->find($where,$field) ;
        $this->assign('info',$userInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改用户
     */
    public function submitEditUser(){
        $param = $this->request->param() ;
        $consoleusermodel = new \app\admin\model\ConsoleUser() ;
        $result = $consoleusermodel->updateUser($param) ;
        if($result['status'] == 0){
            $this->error($result['msg']) ;
        }else{
            $this->success($result['msg']) ;
        }
    }

    /**
     * 设置用户角色
     */
    public function userRole(){
        //查询出所有角色
        $user_id = $this->request->param('user_id') ;
        if(empty($user_id)){
            $this->error('参数错误') ;
        }
        $rolelist = Db::name('console_role')
            ->where(['status'=>1])
            ->field('id,role_name')
            ->select() ;
        $this->assign('rolelist',$rolelist) ;
        //查询用户已分配的角色
        $uRoles = Db::name('console_role_user')
            ->where(['user_id'=>$user_id])
            ->field('role_id')
            ->select() ;
        if(!empty($uRoles)){
            $userRole = array_column($uRoles,'role_id') ;
        }else{
            $userRole = [] ;
        }
        $this->assign('userRole',$userRole) ;
        $this->assign('user_id',$user_id) ;
        echo $this->fetch() ;
    }

    /**
     * 提交分配角色
     */
    public function submitUserRole(){
        //查询出所有角色
        $param = $this->request->param() ;
        $user_id = $param['user_id'] ;
        if(empty($user_id)){
            $this->error('参数错误') ;
        }
        $roles = $param['role_id'] ;
        if(empty($roles)){
            $this->error('至少选择一个角色') ;
        }
        $where['user_id'] = $user_id ;
        Db::name('console_role_user')->where($where)->delete() ;
        $roleData = [] ;
        foreach ($roles as $key=>$val){
            $roleData['user_id'] = $user_id ;
            $roleData['role_id'] = $val ;
        }
        if(!empty($roleData)){
            $result = Db::name('console_role_user')->insert($roleData) ;
            if($result){
                $this->success('角色分配成功') ;
            }else{
                $this->error('角色分配失败') ;
            }
        }else{
            $this->error('至少选择一个角色') ;
        }
    }
}