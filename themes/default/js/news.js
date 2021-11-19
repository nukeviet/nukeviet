/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(document).ready(function() {
    // Xem file đính kèm
    $('[data-toggle="collapsefile"]').each(function() {
        $('#' + $(this).attr('id')).on('show.bs.collapse', function() {
            if ('false' == $(this).attr('data-loaded')) {
                $(this).attr('data-loaded', 'true')
                $(this).find('iframe').attr('src', $(this).data('src'))
            }
        })
    })

    // Xem ảnh đính kèm
    $('[data-toggle="newsattachimage"]').click(function(e) {
        e.preventDefault();
        modalShow('', '<div class="text-center"><img src="' + $(this).data('src') + '" style="max-width: 100%; height: auto;"/></div>');
    });
});

function sendrating(id, point, newscheckss) {
    if (point == 1 || point == 2 || point == 3 || point == 4 || point == 5) {
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + newscheckss + '&point=' + point, function(res) {
            res = res.split('|');
            $('#stringrating').html(res[0]);
            if (typeof res[1] != 'undefined' && res[1] != '0') {
                $('#numberrating').html(res[1]);
            }
            if (typeof res[2] != 'undefined' && res[2] != '0') {
                $('#click_rating').html(res[2]);
            }
        });
    }
}

function nv_del_content(id, checkss, base_adminurl, detail) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(base_adminurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                if (detail) {
                    window.location.href = r_split[2];
                } else {
                    window.location.href = strHref;
                }
            } else if (r_split[0] == 'ERR') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function get_alias(op) {
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + op + '&nocache=' + new Date().getTime(), 'get_alias=' + encodeURIComponent(title), function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
    return false;
}

function fix_news_image() {
    var news = $('#news-bodyhtml'),
        newsW, w, h;
    if (news.length) {
        var newsW = news.innerWidth();
        $.each($('img', news), function() {
            if (typeof $(this).data('width') == "undefined") {
                w = $(this).innerWidth();
                h = $(this).innerHeight();
                $(this).data('width', w);
                $(this).data('height', h);
            } else {
                w = $(this).data('width');
                h = $(this).data('height');
            }

            if (w > newsW) {
                $(this).prop('width', newsW);
                $(this).prop('height', h * newsW / w);
            }
        });
    }
}

function newsSendMailModal(fm, url, sess) {
    var loaded = $(fm).attr('data-loaded');
    if ('false' == loaded) {
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'checkss=' + sess,
            success: function(e) {
                $('.modal-body', $(fm)).html(e);
                if ($('[data-toggle=recaptcha]', $(fm)).length) {
                    reCaptcha2Recreate($(fm));
                    "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
                } else if ($("[data-recaptcha3]", $(fm)).length && "undefined" === typeof grecaptcha) {
                    reCaptcha3ApiLoad()
                }
                $(fm).attr('data-loaded', 'true');
                $(fm).modal('show')
            }
        })
    } else {
        $(fm).modal('show')
    }
}

function newsSendMail(event, form) {
    event.preventDefault();
    var a = $("[name=friend_email]", form).val();
    a = trim(strip_tags(a));
    $("[name=friend_email]", form).val(a);
    if (!nv_mailfilter.test(a)) return alert($("[name=friend_email]", form).data("error")), $("[name=friend_email]", form).focus(), !1;
    a = $("[name=your_name]", form).val();
    a = trim(strip_tags(a));
    $("[name=your_name]", form).val(a);
    if ("" == a || !nv_uname_filter.test(a)) return alert($("[name=your_name]", form).data("error")), $("[name=your_name]", form).focus(), !1;
    if ($("[name=nv_seccode]", form).length && (a = $("[name=nv_seccode]", form).val(), a.length != parseInt($("[name=nv_seccode]", form).attr("maxlength")) || !/^[a-z0-9]+$/i.test(a))) return alert($("[name=nv_seccode]", form).data("error")), $("[name=nv_seccode]", form).focus(), !1;
    $("[name=your_message]", form).length && $("[name=your_message]", form).val(trim(strip_tags($("[name=your_message]", form).val())));
    a = $(form).serialize();
    $("input,button,textarea", form).prop("disabled", !0);
    $.ajax({
        type: $(form).prop("method"),
        cache: !1,
        url: $(form).prop("action"),
        data: a,
        dataType: "json",
        success: function(b) {
            $("input,button,textarea", form).prop("disabled", !1);
            var c = $("[onclick*='change_captcha']", form);
            c && c.click();
            ($("[data-toggle=recaptcha]", form).length || $("[data-recaptcha3]", $(form).parent()).length) && change_captcha();
            "error" == b.status ? (alert(b.mess), b.input && $("[name=" + b.input + "]", form).focus()) : (alert(b.mess), $("[name=friend_email]", form).val(''), $("[name=your_message]", form).length && $("[name=your_message]", form).val(''), $("[data-dismiss=modal]", form).click())
        }
    })
}

$(window).on('load', function() {
    fix_news_image();
});

$(window).on("resize", function() {
    fix_news_image();
});
