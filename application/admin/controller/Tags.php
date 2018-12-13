<?php
/**
 * 标签管理
 * User: singeo
 * Date: 2018/12/13 0013
 * Time: 下午 3:59
 */

namespace app\admin\controller;


use think\Db;

class Tags extends Base
{
    /**
     * 标签列表
     * @return mixed
     */
    public function index(){
        $keys = $this->request->param('keys/s') ;
        $where = [] ;
        if(!empty($keys)){
            $where['tags_name'] = ['like','%'.$keys.'%'] ;
        }
        $list = Db::name('tags')
            ->where($where)
            ->order('tags_id DESC')
            ->paginate(10,false,["query"=>$this->request->param()]) ;
        $this->assign('list',$list) ;
        return $this->fetch('tags/index') ;
    }

    /**
     * 删除文章标签
     */
    public function tagsDel(){
        $tags_id  = $this->request->param('id/d') ;
        $info = Db::name('tags')
            ->where(['tags_id'=>$tags_id])
            ->field('tags_id,tags_name')
            ->find() ;
        $this->assign('info',$info) ;
        echo $this->fetch('tags/tagsdel') ;
    }

    /**
     * 提交文章标签删除
     */
    public function submitTagsDel(){
        $tags_id  = $this->request->param('tags_id/d') ;
        $res = Db::name('tags')
            ->where(['tags_id'=>$tags_id])
            ->delete() ;
        if($res !== false ){
            Db::name('article_tags')->where(['tags_id'=>$tags_id])->delete() ;
            $this->success('文章标签删除成功') ;
        }else{
            $this->error('文章标签删除失败') ;
        }
    }
}