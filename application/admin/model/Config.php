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
        $rst = Db::name('Config')->insert($data) ;
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
     * 修改配置项
     * @param $data
     * @return array
     */
    public function updateConfig($data){
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
        $where['id'] = $data['id'] ;
        unset($data['id']) ;
        $rst = Db::name('Config')->where($where)->update($data) ;
        if($rst){
            $arr['status'] = 1 ;
            $arr['msg'] = 'success' ;
            return $arr;
        }else{
            $arr['msg'] = 'fail' ;
            return $arr;
        }
    }


    public function storeConfig($group_id, $data){
        $arr['status'] = 0 ;
        if(empty($data)){
            $arr['msg'] = '没有可保存的数据' ;
            return $arr ;
        }
        $field = 'c_key,c_value' ;
        $stored = Db::name('Config')
            ->where(['c_group'=>$group_id,'status'=>1])
            ->field($field)
            ->select() ;
        if(empty($stored)){
            $arr['msg'] = '没有可保存的数据' ;
            return $arr ;
        }else{
            $c_stored = [] ;
            foreach ($stored as $item){
                $c_stored[$item['c_key']] = $item['c_value'] ;
            }
            foreach ($data as $key=>$datum) {
                if($c_stored[$key] != $datum){
                    $c_data['c_value'] = $datum ;
                    Db::name('Config')->where(['c_key'=>$key])->update($c_data) ;
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
            Cache::set('web_config', $rel_config) ;
            $arr['status'] = 1 ;
            $arr['msg'] = '更新成功' ;
            return $arr ;
        }
    }
}