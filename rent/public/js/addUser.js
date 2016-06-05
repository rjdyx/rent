// $(function () {
//     $(".form-horizontal").validate({
//         onsubmit: true,// 是否在提交是验证
//         onfocusout: false,// 是否在获取焦点时验证
//         onkeyup: false,// 是否在敲击键盘时验证
//
//         rules: {
//             name: "required",
//             password: {
//                 required: true,
//                 minlength: 6
//             },
//             password_confirmation: {
//                 required: true,
//                 minlength: 6,
//                 equalTo: "#password"
//             },
//             email: {
//                 required: true,
//                 email: true
//             }
//         },
//         messages: {
//             name: "请输入您的名字",
//             password: {
//                 required: "请输入密码",
//                 minlength: "密码长度不能小于 6 个字母"
//             },
//             confirm_password: {
//                 required: "请输入密码",
//                 minlength: "密码长度不能小于 6 个字母",
//                 equalTo: "两次密码输入不一致"
//             },
//             email: "请输入一个正确的邮箱",
//         }
//     });
// })