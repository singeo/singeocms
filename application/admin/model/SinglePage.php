<?php
/**
 * 单页页面管理模型
 * User: singeo
 * Date: 2018/11/7 0007
 * Time: 下午 2:34
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class SinglePage extends Base
{
    /**
     * 单页页面编辑
     * @param $data
     * @return array
     */
    public function saveSinglePage($data){
        $cid = $data['cid'] ;
        $where['cid'] = $cid ;
        $count = Db::name('single_page')->where($where)->count() ;
        if($count > 0){
            unset($data['cid']) ;
            $data['update_time'] = time() ;
            $rst = Db::name('single_page')->where($where)->update($data) ;
        }else{
            $data['update_time'] = time() ;
            $rst = Db::name('single_page')->insert($data) ;
        }
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}