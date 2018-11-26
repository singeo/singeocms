//生成签名
function create_sign(params){
    var paramStr = "";
    if (typeof params == "string") {
        paramStr = params;
    }else if (typeof params == "object") {
        var arr = [];
        for (var i in params) {
            if (params.hasOwnProperty(i) || i != 'sign') {
                arr.push((i + "=" + params[i]));
            }
        }
        paramStr = arr.join(("&"));
    }
    if (paramStr) {
        var newParamStr = paramStr.split("&").sort().join("&");
        var sign = md5(newParamStr.toLowerCase()).toUpperCase() ;
        return sign ;
    }
    return false ;
}

//rsa加密 ,用于密码加密处理
function encrypt_rsa(str) {
    if(str == "") return false ;
    var rsa = new RSAKey();
    var modulus = "BAA63BA7BAFDCA72E888A7B21B70E897EF095CFD6424E9933167CE64BC82E2A7CBE6FF690E51A2E9093813387E1882FF4927FEEDE6AD3A4E706EA07D93D30C81159D044F5D6B14711A1385F8FD6E42BA01FAFF1D891C9920CA941DC03FF9ADBFB46E005A328069D8BB908D223D3A0798E6DCEF012729CA50D8CA6D1A2930D85F";
    var exponent = "10001";
    rsa.setPublic(modulus, exponent);
    return rsa.encrypt(str);
}
