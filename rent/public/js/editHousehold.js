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

var flag = 0;

/**
 * 新增租房模块
 */
function addRent() {

    if (count == 2 || flag != 0) {
        return;
    }
    flag = 1;

    tmp = '<div class="addHouseHold-content addHouseHold-content-house rent-item item' + (++count) + '">' +
        '<div class="addHouseHold-title">租房' + count + '<a class="close" onclick="$(\'.item' + count + '\').remove();count--;flag = 0;"></a></div><form class="form-rent-item" id="form-item' + count + '"><table> <tr> <td>' +
        '<label for="region">区域：</label></td> <td class="td-right">' +
        '<select class="region" id="region' + count + '" name="region"></select></td> <td>' +
        '<label for="address">房址：</label> </td><td>' +
        '<select class="address" id="address' + count + '" name="address"></select></td></tr><tr><td>' +
        '<label for="area">租房面积：</label></td><td class="td-right">' +
        '<input class="area" name="area" type="text"/></td><td>' +
        '<label for="first-check-in-time">第一次入住时间：</label></td><td>' +
        '<input type="text" id="checkInTime' + count + '" class="first-check-in-time" name="firsttimeCheckIn" value="" readonly="readonly">' +
        '</td> </tr></table></form>' +
        '<div class="opt-rent-btn">' +
        '<button onclick="addSingleRent(\'form-item' + count + '\',' + count + ')">保存</button>' +
        '</div></div>';
    $(".new-rent-area").append(tmp);
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
 * 根据区域获取房址
 */
function getAddressByArea(name, count) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/getAddressByArea/" + name,
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
 * 退房
 */
function checkOutRent(id) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/checkOutRent/" + id,
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                window.location.reload();
            }
            if (ret == 'failed') {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 新增租房
 * @param id
 */
function addSingleRent(id,order) {
    var data = $("#" + id).serializeArray();
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addSingleRent",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: $("#householdId").val(),
            order:order,
            data: data
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                window.location.reload();
            }
            if (ret == 'failed') {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 租房作废，不记录房租信息
 * @param id
 */
function deleteRent(id) {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/deleteRent/"+id,
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                window.location.reload();
            }
            if (ret == 'failed') {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 保存住户基本信息的修改
 * @param id
 */
function saveChange(id) {
    var data = $("#form-household-base-msg").serializeArray();
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/saveChange",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id,
            data: data
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                showSuccessTip();
                window.location.reload();
            }
            if (ret == 'failed') {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}