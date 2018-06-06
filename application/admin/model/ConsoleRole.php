<?php
/**
 * 角色管理
 * User: Administrator
 * Date: 2018/5/24 0024
 * Time: 上午 9:23
 */

namespace app\admin\model;


use think\Db;
use think\Validate;

class ConsoleRole extends Base
{

    //验证规则
    protected $rule = [
        'role_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'role_name.require' => '角色名必须',
        'role_name.max'     => '用户名长度小于30',
        'role_name.token'   => '不能重复提交',
    ];
    /**
     * 新增角色
     * @param $data
     * @return array
     */
    public function saveRole($data){
        $arr['status'] = 0 ;
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $arr['msg'] = $validate->getError() ;
            return $arr;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $data['status'] = 1 ;
        $rst = Db::name('ConsoleRole')->insert($data) ;
        if($rst){
            $arr['status'] = 1 ;
            $arr['msg'] = 'success' ;
            return $arr;
        }else{
            $arr['msg'] = 'fail' ;
            return $arr;
        }
    }
    /**
     * 新增角色
     * @param $data
     * @return array
     */
    public function updateRole($data){
        $arr['status'] = 0 ;
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $arr['msg'] = $validate->getError() ;
            return $arr;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['update_time'] = time() ;
        $where['id'] = $data['id'] ;
        unset($data['id']) ;
        $rst = Db::name('ConsoleRole')->where($where)->update($data) ;
        if($rst){
            $arr['status'] = 1 ;
            $arr['msg'] = 'success' ;
            return $arr;
        }else{
            $arr['msg'] = 'fail' ;
            return $arr;
        }
    }
}