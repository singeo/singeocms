<?php
/**
 * 页面管理
 * User: singeo
 * Date: 2018/11/7 0007
 * Time: 下午 2:32
 */

namespace app\admin\controller;


use think\Db;

class SinglePage extends Base
{
    /**
     * 单页列表页面
     */
    public function index(){
        $cid = $this->request->param('cid',0) ;
        $where['status'] = 1 ;
        $where['channel_type'] = 2 ; //单页模型
        $field = 'cid,c_name' ;
        $orderby = 'sort ASC ,cid DESC' ;
        $catelist = Db::name('arctype')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $this->assign('cateTree',$catelist) ;
        if($cid == 0 && !empty($catelist)){
            $cid = $catelist[0]['cid'] ;
        }
        $where = [] ;
        $singlemodel = new \app\admin\model\SinglePage() ;
        $where['cid'] = $cid ;
        $field = 'cid,p_content' ;
        $info = $singlemodel->find($where,$field) ;
        $this->assign('info',$info) ;
        $this->assign('cid',$cid) ;
        return $this->fetch() ;
    }

    /**
     * 提交修改单页页面
     */
    public function submitSingleEdit(){
        $param = $this->request->param() ;
        $singlemodel = new \app\admin\model\SinglePage() ;
        $result = $singlemodel->saveSinglePage($param) ;
        if(!$result){
            $this->error($singlemodel->getErrorMsg()) ;
        }else{
            $this->success('修改单页页面成功') ;
        }
    }
}