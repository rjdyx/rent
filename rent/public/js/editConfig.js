$(function () {

})


/**
 *添加区域
 */
function addArea() {
    var name = $("#addAreaNameInput").val();
    $("#addAreaTip").fadeOut(200);
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addArea",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: name
        },
        dataType: "json",
        success: function (ret) {
            if(ret == 'success'){
                showSuccessTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}