/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */

function validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
    $(a).tooltip({
       container: "body",
        placement: "bottom",
        title: function() {
            return "" != $(a).attr("data-current-mess") ? $(a).attr("data-current-mess") : nv_required
        }
    });
    $(a).focus().tooltip("show");
    "DIV" == $(a).prop("tagName") && $("input", a)[0].focus()
}

function validCheck(a) {
    if ($(a).is(':visible')) {
        var c = $(a).attr("data-pattern"),
            d = $(a).val(),
            b = $(a).prop("tagName"),
            e = $(a).prop("type");
        if ("INPUT" == b && "email" == e) {
            if (!nv_mailfilter.test(d)) return !1
        } else if ("SELECT" == b) {
            if (!$("option:selected", a).length) return !1
        } else if ("DIV" == b && $(a).is(".radio-box")) {
            if (!$("[type=radio]:checked", a).length) return !1
        } else if ("DIV" == b && $(a).is(".check-box")) {
            if (!$("[type=checkbox]:checked", a).length) return !1
        } else if ("INPUT" == b || "TEXTAREA" == b) if ("undefined" == typeof c || "" == c) {
            if ("" == d) return !1
        } else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(d)) return !1;
    }
    return !0
}

function validErrorHidden(a, b) {
    if (!b) b = 2;
    b = parseInt(b);
    var c = $(a),
        d = $(a);
    for (var i = 0; i < b; i++) {
        c = c.parent();
        if (i >= 2) d = d.parent()
    }
    d.tooltip("destroy");
    c.removeClass("has-error")
}

function formErrorHidden(a) {
    $(".has-error", a).removeClass("has-error");
    $("[data-mess]", a).tooltip("destroy")
}

function validReset(a) {
    var d = $(".nv-info", a).attr("data-default");
    if (!d) d = $(".nv-info-default", a).html();
    $(".nv-info", a).removeClass("error success").html(d);
    formErrorHidden(a);
    $("input,button,select,textarea", a).prop("disabled", !1);
    $(a)[0].reset()
}

function confirmpass_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            var b = $("[onclick*='change_captcha']", a);
            b && b.click();
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1),
                $(".tooltip-current", a).removeClass("tooltip-current"),
                "" != d.input ? $(a).find("[name=\"" + d.input + "\"]").each(function() {
                    $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                    validErrorShow(this)
                }) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
                    $("[type=submit]", a).prop("disabled", !1)
                }, 1E3)
            } else {
                window.location.href = window.location.href;
            }
        }
    }));
    return !1
}

function opt_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            var b = $("[onclick*='change_captcha']", a);
            b && b.click();
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1),
                $(".tooltip-current", a).removeClass("tooltip-current"),
                "" != d.input ? $(a).find("[name=\"" + d.input + "\"]").each(function() {
                    $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                    validErrorShow(this)
                }) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
                    $("[type=submit]", a).prop("disabled", !1)
                }, 1E3)
            } else {
                window.location.href = (typeof d.redirect != 'undefined' && d.redirect != '') ? d.redirect : window.location.href;
            }
        }
    }));
    return !1
}

$(document).ready(function() {
    // View secretkey
    $('[data-toggle="manualsecretkey"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).attr('href'));
    });
    // View backupcode
    $('[data-toggle="viewcode"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).attr('href'));
    });
    // Tắt xác thực 2 bước
    $('[data-toggle="turnoff2step"]').click(function() {
        $(this).prop('disabled', true);
        var tokend = $(this).data('tokend');
        $.post(
            nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            'turnoff2step=1&tokend=' + tokend,
            function(res) {
                if (res == 'OK') {
                    window.location.reload(true);
                } else {
                    alert(res);
                }
            }
        );
    });
    // Đổi mã
    $('[data-toggle="changecode2step"]').click(function() {
        $(this).prop('disabled', true);
        var tokend = $(this).data('tokend');
        $.post(
            nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            'changecode2step=1&tokend=' + tokend,
            function(res) {
                if (res == 'OK') {
                    window.location.reload(true);
                } else {
                    alert(res);
                }
            }
        );
    });
});
