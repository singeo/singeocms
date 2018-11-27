<?php
/**
 * 签名和验签
 * User: singeo
 * Date: 2018/11/20 0020
 * Time: 上午 11:35
 */
namespace app\appapi\library ;
class ApiEncrypt
{
    /**
     * 私钥
     * @var string
     */
    private $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC6pjunuv3KcuiIp7IbcOiX7wlc/WQk6ZMxZ85kvILip8vm/2kO
UaLpCTgTOH4Ygv9JJ/7t5q06TnBuoH2T0wyBFZ0ET11rFHEaE4X4/W5CugH6/x2J
HJkgypQdwD/5rb+0bgBaMoBp2LuQjSI9OgeY5tzvAScpylDYym0aKTDYXwIDAQAB
AoGBAIb3eZ+6KZbhLKDUkoghRy/GXADwAiBm/lb1d1uErSh0qY8qFa+S/LiCQBg1
+4iCAVPHJiKlcZH98nMsfmIMdLcNvn7exIr2gO/DK07ubk6JH+vC3+maTUYWAWMk
qq0lNTuehU20vlmyGBsMFWq+kcM/V8urnNUJeJtmqA5SVkuBAkEA7hUG24f9iKfh
K7N8DQ+A5tvWNPikWxfX9rC0gcctLbTQFvFrGX5vLgu+/eC1+5swqxXx0v21hi5I
50NVHPpdPwJBAMiySMVaUXM35Y1oqaLo6bzbooAS6cdQFMRrfd5W0kUl6IA88mSE
L6spygn8jSHP04WQESNxEfWPRxVZu+qOHOECQQCD1mr0uCqCOQysiXiBNvuXW1cU
ADfrJZn2xkU+tE/lRoIQomE/Pc9NPT3nEj9T880QgFdoEgwqIIlIXvXL1Sw7AkBm
g8rwJAZe2DqFVOTxtg9OzNHgociQarNw8YdFvwuBDrAIcRlPhsXipGHzX/GnR8VA
ACsA84y85gblPQTj9tuBAkAaK1XcYGsHAJZBflhTcb7EsWvyCTqaaX/gcgFZB88L
cPeXRizEqC3OsvuPkTKZ1GWpPD+NIZiftZ49Cw8C9u/W
-----END RSA PRIVATE KEY-----' ;
    /**
     * 公钥
     * @var string
     */
    private $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6pjunuv3KcuiIp7IbcOiX7wlc
/WQk6ZMxZ85kvILip8vm/2kOUaLpCTgTOH4Ygv9JJ/7t5q06TnBuoH2T0wyBFZ0E
T11rFHEaE4X4/W5CugH6/x2JHJkgypQdwD/5rb+0bgBaMoBp2LuQjSI9OgeY5tzv
AScpylDYym0aKTDYXwIDAQAB
-----END PUBLIC KEY-----' ;

    /**
     * 取模
     * @var string
     */
    private $modulus = 'BAA63BA7BAFDCA72E888A7B21B70E897EF095CFD6424E9933167CE64BC82E2A7CBE6FF690E51A2E9093813387E1882FF4927FEEDE6AD3A4E706EA07D93D30C81159D044F5D6B14711A1385F8FD6E42BA01FAFF1D891C9920CA941DC03FF9ADBFB46E005A328069D8BB908D223D3A0798E6DCEF012729CA50D8CA6D1A2930D85F' ;
    private $exponent = '10001' ;
    /**
     * 生成签名
     * @param $data
     * @return string
     */
    public function makeSign($data) {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        if (count($data) >= 1) {
            //参数字典排序
            ksort($data);
            $str = '';
            $i = 0;
            foreach ($data as $key => $val) {
                if ($i == 0) {
                    $str .= $key . "=" . $val;
                } else {
                    $str .= "&" . $key . "=" . $val;
                }
                $i++;
            }
            return strtoupper(md5($str));
        }
        return false ;
    }

    /**
     * 解密RSA
     * @param $enStr
     * @return bool
     */
    public function decryptRSA($enStr){
        if(!$enStr) return false ;
//        $hex_encrypt_data = trim($enStr); //十六进制数据
//        $encrypt_data = pack("H*", $hex_encrypt_data);//对十六进制数据进行转换
        openssl_private_decrypt($enStr, $decrypt_data, $this->private_key);
        return $decrypt_data ;
    }

    public function encryptRSA($str){
        if(!$str) return false ;
        openssl_public_encrypt($str,$encrypt_data,$this->public_key) ;
        return base64_encode($encrypt_data) ;
    }
}