$(function () {
    var par = {} ;
    par.user_id = 98 ;
    par.asr = '1123' ;
    par.role = 'common' ;
    par.name = '啊哈哈哈哈哈' ;
    par.passwd = encrypt_rsa('123456777') ;
    var sign  = create_sign(par) ;
    if(sign){
        par.sign = sign ;
    }
    $.ajax({
        url: 'https://wechat.liantoujinrong.com/Index/bannerList',
        data: par,
        type: 'POST',
        dataType:'JSON',
        xhrFields: {withCredentials: true},
        success:function (context) {
            console.log(context) ;
        },
        error:function (errorText) {
            console.log(errorText) ;
        }
    }) ;
}) ;