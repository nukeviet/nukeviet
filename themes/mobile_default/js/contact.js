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
    $(".nv-info", a).removeClass("error success").html($(".nv-info", a).attr("data-mess"));
    $(a)[0].reset();
    formChangeCaptcha(a);
}

function nv_validErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $(a).parent().parent().parent().find(".nv-info").removeClass("success").addClass("error").html($(a).attr("data-current-mess"));
    $(a).focus()
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
    $(".nv-info", a).removeClass("error success").html($(".nv-info", a).attr("data-mess"));
    var c = 0;
    $(a).find(".required,input[data-callback]").each(function() {
        $(this).val(trim(strip_tags($(this).val())));
        if (!nv_validCheck(this)) return c++, $(this).attr("data-current-mess", $(this).attr("data-mess")), nv_validErrorShow(this), !1
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
                    $(a).find("[name=" + b.input + "]:visible").each(function() {
                        $(this).attr("data-current-mess", b.mess);
                        nv_validErrorShow(this)
                    })
                } else {
                    $(".nv-info", a).html(b.mess).removeClass("success").addClass("error");
                    setTimeout(function() {
                        $(".nv-info", a).removeClass("error success").html($(".nv-info", a).attr("data-mess"))
                    }, 5E3);
                }
            } else if ('success' == b.status) {
                $(".nv-info", a).html(b.mess).removeClass("error").addClass("success");
                setTimeout(function() {
                    $(a).find("[type='submit']").prop("disabled", !1);
                    nv_validReset(a);
                    if ($('#feedback-form').length) {
                        $('#feedback-form').modal('hide')
                    }
                }, 5E3)

            }
        }
    }));
    return !1
};

$(function() {
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
        var a = $(this).parent().parent().parent();
        $(".has-error", a).removeClass("has-error");
        $(".nv-info", a).removeClass("error success").html($(".nv-info", a).attr("data-mess"))
    });

    $('.show-feedback-form').on('click', function() {
        $('#feedback-form').modal('show')
    })
})