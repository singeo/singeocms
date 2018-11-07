<?php
/**
 * 广告分类模型
 * User: singeo
 * Date: 2018/11/2 0002
 * Time: 上午 10:44
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class AdvertCategory extends Base
{
    //验证规则
    protected $rule = [
        'c_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'c_name.require' => '广告分类名称名必须',
        'c_name.max'     => '广告分类名称长度小于30',
        'c_name.token'   => '不能重复提交',
    ];
    /**
     * 广告分类新增
     * @param $data
     * @return array
     */
    public function saveAdvertCategory($data){
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
        $rst = Db::name('AdvertCategory')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 广告分类修改
     * @param $data
     * @return array
     */
    public function updateAdvertCategory($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['update_time'] = time() ;
        $where['cid'] = $data['cid'] ;
        unset($data['cid']) ;
        $rst = Db::name('AdvertCategory')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}