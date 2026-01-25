/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Voting functions
// Gioi han phuong an bau chon
function votingAcceptNumber(obj) {
    var form = $(obj).parents('form');
    if ($('[name*=option]:checked', form).length >= parseInt(form.data('accept'))) {
        $('[name*=option]', form).not(':checked').prop('disabled', true)
    } else {
        $('[name*=option]', form).prop('disabled', false)
    }
}

//Voting functions
function votingSend(form) {
    var id = $(form).data('id'),
        checkss = $(form).data('checkss'),
        num = parseInt($(form).data('accept')),
        errmsg = $(form).data('errmsg'),
        vals = "0";
    $('[name*=option]:checked', form).each(function() {
        vals = (num == 1) ? $(this).val() : vals + ("," + $(this).val())
    });
    if ("0" === vals) {
        alert(errmsg);
    } else if ($("[data-recaptcha2],[data-recaptcha3]", $(form).parent()).length) {
        votingSendSubmit(id, checkss, vals, $('[name=g-recaptcha-response]', form).val());
    } else if ($("[data-turnstile]", $(form).parent()).length) {
        votingSendSubmit(id, checkss, vals, $('[name=cf-turnstile-response]', form).val());
    } else if ($("[data-captcha]", $(form).parent()).length) {
        votingSendSubmit(id, checkss, vals, $('[name=' + $(form).data('captcha') + ']', form).val());
    } else {
        votingSendSubmit(id, checkss, vals)
    }
}

function votingSendSubmit(id, checkss, vals, capt) {
    $.ajax({
        type: "POST",
        cache: !1,
        url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=voting&" + nv_fc_variable + "=main&vid=" + id + "&checkss=" + checkss + "&lid=" + vals + ('undefined' != typeof capt ? '&captcha=' + capt : ''),
        data: "nv_ajax_voting=1",
        dataType: "html",
        success: function(res) {
            if ("0" != vals && "undefined" != typeof capt && "" != capt) {
                change_captcha()
            }
            if (res.match(/^ERROR\|/g)) {
                alert(res.substring(6));
            } else {
                modalShow('', res)
            }
        }
    });
}

$(function() {
    // Voting form submit
    $('body').on('submit', '[data-toggle=votingSend]', function(e) {
        e.preventDefault();
        votingSend(this)
    });

    // Xem kết quả bình chọn
    $('body').on('click', '[data-toggle=votingResult]', function(e) {
        e.preventDefault();
        votingSendSubmit($(this).parents('form').data('id'), $(this).parents('form').data('checkss'), '0')
    });

    // Giới hạn số phương án bình chọn
    $('body').on('click', '[data-toggle=votingAcceptNumber]', function() {
        votingAcceptNumber(this)
    });
})
