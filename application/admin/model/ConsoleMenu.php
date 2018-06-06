<?php
/**
 * 后台菜单操作模型
 * User: singeo
 * Date: 2018/4/27 0027
 * Time: 上午 10:12
 */
namespace app\admin\model;

use think\Db;
use think\Validate;

class ConsoleMenu extends Base
{
    //验证规则
    protected $rule = [
        'menu_name'  => ['require','max' => 30,'token' => 'token_hash'],
        'menu_url'  => ['require','unique:console_menu'],
    ];
    //验证错误提示信息
    protected $message = [
        'menu_name.require' => '菜单名必须',
        'menu_name.max'     => '菜单名长度小于30',
        'menu_name.token'   => '不能重复提交',
        'menu_url.require'   => '菜单地址必须',
        'menu_url.unique'   => '菜单地址不能重复',
    ];
    /**
 * 新增角色
 * @param $data
 * @return array
 */
    public function saveMenu($data){
        $arr['status'] = 0 ;
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $arr['msg'] = $validate->getError() ;
            return $arr;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $data['create_time'] = time() ;
        $rst = Db::name('ConsoleMenu')->insert($data) ;
        if($rst){
            $arr['status'] = 1 ;
            $arr['msg'] = 'success' ;
            return $arr;
        }else{
            $arr['msg'] = 'fail' ;
            return $arr;
        }
    }

    /**
     * 修改角色
     * @param $data
     * @return array
     */
    public function updateMenu($data){
        $arr['status'] = 0 ;
        $validate = new Validate($this->rule, $this->message) ;
        // 数据自动验证
        if (!$validate->check($data)) {
            $arr['msg'] = $validate->getError() ;
            return $arr;
        }
        if (isset($data['token_hash'])){
            unset($data['token_hash']) ;
        }
        $where['id'] = $data['id'] ;
        unset($data['id']) ;
        $rst = Db::name('ConsoleMenu')->where($where)->update($data) ;
        if($rst){
            $arr['status'] = 1 ;
            $arr['msg'] = 'success' ;
            return $arr;
        }else{
            $arr['msg'] = 'fail' ;
            return $arr;
        }
    }

}