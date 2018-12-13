<?php
/**
 * 文章作者管理model
 * User: singeo
 * Date: 2018/12/13 0013
 * Time: 下午 3:04
 */

namespace app\admin\model;


use think\Db;
use think\Validate;

class ArticleAuthor extends Base
{
//验证规则
    protected $rule = [
        'author_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'author_name.require' => '作者名称必须',
        'author_name.max'     => '作者名称长度小于30',
        'author_name.token'   => '不能重复提交'
    ];

    /**
     * 新增作者
     * @param $data
     * @return array
     */
    public function saveAuthor($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('ArticleAuthor')->insertGetId($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 作者修改
     * @param $data
     * @return array
     */
    public function updateAuthor($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['id'] = $data['author_id'] ;
        unset($data['author_id']) ;
        $rst = Db::name('ArticleAuthor')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}