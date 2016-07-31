$(function () {

})

var hasShareHousehold = 0;
var householdId = 0;

/**
 * 查找合租人
 */
function searchShareHousehold() {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/searchShareHousehold",
        data: {
            name:$("#index-name").val(),
            jobNumber:$("#index-jobNumber").val()
        },
        dataType: "json",
        success: function (ret) {
            //清空
            $("#region").val('');
            $("#address").val('');
            $("#first-check-in-time").val('');
            $("#area").val('');
            $("#roomNumber").val('');
            $("#remark").html('');
            hasShareHousehold = 0;
            householdId = 0;
            if(ret == 'none'){
                showcommonErrorTip('查无此人');
            }else if(ret == 'rented'){
                showcommonErrorTip('此人已和其他人合租');
            }else if(ret == 'illegal'){
                showcommonErrorTip('此人拥有的单间多于或少于1')
            }else {
                //赋值
                householdId = ret['householdId'];
                $("#region").val(ret['region']);
                $("#address").val(ret['address']);
                $("#first-check-in-time").val(ret['firsttime_check_in']);
                $("#area").val(ret['area']);
                $("#roomNumber").val(ret['room_number']);
                $("#remark").html(ret['remark']);
                hasShareHousehold = 1;
            }
        },
        error: function () {
            alert('errot');
        }
    });
}


/**
 * 新增合租住户信息
 */
function addShareHousehold() {
    baseData = $("#form-household-base-msg").serializeArray();
    result = userMsgValidate(baseData);
    if(!result){
        return false;
    }
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addShareHousehold",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            baseData: baseData,
            householdId: householdId
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
 * 新增用户时的验证器
 * @param baseData
 * @param rentData
 */
function userMsgValidate(baseData) {
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
    if(baseData[3]['value'] == ''){
        showcommonErrorTip('单位不能为空');
        return false;
    }
    if(baseData[3]['value'].length < 1 || baseData[3]['value'].length > 20 ){
        showcommonErrorTip('单位长度为1~20');
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