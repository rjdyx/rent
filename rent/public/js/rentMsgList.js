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
            tmp = '';
            tmp += '<tr><td>天数</td><td>'+ret['intervel']+'</td></tr>';
            tmp += '<tr><td>是否离职</td><td>'+(ret['isDimission']==0?'否':'是')+'</td></tr>';
            tmp += '<tr><td>是否有房</td><td>'+(ret['hasHouse']==0?'否':(ret['hasHouse']==1?'八区内有房':'有房改房'))+'</td></tr>';
            tmp += '<tr><td>年限</td><td>'+ret['time']+'</td></tr>';
            tmp += '<tr><td>租金x比例</td><td>'+ret['money']+'</td></tr>';
            $('.showDetailTable').children().remove();
            $('.showDetailTable').append(tmp);
            $('#showDetailTip').fadeIn(200);
        },
        error: function () {
            alert('errot');
        }
    });
}