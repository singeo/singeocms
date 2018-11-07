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
        $where['status'] = 1 ;
        $field = 'id,p_name,p_description,p_keyword,p_picurl,p_content,sort,status,create_time' ;
        $orderby = 'sort ASC ,id DESC' ;
        $list = Db::name('single_page')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->paginate(10,false,["query"=>$this->request->param()]) ;
        if($list === false){
            $this->error('获取数据失败') ;
        }
        $this->assign('list',$list) ;
        return $this->fetch() ;
    }

    /**
     * 添加单页列表页面
     */
    public function singleAdd(){
        echo $this->fetch('singleadd') ;
    }

    /**
     * 提交添加单页列表页面
     */
    public function submitSingleAdd(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $singeomodel = new \app\admin\model\SinglePage() ;
        $result = $singeomodel->saveSinglePage($param,$file) ;
        if(!$result){
            $this->error($singeomodel->getErrorMsg()) ;
        }else{
            $this->success('新增成功') ;
        }
    }

    /**
     * 修改单页页面
     */
    public function singleEdit(){
        $id = $param = $this->request->param('id/d') ;
        $singeomodel = new \app\admin\model\SinglePage() ;
        $where['id'] = $id ;
        $field = 'id,p_name,p_description,p_keyword,p_picurl,p_content,sort,status' ;
        $info = $singeomodel->find($where,$field) ;
        $this->assign('info',$info) ;
        echo $this->fetch('singleedit') ;
    }

    /**
     * 提交修改单页页面
     */
    public function submitSingleEdit(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $singlemodel = new \app\admin\model\SinglePage() ;
        $result = $singlemodel->updateSinglePage($param,$file) ;
        if(!$result){
            $this->error($singlemodel->getErrorMsg()) ;
        }else{
            $this->success('修改单页页面成功') ;
        }
    }

    /**
     * 单页页面删除
     */
    public function singleDel(){
        $id  = $this->request->param('id/d') ;
        $info = Db::name('single_page')
            ->where(['id'=>$id])
            ->field('id,p_name')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交单页页面删除
     */
    public function submitSingleDel(){
        $id  = $this->request->param('id/d') ;
        $res = Db::name('single_page')
            ->where(['id'=>$id])
            ->update(['status'=>-1]) ;
        if($res !== false ){
            $this->success('单页页面删除成功') ;
        }else{
            $this->error('单页页面删除失败') ;
        }
    }
}