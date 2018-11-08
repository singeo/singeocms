/**
 * Created by 冯欣 on 2017/5/9.
 */
//模态框的扩展
//定义一个全局变量加载层
var __Loading ;
;(function($){
    var modalHeader = '<div class="modal-header"><h4 class="modal-title" id="myModalLabel"></h4></div>' ;
    var modalBody = '<div class="modal-body"></div>' ;
    var modelFooter = '<div class="modal-footer"></div>' ;
    var init = function(id){
        var modalInitHtml = '<div class="modal fade" id="'+id+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' ;
        modalInitHtml += '<div class="modal-dialog">' ;
        modalInitHtml += '<div class="modal-content"></div></div></div>' ;
        $('body').append(modalInitHtml) ;
    }
    var defaults = {
        content : '这里是内容' ,
        btn : false,
        remote: '',
        backdrop: 'static',
        keyboard: false,
        show:true,
        style:false,
        before: function(obj){},
        success: function(obj){},
        close: function(obj){},
        end: function(obj){
            $(obj).remove() ;
        },
        yes:function(obj){
            $(obj).modal('hide') ;
        }
    };
    return $.extend({
        myModal: {
            alert : function(options,callback){
                var opts = $.extend({}, defaults, options);
                var modalId = 'modal'+ Math.ceil(Math.random()*10000) ;
                init(modalId) ;
                if(opts.style){
                    $('.modal-dialog','#'+ modalId).css(opts.style) ;
                }
                $('.modal-content','#'+ modalId).append(modalBody) ;
                $('.modal-content','#'+ modalId).append(modelFooter) ;
                if(opts.btn){
                    $('.modal-content .modal-footer','#'+ modalId).html('<button type="button" class="btn btn-success" data-dismiss="modal">确认</button>') ;
                }
                $('.modal-content .modal-body','#'+ modalId).html(opts.content) ;
                $('#'+ modalId).modal({
                    backdrop : opts.backdrop ,
                    keyboard : false ,
                    show : true ,
                }) ;
                $('#'+ modalId).on('show.bs.modal', function () {
                    opts.before(this) ;
                });
                $('#'+ modalId).on('shown.bs.modal', function () {
                    opts.success(this) ;
                });
                $('#'+ modalId).on('hide.bs.modal', function () {
                    opts.close(this) ;
                });
                $('#'+ modalId).on('hidden.bs.modal', function () {
                    opts.end(this) ;
                });
                return modalId ;
            },
            confirm : function(options){
                var opts = $.extend({}, defaults, options);
                var modalId = 'modal'+ Math.ceil(Math.random()*10000) ;
                init(modalId) ;
                if(opts.style){
                    $('.modal-dialog','#'+ modalId).css(opts.style) ;
                }
                $('.modal-content','#'+ modalId).append(modalBody) ;
                $('.modal-content','#'+ modalId).append(modelFooter) ;
                if(opts.btn){
                    $('.modal-content .modal-footer','#'+ modalId).html('<button type="button" class="btn btn-default" data-dismiss="modal">取消</button><button type="button" class="btn btn-success yes">确认</button>') ;
                }
                $('.modal-content .modal-body','#'+ modalId).html(opts.content) ;
                $('#'+ modalId).modal({
                    backdrop : opts.backdrop ,
                    keyboard : false ,
                    show : true ,
                }) ;
                $('#'+ modalId).on('show.bs.modal', function () {
                    opts.before(this) ;
                });
                $('#'+ modalId).on('shown.bs.modal', function () {
                    opts.success(this) ;
                });
                $('#'+ modalId).on('hide.bs.modal', function () {
                    opts.close(this) ;
                });
                $('#'+ modalId).on('hidden.bs.modal', function () {
                    opts.end(this) ;
                });
                $('#'+ modalId).on('click','.yes', function () {
                    opts.yes('#'+ modalId) ;
                });
                return modalId ;
            },
            open : function(options){ //自定义打开一个页面
                var opts = $.extend({}, defaults, options);
                var modalId = 'modal'+ Math.ceil(Math.random()*10000) ;
                init(modalId) ;
                if(opts.style){
                    $('.modal-dialog','#'+ modalId).css(opts.style) ;
                }
                $('#'+ modalId).modal({
                    remote : opts.remote ,
                    backdrop : opts.backdrop ,
                    keyboard : false ,
                    show : true ,
                }) ;
                $('#'+ modalId).on('show.bs.modal', function () {
                    opts.before(this) ;
                });
                $('#'+ modalId).on('shown.bs.modal', function () {
                    opts.success(this) ;
                });
                $('#'+ modalId).on('hide.bs.modal', function () {
                    opts.close(this) ;
                });
                $('#'+ modalId).on('hidden.bs.modal', function () {
                    opts.end(this) ;
                });
                return modalId ;
            },
            loading : function (options) {
                var opts = $.extend({}, defaults, options);
                var modalId = 'modal'+ Math.ceil(Math.random()*10000) ;
                init(modalId) ;
                $('.modal-content','#'+ modalId).append(modalBody) ;
                var _loadHtml = '<div style="line-height:32px;padding-left:40px;background:url(/Static/Admin/images/loading.gif) no-repeat left center;">' + opts.content + '</div>'
                $('.modal-content .modal-body','#'+ modalId).html(_loadHtml) ;
                $('#'+ modalId).modal({
                    backdrop : opts.backdrop ,
                    keyboard : false ,
                    show : true ,
                }) ;
                __Loading =  $('#'+ modalId) ;
                return modalId ;
            },
            closeLoading : function () {
                __Loading.remove() ;
                $('.modal-backdrop').remove();
            },
            closeModal : function (id) {
                $('#'+id).modal('hide');
            }
        }
    });
})(jQuery);