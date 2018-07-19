<?php
/**
 * 网站设置
 * User: 冯欣
 * Date: 2018/5/30 0030
 * Time: 上午 9:44
 */

namespace app\admin\controller;


use app\admin\traits\FileTraits;
use think\Db;

class Webconfig extends Base
{
    use FileTraits ;
    /**
     * 网站设置首页
     */
    public function index(){
        $group = config('web_config_group') ;
        $field = 'id,c_group,c_type,c_name,c_key,c_value,enum_value,remark' ;
        $order = 'sort ASC,id DESC' ;
        $list = Db::name('Config')
            ->where(['status' => 1])
            ->field($field)
            ->order($order)
            ->select() ;
        $groupList = [] ;
        //分组
        foreach ($list as $item){
            if(in_array($item['c_type'], ['select','radio','checkbox'])){
                $enum_value = explode(',',$item['enum_value']) ;
                if(!empty($enum_value)){
                    $enum_arr = [] ;
                    foreach ($enum_value as $key=>$value){
                        $enum = explode('|',$value) ;
                        $enum_arr[$key]['o_val'] = isset($enum[0]) ? $enum[0] : '' ;
                        $enum_arr[$key]['o_text'] = isset($enum[1]) ? $enum[1] : '' ;
                    }
                    $item['enum_value'] = $enum_arr ;
                }
            }
            if(isset($groupList[$item['c_group']])){
                array_push($groupList[$item['c_group']], $item) ;
            }else{
                $groupList[$item['c_group']][] = $item ;
            }
        }
//        print_r($groupList) ;
        $this->assign('groupList',$groupList) ;
        $this->assign('config_group',$group) ;
        return $this->fetch() ;
    }

    /**
     * 添加新的配置项
     */
    public function configAdd(){
        $this->assign('config_group',config('web_config_group')) ;
        $this->assign('onfig_field_type',config('web_config_field_type')) ;
        echo $this->fetch() ;
    }

    /**
     * 提交新增配置项
     */
    public function submitConfigAdd(){
        $param = $this->request->param() ;
        $configmodel = new \app\admin\model\Config() ;
        $result = $configmodel->saveConfig($param) ;
        if(!$result){
            $this->error($configmodel->getErrorMsg()) ;
        }else{
            $this->success('新增配置项成功') ;
        }
    }

    /**
     * 添加新的配置项
     */
    public function configEdit(){
        $id = $this->request->param('id') ;
        if(empty($id)){
            $this->error('参数错误') ;
        }
        $field = 'id,c_group,c_type,c_name,c_key,c_value,enum_value,sort,status,remark' ;
        $order = 'sort ASC,id DESC' ;
        $info = Db::name('Config')
            ->where(['id' => $id])
            ->field($field)
            ->order($order)
            ->find() ;
        $this->assign('info',$info) ;
        $this->assign('config_group',config('web_config_group')) ;
        $this->assign('onfig_field_type',config('web_config_field_type')) ;
        echo $this->fetch() ;
    }

    /**
     * 提交修改配置项
     */
    public function submitConfigEdit(){
        $param = $this->request->param() ;
        $configmodel = new \app\admin\model\Config() ;
        $result = $configmodel->updateConfig($param) ;
        if(!$result){
            $this->error($configmodel->getErrorMsg()) ;
        }else{
            $this->success('修改配置项成功') ;
        }
    }

    /**
     * 提交更新的配置项
     */
    public function submitUpdateConfig(){
        $param = $this->request->param() ;
        $c_group = $param['c_group'] ;
        unset($param['c_group']) ;
        $configmodel = new \app\admin\model\Config() ;
        $result = $configmodel->storeConfig($c_group, $param) ;
        if(!$result){
            $this->error($configmodel->getErrorMsg()) ;
        }else{
            $this->success('更新配置项成功') ;
        }
    }
}