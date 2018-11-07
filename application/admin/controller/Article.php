<?php
/**
 * 文章列表管理
 * User: singeo
 * Date: 2018/8/29 0029
 * Time: 上午 9:57
 */

namespace app\admin\controller;


use think\Db;
use app\admin\library\TreeShape;
class Article extends Base
{
    /**
     * 文章列表页
     */
    public function index(){
        $field = 'a.id,a.cid,a.article_title,a.article_desc,a.article_pic,'
                .'a.view_num,a.publish_time,a.sort,a.status,a.is_head,a.is_recom,a.is_top,'
                .'ac.cate_title,aa.author_name,ar.source_name' ;
        $orderby = 'a.sort ASC,publish_time DESC,a.id DESC' ;
        $join[] = ['article_category as ac','ac.cid = a.cid'] ;
        $join[] = ['article_author as aa','aa.id = a.author','LEFT'] ;
        $join[] = ['article_source as ar','ar.id = a.source','LEFT'] ;
        $where['a.status'] = 1 ;
        $list = Db::name('article')
            ->alias('a')
            ->join($join)
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->paginate(10,false,["query"=>$this->request->param()]) ;
        $this->assign('list',$list) ;
        return $this->fetch() ;
    }

    /**
     * 添加文章页
     */
    public function articleAdd(){
        $field = 'cid,pid,cate_title' ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = Db::name('article_category')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
        $this->assign('cateTree',$catelist) ;
        //获取文章作者
        $authorlist = Db::name('article_author')->where(['status'=>1])->field('id,author_name')->select() ;
        $this->assign('authorlist',$authorlist) ;
        //获取文章来源列表
        $sourcelist = Db::name('article_source')->where(['status'=>1])->field('id,source_name')->select() ;
        $this->assign('sourcelist',$sourcelist) ;
        echo $this->fetch() ;
    }

    /**
     * 提交新增文章
     */
    public function submitArticleAdd(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $articlemodel = new \app\admin\model\Article() ;
        $result = $articlemodel->saveArticle($param,$file) ;
        if(!$result){
            $this->error($articlemodel->getErrorMsg()) ;
        }else{
            $this->success('新增文章成功') ;
        }
    }

    /**
     * 文章修改
     */
    public function articleEdit(){
        $aid  = $this->request->param('aid/d') ;
        $info = Db::name('article')
            ->where(['id'=>$aid])
            ->find() ;
        $field = 'cid,pid,cate_title' ;
        $where['status'] = 1 ;// 正常
        $orderby = 'sort ASC,cid DESC' ;
        $catelist = Db::name('article_category')
            ->where($where)
            ->field($field)
            ->order($orderby)
            ->select() ;
        $catelist = TreeShape::tree($catelist,'cate_title','cid', 'pid') ;
        $this->assign('cateTree',$catelist) ;
        //获取文章作者
        $authorlist = Db::name('article_author')
            ->where(['status'=>1])
            ->field('id,author_name')
            ->select() ;
        $this->assign('authorlist',$authorlist) ;
        //获取文章来源列表
        $sourcelist = Db::name('article_source')
            ->where(['status'=>1])
            ->field('id,source_name')
            ->select() ;
        $this->assign('sourcelist',$sourcelist) ;
        //读取文章标签
        $articleTagsModel = new \app\admin\model\ArticleTags() ;
        $aTags = $articleTagsModel->getArticleTags($aid) ;
        if(is_array($aTags)){
            $tagsNameArr = array_column($aTags,'tags_name') ;
            $info['article_tags'] = implode(',',$tagsNameArr) ;
        }else{
            $info['article_tags'] = '' ;
        }
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改文章
     */
    public function submitArticleEdit(){
        $param = $this->request->param() ;
        $file = $this->request->file() ;
        $articlemodel = new \app\admin\model\Article() ;
        $result = $articlemodel->updateArticle($param,$file) ;
        if(!$result){
            $this->error($articlemodel->getErrorMsg()) ;
        }else{
            $this->success('修改文章成功') ;
        }
    }

    /**
     * 文章删除
     */
    public function articleDel(){
        $aid  = $this->request->param('aid/d') ;
        $info = Db::name('article')
            ->where(['id'=>$aid])
            ->field('id,article_title')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch() ;
    }

    /**
     * 提交文章删除
     */
    public function submitArticleDel(){
        $aid  = $this->request->param('aid/d') ;
        $res = Db::name('article')
            ->where(['id'=>$aid])
            ->update(['status'=>-1]) ;
        if($res !== false ){
            $this->success('文章删除成功') ;
        }else{
            $this->error('文章删除失败') ;
        }
    }
}