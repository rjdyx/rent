(function () {

})

/**
 * 显示重置密码提示框
 * @param id
 */
function showResetPWDDialog(id) {
    $('#resetPWDTip').fadeIn(200);
    $("#resetPWD-id").val(id);
}

/**
 * 重置密码
 */
function resetPWD() {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/resetPWD",
        data: {
            id: $("#resetPWD-id").val(),
            password: $("#resetPWDNameInput").val()
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                $('#resetPWDTip').fadeOut(200);
            } else {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 冻结或解冻账号
 * @param id
 * @param flag
 */
function lock(id, flag) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/lock/" + id + "/" + flag,
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                if(flag == 0){
                    $("#lock-"+id).attr('onclick','lock('+id+',1)');
                    $("#lock-"+id).html('解冻');
                }else{
                    $("#lock-"+id).attr('onclick','lock('+id+',0)');
                    $("#lock-"+id).html('冻结');
                }

            }
        },
        error: function () {
            alert('errot');
        }
    });
}