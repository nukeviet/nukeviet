/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if ("undefined" == typeof base_siteurl) {
    var base_siteurl = "./";
}

$(document).ready(function() {
    $('body').on('change', '[data-toggle=changeLang]', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).val(),
            cache: false
        }).done(function() {
            location.reload()
        })
    });

    $('body').on('click', '[data-toggle=preLogout]', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).data('href'),
            cache: false
        }).done(function() {
            location.reload()
        })
    });

    $('body').on('click', '[data-toggle=login2step_change]', function(e) {
        e.preventDefault();
        $('input[type="text"]', $(this).parents('form')).val('');
        $('div.stepipt', $(this).parents('form')).toggleClass('hidden')
    });

    $('body').on('click', '[data-toggle=nv_change_captcha]', function(e) {
        e.preventDefault();
        $('#vimg').attr('src', base_siteurl + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
        $('#seccode').val('')
    });

    $('[data-toggle=preForm] [type=submit]').on('click', function(e) {
        e.preventDefault();
        if (!$('#reCaptcha').length && $("[name=g-recaptcha-response]").length && typeof grecaptcha !== "undefined") {
            grecaptcha.ready(function() {
                grecaptcha.execute(sitekey, {
                    action: 'loginSubmit'
                }).then(function(token) {
                    $("[name=g-recaptcha-response]").val(token);
                    $('[data-toggle=preForm]').submit()
                })
            })
        } else {
            $('[data-toggle=preForm]').submit()
        }
    });

    // Submit form
    $('body').on('submit', '[data-toggle=preForm]', function(e) {
        e.preventDefault();
        // Kiểm tra form đăng nhập bước 1
        var uname = $('[name=nv_login]', this),
            upass = $('[name=nv_password]', this),
            seccode = $('[name=nv_seccode]', this);

        if (uname.val() == '') {
            $('.inner-message', form).text(uname.data('error-mess')).removeClass('normal success').addClass('error');
            uname.focus();
            return !1
        }
        if (upass.val() == '') {
            $('.inner-message', form).text(upass.data('error-mess')).removeClass('normal success').addClass('error');
            upass.focus();
            return !1
        }
        if (seccode.length && seccode.val() == '') {
            $('.inner-message', form).text(seccode.data('error-mess')).removeClass('normal success').addClass('error');
            seccode.focus();
            return !1
        }

        var form = $(this),
            data = form.serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $(this).attr('action'),
            data: data,
            dataType: 'json',
            success: function(e) {
                if (e.status == 'success') {
                    $('.form-detail', form).hide();
                    $('.inner-message', form).text(e.mess).removeClass('normal error').addClass('success');
                    $('#langinterface').remove();
                    var hr = e.redirect != '' ? e.redirect : window.location.href;
                    $('#adm-redirect').attr('href', hr).toggleClass('hidden');
                    setTimeout(function() {
                        window.location.href = hr
                    }, 3E3)
                } else if (e.status == '2step') {
                    location.reload()
                } else {
                    if (typeof reCaptcha2 !== "undefined" && typeof grecaptcha !== "undefined") {
                        grecaptcha.reset(reCaptcha2);
                        $('[type=submit]').prop('disabled', true)
                    } else if ($("[data-toggle=nv_change_captcha]", form).length) {
                        $("[data-toggle=nv_change_captcha]", form).trigger('click')
                    }
                    $('.inner-message', form).html(e.mess).removeClass('normal success').addClass('error');
                    if (e.input != '') {
                        $('[name=' + e.input + ']', form).focus()
                    }
                }
            }
        })
    });

    $('body').on('submit', '[data-toggle=step2Form]', function(e) {
        e.preventDefault();
        // Kiểm tra form xác thực 2 bước
        var otp = $('#nv_totppin'),
            backupcode = $('#nv_backupcodepin');

        if (otp.is(':visible') && otp.val() == '') {
            $('.inner-message', form).text(otp.data('error-mess')).removeClass('normal success').addClass('error');
            otp.focus();
            return !1
        }
        if (backupcode.is(':visible') && backupcode.val() == '') {
            $('.inner-message', form).text(backupcode.data('error-mess')).removeClass('normal success').addClass('error');
            backupcode.focus();
            return !1
        }

        var form = $(this),
            data = form.serialize();
        $('input,button', form).prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $(this).attr('action'),
            data: data,
            dataType: 'json',
            success: function(e) {
                if (e.status == 'success') {
                    $('.form-detail', form).hide();
                    $('.inner-message', form).text(e.mess).removeClass('normal error').addClass('success');
                    $('#langinterface').remove();
                    var hr = e.redirect != '' ? e.redirect : window.location.href;
                    $('#adm-redirect').attr('href', hr).toggleClass('hidden');
                    setTimeout(function() {
                        window.location.href = hr
                    }, 3E3)
                } else {
                    $('input,button', form).prop('disabled', false);
                    $('.inner-message', form).text(e.mess).removeClass('normal success').addClass('error');
                    if (e.input != '') {
                        $('[name=' + e.input + ']', form).focus()
                    }
                }
            }
        })
    });

    $('.wrapper').fadeIn(400, function() {
        // Loaded
        if ($('#nv_login').length) {
            $('#nv_login').focus();
        }
    });

    // Mở popup (chưa sử dụng nhưng để đây phòng về sau)
    $('[data-toggle="popup-oauth"]').on('click', function(e) {
        nv_open_browse($(this)[0], "NVADMINOAUTH", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,location=no,status=no");
        e.preventDefault();
    });
});
