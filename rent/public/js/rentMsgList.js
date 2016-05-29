$(function () {

})

/**
 * 显示详情
 * @param id
 */
function showDetail(id) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/showDetail/"+id,
        dataType: "json",
        success: function (ret) {
            arr = new Array();
            arr = ret.split('，');
            tmp = '';
            for(i = 0; i < arr.length; i++){
                arr_two = new Array();
                arr_two = arr[i].split('：');
                tmp += '<tr><td>'+arr_two[0]+'</td><td>'+arr_two[1]+'</td></tr>';
            }
            $('.showDetailTable').children().remove();
            $('.showDetailTable').append(tmp);
            $('#showDetailTip').fadeIn(200);
        },
        error: function () {
            alert('errot');
        }
    });
}