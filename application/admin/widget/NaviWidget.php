<?php
/**
 * 导航小部件
 * User: singeo
 * Date: 2018/11/9 0009
 * Time: 上午 10:10
 */
namespace app\admin\widget;

class NaviWidget extends \think\Controller
{
    /**
     * 获取导航分类
     * @param $cur_category
     * @return mixed
     */
    public function category_list($cur_category = null){
        //文章分类列表
        $articlecategorymodel = new \app\admin\model\ArticleCategory() ;
        $articleWhere['status'] = 1 ;
        $articleField = 'cid,cate_title' ;
        $articleOrderby = 'sort,cid DESC' ;
        $article_list = $articlecategorymodel->getList($articleWhere,$articleField,null,$articleOrderby) ;
        $this->assign('article_list',$article_list) ;
        //获取单页分类列表
        $singlemodel = new \app\admin\model\SinglePage() ;
        $singleWhere['status'] = 1 ;
        $singleField = 'id,p_name' ;
        $singleOrderby = 'sort,id DESC' ;
        $single_list = $singlemodel->getList($singleWhere,$singleField,null,$singleOrderby) ;
        $this->assign('single_list',$single_list) ;
        //获取广告分类
        $advertcategorymodel = new \app\admin\model\AdvertCategory() ;
        $advertWhere['status'] = 1 ;
        $advertField = 'cid,c_name' ;
        $advertOrderby = 'sort,cid DESC' ;
        $advert_list = $advertcategorymodel->getList($advertWhere,$advertField,null,$advertOrderby) ;
        $this->assign('advert_list',$advert_list) ;
        $isserialized = is_serialized($cur_category) ;
        if($isserialized) {
            $cur_category = unserialize($cur_category) ;
        }else{
            $cur_category = null ;
            $cur_category['model'] = '' ;
            $cur_category['cid'] = '' ;
        }
        $this->assign('cur_category',$cur_category) ;//当前分类
        return $this->fetch('widget/category_list') ;
    }

    /**
     * 模板列表
     * @param $cur_tpl
     * @return mixed
     */
    public function tpl_list($cur_tpl = "",$channel_field = ""){
        $tpl_dir = config('app_config.web_tpl_path') ;
        $tpl_list = scan_files($tpl_dir) ;
        $this->assign('tpl_list',$tpl_list) ;
        $this->assign('cur_tpl',$cur_tpl) ;
        $this->assign('channel_field',$channel_field) ;
        return $this->fetch('widget/tpl_list') ;
    }
}