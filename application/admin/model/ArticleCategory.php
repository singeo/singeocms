<?php
/**
 * 文章分类管理模型
 * User: singeo
 * Date: 2018/8/9 0009
 * Time: 上午 9:48
 */

namespace app\admin\model;


use think\Db;
use think\Validate;

class ArticleCategory extends Base
{
    //验证规则
    protected $rule = [
        'cate_title'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'cate_title.require' => '文章栏目名称名必须',
        'cate_title.max'     => '菜单名长度小于30',
        'cate_title.token'   => '不能重复提交',
    ];

    /**
     * 文章栏目新增
     * @param $data
     * @return array
     */
    public function saveArticleCategory($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['cate_pic'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['cate_pic'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['cate_pic'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('ArticleCategory')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 文章栏目修改
     * @param $data
     * @return array
     */
    public function updateArticleCategory($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['cid'] = $data['cid'] ;
        unset($data['cid']) ;
        $rst = Db::name('ArticleCategory')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}