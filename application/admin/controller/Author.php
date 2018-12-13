<?php
/**
 * 作者管理
 * User: singeo
 * Date: 2018/12/13 0013
 * Time: 下午 2:46
 */

namespace app\admin\controller;


use think\Db;

class Author extends Base
{
    /**
     * 作者列表
     * @return mixed
     */
    public function index(){
        $list = Db::name('article_author')
            ->order('id DESC')
            ->paginate(10,false,["query"=>$this->request->param()]) ;
        $this->assign('list',$list) ;
        return $this->fetch('author/index') ;
    }

    /**
     * 新增作者
     */
    public function authorAdd(){
        echo $this->fetch('author/authoradd') ;
    }

    /**
     * 提交新增作者
     */
    public function submitAuthorAdd(){
        $param = $this->request->param() ;
        $authormodel = new \app\admin\model\ArticleAuthor() ;
        $result = $authormodel->saveAuthor($param) ;
        if(!$result){
            $this->error($authormodel->getErrorMsg()) ;
        }else{
            $this->success('添加作者成功') ;
        }
    }

    /**
     * 作者修改
     */
    public function authorEdit(){
        $author_id  = $this->request->param('id/d') ;
        $info = Db::name('ArticleAuthor')
            ->where(['id'=>$author_id])
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch('author/authoredit') ;
    }

    /**
     * 提交修改作者
     */
    public function submitAuthorEdit(){
        $param = $this->request->param() ;
        $authormodel = new \app\admin\model\ArticleAuthor() ;
        $result = $authormodel->updateAuthor($param) ;
        if(!$result){
            $this->error($authormodel->getErrorMsg()) ;
        }else{
            $this->success('修改作者成功') ;
        }
    }

    /**
     * 作者删除
     */
    public function authorDel(){
        $author_id  = $this->request->param('id/d') ;
        $info = Db::name('ArticleAuthor')
            ->where(['id'=>$author_id])
            ->field('id,author_name')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch('author/authordel') ;
    }

    /**
     * 提交作者删除
     */
    public function submitAuthorDel(){
        $author_id  = $this->request->param('author_id/d') ;
        $res = Db::name('ArticleAuthor')
            ->where(['id'=>$author_id])
            ->delete() ;
        if($res !== false ){
            $this->success('作者删除成功') ;
        }else{
            $this->error('作者删除失败') ;
        }
    }
}