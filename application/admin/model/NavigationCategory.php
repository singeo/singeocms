<?php
/**
 * 导航分类模型管理
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 下午 4:18
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class NavigationCategory extends Base
{
    //验证规则
    protected $rule = [
        'cate_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'cate_name.require' => '导航分类名称名必须',
        'cate_name.max'     => '导航分类名称长度小于30',
        'cate_name.token'   => '不能重复提交',
    ];
    /**
     * 导航分类新增
     * @param $data
     * @return array
     */
    public function saveNavigationCategory($data){
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
        $rst = Db::name('NavigationCategory')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 导航分类修改
     * @param $data
     * @return array
     */
    public function updateNavigationCategory($data){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['cate_id'] = $data['cid'] ;
        unset($data['cid']) ;
        $rst = Db::name('NavigationCategory')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}