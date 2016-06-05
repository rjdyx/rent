$(function () {
    $("input[name=hasHouse]").click(function () {
        changeHouseTimeModel();
    });

    $("#isDimission").click(function () {
        isDimission();
    });

    $("#region1").change(function(){
        id = $(this).attr('id');
        count = id.substring(6);
        getAddressByArea($(this).val(),count);
    });
})

var count = 1;

/**
 * 新增租房模块
 */
function addRent() {


    if(count == 2){
        return ;
    }

    count++;

    $(".add-new-rent").remove();
    tmp = '<div class="addHouseHold-content addHouseHold-content-house rent-item item' + (count) + '">' +
        '<div class="addHouseHold-title">租房' + count + '<a class="close" onclick="$(\'.item' + count + '\').remove();count--;"></a></div><form class="form-rent-item"><table> <tr> <td>' +
        '<label for="region">区域：</label></td> <td class="td-right">' +
        '<select class="region" id="region'+count+'" name="region"></select></td> <td>' +
        '<label for="address">房址：</label> </td><td>' +
        '<select class="address" id="address'+count+'" name="address"></select></td></tr><tr><td>' +
        '<label for="area">租房面积：</label></td><td class="td-right">' +
        '<input class="area" name="area" type="text" placeholder="必填，数字"></td><td>' +
        '<label for="first-check-in-time">第一次入住时间：</label></td><td>' +
        '<input type="text" id="checkInTime' + count + '" class="first-check-in-time" name="firsttimeCheckIn" value="" readonly="readonly" placeholder="必选">' +
        '</td> </tr></table></form></div>';
    tmp += '<div class="add-new-rent">' +
        '<button onclick="addRent()">新增租房</button><button id="btn-right" onclick="addHousehold()">保存</button>' +
        '</div>';
    $(".content").append(tmp);
    $('#checkInTime' + count).cxCalendar();
    $("#region"+count).change(function(){
        id = $(this).attr('id');
        count = id.substring(6);
        getAddressByArea($(this).val(),count);
    });
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/getAreaAndAddress",
        dataType: "json",
        success: function (ret) {
            var tmp = '';
            for (i = 0; i < ret['areas'].length; i++) {
                tmp += '<option value="' + ret['areas'][i]['id'] + '">' + ret['areas'][i]['name'] + '</option>';
            }
            $("#region"+count).append(tmp);
            tmp = '';
            for (i = 0; i < ret['addresses'].length; i++) {
                tmp += '<option value="' + ret['addresses'][i]['id'] + '">' + ret['addresses'][i]['name'] + '</option>';
            }
            $("#address"+count).append(tmp);
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 改变有房无房
 */
function changeHouseTimeModel() {
    switch ($("input[name=hasHouse]:checked").val()) {
        case "0":
            $("#has-house-label").html("无房时间：");
            break;
        case "1":
        case "2":
            $("#has-house-label").html("有房时间：");
            break;
        default:
            break;
    }
}


/**
 * 判断是否离职，使得离职时间是否可选
 */
function isDimission() {
    if ($("#isDimission").is(':checked')) {
        $('#dimission-time').show();
    } else {
        $('#dimission-time').hide();
    }

}

/**
 * 新增住户信息
 */
function addHousehold() {
    var baseData = $("#form-household-base-msg").serializeArray();
    var rentArr = new Array();
    $(".form-rent-item").each(function (index) {
        rentArr[index] = $(this).serializeArray();
    });
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addHousehold",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            baseData: baseData,
            rentArr: rentArr
        },
        dataType: "json",
        success: function (ret) {
            if(ret == 'success'){
                showSuccessTip();
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            }else if (ret == 'baseMsgError') {
                showErrorTip();
                return;
            }else if (ret == 'rentMsgError') {
                showErrorTip();
                return;
            }
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 根据区域获取房址
 */
function getAddressByArea(id,count) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/getAddressByArea/"+id,
        dataType: "json",
        success: function (ret) {
            $("#address"+count).children().remove();
            var tmp = '';
            debugger;
            for (i = 0; i < ret.length; i++) {
                tmp += '<option value="' + ret[i]['id'] + '">' + ret[i]['name'] + '</option>';
            }
            $("#address"+count).append(tmp);


        },
        error: function () {
            alert('errot');
        }
    });
}