$(function () {

})

/**
 * 删除住户信息
 * @param id
 */
function deleteHouseholdMsg() {
    id = $("#input-id").val();
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/deleteHouseholdMsg/"+id,
        dataType: "json",
        success: function (ret) {
            if(ret == 'success'){
                $("#commonTip").fadeOut(200);
                showSuccessTip();
                window.location.reload();
            }
            if (ret == 'failed') {
                $("#commonTip").fadeOut(200);
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}