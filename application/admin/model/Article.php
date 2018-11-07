<?php
/**
 * 文章信息管理
 * User: singeo
 * Date: 2018/8/29 0029
 * Time: 上午 10:15
 */

namespace app\admin\model;


use think\Db;
use think\Validate;

class Article extends Base
{
    //验证规则
    protected $rule = [
        'article_title'  => ['require','max' => 255,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'article_title.require' => '文章标题必须',
        'article_title.max'     => '文章标题长度小于255',
        'article_title.token'   => '不能重复提交',
    ];

    /**
     * 文章新增
     * @param $data
     * @return array
     */
    public function saveArticle($data,$file){
        if(!empty($file['article_pic'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['article_pic'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['article_pic'] = $uploadRes['url'] ;
            }
        }
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['publish_time'] = strtotime($data['publish_time']) ;
        $data['create_time'] = time() ;
        $article_tags = $data['article_tags'] ;
        unset($data['article_tags']) ;
        $rst = Db::name('article')->insertGetId($data) ;
        if($rst){
            $articleTagsModel = new \app\admin\model\ArticleTags() ;
            $articleTagsModel->saveArticleTags($article_tags,$rst) ;
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 文章修改
     * @param $data
     * @return array
     */
    public function updateArticle($data,$file){
        if(!empty($file['article_pic'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['article_pic'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['article_pic'] = $uploadRes['url'] ;
            }
        }
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $aid = $data['aid'] ;
        $where['id'] = $aid ;
        unset($data['aid']) ;
        $data['publish_time'] = strtotime($data['publish_time']) ;
        $data['update_time'] = time() ;
        $article_tags = $data['article_tags'] ;
        unset($data['article_tags']) ;
        $rst = Db::name('article')->where($where)->update($data) ;
        if($rst){
            $articleTagsModel = new \app\admin\model\ArticleTags() ;
            $articleTagsModel->saveArticleTags($article_tags,$aid) ;
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}