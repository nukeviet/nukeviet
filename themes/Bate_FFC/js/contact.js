/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_validReset(a) {
    $(".has-error", a).removeClass("has-error");
    $("[data-mess]", a).tooltip("destroy");
    $(a)[0].reset();
    var b = $("[onclick*='change_captcha']", a);
    b.length ? b.click() : ($("[data-toggle=recaptcha]", $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) && change_captcha()
}

function nv_validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
    $(a).tooltip({
        title: function() {
            return $(a).attr("data-current-mess")
        }
    });
    $(a).focus().tooltip("show")
}

function nv_validErrorHidden(a) {
    $(a).parent().parent().removeClass("has-error")
}

function nv_uname_check(val) {
    return (val.length >= 3 && nv_uname_filter.test(val)) ? true : false;
}

function nv_validCheck(a) {
    var c = $(a).attr("data-pattern"),
        b = $(a).val(),
        f = $(a).attr("data-callback");
    if ("email" == $(a).prop("type") && !nv_mailfilter.test(b)) return !1;
    else if ("undefined" != typeof f && "nv_uname_check" == f) {
        if (!nv_uname_check(b)) return !1
    } else if ("undefined" == typeof c || "" == c) {
        if ("" == b) return !1
    } else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(b)) return !1;
    return !0
}

function nv_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0;
    $(a).find(".required,input[data-callback]").each(function() {
        $(this).val(trim(strip_tags($(this).val())));
        if (!nv_validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), nv_validErrorShow(this), !1
    });
    c || ($(a).find("[type='submit']").prop("disabled", !0), $.ajax({
        type: $(a).prop("method"),
        cache: !1,
        url: $(a).prop("action"),
        data: $(a).serialize(),
        dataType: "json",
        success: function(b) {
            change_captcha('.fcode');
            "error" == b.status && "" != b.input ? ($(".tooltip-current", a).removeClass("tooltip-current"), $(a).find("[name=" + b.input + "]").each(function() {
                $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                nv_validErrorShow(this)
            }), setTimeout(function() {
                $(a).find("[type='submit']").prop("disabled", !1)
            }, 1E3)) : ($("input,select,button,textarea", a).prop("disabled", !0), "error" == b.status ? $(a).next().html(b.mess).removeClass("alert-info").addClass("alert-danger").show() : $(a).next().html(b.mess).removeClass("alert-danger").addClass("alert-info").show(), $("[data-mess]").tooltip("destroy"), setTimeout(function() {
                $(a).next().hide();
                $("input,select,button,textarea", a).not(".disabled").prop("disabled", !1);
                nv_validReset(a)
            }, 5E3))
        }
    }));
    return !1
};
$(function() {
    var a = $("#contactButton");
    if (a) {
        var b = $(".ctb", a),
            c = $(".panel", a),
            d = function() {
                c.hide();
                b.removeClass("fs").show()
            },
            e = $("[data-cs]", a);
        $(document).on("keydown", function(a) {
            27 === a.keyCode && b.is(".fs") && d()
        });
        $(document).on("click", function() {
            b.is(".fs") && d()
        });
        c.on("click", function(a) {
            a.stopPropagation()
        });
        $(".close", a).on("click", function() {
            d()
        });
        b.on("click", function() {
            return b.is(".ld") ? (b.addClass("fs").hide(), c.fadeIn(), !1) : ($.ajax({
                type: "POST",
                cache: !1,
                url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + b.attr("data-module"),
                data: "loadForm=1&checkss=" + e.data("cs"),
                dataType: "html",
                success: function(a) {
                    e.html(a);
                    b.addClass("ld fs").hide();
                    c.fadeIn();
                    change_captcha()
                }
            }), !1)
        })
    }
});
