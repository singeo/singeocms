<?php
/**
 * 数据库备份与还原
 * User: 冯欣
 * Date: 2018/5/31 0031
 * Time: 下午 3:17
 */

namespace app\admin\controller;


use app\admin\library\Backup;
use app\library\Lib;
use think\Db;
use think\Exception;

class Datas extends Base
{
    /**
     * 数据备份
     */
    public function index(){
        $backup = new Backup() ;
        $tables = $backup->dataList() ;
        $total = 0 ;
        foreach ($tables as &$item){
            $item['size'] = Lib::format_bytes($item['data_length'] + $item['index_length']);
            $total += $item['data_length'] + $item['index_length'];
        }
        $this->assign('list', $tables);
        $this->assign('total', Lib::format_bytes($total));
        $this->assign('tableNum', count($tables));
        return $this->fetch();
    }

    /**
     * 备份数据的操作
     */
    public function backup(){
        //防止备份数据过程超时
        function_exists('set_time_limit') && set_time_limit(0);
        $param = $this->request->param();
        if(!empty($param['tables']) && is_array($param['tables'])){ //初始化
            $tables = $param['tables'] ;
            $path = config('data_backup_path');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => $path . DS,
                'part'     => config('data_backup_part_size'),
                'compress' => config('data_backup_compress'),
                'level'    => config('data_backup_compress_level'),
            );
            $now_time = time() ;
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->ajaxError('检测到有一个备份任务正在执行，请稍后再试！') ;
            } else {
                //创建锁文件
                file_put_contents($lock, $now_time);
            }

            //检查备份目录是否可写
            if(!is_writeable($config['path'])){
                $this->ajaxError('备份目录不存在或不可写，请检查后重试！') ;
            }
            session('backup_config', $config);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            $backup = new Backup($config);
            if(false !== $backup->Backup_Init()){
                $tab = ['id' => 0, 'start' => 0];
                $resData['tables'] = $tables ;
                $resData['tab'] = $tab ;
                $this->ajaxSuccess('初始化成功！',1,$resData) ;
            } else {
                $this->ajaxError('初始化失败，备份文件创建失败！') ;
            }
        } elseif (is_numeric($param['id']) && is_numeric($param['start'])) { //备份数据
            $id = $param['id'] ;
            $start = $param['start'] ;
            $tables = session('backup_tables');
            //备份指定表
            $backup = new Backup(session('backup_config'));
            $start  = $backup->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->ajaxError('备份出错') ;
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $resData['tab'] = $tab ;
                    $this->ajaxSuccess('备份完成！',1,$resData) ;
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_config', null);
                    $resData['tab'] = null ;
                    $this->ajaxSuccess('备份完成！') ;
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                $resData['tab'] = $tab ;
                $this->ajaxSuccess("正在备份...({$rate}%)",1,$resData) ;
            }

        } else {//出错
            $this->ajaxError('参数错误！') ;
        }
    }

    /**
     * 优化
     */
    public function optimize() {
        $table = $this->request->param('tablename') ;
        if (empty($table)) {
            return ['status' => 0 , 'msg' => '请选择要优化的表'] ;
        }
        try{
            $backup = new Backup() ;
            $result = $backup->optimize($table) ;
            return ['status' => 1 , 'msg' => '优化表成功'] ;
        }catch (Exception $e){
            return ['status' => 0 , 'msg' => $e->getMessage()] ;
        }
    }

    /**
     * 修复
     */
    public function repair() {
        $table = $this->request->param('tablename') ;
        if (empty($table)) {
            return ['status' => 0 , 'msg' => '请选择要修复的表'] ;
        }
        try{
            $backup = new Backup() ;
            $result = $backup->repair($table) ;
            return ['status' => 1 , 'msg' => '修复表成功'] ;
        }catch (Exception $e){
            return ['status' => 0 , 'msg' => $e->getMessage()] ;
        }
    }

    /**
     * 数据库还原
     */
    public function restore(){
        $path = config('data_backup_path');
        $config = [
            'path' => $path
        ] ;
        $backup = new Backup($config) ;
        $filelist = $backup->fileList() ;
        $totalSize = 0 ;
        if(!empty($filelist)){
            foreach ($filelist as &$item){
                $totalSize += $item['size'] ;
                $item['size'] = Lib::format_bytes($item['size']) ;
            }
        }
        $filelist = Lib::list_sort_by($filelist,'time','desc') ;
        $this->assign('totalSize',Lib::format_bytes($totalSize)) ;
        $this->assign('totalNum',count($filelist)) ;
        $this->assign('list',$filelist) ;
        return $this->fetch() ;
    }

    /**
     * 恢复
     */
    public function import(){
        function_exists('set_time_limit') && set_time_limit(0);
        $param = $this->request->param() ;
        if(isset($param['time']) && !isset($param['part']) && !isset($param['start'])){ //初始化
            //获取备份文件信息
            $time = $param['time'] ;
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->ajaxSuccess('初始化完成！', 1, ['part' => 1, 'start' => 0]);
            } else {
                $this->ajaxError('备份文件可能已经损坏，请检查！');
            }
        } elseif(is_numeric($param['part']) && is_numeric($param['start'])) {
            $part = $param['part'] ;
            $start = $param['start'] ;
            $list  = session('backup_list');
            $db = new Backup(array(
                'path'     => realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));
            $db->setFile($list[$part]) ;
            $start = $db->import($start);
            if(false === $start){
                $this->ajaxError('还原数据出错！');
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = ['part' => $part, 'start' => 0];
                    $this->ajaxSuccess("正在还原...#{$part}", 1, $data);
                } else {
                    session('backup_list', null);
                    $this->ajaxSuccess('还原完成！');
                }
            } else {
                $data = ['part' => $part, 'start' => $start[0]];
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->ajaxSuccess("正在还原...#{$part} ({$rate}%)", 1, $data);
                } else {
                    $data['gz'] = 1;
                    $this->ajaxSuccess("正在还原...#{$part}", 1, $data);
                }
            }
        } else {
            $this->ajaxError('参数错误！');
        }
    }


    /**
     * 下载
     * @param int $time
     */
    public function downFile() {
        $time = $this->request->param('time') ;
        $part = $this->request->param('part') ;
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        if(is_array($files)){
            foreach ($files as $filePath){
                if (!file_exists($filePath)) {
                    $this->error("该文件不存在，可能是被删除");
                }else{
                    $filename = basename($filePath);
                    header("Content-type: application/octet-stream");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . filesize($filePath));
                    readfile($filePath);
                }
            }
        }
    }

    /**
     * 删除页面
     * @param int $time
     */
    public function delFile() {
        $time = $this->request->param('time') ;
        if(empty($time)){
            $this->error('参数错误') ;
        }
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path) ;
        $this->assign('time', $time) ;
        $this->assign('files', $files) ;
        echo $this->fetch() ;
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function submitDel($time = 0){
        $time = $this->request->param('time') ;
        if(empty($time)){
            $this->error('参数错误') ;
        }
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if(count(glob($path))){
            $this->error('备份文件删除失败，请检查权限！');
        } else {
            $this->success('备份文件删除成功！');
        }

    }
}