(function () {
    
})

function searchRentByOne() {
    $.ajax({
        type: "get",
        url: rootUrl + "/searchRentByOne",
        data: {
            data:$("#form-index").serializeArray()
        },
        dataType: "json",
        success: function (ret) {
            if(ret == 'none'){
                alert('none');
                return ;
            }
            // $(".index-search").animate({
            //     top:'28%'
            // });
            $("#index-info-list").fadeIn(200);
            tmp = '';
            for(i = 0;i<ret.length;i++){
                tmp += '<tr>'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+FormatDate(ret[i]['firsttime_check_in'])+'</td>'+
                            '<td>'+(ret[i]['lasttime_pay_rent'] == null?'':FormatDate(ret[i]['lasttime_pay_rent']))+'</td>'+
                            '<td>'+FormatDate(ret[i]['time_pay_rent'])+'</td>'+
                            '<td>'+ret[i]['rent']+'</td>'+
                            '<td>'+ret[i]['order']+'</td>'+
                            '<td>'+(ret[i]['isDimission']==0?'否':'是')+'</td>'+
                            '<td>'+(ret[i]['hasHouse']==0?'否':(ret[i]['hasHouse']==1?'商品房':'房改房'))+'</td>'+
                            '<td>'+ret[i]['time']+'</td>'+
                            '<td>'+ret[i]['region']+'</td>'+
                            '<td>'+ret[i]['address']+'</td>'+
                            '<td>'+ret[i]['money']+'</td>'+
                            '<td>'+ret[i]['area']+'</td>'+
                        '</tr>';
            }
            $('#index-result-content').children().remove();
            $('#index-result-content').append(tmp);
        },
        error: function () {
            alert('errot');
        }
    });
}

function FormatDate(strTime) {
    var date = new Date(strTime);
    return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
}