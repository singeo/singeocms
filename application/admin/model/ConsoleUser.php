<?php
/**
 * 后台用户模型类
 * User: singeo
 * Date: 2018/4/27 0027
 * Time: 下午 12:02
 */

namespace app\admin\model;


use think\Db;

class ConsoleUser extends Base
{

    /**
     * 新增管理员
     * @param $data
     * @return array
     */
    public function saveUser($data){
        // 数据自动验证
        if (!$this->validateData($data)) {
            $this->setErrorMsg($this->getError()) ;
            return false ;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $password_salt  = createSalt() ;
        $en_password = createPassword($data['user_pass'],$password_salt) ;
        $data['user_pass'] = $en_password ;
        $data['user_pass_salt'] = $password_salt ;
        $data['create_time'] = time() ;
        $rst = Db::name('ConsoleUser')->insert($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false ;
        }
    }

    /**
     * 修改管理员信息
     * @param $data
     * @return array
     */
    public function updateUser($data){
        $arr['status'] = 0 ;
        // 数据自动验证
        if (!$this->validateData($data)) {
            $this->setErrorMsg($this->getError()) ;
            return false ;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $id = $data['id'] ;
        unset($data['id']) ;
        $where['id'] = $id ;
        $rst = Db::name('ConsoleUser')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false ;
        }
    }
}