$(function () {
    // init();
    init2();
})

function init() {
    $("#addArea-form").validate({
        onsubmit: true,// 是否在提交是验证
        onfocusout: false,// 是否在获取焦点时验证
        onkeyup: false,// 是否在敲击键盘时验证

        rules: {
            areaName: {
                required: true,
                minlength: 2,
                maxlength: 20,
            }
        },
        messages: {
            areaName: {
                required: '请输入区域名',
                minlength: '长度不能少于2',
                maxlength: '长度不能超过20',
            }
        },
        submitHandler: function (form) {  //通过之后回调
            addArea();
            $('#addAreaTip').css('width', '400px');
        },
        invalidHandler: function (form, validator) {  //不通过回调
            $('#addAreaTip').css('width', '432px');
            return false;
        }
    });
    $("#editArea-form").validate({
        onsubmit: true,// 是否在提交是验证
        onfocusout: false,// 是否在获取焦点时验证
        onkeyup: false,// 是否在敲击键盘时验证

        rules: {
            areaName: {
                required: true,
                minlength: 2,
                maxlength: 20,
            }
        },
        messages: {
            areaName: {
                required: '请输入区域名',
                minlength: '长度不能少于2',
                maxlength: '长度不能超过20',
            }
        },
        submitHandler: function (form) {  //通过之后回调
            editArea();
            $('#editAreaTip').css('width', '400px');
        },
        invalidHandler: function (form, validator) {  //不通过回调
            $('#editAreaTip').css('width', '432px');
            return false;
        }
    });
    $("#form_addAddress").validate({
        onsubmit: true,// 是否在提交是验证
        onfocusout: false,// 是否在获取焦点时验证
        onkeyup: false,// 是否在敲击键盘时验证

        rules: {
            AddressName: {
                required: true,
                minlength: 2,
                maxlength: 20
            },
            TurnoverRent: {
                required: true,
                number: true
            },
            DiscountRent: {
                required: true,
                number: true
            },
            MarketRent: {
                required: true,
                number: true
            }
        },
        messages: {
            AddressName: {
                required: '请输入房址',
                minlength: '长度不能小于2',
                maxlength: '长度不能超过20'
            },
            TurnoverRent: {
                required: '请输入周转期租金',
                number: '必须为数字'
            },
            DiscountRent: {
                required: '请输入优惠市场租金',
                number: '必须为数字'
            },
            MarketRent: {
                required: '请输入市场租金',
                number: '必须为数字'
            }
        },
        submitHandler: function (form) {  //通过之后回调
            addAddress();
            $('#addAddressTip').css('width', '400px');
        },
        invalidHandler: function (form, validator) {  //不通过回调
            $('#addAddressTip').css('width', '432px');
            return false;
        }
    });
    $("#form_editAddress").validate({
        onsubmit: true,// 是否在提交是验证
        onfocusout: false,// 是否在获取焦点时验证
        onkeyup: false,// 是否在敲击键盘时验证

        rules: {
            AddressName: {
                required: true,
                minlength: 2,
                maxlength: 20
            },
            TurnoverRent: {
                required: true,
                number: true
            },
            DiscountRent: {
                required: true,
                number: true
            },
            MarketRent: {
                required: true,
                number: true
            }
        },
        messages: {
            AddressName: {
                required: '请输入房址',
                minlength: '长度不能小于2',
                maxlength: '长度不能超过20'
            },
            TurnoverRent: {
                required: '请输入周转期租金',
                number: '必须为数字'
            },
            DiscountRent: {
                required: '请输入优惠市场租金',
                number: '必须为数字'
            },
            MarketRent: {
                required: '请输入市场租金',
                number: '必须为数字'
            }
        },
        submitHandler: function (form) {  //通过之后回调
            editAddress();
            $('#editAddressTip').css('width', '400px');
        },
        invalidHandler: function (form, validator) {  //不通过回调
            $('#editAddressTip').css('width', '490px');
            return false;
        }
    });
}

