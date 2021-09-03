/**
 * 自定义的一些JS函数放在这里
 */
//表格汉化
var lang = {
    "sProcessing": "处理中...",
    "sLengthMenu": "每页 _MENU_ 项",
    "sZeroRecords": "没有匹配结果",
    "sInfo": "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项。",
    "sInfoEmpty": "当前显示第 0 至 0 项，共 0 项",
    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
    "sInfoPostFix": "",
    "sSearch": "搜索:",
    "sUrl": "",
    "sEmptyTable": "暂无可用数据",
    "sLoadingRecords": "载入中...",
    "sInfoThousands": ",",
    "oPaginate": {
        "sFirst": "首页",
        "sPrevious": "上页",
        "sNext": "下页",
        "sLast": "末页",
        "sJump": "跳转"
    },
    "oAria": {
        "sSortAscending": ": 以升序排列此列",
        "sSortDescending": ": 以降序排列此列"
    }
};

//修改密码modal框
function editPassword(){
    $.myModal.open({
        remote:'/Admin/Index/editPassword',
        backdrop: 'static',
        keyboard: false,
        show:true,
        success:function(o){
            initModal();
        }
    }) ;
}

//ajax 分页
function ajax_page(pageCont,total,curr,isSkip,callback){
    //显示分页
    laypage({
        cont: pageCont, //容器。值支持id名、原生dom对象，jquery对象。
        pages: total, //通过后台拿到的总页数
        curr:  curr || 1, //当前页
        skip: isSkip, //是否显示跳转
        skin:'#1E9FFF',
        jump: function(obj, first){ //触发分页后的回调
            if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                callback(obj.curr);
            }
        }
    });
}

/**
 * 全选和非全选
 */
function checkAllOrNot() {
    //全选的实现
    $(".check-all").on('click',function(){
        $(".check-ids").prop("checked", this.checked);
    });
    $(".check-ids").on('click',function(){
        var option = $(".check-ids");
        var is_all = true ;
        option.each(function(i){
            if(!this.checked){
                is_all = false ;
            }
        });
        $(".check-all").prop("checked", is_all);
    });
}

/**
 * 初始化wangEditor编辑器
 * @param editor_id
 * @param textarea
 */
function initWangEditor(editor_id,textarea) {
    var E = window.wangEditor ;
    var editor = new E(editor_id) ;
    editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        textarea.val(html) ;
    } ;
    editor.customConfig.uploadFileName = 'imgFile' ;
    editor.customConfig.uploadImgServer = '/admin/Webconfig/wangUpload?dir=image' ;
    editor.customConfig.uploadImgHooks = {
        before: function (xhr, editor, files) {
            // 图片上传之前触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件

            // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
            // return {
            //     prevent: true,
            //     msg: '放弃上传'
            // }
        },
        success: function (xhr, editor, result) {
            // 图片上传并返回结果，图片插入成功之后触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        fail: function (xhr, editor, result) {
            alert(result.msg) ;
            // 图片上传并返回结果，但图片插入错误时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
        },
        error: function (xhr, editor) {
            // 图片上传出错时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },
        timeout: function (xhr, editor) {
            // 图片上传超时时触发
            // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
        },

        // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
        // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
        customInsert: function (insertImg, result, editor) {
            // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
            // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

            // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
            var url = result.url ;
            insertImg(url)

            // result 必须是一个 JSON 格式字符串！！！否则报错
        }
    } ;
    editor.create() ;
    editor.txt.html(textarea.val()) ;
    return editor ;
}
