<?php
/**
 * 模型基类
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 上午 10:14
 */

namespace app\admin\model;
use think\Collection;
use think\Exception;
use think\Model;

class Base extends Model
{
    /**
     * 查询多条记录
     * @param null $where 查询条件
     * @param null $field 需要查询的字段
     * @param null $limit 查询条数限制
     * @param null $order 排序
     * @return false|\PDOStatement|string|\think\Collection|array
     */
    public function getList($where = null,$field = null,$limit = null,$order = null)
    {
        try{
            $result = self::where($where)
                ->field($field)
                ->limit($limit)
                ->order($order)
                ->select() ;
            if(!empty($result)){
                return Collection::make($result)->toArray() ;
            }else{
                return false ;
            }
        }catch (Exception $e){
            return false ;
        }
    }

    /**
     * 查询单条记录
     * @param null $where 条件
     * @param null $field 字段
     * @param null $order 排序
     * @return array|bool
     */
    public function find($where = null,$field = null,$order = null){
        try{
            $result = self::where($where)
                ->field($field)
                ->order($order)
                ->find() ;
            if(!empty($result)){
                return $result->toArray() ;
            }else{
                return false ;
            }
        }catch (Exception $e){
            return false ;
        }
    }
}