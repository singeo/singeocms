<?php
/**
 * 抽奖
 * User: singeo
 * Date: 2018/11/5 0005
 * Time: 下午 3:09
 *   CREATE TABLE `g_reward_activity` (
 *       `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
 *       `activity_name` varchar(255) DEFAULT NULL COMMENT '活动名称',
 *       `activity_type` tinyint(3) DEFAULT NULL COMMENT '抽奖活动类型',
 *       `award_num` smallint(6) unsigned DEFAULT '0' COMMENT '奖品数量',
 *       `start_time` int(10) DEFAULT NULL COMMENT '抽奖开始时间',
 *       `end_time` int(10) DEFAULT NULL COMMENT '抽奖结束时间',
 *       `status` tinyint(1) DEFAULT '1' COMMENT '状态1正常，-1失效',
 *       `create_time` int(10) DEFAULT NULL COMMENT '活动创建时间',
 *       PRIMARY KEY (`id`)
 *   ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='设置抽奖活动';
 *   CREATE TABLE `g_reward_set` (
 *       `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
 *       `activity_type` tinyint(1) DEFAULT NULL COMMENT '所属活动类型',
 *       `award_name` varchar(255) DEFAULT NULL COMMENT '奖品名称',
 *       `award_num` smallint(6) unsigned DEFAULT '0' COMMENT '奖品数量',
 *       `remain_award_num` smallint(6) unsigned DEFAULT '0' COMMENT '剩余奖品数量',
 *       `draw_rate` decimal(7,2) DEFAULT '0.00' COMMENT '中奖概率',
 *       `status` tinyint(1) DEFAULT '1' COMMENT '状态1正常，-1失效',
 *       `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
 *       PRIMARY KEY (`id`)
 *   ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='设置活动的奖品';
 *   CREATE TABLE `g_reward_record` (
 *       `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
 *       `uid` int(11) DEFAULT NULL COMMENT '用户ID',
 *       `reward_id` int(11) DEFAULT NULL COMMENT '中奖ID',
 *       `create_time` int(10) DEFAULT NULL COMMENT '中奖时间',
 *       PRIMARY KEY (`id`)
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='中奖纪录';
 */

namespace app\common\library;


use think\Db;

class DrawAward
{
    /**
     * 执行抽奖
     * @return mixed
     */
    public function runDraw(){
        $arr['status'] = 0 ;
        Db::startTrans() ;
        $awardList = $this->getAward(1) ;
        if(empty($awardList)){
            Db::rollback() ;
            $arr['msg'] = '没有可以抽奖的奖品' ;
            return $arr ;
        }
        //根据奖品的概率获取抽奖;
        $rateNum = array_sum(array_column($awardList,'draw_rate')) ;
        $award = $this->getRand($rateNum, $awardList) ;
        $wResult = $this->grantAward($award) ;
        if($wResult['status'] == 0){
            Db::rollback() ;
            $arr['msg'] = $wResult['msg'] ;
            return $arr ;
        }
        Db::commit() ;
        $arr['status'] = 1 ;
        $arr['msg'] = 'success' ;
        return $arr ;
    }
    /**
     * 获取抽奖奖品
     * @param $atype
     * @return false|\PDOStatement|string|\think\Collection
     */
    private function getAward($atype){
        $where['activity_type'] = $atype ;
        $where['remain_award_num'] = ['gt', 0] ;
        $field = 'id,award_name,award_key,draw_rate' ;
        $award = Db::name('reward_set')
            ->where($where)
            ->field($field)
            ->select() ;
        return $award ;
    }

    /**
     * 获取奖品
     * @param $proSum 概率数组的总概率精度
     * @param $proArr 奖品数组
     * @return array
     */
    private function getRand($proSum, $proArr) {
        //概率数组循环
        $result = [] ;
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur['draw_rate']) {
                $result = $proCur;
                break;
            } else {
                $proSum -= $proCur['draw_rate'];
            }
        }
        return $result;
    }

    /**
     * 写入中奖记录
     * @param $award
     * @return mixed
     */
    private function grantAward($award){
        $arr['status'] = 0 ;
        $setWhere['id'] = $award['id'] ;
        $setData['remain_award_num'] = ['exp','remain_award_num - 1'] ;
        $rst = Db::name('reward_set')->where($setWhere)->update($setData) ;
        if(!$rst){
            $arr['msg'] = '奖品已经发放完' ;
            return $arr ;
        }
        $recordData['uid'] = 100 ;
        $recordData['reward_id'] = $award['id'] ;
        $recordData['create_time'] = time() ;
        $res = Db::name('reward_record')->insert($recordData) ;
        if(!$res){
            $arr['msg'] = '中奖记录写入失败' ;
            return $arr ;
        }
        $arr['status'] = 1 ;
        $arr['msg'] = 'success' ;
        return $arr ;
    }
}