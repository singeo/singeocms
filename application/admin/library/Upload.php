<?php
/**
 * 上传文件
 * User: singeo
 * Date: 2018/10/23 0023
 * Time: 下午 4:11
 */

namespace app\admin\library;


class Upload
{
    /**
     * 上传文件
     * @param $file 上传的文件
     * @param $dir
     * @return mixed
     */
    public function doUpload($file,$dir = null){
        $arr['status'] = 0 ;
        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //检查目录名
        $dir_name = empty($dir) ? 'image' : trim($dir);
        if (empty($ext_arr[$dir_name])) {
            // 上传失败获取错误信息
            $arr['status'] = 0 ;
            $arr['msg'] = '目录名不正确' ;
            return $arr ;
        }
        $file_name = $file->getInfo('name') ;
        //获得文件扩展名
        $temp_arr = explode(".", $file_name);
        $file_ext = array_pop($temp_arr);
        $file_ext = trim($file_ext);
        $file_ext = strtolower($file_ext);
        //检查扩展名
        if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
            // 上传失败获取错误信息
            $arr['status'] = 0 ;
            $arr['msg'] = "上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。" ;
            return $arr ;
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            if(empty($dir)){
                $upload_dir = DS . 'uploads' ;
            }else{
                $upload_dir = DS . 'uploads' . DS . $dir ;
            }
            $info = $file->move(ROOT_PATH . 'public' . $upload_dir,$this->createFileName());
            if($info){
                $save_dir = $upload_dir . DS . $info->getSaveName() ;
                $arr['status'] = 1 ;
                $arr['msg'] = 'success' ;
                $arr['url'] = str_replace('\\','/', $save_dir) ;
                return $arr ;
            }else{
                // 上传失败获取错误信息
                $arr['status'] = 0 ;
                $arr['msg'] = $file->getError() ;
                return $arr ;
            }
        }
    }

    /**
     * 创建生成的文件名
     */
    private function createFileName(){
        return $savename = date('Ymd') . DS . substr(md5(microtime(true)),5,13) ;
    }
}