function init2() {
    addAreaOptions = {
        success: function (ret){
            if (ret == 'success') {
                $("#addAreaTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
            } else {
                showErrorTip();
            }
        },　　　　   //提交成功后执行的回调函数
        dataType: "json",　　　　　　　//服务器返回数据类型
        clearForm: true,　　　　　　 //提交成功后是否清空表单中的字段值
        restForm: true,　　　　　　  //提交成功后是否重置表单中的字段值，即恢复到页面加载时的状态
    }
    $("#addArea-form").ajaxForm(addAreaOptions);
    editAreaOptions = {
        success: function (ret){
            if (ret == 'success') {
                $("#editAreaTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
            } else {
                showErrorTip();
            }
        },　　　　   //提交成功后执行的回调函数
        dataType: "json",　　　　　　　//服务器返回数据类型
        clearForm: true,　　　　　　 //提交成功后是否清空表单中的字段值
        restForm: true,　　　　　　  //提交成功后是否重置表单中的字段值，即恢复到页面加载时的状态
    }
    $("#editArea-form").ajaxForm(editAreaOptions);
    addAddressOptions = {
        success: function (ret){
            if (ret == 'success') {
                $("#addAddressTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
            } else {
                showErrorTip();
            }
        },　　　　   //提交成功后执行的回调函数
        dataType: "json",　　　　　　　//服务器返回数据类型
        clearForm: true,　　　　　　 //提交成功后是否清空表单中的字段值
        restForm: true,　　　　　　  //提交成功后是否重置表单中的字段值，即恢复到页面加载时的状态
    }
    $("#form_addAddress").ajaxForm(addAddressOptions);
    addAddressOptions = {
        success: function (ret){
            if (ret == 'success') {
                $("#editAddressTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
            } else {
                showErrorTip();
            }
        },　　　　   //提交成功后执行的回调函数
        dataType: "json",　　　　　　　//服务器返回数据类型
        clearForm: true,　　　　　　 //提交成功后是否清空表单中的字段值
        restForm: true,　　　　　　  //提交成功后是否重置表单中的字段值，即恢复到页面加载时的状态
    }
    $("#form_editAddress").ajaxForm(addAddressOptions);
}

/**
 * 显示新增区域查看
 */
function showAddArea() {
    $('#addAreaTip').fadeIn(200);
    $('#addAreaNameInput').val("");
}

/**
 * 显示新增房址窗口
 */
function showAddAddress() {
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/getAllArea",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function (ret) {
            $('#addAddressTip').fadeIn(200);
            $('#addAddressNameInput').val("");
            $('#addTurnoverRentInput').val("");
            $('#addDiscountRentInput').val("");
            $('#addMarketRentInput').val("");
            $("#addAreaOption").children().remove();
            var tmp = '';
            for (i = 0; i < ret.length; i++) {
                tmp += '<option value="' + ret[i]['id'] + '">' + ret[i]['name'] + '</option>';
            }
            $("#addAreaOption").append(tmp);
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 *添加区域
 */
function addArea() {
    var name = $("#addAreaNameInput").val();
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addArea",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: name
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#addAreaTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
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
 *添加房址
 */
function addAddress() {
    var data = $("#form_addAddress").serializeArray();
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/addAddress",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: data
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#addAddressTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
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
 * 显示所有区域和房址
 */
function getAllAreaAndAddress() {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/getAllAreaAndAddress",
        dataType: "json",
        success: function (ret) {
            $("#areaAndAddress").children().remove();
            var tmp = '';
            for (i = 0; i < ret.length; i++) {
                tmp += '<div class="item" id="' + ret[i][0]['id'] + '">' +
                    '<div class="item-title">' +
                    '<span class="item-area">' + ret[i][0]['name'] + '</span>' +
                    '<span class="item-top-operation"><a onclick="showEditAreaDialog(\'' + ret[i][0]['id'] + '\',\'' + ret[i][0]['name'] + '\')">编辑</a><a onclick="showSureDeleteDialog(\'' + ret[i][0]['id'] + '\',\'0\')">删除</a></span>' +
                    '</div>' +
                    '<div class="item-content">' +
                    '<table>' +
                    '<tr class="item-content-th">' +
                    '<td>房址</td>' +
                    '<td>周转租金</td>' +
                    '<td>优惠市场租金</td>' +
                    '<td>市场租金</td>' +
                    '<td>操作</td>' +
                    '</tr>';
                for (j = 0; j < ret[i][1].length; j++) {
                    tmp += '<tr id="' + ret[i][1][j]['id'] + '">' +
                        '<td>' + ret[i][1][j]['name'] + '</td>' +
                        '<td>' + ret[i][1][j]['turnover_rent'] + '</td>' +
                        '<td>' + ret[i][1][j]['discount_rent'] + '</td>' +
                        '<td>' + ret[i][1][j]['market_rent'] + '</td>' +
                        '<td><a onclick="showEditAddressDialog(\'' + ret[i][1][j]['id'] + '\',\'' + ret[i][1][j]['name'] + '\',\'' + ret[i][1][j]['turnover_rent'] + '\',\'' + ret[i][1][j]['discount_rent'] + '\',\'' + ret[i][1][j]['market_rent'] + '\')">编辑</a>&nbsp;&nbsp;<a onclick="showSureDeleteDialog(\'' + ret[i][1][j]['id'] + '\',\'1\')">删除</a></td>' +
                        '</tr>';
                }
                tmp += '</table></div></div>';
            }
            $("#areaAndAddress").append(tmp);
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 显示编辑区域名窗口
 */
function showEditAreaDialog(id, name) {
    $("#edit-area-id").val(id);
    $("#editAreaNameInput").val(name);
    $("#editAreaTip").fadeIn(200);
}

/**
 * 编辑区域名
 */
function editArea() {
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/editArea",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: $("#edit-area-id").val(),
            name: $("#editAreaNameInput").val()
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#editAreaTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
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
 * 显示编辑房址窗口
 */
function showEditAddressDialog(id, AddressName, TurnoverRent, DiscountRent, MarketRent) {
    $("#edit-address-id").val(id);
    $("#editAddressNameInput").val(AddressName);
    $("#editTurnoverRentInput").val(TurnoverRent);
    $("#editDiscountRentInput").val(DiscountRent);
    $("#editMarketRentInput").val(MarketRent);
    $("#editAddressTip").fadeIn(200);
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/getAllAreaAndCheckOne",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id
        },
        dataType: "json",
        success: function (ret) {
            $("#editAreaOption").children().remove();
            var tmp = '';
            for (i = 0; i < ret.areas.length; i++) {
                tmp += '<option ' + (ret.areas[i]['id'] == ret.areaId ? 'selected="selected"' : '') + ' value="' + ret.areas[i]['id'] + '">' + ret.areas[i]['name'] + '</option>';
            }
            $("#editAreaOption").append(tmp);
        },
        error: function () {
            alert('errot');
        }
    });
}

/**
 * 编辑房址
 */
function editAddress() {
    var id = $("#edit-address-id").val();
    var data = $("#form_editAddress").serializeArray();
    $.ajax({
        type: "post",
        url: rootUrl + "/admin/editAddress",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id,
            data: data
        },
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#editAddressTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
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
 * 显示确认删除提示框
 * @param id
 * @param flag
 */
function showSureDeleteDialog(id, flag) {
    $("#sure-delete-id").val(id);
    $("#sure-delete-flag").val(flag);
    $("#sureDeleteTip").fadeIn(200);
}

/**
 * 选择删除方法
 */
function chooseDeleteFMethod() {
    flag = $("#sure-delete-flag").val();
    if (flag == 0) {
        deleteArea();
    } else {
        deleteAddress();
    }
}

/**
 * 删除区域
 */
function deleteArea() {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/deleteArea/" + $("#sure-delete-id").val(),
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#sureDeleteTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
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
 * 删除房址
 */
function deleteAddress() {
    $.ajax({
        type: "get",
        url: rootUrl + "/admin/deleteAddress/" + $("#sure-delete-id").val(),
        dataType: "json",
        success: function (ret) {
            if (ret == 'success') {
                $("#sureDeleteTip").fadeOut(200);
                showSuccessTip();
                getAllAreaAndAddress();
            } else {
                showErrorTip();
            }
        },
        error: function () {
            alert('errot');
        }
    });
}