<?php
/**
 * 配置项模型
 * User: 冯欣
 * Date: 2018/5/30 0030
 * Time: 下午 2:48
 */

namespace app\admin\model;


use think\Cache;
use think\Db;
use think\Validate;

class Config extends Base
{
    //验证规则
    protected $rule = [
        'c_name'  => ['require','max' => 20,'token' => 'token_hash'],
        'c_key'  => ['require','unique:config'],
    ];
    //验证错误提示信息
    protected $message = [
        'c_name.require' => '配置项名称必须',
        'c_name.max'     => '配置项名称长度小于20',
        'c_name.token'   => '不能重复提交',
        'c_key.require'   => '配置项key必须',
        'c_key.unique'   => '配置项key不能重复',
    ];
    /**
     * 新增配置项
     * @param $data
     * @return array
     */
    public function saveConfig($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $rst = Db::name('Config')->insert($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('failed') ;
            return false;
        }
    }

    /**
     * 修改配置项
     * @param $data
     * @return array
     */
    public function updateConfig($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['id'] = $data['id'] ;
        unset($data['id']) ;
        $rst = Db::name('Config')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }


    /**
     * 保存配置数据
     * @param $group_id
     * @param $data
     * @return mixed
     */
    public function storeConfig($group_id, $data){
        if(empty($data)){
            $this->setErrorMsg('没有可保存的数据') ;
            return false;
        }
        $field = 'c_key,c_value,c_type' ;
        $stored = Db::name('Config')
            ->where(['c_group'=>$group_id,'status'=>1])
            ->field($field)
            ->select() ;
        if(empty($stored)){
            $this->setErrorMsg('没有可保存的数据') ;
            return false;
        }else{
            $c_stored = array_combine(array_column($stored,'c_key'),$stored) ;
            /**foreach ($stored as $item){
                $c_stored[$item['c_key']] = $item['c_value'] ;
                if($item['c_type'] == 'file' && !empty($item['c_value'])){
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $item['c_value']) ;
                }
            }**/
            foreach ($data as $key=>$datum) {
                if($c_stored[$key]['c_value'] != $datum){
                    $c_data['c_value'] = $datum ;
                    Db::name('Config')->where(['c_key'=>$key])->update($c_data) ;
                    if($c_stored[$key]['c_type'] == 'file'){
                        @unlink($_SERVER['DOCUMENT_ROOT'].$c_stored[$key]['c_value']) ;
                    }
                }
            }
            $config = Db::name('Config')
                ->where(['status'=>1])
                ->field($field)
                ->select() ;
            $rel_config = [] ;
            foreach ($config as $item){
                $rel_config[$item['c_key']] = $item['c_value'] ;
            }
            //更新完成将配置写入到缓存中去
            Cache::set(config('web_config_catch'), $rel_config) ;
            return true ;
        }
    }
}