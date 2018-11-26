<?php
/**
 * 网站配置表管理
 * User: singeo
 * Date: 2018/11/12 0012
 * Time: 下午 4:26
 */
namespace app\common\model;

use think\Db;
use think\Cache;
class Config extends Base
{
    /**
     * 设置网站配置
     */
    public function setWebConfig(){
        $field = 'c_key,c_value' ;
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
    }
}