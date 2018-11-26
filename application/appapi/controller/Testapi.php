<?php
/**
 * 测试接口
 * User: singeo
 * Date: 2018/11/20 0020
 * Time: 下午 3:49
 */
namespace app\appapi\controller ;

class Testapi extends Base
{
    public function tapi(){
        $data['session_id'] = session_id() ;
        $this->ajaxSuccess($data) ;
    }
}