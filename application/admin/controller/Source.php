<?php
/**
 * 文章来源管理
 * User: singeo
 * Date: 2018/12/13 0013
 * Time: 下午 3:28
 */

namespace app\admin\controller;


use think\Db;

class Source extends Base
{
    /**
     * 来源列表
     * @return mixed
     */
    public function index(){
        $list = Db::name('article_source')
            ->order('id DESC')
            ->paginate(10,false,["query"=>$this->request->param()]) ;
        $this->assign('list',$list) ;
        return $this->fetch('source/index') ;
    }

    /**
     * 新增来源
     */
    public function sourceAdd(){
        echo $this->fetch('source/sourceadd') ;
    }

    /**
     * 提交新增来源
     */
    public function submitSourceAdd(){
        $param = $this->request->param() ;
        $sourcemodel = new \app\admin\model\ArticleSource() ;
        $result = $sourcemodel->saveSource($param) ;
        if(!$result){
            $this->error($sourcemodel->getErrorMsg()) ;
        }else{
            $this->success('添加来源成功') ;
        }
    }

    /**
     * 来源修改
     */
    public function sourceEdit(){
        $author_id  = $this->request->param('id/d') ;
        $info = Db::name('ArticleSource')
            ->where(['id'=>$author_id])
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch('source/sourceedit') ;
    }

    /**
     * 提交修改来源
     */
    public function submitSourceEdit(){
        $param = $this->request->param() ;
        $sourcemodel = new \app\admin\model\ArticleSource() ;
        $result = $sourcemodel->updateSource($param) ;
        if(!$result){
            $this->error($sourcemodel->getErrorMsg()) ;
        }else{
            $this->success('修改来源成功') ;
        }
    }

    /**
     * 来源删除
     */
    public function sourceDel(){
        $author_id  = $this->request->param('id/d') ;
        $info = Db::name('ArticleSource')
            ->where(['id'=>$author_id])
            ->field('id,source_name')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch('source/sourcedel') ;
    }

    /**
     * 提交来源删除
     */
    public function submitSourceDel(){
        $author_id  = $this->request->param('source_id/d') ;
        $res = Db::name('ArticleSource')
            ->where(['id'=>$author_id])
            ->delete() ;
        if($res !== false ){
            $this->success('来源删除成功') ;
        }else{
            $this->error('来源删除失败') ;
        }
    }
}