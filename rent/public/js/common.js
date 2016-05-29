$(function () {
    //导航切换
    $(".menuson .header").click(function () {
        var $parent = $(this).parent();
        $(".menuson>li.active").not($parent).removeClass("active open").find('.sub-menus').hide();

        $parent.addClass("active");
        if (!!$(this).next('.sub-menus').size()) {
            if ($parent.hasClass("open")) {
                $parent.removeClass("open").find('.sub-menus').hide();
            } else {
                $parent.addClass("open").find('.sub-menus').show();
            }


        }
    });

    // 三级菜单点击
    $('.sub-menus li').click(function (e) {
        $(".sub-menus li.active").removeClass("active")
        $(this).addClass("active");
    });

    $('.title').click(function () {
        var $ul = $(this).next('ul');
        $('dd').find('.menuson').slideUp();
        if ($ul.is(':visible')) {
            $(this).next('.menuson').slideUp();
        } else {
            $(this).next('.menuson').slideDown();
        }
    });


})

/**
 * 显示成功操作提示框
 * @param content
 * @param width
 * @param height
 */
function showSuccessTip(content, width, height) {
    $("#st-container").fadeIn(200);
    setTimeout(function () {
        $("#st-container").fadeOut(200);
    }, 700);
}

/**
 * 显示失败操作提示框
 * @param content
 * @param width
 * @param height
 */
function showErrorTip(content, width, height) {
    $("#et-container").fadeIn(200);
    setTimeout(function () {
        $("#et-container").fadeOut(200);
    }, 700);
}

/**
 * 显示通用提示框
 * @param title 标题
 * @param content 内容
 * @param leftBtnName 左按钮文字
 * @param leftBtnFun 左按钮点击事件名
 * @param args 用二维数组传参数，用隐藏的input保存数据
 */
function showCommonDialog(title, content, leftBtnName, leftBtnFun, args) {
    $("#commonTop span").html(title);
    $("#commonInfo p").html(content);
    $("#commonSure").val(leftBtnName);
    $("#commonSure").attr('onclick', leftBtnFun + "()");
    $("#commonTip").fadeIn(200);
    for (i = 0; i < args.length; i++) {
        $("#" + args[i][0]).remove();
        var tmp = '';
        tmp += '<input type="hidden" id="' + args[i][0] + '" value="' + args[i][1] + '">';
    }
    $("#commonTip").append(tmp);
}

/**
 * 隐藏弹出窗口
 * @param dialogId
 */
function fadeOutDialog(dialogId) {
    $('#'+dialogId).fadeOut(200);
    $('label[class=error]').remove();
    $('#'+dialogId).css('width', '400px');
}
