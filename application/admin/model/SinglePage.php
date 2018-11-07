<?php
/**
 * 单页页面管理模型
 * User: singeo
 * Date: 2018/11/7 0007
 * Time: 下午 2:34
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class SinglePage extends Base
{
    //验证规则
    protected $rule = [
        'p_name'  => ['require','max' => 30,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'p_name.require' => '单页页面名称名必须',
        'p_name.max'     => '单页页面名称长度小于30',
        'p_name.token'   => '不能重复提交',
    ];
    /**
     * 单页页面新增
     * @param $data
     * @return array
     */
    public function saveSinglePage($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['p_picurl'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['p_picurl'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['p_picurl'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('single_page')->insert($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 单页页面修改
     * @param $data
     * @return array
     */
    public function updateSinglePage($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['p_picurl'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['p_picurl'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['p_picurl'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['update_time'] = time() ;
        $where['id'] = $data['id'] ;
        unset($data['id']) ;
        $rst = Db::name('single_page')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}