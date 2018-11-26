<?php
/**
 * 文档模型管理
 * User: singeo
 * Date: 2018/11/13 0013
 * Time: 下午 3:56
 */

namespace app\admin\controller;


use think\Db;

class ChannelType extends Base
{
    /**
     * 模型列表
     */
    public function index(){
        $channelmodel = new \app\admin\model\ChannelType() ;
//        $chWhere['status'] = 1 ;
        $field = 'id,m_title,m_act,sort,status' ;
        $orderby = 'sort,id DESC' ;
        $list = $channelmodel->getList(null,$field,null,$orderby) ;
        $this->assign('list',$list) ;
        return $this->fetch() ;
    }

    /**
     * 新增模型接口
     */
    public function channelAdd(){
        echo $this->fetch() ;
    }

    /**
     * 提交新增模型
     */
    public function submitChannelAdd(){
        $param = $this->request->param() ;
        $channelmodel = new \app\admin\model\ChannelType() ;
        $result = $channelmodel->saveChannelType($param) ;
        if(!$result){
            $this->error($channelmodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 修改模型接口
     */
    public function channelEdit(){
        $channel_id = $this->request->param('id/d') ;
        if(empty($channel_id)){
            $this->error('参数错误') ;
        }
        $channelmodel = new \app\admin\model\ChannelType() ;
        $chWhere['id'] = $channel_id ;
        $field = 'id,m_title,m_act,sort,status' ;
        $info = $channelmodel->find($chWhere,$field) ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改模型
     */
    public function submitChannelEdit(){
        $param = $this->request->param() ;
        $channelmodel = new \app\admin\model\ChannelType() ;
        $result = $channelmodel->updateChannelType($param) ;
        if(!$result){
            $this->error($channelmodel->getErrorMsg()) ;
        }else{
            $this->success('修改成功') ;
        }
    }

    /**
     * 删除模型
     */
    public function channelDel(){
        $channel_id = $this->request->param('id/d') ;
        if(empty($channel_id)){
            $this->error('参数错误') ;
        }
        $where['id'] = $channel_id ;
        $field = 'id,m_title' ;
        $cateInfo = Db::name('channel_type')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$cateInfo) ;
        echo $this->fetch() ;
    }

    /**
     * 提交删除模型
     */
    public function submitChannelDel(){
        $channel_id = $this->request->param('channel_id/d') ;
        if(empty($channel_id)){
            $this->error('参数错误') ;
        }
        $upData['status'] = -1 ;
        $upData['update_time'] = time() ;
        $result = Db::name('channel_type')
            ->where(['id'=>$channel_id])
            ->update($upData) ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}