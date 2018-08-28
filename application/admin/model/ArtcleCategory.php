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

class ArtcleCategory extends Base
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
     * 新增角色
     * @param $data
     * @return array
     */
    public function saveArtcleCategory($data){
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
        $rst = Db::name('ArtcleCategory')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 修改角色
     * @param $data
     * @return array
     */
    public function updateArtcleCategory($data){
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
        $rst = Db::name('ArtcleCategory')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}