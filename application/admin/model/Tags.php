<?php
/**
 * 标签
 * User: singeo
 * Date: 2018/10/30 0030
 * Time: 下午 3:12
 */

namespace app\admin\model;


use think\Db;

class Tags extends Base
{
    /**
     * 检查tags里面的标签是否已经在标中存在，返回tags_id
     * @param $tags
     * @return array|bool
     */
    public function insTags($tags){
        if(empty($tags)){
            return false ;
        }
        $tagsId = [] ;
        foreach ($tags as $t){
            if(!empty($t)){
                $tags_id = Db::name('tags')->where(['tags_name'=>$t])->value('tags_id') ;
                if($tags_id > 0){
                    $tagsId[] = $tags_id ;
                }else{
                    $tagsData['tags_name'] = $t ;
                    $tagsData['add_time'] = time() ;
                    $tagsId[] = Db::name('tags')->insertGetId($tagsData) ;
                }
            }
        }
        return $tagsId ;
    }
}