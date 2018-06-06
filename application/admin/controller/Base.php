<?php
/**
 * 后台基类
 * User: Administrator
 * Date: 2018/4/25 0025
 * Time: 下午 2:31
 */

namespace app\admin\controller;

use app\admin\library\Auth;
use app\admin\library\TreeShape;
use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //session(config('admin_login_info'),['id'=>1]) ;
        //验证权限
        $consoleInfo = session(config('admin_login_info')) ;
        if(empty($consoleInfo)){
            $this->error('请重新登录',config('app_config.website')) ;
        }else{
            $module = $request->module() ;
            $controller = $request->controller() ;
            $action = $request->action() ;
            $url = '/'.$module.'/'.$controller.'/'.$action ;
            $this->assign('curAction',$url) ;
            $auth = new Auth() ;
            if(!in_array($url,config('white_menu_url')) && $consoleInfo['id'] != config('super_admin_uid')) { // 不进行权限验证的操作
                if(!$auth->check($url,$consoleInfo['id'])){
                    $this->error('没有权限') ;
                }
            }
            $resMenu = $auth->getAuthMenu($consoleInfo['id']) ;
            if($resMenu !== -1){
                $resMenu = TreeShape::channelLevel($resMenu,0,'','id','parent_id') ;
                $this->assign('resMenu',$resMenu) ;
            }else{
                $this->error('没有权限') ;
            }
            $this->setTitle($url) ;
            $this->assign('consoleInfo', $consoleInfo) ;
        }
    }

    /**
     * 设置当前页面title
     * @param string $title
     */
    public function setTitle($curUrl = "", $title = ""){
        if(!empty($title)){
            $this->assign('title',$title) ;
        }else{
            if(empty($curUrl)){
                $request = Request::instance() ;
                $module = $request->module() ;
                $controller = $request->controller() ;
                $action = $request->action() ;
                $curUrl = '/'.$module.'/'.$controller.'/'.$action ;
            }
            $menu = new \app\admin\model\ConsoleMenu() ;
            $where['menu_url'] = $curUrl ;
            $field = 'menu_name' ;
            $res = $menu->find($where,$field) ;
            $this->assign('title',$res['menu_name']) ;
        }
    }

    /**
     * 重写错误跳转
     * @param string $msg
     * @param null $url
     */
    public function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        if($this->request->isAjax()){
            $this->modaltips($msg) ;
        }else{
            parent::error($msg, $url, $data, $wait, $header) ;
        }
    }

    /**
     * @param $msg
     */
    public function modaltips($msg){
        $this->assign('message',$msg) ;
        echo $this->fetch('common/modal_tips') ;
        exit ;
    }

    /**
     * @param string $message
     * @param int $status
     * @param array $data
     */
    public function ajaxSuccess($message = 'success', $status = 1, $data = []){
        $result['status'] = $status ;
        $result['msg'] = $message ;
        if(!empty($data)){
            $result['data'] = $data ;
        }
        echo json_encode($result) ;
        exit ;
    }

    /**
     * @param string $message
     * @param int $status
     * @param array $data
     */
    public function ajaxError($message = 'error', $status = 0, $data = []){
        $result['status'] = $status ;
        $result['msg'] = $message ;
        if(!empty($data)){
            $result['data'] = $data ;
        }
        echo json_encode($result) ;
        exit ;
    }
}