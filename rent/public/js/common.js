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


function deleteItem() {
    layer.confirm('确定删除 ？', {
        btn: ['确定', '取消'] //按钮
    }, function () {

    }, function () {
        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
        parent.layer.close(index);
    });
}
