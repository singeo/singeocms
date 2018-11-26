<?php
/**
 * appapi接口
 * User: singeo
 * Date: 2018/11/20 0020
 * Time: 下午 3:48
 */
namespace app\appapi\controller ;

use think\Request;

class Base
{
    /**
     * 构造函数
     * Base constructor.
     */
    public function __construct(Request $request)
    {
        $this->allowOrigin() ;
        $param = $request->param() ;
        if(!isset($param['sign'])){
            $this->ajaxError('签名数据不存在') ;
        }
        $apiencrypt = new \app\appapi\library\ApiEncrypt() ;
        $sign = $apiencrypt->makeSign($param) ;
        if($sign != $param['sign']){
            $this->ajaxError('验签失败') ;
        }
    }

    /**
     * 跨域请求配置
     */
    private function allowOrigin(){
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        //允许请求的域名
        $allow_origin = [
            '*',
        ];
        if(in_array($origin, $allow_origin)){
            header('Access-Control-Allow-Origin:'.$origin);
            header('Access-Control-Allow-Methods:POST');
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }
    }


    /**
     * ajax返回错误信息
     * @param int $status
     * @param string $message
     * @param array $data
     */
    protected function ajaxError($message = 'error',$status = 0 ,$data = []){
        $arr['status'] = $status ;
        $arr['message'] = $message ;
        $arr['data'] = $data ;
        echo json_encode($arr) ;
        exit ;
    }

    /**
     * ajax返回成功信息
     * @param array $data
     * @param int $status
     * @param string $message
     */
    protected function ajaxSuccess($data = [], $status = 1 ,$message = 'success'){
        $arr['status'] = $status ;
        $arr['message'] = $message ;
        $arr['data'] = $data ;
        echo json_encode($arr) ;
        exit ;
    }
}