<?php
/**
 * 广告管理
 * User: singeo
 * Date: 2018/11/2 0002
 * Time: 下午 4:41
 */

namespace app\admin\model;

use think\Db;
use think\Validate;
class Advert extends Base
{
    //验证规则
    protected $rule = [
        'a_title'  => ['require','max' => 255,'token' => 'token_hash'],
        'category_id'=> ['require'],
    ];
    //验证错误提示信息
    protected $message = [
        'a_title.require' => '广告标题必须',
        'a_title.max'     => '广告标题长度小于255',
        'a_title.token'   => '不能重复提交',
        'category_id.require' => '广告所属分类必须'
    ];

    /**
     * 新增广告
     * @param $data
     * @return array
     */
    public function saveAdvert($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        if(!empty($file['a_pic'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['a_pic'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['a_pic'] = $uploadRes['url'] ;
            }
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('advert')->insertGetId($data) ;
        if($rst){
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
    public function updateAdvert($data,$file){
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $this->setErrorMsg($validate->getError()) ;
            return false;
        }
        $aid = $data['aid'] ;
        if(!empty($file['a_pic'])){
            $uploadcls = new \app\admin\library\Upload() ;
            $uploadRes = $uploadcls->doUpload($file['a_pic'],'image') ;
            if($uploadRes['status'] == 0){
                $this->setErrorMsg($uploadRes['msg']) ;
                return false;
            }else{
                $data['a_pic'] = $uploadRes['url'] ;
            }
            //读取之前的图片，删除之，节省空间。
            $old_pic = Db::name('advert')->where(['aid'=>$aid])->value('a_pic') ;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['aid'] = $aid ;
        unset($data['aid']) ;
        $data['update_time'] = time() ;
        $rst = Db::name('advert')->where($where)->update($data) ;
        if($rst){
            if(!empty($old_pic)){
                @unlink($_SERVER['DOCUMENT_ROOT'] . $old_pic) ;
            }
            return true ;
        }else{
            $this->setErrorMsg('fail') ;
            return false;
        }
    }
}