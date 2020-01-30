/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19/3/2010 22:58
 */

$(document).ready(function() {
    // Đổi mã bảo mật
    $('#changevimg').on('click', function(e) {
        e.preventDefault();
        $('#vimg').attr('src', nv_base_siteurl + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
        $('#nv_seccode').val('');
    });

    // Đổi kiểu xác thực hai bước
    $('.login2step-change').on('click', function(e) {
        e.preventDefault();
        $('.nv-login2step-opt').find('[type="text"]').val('');
        $('.nv-login2step-opt').toggleClass('d-none');
    });

    // Kiểm tra các input nhập khi submit form
    $('#admin-login-form').submit(function(e) {
        var uname = $('#nv_login'),
            upass = $('#nv_password'),
            otp = $('#nv_totppin'),
            backupcode = $('#nv_backupcodepin'),
            seccode = $('#seccode'),
            validForm = true;

        if (uname.val() == '') {
            uname.focus();
            validForm = false;
        } else if (upass.val() == '') {
            upass.focus();
            validForm = false;
        } else if (otp.is(':visible') && otp.val() == '') {
            otp.focus();
            validForm = false;
        } else if (backupcode.is(':visible') && backupcode.val() == '') {
            backupcode.focus();
            validForm = false;
        } else if (seccode.length && seccode.val() == '') {
            seccode.focus();
            validForm = false;
        }
        if (!validForm) {
            e.preventDefault();
        }
    });

    // Đưa ngay con trỏ vào ô nhập username khi load trang
    if ($('#nv_login').length) {
        $('#nv_login').focus();
    }
});
