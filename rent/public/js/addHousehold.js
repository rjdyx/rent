$(function () {
    $("input[name=hasHouse]").click(function () {
        changeHouseTimeModel();
    });

    $("#isDimission").click(function () {
        isDimission();
    });

    $("#region1").change(function () {
        id = $(this).attr('id');
        count = id.substring(6);
        getAddressByArea($(this).val(), count);
    });
})

var count = 1;
var is_jobNumber_unique = true;

/**
 * 新增租房模块
 */
function addRent() {


    if (count == 2) {
        return;
    }

    count++;

    $(".add-new-rent").remove();
    tmp = '<div class="addHouseHold-content addHouseHold-content-house rent-item item' + (count) + '">' +
        '<div class="addHouseHold-title">租房' + count + '<a class="close" onclick="$(\'.item' + count + '\').remove();count--;"></a></div><form class="form-rent-item"><table> <tr> <td>' +
        '<label for="region">区域：</label></td> <td class="td-right">' +
        '<select class="region" id="region' + count + '" name="region"></select></td> <td>' +
        '<label for="address">房址：</label> </td><td>' +
        '<select class="address" id="address' + count + '" name="address"></select></td></tr><tr><td>' +
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
    $("#region" + count).change(function () {
        id = $(this).attr('id');
        count = id.substring(6);
        getAddressByArea($(this).val(), count);
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
            $("#region" + count).append(tmp);
            tmp = '';
            for (i = 0; i < ret['addresses'].length; i++) {
                tmp += '<option value="' + ret['addresses'][i]['id'] + '">' + ret['addresses'][i]['name'] + '</option>';
            }
            $("#address" + count).append(tmp);
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
    baseData = $("#form-household-base-msg").serializeArray();
    rentData = $(".form-rent-item").serializeArray();
    result = userMsgValidate(baseData, rentData);
    if(!result){
        return false;
    }
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addHousehold",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            baseData: baseData,
            rentData: rentData
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                setTimeout(function () {
                    // window.location.reload();
                }, 1000);
            } else if (ret == 'baseMsgError') {
                showErrorTip();
                return;
            } else if (ret == 'rentMsgError') {
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
function getAddressByArea(id, count) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/getAddressByArea/" + id,
        dataType: "json",
        success: function (ret) {
            $("#address" + count).children().remove();
            var tmp = '';
            debugger;
            for (i = 0; i < ret.length; i++) {
                tmp += '<option value="' + ret[i]['id'] + '">' + ret[i]['name'] + '</option>';
            }
            $("#address" + count).append(tmp);


        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 新增用户时的验证器
 * @param baseData
 * @param rentData
 */
function userMsgValidate(baseData, rentData) {
    //name的验证
    if(baseData[0]['value'] == ''){
        showcommonErrorTip('姓名不能为空');
        return false;
    }
    if(baseData[0]['value'].length < 1 || baseData[0]['value'].length > 10 ){
        showcommonErrorTip('姓名长度为1~10');
        return false;
    }

    //jobNumber的验证
    if(baseData[1]['value'] == ''){
        showcommonErrorTip('工号不能为空');
        return false;
    }
    if(baseData[1]['value'].length < 1 || baseData[1]['value'].length > 12 ){
        showcommonErrorTip('工号长度为1~10');
        return false;
    }

    if(!is_jobNumber_unique){
        showcommonErrorTip('工号已存在');
        return false;
    }

    //cardNumber的验证
    if(baseData[2]['value'] != '' && (baseData[2]['value'].length < 1 || baseData[2]['value'].length > 19) ){
        showcommonErrorTip('银行卡号长度为1~19');
        return false;
    }

    //institution的验证
    if(baseData[4]['value'] == ''){
        showcommonErrorTip('单位不能为空');
        return false;
    }
    if(baseData[4]['value'].length < 1 || baseData[4]['value'].length > 20 ){
        showcommonErrorTip('单位长度为1~20');
        return false;
    }

    //inputCountTime的验证
    r = /\d{1,}\.\d{1,}\.\d{1,}/;
    if(baseData[5]['value'] != '' && baseData[5]['value'].match(r) == null){
        showcommonErrorTip('累计时间格式错误');
        return false;
    }
    //inSchoolTime的验证
    if($('#inSchoolTime').val() == ''){
        showcommonErrorTip('入校时间必选');
        return false;
    }

    //checkInTime1的验证
    if($('#checkInTime1').val() == ''){
        showcommonErrorTip('租房入住时间必选');
        return false;
    }

    //area的验证
    rex = /^\d+(\.\d+)?$/;
    if(rentData[3]['value'] == ''){
        showcommonErrorTip('房租面积必填');
        return false;
    }

    return true;

}

/**
 * 验证工号是否重复
 * @param jobNumber
 * @returns {boolean}
 */
function validateJobNumber(jobNumber) {
    jobNumber = $("#jobNumber").val();
    if(jobNumber == ""){
        return false;
    }
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/validateJobNumber",
        data:{
            jobNumber:jobNumber
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                is_jobNumber_unique = true;
                return true;
            } else if (ret == 'error') {
                showcommonErrorTip('工号已存在');
                is_jobNumber_unique = false;
                return false;
            }
        },
        error: function () {
            alert('errot');
        }
    });
}