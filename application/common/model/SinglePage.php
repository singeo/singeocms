<?php
/**
 * 单页管理model 信息
 * User: singeo
 * Date: 2018/11/26 0026
 * Time: 下午 2:38
 */

namespace app\common\model;


use think\Db;

class SinglePage extends Base
{
    /**
     * 获取单页信息
     * @param $cid
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getSingleInfo($cid){
        $where['cid'] = $cid ;
        $field = 'p_content' ;
        $info = Db::name('single_page')->where($where)->field($field)->find() ;
        return $info ;
    }
}