<?php
/**
 * 前台导航管理模型
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 4:52
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class Navigation extends Base
{
    //验证规则
    protected $rule = [
        'nav_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'nav_name.require' => '导航名称名必须',
        'nav_name.max'     => '导航名称长度小于30',
        'nav_name.token'   => '不能重复提交',
    ];
    /**
     * 导航新增
     * @param $data
     * @return array
     */
    public function saveNavigation($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('navigation')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 导航修改
     * @param $data
     * @return array
     */
    public function updateNavigation($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['nav_id'] = $data['nav_id'] ;
        unset($data['nav_id']) ;
        $rst = Db::name('Navigation')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}