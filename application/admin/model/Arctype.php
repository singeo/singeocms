<?php
/**
 * 栏目模型
 * User: singeo
 * Date: 2018/11/13 0013
 * Time: 下午 5:07
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class Arctype extends Base
{
    //验证规则
    protected $rule = [
        'c_name'  => ['require','max' => 20,'token' => 'token_hash'],
    ];
    //验证错误提示信息
    protected $message = [
        'c_name.require' => '文章标题必须',
        'c_name.max'     => '文章标题长度小于255',
        'c_name.token'   => '不能重复提交',
    ];

    /**
     * 栏目新增
     * @param $data
     * @return array
     */
    public function saveArctype($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['c_picurl'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['c_picurl'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['c_picurl'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('arctype')->insertGetId($data) ;
        if($rst){
            return true;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }

    /**
     * 栏目修改
     * @param $data
     * @return array
     */
    public function updateArctype($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['c_picurl'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['c_picurl'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['c_picurl'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $cid = $data['cid'] ;
        $where['cid'] = $cid ;
        unset($data['cid']) ;
        $data['update_time'] = time() ;
        $rst = Db::name('arctype')->where($where)->update($data) ;
        if($rst){
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}