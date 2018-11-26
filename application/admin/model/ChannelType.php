<?php
/**
 * 文档模型管理model
 * User: singeo
 * Date: 2018/11/13 0013
 * Time: 下午 4:12
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class ChannelType extends Base
{
    //验证规则
    protected $rule = [
        'm_title'  => ['require','max' => 30,'token' => 'token_hash'],
        'm_act'=>['require'],
    ];
    //验证错误提示信息
    protected $message = [
        'm_title.require' => '模型标题必须',
        'm_title.max'     => '模型标题长度小于30',
        'm_title.token'   => '不能重复提交',
        'm_act.require' => '模型操作地址必须',
    ];
    /**
     * 文档模型新增
     * @param $data
     * @return array
     */
    public function saveChannelType($data){
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
        $rst = Db::name('channel_type')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 文档模型修改
     * @param $data
     * @return array
     */
    public function updateChannelType($data){
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
        $where['id'] = $data['channel_id'] ;
        unset($data['channel_id']) ;
        $rst = Db::name('channel_type')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}