/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_validReset(a)
{
    $(".has-error",a).removeClass("has-error");
    $("[data-mess]",a).tooltip("destroy");
    $(a)[0].reset();
}

function nv_validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]",$(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
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

function nv_validCheck(a) {
    var c = $(a).attr("data-pattern"),
        b = $(a).val();
    if ("email" == $(a).prop("type") && !nv_mailfilter.test(b)) return !1;
    if ("undefined" == typeof c || "" == c) {
        if ("" == b) return !1
    } else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(b)) return !1;
    return !0
}

function nv_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0;
    $(a).find(".required").each(function() {
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
            }, 1E3), (nv_is_recaptcha && change_captcha())) : ($("input,select,button,textarea", a).prop("disabled", !0), "error" == b.status ? $(a).next().html(b.mess).removeClass("alert-info").addClass("alert-danger").show() : $(a).next().html(b.mess).removeClass("alert-danger").addClass("alert-info").show(), $("[data-mess]").tooltip("destroy"), setTimeout(function() {
                $(a).next().hide();
                $("input,select,button,textarea", a).not(".disabled").prop("disabled", !1);
                nv_validReset(a);
                (nv_is_recaptcha && change_captcha());
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
                url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + b.attr( "data-module" ),
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