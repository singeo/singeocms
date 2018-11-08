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
        remote:'/Admin/AdminUser/editPassword',
        backdrop: 'static',
        keyboard: false,
        show:true,
        success:function(o){
            userValid();
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
