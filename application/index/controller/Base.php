<?php
/**
 * Created by PhpStorm.
 * User: singeo
 * Date: 2018/11/12 0012
 * Time: 下午 2:26
 */

namespace app\index\controller;


use think\Cache;
use think\Controller;
use think\Request;

class Base extends Controller
{
    /**
     * 栏目ID
     * @var
     */
    protected $column_id = 0 ;

    /**
     * 文章ID
     */
    protected $article_id = 0 ;

    /**
     * 标签ID
     */
    protected $tags_id = 0 ;

    /**
     * 当前栏目信息
     * @var array
     */
    protected $cur_column_info = [] ;

    /**
     * 网站配置信息
     * @var array
     */
    protected $web_config = [] ;


    /**
     * Base constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //读取网站配置
        $this->web_config = Cache::get(config('web_config_catch')) ;
        if(empty($webConfig)){
            $configmodel = new \app\common\model\Config() ;
            $configmodel->setWebConfig() ;
            $this->web_config = Cache::get(config('web_config_catch')) ;
        }
        $this->setTitle() ;
    }

    /**
     * 设置标题
     */
    public function setTitle($title = '', $keywords = '', $description = ''){
        if($title == ""){
            $cid = $this->request->param('cid/d',0) ;
            $article_id = $this->request->param('id/d',0) ;
            $tags_id = $this->request->param('tagid/d',0) ;
            $this->column_id = $cid ;
            $this->article_id = $article_id ;
            $this->tags_id = $tags_id ;
            if($this->column_id == 0){ //如果栏目ID为空
                if($this->article_id == 0){
                    if($this->tags_id == 0){
                        $seo['title'] = $this->web_config['web_title'] ;
                        $seo['keywords'] = $this->web_config['web_keywords'] ;
                        $seo['description'] = $this->web_config['web_description'] ;
                    }else{
                        $tagsmodel = new \app\common\model\Tags() ;
                        $tagsmodel->updateHitNum($tags_id) ;
                        $info = $tagsmodel->getTagsInfo($tags_id) ;
                        $seo['title'] = $info['tags_name'] .'-'.$this->web_config['web_title']  ;
                        $seo['keywords'] = $info['tags_name'] .','. $this->web_config['web_keywords'] ;
                        $seo['description'] = $this->web_config['web_description']  ;
                        $this->assign('tagsinfo',$info) ;
                    }
                }else{
                    $articlemodel = new \app\common\model\Article() ;
                    $articlemodel->updateViewnum($this->article_id) ; //更新点击量
                    $info = $articlemodel->articleInfo($this->article_id) ;
                    if(empty($info)){
                        $this->error('文章不存在') ;
                    }
                    $this->column_id = $info['cid'] ;
                    $seo['title'] = $info['article_title'] .'-'.$this->web_config['web_title']  ;
                    $seo['keywords'] = empty($info['seo_keywords']) ? $this->web_config['web_keywords'] : $info['seo_keywords'] ;
                    $seo['description'] = empty($info['seo_desc']) ? (empty($info['article_desc']) ? $this->web_config['web_description'] : $info['article_desc']) : $info['seo_desc'] ;
                    $this->assign('article_info',$info) ;
                }
            }else{
                $arctypemodel = new \app\common\model\Arctype() ;
                $where['cid'] = $cid ;
                $field = 'cid,pid,c_name,c_description,c_picurl,seo_title,seo_keywords,seo_description,'
                        .'link_attr,link_url,template_list' ;
                $column_info = $arctypemodel->find($where,$field) ;
                $this->cur_column_info = $column_info ;
                $seo['title'] = empty($column_info['seo_title']) ? $column_info['c_name'] .'-'.$this->web_config['web_title'] : $column_info['seo_title'] ;
                $seo['keywords'] = empty($column_info['seo_keywords']) ? $this->web_config['web_keywords'] : $column_info['seo_keywords'] ;
                $seo['description'] = empty($column_info['seo_description']) ? $this->web_config['web_description'] : $column_info['seo_description'] ;
            }
        }else{
            $seo['title'] = $title ;
            $seo['keywords'] = $keywords ;
            $seo['description'] = $description ;
        }
        $this->assign('seo',$seo) ;
        $this->assign('cur_column_id',$this->column_id) ;
        $this->assign('cur_column_info',$this->cur_column_info) ;
    }
}