/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var seccodecheck = /^([a-zA-Z0-9])+$/;

if ("undefined" == typeof jsi) {
    var jsi = [];
}
jsi[0] || (jsi[0] = "vi");
jsi[1] || (jsi[1] = "./");
jsi[2] || (jsi[2] = 0);
jsi[3] || (jsi[3] = 6);
var strHref = window.location.href;
if (-1 < strHref.indexOf("?")) {
    var strHref_split = strHref.split("?"),
        script_name = strHref_split[0],
        query_string = strHref_split[1];
} else {
    script_name = strHref, query_string = "";
}

function nv_checkadminlogin_seccode(a) {
    return a.value.length == jsi[3] && seccodecheck.test(a.value) ? !0 : !1
}

function nv_randomPassword(a) {
    for (var b = "", c = 0; c < a; c++) {
        b += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(Math.floor(62 * Math.random()));
    }
    return b;
}

function nv_checkadminlogin_submit() {
    if (1 == jsi[2]) {
        var a = document.getElementById("seccode");
        if (!nv_checkadminlogin_seccode(a)) {
            return alert(login_error_security), a.focus(), !1
        }
    }
    var a = document.getElementById("login"),
        b = document.getElementById("password");
    return "" == a.value ? (a.focus(), !1) : "" == b.value ? (b.focus(), !1) : !0
}

function nv_change_captcha() {
    var a = document.getElementById("vimg");
    nocache = nv_randomPassword(10);
    a.src = jsi[1] + "index.php?scaptcha=captcha&nocache=" + nocache;
    document.getElementById("seccode").value = "";
    return !1
};

function login2step_change(ele) {
    var ele = $(ele),
        form = ele,
        i = 0;
    while (!form.is('form')) {
        if (i++ > 100) {
            break;
        }
        form = form.parent();
    }
    if (form.is('form')) {
        $('.loginStep2 input[type="text"]', form).val('');
        $('.loginStep2 > div.stepipt', form).toggleClass('hidden');
    }
    return false;
}

$(document).ready(function() {
    // Submit form
    $('#admin-login-form').submit(function(e) {
        var validForm = true;

        if ($('.loginStep1', $(this)).is(':visible')) {
            // Kiểm tra form đăng nhập bước 1
            var uname = $('#nv_login');
            var upass = $('#nv_password');
            var seccode = $('#seccode');

            if (uname.val() == '') {
                uname.focus();
                validForm = false;
            } else if (upass.val() == '') {
                upass.focus();
                validForm = false;
            } else if (seccode.length && seccode.val() == '') {
                seccode.focus();
                validForm = false;
            }
        } else {
            // Kiểm tra form xác thực 2 bước
            var otp = $('#nv_totppin');
            var backupcode = $('#nv_backupcodepin');

            if (otp.is(':visible') && otp.val() == '') {
                otp.focus();
                validForm = false;
            } else if (backupcode.is(':visible') && backupcode.val() == '') {
                backupcode.focus();
                validForm = false;
            }
        }
        if (!validForm) {
            e.preventDefault();
        }
    });

    // Loaded
    if ($('#nv_login').length) {
        $('#nv_login').focus();
    }

    // Mở popup (chưa sử dụng nhưng để đây phòng về sau)
    $('[data-toggle="popup-oauth"]').on('click', function(e) {
        nv_open_browse($(this)[0], "NVADMINOAUTH", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,location=no,status=no");
        e.preventDefault();
    });
});
