<?php
/**
 * 栏目管理
 * User: singeo
 * Date: 2018/11/13 0013
 * Time: 下午 3:34
 */

namespace app\admin\controller;


use app\admin\library\TreeShape;
use think\Db;

class Arctype extends Base
{
    /**
     * 栏目列表页
     */
    public function index(){
        $orderby = 'sort ASC,cid DESC' ;
        $feild = 'a.cid,a.pid,a.channel_type,a.c_name,a.sort,a.is_show,a.status,ct.m_title,ct.m_act' ;
        $join[] = ['channel_type as ct','a.channel_type = ct.id','LEFT'] ;
        $list = Db::name('arctype')
            ->join($join)
            ->alias('a')
            ->field($feild)
            ->order($orderby)
            ->select() ;

        $list = TreeShape::tree($list,'c_name','cid', 'pid') ;
        $this->assign('list',$list) ;
        return $this->fetch() ;
    }

    /**
     * 新增栏目
     */
    public function columnAdd(){
        $cid = $this->request->param('cid/d',0) ;
        $where['status'] = 1 ;
        $orderby = 'sort ASC,cid DESC' ;
        $feild = 'cid,pid,c_name' ;
        $list = Db::name('arctype')
            ->field($feild)
            ->order($orderby)
            ->select() ;
        $cateTree = TreeShape::tree($list,'c_name','cid', 'pid') ;
        $this->assign('cateTree',$cateTree) ;
        //模型
        $channelmodel = new \app\admin\model\ChannelType() ;
        $where = [] ;
        $where['status'] = 1 ;
        $feild = 'id,m_title' ;
        $orderby = 'sort ASC,id DESC' ;
        $modelist = $channelmodel->getList($where,$feild,null,$orderby) ;
        $this->assign('modelist',$modelist) ;

        $this->assign('cid',$cid) ;
        echo $this->fetch() ;
    }

    /**
     * 提交新增栏目
     */
    public function submitColumnAdd(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $arctypemodel = new \app\admin\model\Arctype() ;
        $result = $arctypemodel->saveArctype($param,$file) ;
        if(!$result){
            $this->error($arctypemodel->getErrorMsg()) ;
        }else{
            $this->success('新增栏目成功') ;
        }
    }

    /**
     * 修改栏目
     */
    public function columnEdit(){
        $cid = $this->request->param('cid/d',0) ;
        $arctypemodel = new \app\admin\model\Arctype() ;
        $where['status'] = 1 ;
        $orderby = 'sort ASC,cid DESC' ;
        $feild = 'cid,pid,c_name' ;
        $list = $arctypemodel->getList($where,$feild,null,$orderby) ;
        $cateTree = TreeShape::tree($list,'c_name','cid', 'pid') ;
        $this->assign('cateTree',$cateTree) ;

        //栏目信息
        $where = [] ;
        $where['cid'] = $cid ;
        $feild = 'cid,pid,channel_type,c_name,c_description,c_picurl,seo_title,seo_keywords,seo_description,'.
                'link_attr,link_url,template_list,sort,is_show,status';
        $info = $arctypemodel->find($where,$feild) ;
        $this->assign('info',$info) ;
        //模型
        $channelmodel = new \app\admin\model\ChannelType() ;
        $where = [] ;
        $where['status'] = 1 ;
        $feild = 'id,m_title' ;
        $orderby = 'sort ASC,id DESC' ;
        $modelist = $channelmodel->getList($where,$feild,null,$orderby) ;
        $this->assign('modelist',$modelist) ;

        $this->assign('cid',$cid) ;
        echo $this->fetch() ;
    }

    /**
     * 修改新增栏目
     */
    public function submitColumnEdit(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $arctypemodel = new \app\admin\model\Arctype() ;
        $result = $arctypemodel->updateArctype($param,$file) ;
        if(!$result){
            $this->error($arctypemodel->getErrorMsg()) ;
        }else{
            $this->success('修改栏目成功') ;
        }
    }

    /**
     * 删除栏目
     */
    public function columnDel(){
        $cid = $this->request->param('cid') ;
        if(empty($cid)){
            $this->error('参数错误') ;
        }
        $where['cid'] = $cid ;
        $field = 'cid,c_name' ;
        $info = Db::name('arctype')
            ->where($where)
            ->field($field)
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交删除栏目
     */
    public function submitColumnDel(){
        $cid = $this->request->param('cid') ;
        if(empty($cid)){
            $this->error('参数错误') ;
        }
        $column_count = Db::name('arctype')->where(['pid'=>$cid])->count() ;
        if($column_count > 0){
            $this->error('该菜单下存在子栏目，不能删除') ;
        }
        $result = Db::name('arctype')->where(['cid'=>$cid])->delete() ;
        if(!$result){
            $this->error('删除操作失败！') ;
        }else{
            $this->success('删除操作成功') ;
        }
    }
}