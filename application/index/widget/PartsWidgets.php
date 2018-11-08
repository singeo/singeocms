<?php
/**
 * 一些小部件
 * User: singeo
 * Date: 2018/11/8 0008
 * Time: 上午 11:14
 */
namespace app\index\widget ;

use think\Controller;
use think\Db;

class PartsWidgets extends Controller
{
    /**
     * 首页banner
     * @return mixed
     */
    public function index_banner(){
        $field = 'aid,a_title,a_desc,a_pic,link_url' ;
        $orderby = 'sort,aid DESC' ;
        $banner_list = Db::name('advert')
            ->where(['category_id'=>2,'status'=>1])
            ->field($field)
            ->order($orderby)
            ->select() ;
        $this->assign('banner_list',$banner_list) ;
        return $this->fetch('widget/index_banner') ;
    }

    /**
     * 首页右边bar
     */
    public function index_right_bar(){
        return $this->fetch('widget/index_right_bar') ;
    }

    /**
     * 内页右边bar
     */
    public function inner_right_bar(){
        return $this->fetch('widget/inner_right_bar') ;
    }
}