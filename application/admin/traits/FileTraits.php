<?php
/**
 * 文件上传，存储管理,适用于所有的上传，只需要在控制器中继承（traits）该类即可，在需要添加权限的地方增加权限
 * User: 冯欣
 * Date: 2018/5/31 0031
 * Time: 上午 9:43
 */

namespace app\admin\traits;


trait FileTraits
{
    /**
     * 上传文件方法
     */
    public function upload(){
        $file = request()->file('imgFile');
        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        $dir = request()->param('dir') ;
        //检查目录名
        $dir_name = empty($dir) ? 'image' : trim($dir);
        if (empty($ext_arr[$dir_name])) {
            // 上传失败获取错误信息
            $result['status'] = 1 ;
            $result['message'] = '目录名不正确' ;
            echo json_encode($result) ;
            exit ;
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
            $result['status'] = 1 ;
            $result['message'] = "上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。" ;
            echo json_encode($result) ;
            exit ;
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            if(empty($dir)){
                $upload_dir = DS . 'uploads' ;
            }else{
                $upload_dir = DS . 'uploads' . DS . $dir ;
            }
            $info = $file->move(ROOT_PATH . 'public' . $upload_dir);
            if($info){
                $save_dir = $upload_dir . DS . $info->getSaveName() ;
                $result['error'] = 0 ;
                $result['url'] = str_replace('\\','/', $save_dir) ;
                echo json_encode($result) ;
                exit ;
            }else{
                // 上传失败获取错误信息
                $result['status'] = 1 ;
                $result['message'] = $file->getError() ;
                echo json_encode($result) ;
                exit ;
            }
        }
    }

    /**
     * 文件管理方法
     */
    public function fileManager()
    {
        $php_path = $_SERVER['DOCUMENT_ROOT'] . '/';
        $php_url = '/';

        //根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $root_path = $php_path . 'uploads/';
        //根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $root_url = $php_url . 'uploads/';
        //图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

        //目录名
        $dir = trim(request()->param('dir')) ;
        $dir_name = empty($dir) ? '' : trim($dir);
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
            echo "Invalid Directory name.";
            exit;
        }
        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                mkdir($root_path);
            }
        }
        $path = trim(request()->param('path')) ;
        //根据path参数，设置各路径和URL
        if (empty($path)) {
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path = realpath($root_path) . '/' .$path;
            $current_url = $root_url . $path;
            $current_dir_path = $path;
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }

        //不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit;
        }
        //最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit;
        }
        //目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit;
        }

        //排序形式，name or size or type
        $order = request()->param('order') ;
        $order = empty($order) ? 'name' : strtolower($order);
        //遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') continue;
                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

        //排序
        usort($file_list, function($a, $b)
        {
            global $order;
            if ($a['is_dir'] && !$b['is_dir']) {
                return -1;
            } else if (!$a['is_dir'] && $b['is_dir']) {
                return 1;
            } else {
                if ($order == 'size') {
                    if ($a['filesize'] > $b['filesize']) {
                        return 1;
                    } else if ($a['filesize'] < $b['filesize']) {
                        return -1;
                    } else {
                        return 0;
                    }
                } else if ($order == 'type') {
                    return strcmp($a['filetype'], $b['filetype']);
                } else {
                    return strcmp($a['filename'], $b['filename']);
                }
            }
        });
        $result = array();
        //相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
        //相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
        //当前目录的URL
        $result['current_url'] = $current_url;
        //文件数
        $result['total_count'] = count($file_list);
        //文件列表数组
        $result['file_list'] = $file_list;

        //输出JSON字符串
        //header('Content-type: application/json; charset=UTF-8');
        echo json_encode($result) ;
    }
}