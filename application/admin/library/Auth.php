<?php

namespace app\admin\library;


class Auth{
    //默认配置
    protected $_config = array(
        'auth_on'           => true,                      // 认证开关
        'auth_type'         => 1,                         // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'console_role',        // 用户组数据表名
        'auth_group_access' => 'console_role_user', // 用户-用户组关系表
        'auth_rule'         => 'console_menu',         // 菜单表
        'auth_user'         => 'console_user'             // 用户信息表
    );
    public function __construct() {
        if (config('auth_config')) {
            //可设置配置项 auth_config, 此配置项为数组。
            $this->_config = array_merge($this->_config, config('auth_config'));
        }
    }
    /**
     * 后台菜单获取
     * @param int $uid 用户ID
     * @return 菜单列表
     */
    public function getAuthMenu($uid){
        if($uid != config('super_admin_uid')){//超级管理员
            if(cache(config('app_config.catch_keys_all')['admin_auth_menu'])){
                return cache(config('app_config.catch_keys_all')['admin_auth_menu']) ;
            }
            //读取用户所属用户组
            $groups = $this->getGroups($uid);
            if(empty($groups)){
                return -1 ;//没有分配用户组
            }
            $ids = array();
            foreach ($groups as $g) {
                $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
            }
            $ids = array_unique($ids);

            $map = array(
                'id' => array('in',$ids),
                'status' => 1,
                'type' => 1 //菜单
            );
        }else{
            $map = array(
                'status' => 1,
                'type' => 1 //菜单
            );
        }
        $field = 'id,parent_id,menu_name,menu_icon,menu_url,type';
        $order = 'sort ASC,id DESC' ;

        $menus = \think\Db::name($this->_config['auth_rule'])->where($map)->field($field)->order($order)->select();

        if($this->_config['auth_type'] == 2){
            cache(config('app_config.catch_keys_all')['admin_auth_menu'],$menus) ;
        }
        return $menus ;
    }

    /**
     * 检查权限
     * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param uid  int           认证用户的id
     * @param string mode        执行check的模式
     * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $mode='url', $relation='or') {
        if (!$this->_config['auth_on'])
            return true;
        $authList = $this->getAuthList($uid); //获取用户需要验证的所有有效规则列表
        if(!empty($authList)){
            $rules = array_column($authList,'menu_url') ;
            if(is_array($rules)){
                if(in_array($name,$rules)){
                    return true ;
                }
            }
        }
        return false;
    }
    /**
     * 根据用户id获取用户组,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户组 array(
     *     array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *     ...)
     */
    public function getGroups($uid) {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $user_groups = \think\Db::name($this->_config['auth_group_access'])
            ->alias('a')
            ->join($this->_config['auth_group']." g", "g.id=a.role_id")
            ->where("a.user_id='$uid' and g.status='1'")
            ->field('a.user_id,role_id,role_name,rules')->select();
        $groups[$uid] = $user_groups ? $user_groups : array();
        return $groups[$uid];
    }
    /**
     * 获得权限列表
     * @param integer $uid  用户id
     * @param integer $type
     */
    protected function getAuthList($uid) {
        static $_authList = array(); //保存用户验证通过的权限列表

        if( $this->_config['auth_type']==2){
            if(cache(config('app_config.catch_keys_all')['admin_auth_rules'])){
                return cache(config('app_config.catch_keys_all')['admin_auth_rules']) ;
            }
        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        $map=array(
            'id'=>array('in',$ids),
            'status'=>1,
        );
        $field = 'id,parent_id,menu_name,menu_icon,menu_url,type' ;
        //读取用户组所有权限规则
        $authList = \think\Db::name($this->_config['auth_rule'])->where($map)->field($field)->select();
        //$_authList[$uid] = $authList;
        if($this->_config['auth_type']==2){
            //规则列表结果保存到session
            cache(config('app_config.catch_keys_all')['admin_auth_rules'],$authList) ;
        }
        return $authList ;
    }
    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
            $userinfo[$uid]=\think\Db::name($this->_config['auth_user'])->where(array('uid'=>$uid))->find();
        }
        return $userinfo[$uid];
    }
}