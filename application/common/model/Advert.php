<?php
/**
 * 广告模型前台管理
 * User: singeo
 * Date: 2018/12/13 0013
 * Time: 下午 5:16
 */

namespace app\common\model;


use think\Db;

class Advert extends Base
{
    /**
     * 获取广告列表
     * @param $cid
     * @param string $order
     * @param null $limit
     */
    public function getAdvertList($cid,$order = 'sort ASC, aid DESC',$limit = null){
        $where['category_id'] = $cid ;
        $field = 'aid,a_title,a_pic,link_url' ;
        $list = Db::name('advert')
            ->where($where)
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select() ;
        return $list ;
    }
}