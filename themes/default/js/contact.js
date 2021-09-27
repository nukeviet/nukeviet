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
    formChangeCaptcha(a);
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
            formChangeCaptcha(a);
            if ("error" == b.status) {
                setTimeout(function() {
                    $(a).find("[type='submit']").prop("disabled", !1)
                }, 1E3);
                if ("" != b.input && $("[name=" + b.input + "]:visible", a).length) {
                    $(".tooltip-current", a).removeClass("tooltip-current");
                    $(a).find("[name=" + b.input + "]:visible").each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                        nv_validErrorShow(this)
                    })
                } else {
                    $(a).next().html(b.mess).removeClass("alert-info").addClass("alert-danger").show();
                    $("[data-mess]").tooltip("destroy");
                    setTimeout(function() {
                        $(a).next().hide()
                    }, 5E3);
                }
            } else {
                $(a).next().html(b.mess).removeClass("alert-danger").addClass("alert-info").show();
                $("[data-mess]").tooltip("destroy");
                setTimeout(function() {
                    $(a).next().hide();
                    $(a).find("[type='submit']").prop("disabled", !1)
                    nv_validReset(a)
                }, 5E3)

            }
        }
    }));
    return !1
};
$(function() {
    if ($("#contactButton").length) {
        var a = $("#contactButton"),
            b = $(".ctb", a),
            c = $(".panel", a),
            e = $("[data-cs]", a);
        $(document).click(function(event) {
            if (b.is(".fs")) {
                if (!($(event.target).closest("#contactButton").length || $(event.target).closest(".modal").length)) {
                    c.hide();
                    b.removeClass("fs").show()
                }
            }
        });
        $(".close", a).on("click", function() {
            c.hide();
            b.removeClass("fs").show()
        });
        b.off('click').on("click", function(event) {
            event.preventDefault();
            if (b.is(".ld")) {
                b.addClass("fs").hide();
                c.fadeIn();
                return !1
            } else {
                $.ajax({
                    type: "POST",
                    cache: !1,
                    url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + b.attr("data-module"),
                    data: "loadForm=1&checkss=" + e.data("cs"),
                    dataType: "html",
                    success: function(a) {
                        e.html(a);
                        b.addClass("ld fs").hide();
                        c.fadeIn();
                        formChangeCaptcha($('form', e))
                    }
                })
            }
        })
    }

    // Form submit
    $('body').on('submit', '[data-toggle=feedback]', function(e) {
        e.preventDefault();
        nv_validForm(this)
    });

    // Form reset
    $('body').on('click', '[data-toggle=fb_validReset]', function(e) {
        e.preventDefault();
        nv_validReset($(this).parents('form'))
    });

    // validErrorHidden
    $('body').on('keypress', '[data-toggle=fb_validErrorHidden]', function() {
        $(this).parent().parent().removeClass("has-error")
    });
});
