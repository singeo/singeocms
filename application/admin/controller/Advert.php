<?php
/**
 * 广告列表管理
 * User: singeo
 * Date: 2018/11/2 0002
 * Time: 上午 9:57
 */

namespace app\admin\controller;


use think\Db;

class Advert extends Base
{
    /**
     * 广告列表
     */
    public function index(){
        $where['a.status'] = 1 ;
        $orderby = 'a.sort ASC,a.aid DESC' ;
        $feild = 'a.aid,a.a_title,a.a_desc,a.a_pic,a.status,a.sort,a.create_time,ac.c_name' ;
        $join[] = ['advert_category as ac','ac.cid = a.category_id'] ;
        $advertList = Db::name('advert')
            ->alias('a')
            ->join($join)
            ->where($where)
            ->field($feild)
            ->order($orderby)
            ->select() ;
        if($advertList === false){
            $this->error('获取数据失败') ;
        }else{
            $this->assign('advertList',$advertList) ;
        }
        return $this->fetch() ;
    }

    /**
     * 新增广告
     */
    public function advertAdd(){
        $categorymodel = new \app\admin\model\AdvertCategory() ;
        $where['status'] = 1 ;
        $field = 'cid,c_name' ;
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = $categorymodel->getList($where, $field,null, $orderby) ;
        $this->assign('catelist',$catelist) ;
        echo $this->fetch() ;
    }

    /**
     * 提交新增广告
     */
    public function submitAdvertAdd(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $advertmodel = new \app\admin\model\Advert() ;
        $result = $advertmodel->saveAdvert($param,$file) ;
        if(!$result){
            $this->error($advertmodel->getErrorMsg()) ;
        }else{
            $this->success('添加广告成功') ;
        }
    }

    /**
     * 广告修改
     */
    public function advertEdit(){
        $aid  = $this->request->param('aid/d') ;
        $info = Db::name('advert')
            ->where(['aid'=>$aid])
            ->find() ;
        $categorymodel = new \app\admin\model\AdvertCategory() ;
        $where['status'] = 1 ;
        $field = 'cid,c_name' ;
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = $categorymodel->getList($where, $field,null, $orderby) ;
        $this->assign('catelist',$catelist) ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改广告
     */
    public function submitAdvertEdit(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $articlemodel = new \app\admin\model\Advert() ;
        $result = $articlemodel->updateAdvert($param,$file) ;
        if(!$result){
            $this->error($articlemodel->getErrorMsg()) ;
        }else{
            $this->success('修改广告成功') ;
        }
    }

    /**
     * 广告删除
     */
    public function advertDel(){
        $aid  = $this->request->param('aid/d') ;
        $info = Db::name('advert')
            ->where(['aid'=>$aid])
            ->field('aid,a_title')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交广告删除
     */
    public function submitAdvertDel(){
        $aid  = $this->request->param('aid/d') ;
        $res = Db::name('advert')
            ->where(['aid'=>$aid])
            ->update(['status'=>-1]) ;
        if($res !== false ){
            $this->success('广告删除成功') ;
        }else{
            $this->error('广告删除失败') ;
        }
    }
}