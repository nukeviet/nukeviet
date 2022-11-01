/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function addpass() {
    $("a[href*=edit_password]").click();
    return !1
}

function safekeySend(a) {
    $(".safekeySend", a).prop("disabled", !0);
    $.ajax({
        type: $(a).prop("method"),
        cache: !1,
        url: $(a).prop("action"),
        data: $(a).serialize() + '&resend=1',
        dataType: "json",
        success: function(e) {
            "error" == e.status ? ($(".safekeySend", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $("[name=\"" + e.input + "\"]", a).addClass("tooltip-current").attr("data-current-mess", $("[name=\"" + e.input + "\"]", a).attr("data-mess")), validErrorShow($("[name=\"" + e.input + "\"]", a))) : ($(".nv-info", a).html(e.mess).removeClass("error").addClass("success").show(), setTimeout(function() {
                var d = $(".nv-info", a).attr("data-default");
                if (!d) d = $(".nv-info-default", a).html();
                $(".nv-info", a).removeClass("error success").html(d);
                $(".safekeySend", a).prop("disabled", !1);
            }, 6E3))
        }
    });
    return !1
}

function changeAvatar(url) {
    if (nv_safemode) return !1;
    nv_open_browse(url, "NVImg", 650, 430, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
    return !1;
}

function deleteAvatar(a, b, c) {
    if (nv_safemode) return !1;
    $(c).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !1,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=avatar/del',
        data: 'checkss=' + b + '&del=1',
        dataType: 'json',
        success: function(e) {
            $(a).attr("src", $(a).attr("data-default"));
        }
    });
    return !1
}

function datepickerShow(a) {
    if ("object" == typeof $.datepicker) {
        $(a).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: !0,
            changeYear: !0,
            showOtherMonths: !0,
            showOn: "focus",
            yearRange: "-90:+0"
        });
        $(a).css("z-index", "9998").datepicker('show');
    }
}

function button_datepickerShow(a) {
    var b = $(a).parent();
    datepickerShow($(".datepicker", b))
}

function verkeySend(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0;
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    d || ($("[name=vsend]", a).val("1"), $("[type=submit]", a).click());
    return !1
}

function addQuestion(a) {
    var b = $(a).parents('form');
    $("[name=question]", b).val($(a).text());
    validErrorHidden($("[name=question]", b));
    return !1
}

function usageTermsShow(t) {
    $.ajax({
        type: 'POST',
        cache: !0,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register',
        data: 'get_usage_terms=1',
        dataType: 'html',
        success: function(e) {
            if ($('#sitemodal').length) {
                if (!$('#sitemodalTerm').length) {
                    $('body').append('<div id="sitemodalTerm" class="modal fade" role="dialog">' + $('#sitemodal').html() + '</div>')
                }
                "" != t && 'undefined' != typeof t && $("#sitemodalTerm .modal-content").prepend('<div class="modal-header"><h2 class="modal-title">' + t + '</h2></div>');
                $("#sitemodalTerm").find(".modal-title").html(t);
                $("#sitemodalTerm").find(".modal-body").html(e);
                $('#sitemodalTerm').on('hidden.bs.modal', function() {
                    $("#sitemodalTerm .modal-content").find(".modal-header").remove()
                });
                $("#sitemodalTerm").modal({
                    backdrop: "static"
                })
            } else {
                alert(strip_tags(e))
            }
        }
    });
    return !1
}

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

function uname_check(val) {
    return (val == '' || nv_uname_filter.test(val)) ? true : false;
}

function required_uname_check(val) {
    return (val != '' && nv_uname_filter.test(val)) ? true : false;
}

function login_check(val, type, max, min) {
    if ('' == val || val.length > max || val.length < min) return false;
    if (type == '1' && !/^[0-9]+$/.test(val)) return false;
    if (type == '2' && !/^[a-z0-9]+$/i.test(val)) return false;
    if (type == '3' && !/^[a-z0-9]+[a-z0-9\-\_\s]+[a-z0-9]+$/i.test(val)) return false;
    if (type == '4' && !nv_unicode_login_pattern.test(val)) return false;
    return true;
}

function validCheck(a) {
    if ($(a).is(':visible')) {
        var c = $(a).attr("data-pattern"),
            d = $(a).val(),
            b = $(a).prop("tagName"),
            e = $(a).prop("type"),
            f = $(a).attr("data-callback");
        if ("INPUT" == b && "email" == e) {
            if (!nv_mailfilter.test(d)) return !1
        } else if ("undefined" != typeof f && "uname_check" == f) {
            if (!uname_check(d)) return $(a).attr("data-mess", $(a).attr("data-error")), !1
        } else if ("undefined" != typeof f && "required_uname_check" == f) {
            if (!required_uname_check(d)) return $(a).attr("data-mess", $(a).attr("data-error")), !1
        } else if ("undefined" != typeof f && "login_check" == f) {
            if (!login_check(d, $(a).data("type"), $(a).attr("maxlength"), $(a).data("minlength"))) return !1
        } else if ("SELECT" == b) {
            if (!$("option:selected", a).length) return !1
        } else if ("DIV" == b && $(a).is(".radio-box")) {
            if (!$("[type=radio]:checked", a).length) return !1
        } else if ("DIV" == b && $(a).is(".check-box")) {
            if (!$("[type=checkbox]:checked", a).length) return !1
        } else if ("INPUT" == b || "TEXTAREA" == b)
            if ("undefined" == typeof c || "" == c) {
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
    $(a)[0].reset();
    formChangeCaptcha(a);
}

function login_validForm(a) {
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
            formChangeCaptcha(a);
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1);
                $(".tooltip-current", a).removeClass("tooltip-current");
                ("" != d.input && $("[name=\"" + d.input + "\"]:visible", a).length) ? $(a).find("[name=\"" + d.input + "\"]:visible").each(function() {
                    $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                    validErrorShow(this)
                }): $(".nv-info", a).html(d.mess).addClass("error").show();
                setTimeout(function() {
                    $("[type=submit]", a).prop("disabled", !1)
                }, 1E3)
            } else if (d.status == "ok") {
                $(".nv-info", a).html(d.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(),
                    $(".form-detail", a).hide(), $("#other_form").hide(), setTimeout(function() {
                        if ("undefined" != typeof d.redirect && "" != d.redirect) {
                            window.location.href = d.redirect;
                        } else {
                            $('#sitemodal').modal('hide');
                            window.location.href = window.location.href;
                        }
                    }, 3E3)
            } else if (d.status == "2steprequire") {
                $(".form-detail", a).hide(), $("#other_form").hide();
                $(".nv-info", a).html("<a href=\"" + d.input + "\">" + d.mess + "</a>").removeClass("error").removeClass("success").addClass("info").show();
            } else {
                $("input,button", a).prop("disabled", !1);
                $('.loginstep1, .loginstep2, .loginCaptcha', a).toggleClass('hidden');
            }
        }
    }));
    return !1
}

function reg_validForm(a) {
    // Xử lý các trình soạn thảo
    if ("undefined" != typeof CKEDITOR)
        for (var c in CKEDITOR.instances) $("#" + c).val(CKEDITOR.instances[c].getData());
    $(".has-error", a).removeClass("has-error");
    var e = 0;
    c = [];
    $(a).find("input.required,input[data-callback],textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return e++,
            $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    e || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: c.type,
        cache: !1,
        url: c.url,
        data: c.data,
        dataType: "json",
        success: function(b) {
            formChangeCaptcha(a);
            if ("error" == b.status) {
                $("input,button,select,textarea", a).prop("disabled", !1);
                $(".tooltip-current", a).removeClass("tooltip-current");
                ("" != b.input && $("[name=\"" + b.input + "\"]:visible", a).length) ? $(a).find('[name="' + b.input + '"]:visible').each(function() {
                    $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                    validErrorShow(this)
                }): ($(".nv-info", a).html(b.mess).addClass("error").show(), $("html, body").animate({
                    scrollTop: $(".nv-info", a).offset().top
                }, 800))
            } else {
                $(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                "ok" == b.input ? setTimeout(function() {
                    $(".nv-info", a).fadeOut();
                    $("input,button,select,textarea", a).prop("disabled", !1);
                    $("[onclick*=validReset]", a).click()
                }, 6E3) : ($("html, body").animate({
                    scrollTop: $(".nv-info", a).offset().top
                }, 800), $(".form-detail", a).hide(), setTimeout(function() {
                    window.location.href = "" != b.input ? b.input : window.location.href
                }, 6E3))
            }
        },
        error: function(b, d, f) {
            window.console.log ? console.log(b.status + ": " + f) : alert(b.status + ": " + f)
        }
    }));

    return !1
}

function lostpass_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0,
        c = [];
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    if (!d) {
        if (($('[data-toggle=recaptcha]', $(a)).length || $("[data-recaptcha2], [data-recaptcha3]", $(a).parent()).length) && $("[name=step]", a).val() == 'step1') {
            $("[name=gcaptcha_session]", a).val($("[name=g-recaptcha-response]", a).val());
        }
        c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0);
        $.ajax({
            type: c.type,
            cache: !1,
            url: c.url,
            data: c.data,
            dataType: "json",
            success: function(b) {
                if (b.status != "ok") {
                    $("[name=step]", a).val(b.step);
                    if ("undefined" != typeof b.info && "" != b.info) $(".nv-info", a).removeClass('error success').html(b.info);
                    $("input,button", a).prop("disabled", !1);
                    $(".required", a).removeClass("required");
                    $(".tooltip-current", a).removeClass("tooltip-current");
                    $("[class*=step]", a).hide();
                    $("." + b.step + " input", a).addClass("required");
                    $("." + b.step, a).show();
                    if (b.input == '') {
                        alert(b.mess);
                        if ("undefined" != typeof b.redirect && "" != b.redirect) {
                            window.location.href = b.redirect
                        }
                    } else {
                        if ("error" == b.status) {
                            if ($("[name=" + b.input + "]:visible", a).length) {
                                $(a).find("[name=" + b.input + "]:visible").each(function() {
                                    $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                                    validErrorShow(this);
                                })
                            } else {
                                alert(b.mess);
                            }
                        }
                    }
                    if (b.step == 'step1') {
                        formChangeCaptcha(a);
                        $("[name=gcaptcha_session]", a).length && $("[name=gcaptcha_session]", a).val('');
                    } else if ($('[data-toggle=recaptcha]', a).length) {
                        $('[data-toggle=recaptcha]', a).remove()
                    } else if ($('[data-captcha]', $(a).parent()).length) {
                        $(a).data('captcha', null);
                    } else if ($('[data-recaptcha2]', $(a).parent()).length) {
                        $(a).data('recaptcha2', null);
                    } else if ($('[data-recaptcha3]', $(a).parent()).length) {
                        $(a).data('recaptcha3', null);
                    }
                } else {
                    $(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                    setTimeout(function() {
                        window.location.href = b.input;
                    }, 6E3);
                }
            }
        });
    }
    return !1;
}

function changemail_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0,
        c = [];
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess",
            $(this).attr("data-mess")), validErrorShow(this), !1
    });
    d || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: c.type,
        cache: !1,
        url: c.url,
        data: c.data,
        dataType: "json",
        success: function(b) {
            $("[name=vsend]", a).val("0");
            "error" == b.status ? ($("input,button,select,textarea", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $(a).find("[name=" + b.input + "]").each(function() {
                $(this).addClass("tooltip-current").attr("data-current-mess",
                    b.mess);
                validErrorShow(this)
            })) : ($(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(), $(".form-detail", a).hide(), setTimeout(function() {
                window.location.href = "" != b.input ? b.input : window.location.href
            }, 6E3))
        }
    }));
    return !1
}

function bt_logout(a) {
    $(a).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !1,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=logout&nocache=' + new Date().getTime(),
        data: 'nv_ajax_login=1',
        dataType: 'html',
        success: function(e) {
            $('.userBlock', $(a).parent().parent().parent().parent()).hide();
            $('.nv-info', $(a).parent().parent().parent().parent()).addClass("text-center success").html(e).show();
            setTimeout(function() {
                window.location.href = window.location.href
            }, 2E3)
        }
    });
    return !1
}

function login2step_change(ele) {
    var ele = $(ele),
        form = ele,
        i = 0;
    while (!form.is('form')) {
        if (i++ > 10) {
            break;
        }
        form = form.parent();
    }
    if (form.is('form')) {
        $('.loginstep2 input,.loginstep3 input', form).val('');
        $('.loginstep2,.loginstep3', form).toggleClass('hidden');
    }
    return false;
}

function changeTabTitle() {
    var n = $("#funcList li.active a").text();
    n += ' <span class="caret"></span>';
    $("#myTabEl").html(n)
}

function edit_group_submit(obj, old) {
    var nw = [];
    if ($('[name^=in_groups]:checked').length) {
        $('[name^=in_groups]:checked').each(function(){
            nw.push($(this).val());
        })
    }

    nw = nw.join();
    if (nw == old) {
        return !1
    }

    reg_validForm(obj);
}

$(function() {
    // Delete user handler
    $('[data-toggle="admindeluser"]').click(function(e) {
        e.preventDefault();
        var data = $(this).data();
        if (confirm(nv_is_del_confirm[0])) {
            $.post(data.link, 'userid=' + data.userid, function(res) {
                if (res == 'OK') {
                    window.location.href = data.back;
                } else {
                    var r_split = res.split("_");
                    if (r_split[0] == 'ERROR') {
                        alert(r_split[1]);
                    } else {
                        alert(nv_is_del_confirm[2]);
                    }
                }
            });
        }
    });

    $('body').on('submit', '[data-toggle=userLogin]', function(e) {
        e.preventDefault();
        login_validForm(this)
    });

    $('body').on('submit', '[data-toggle=reg_validForm]', function(e) {
        e.preventDefault();
        reg_validForm(this)
    });

    $('body').on('submit', '[data-toggle=lostPass]', function() {
        return lostpass_validForm(this)
    });

    $('body').on('submit', '[data-toggle=changemail_validForm]', function() {
        return changemail_validForm(this)
    });

    $('body').on('submit', '[data-toggle=edit_group_submit][data-old]', function(e) {
        e.preventDefault();
        return edit_group_submit(this, $(this).data('old'))
    });

    $('body').on('click', '[data-toggle=validReset]', function(e) {
        e.preventDefault();
        validReset($(this).parents('form'))
    })

    $('body').on('keypress', '[data-toggle=validErrorHidden][data-event=keypress]', function() {
        $('[data-parents]', this) ? validErrorHidden(this, $(this).data('parents')) : validErrorHidden(this)
    });

    $('body').on('change', '[data-toggle=validErrorHidden][data-event=change]', function() {
        $('[data-parents]', this) ? validErrorHidden(this, $(this).data('parents')) : validErrorHidden(this)
    });

    $('body').on('click', '[data-toggle=validErrorHidden][data-event=click]', function() {
        $('[data-parents]', this) ? validErrorHidden(this, $(this).data('parents')) : validErrorHidden(this)
    });

    $('body').on('focus', '[data-focus=datepickerShow]', function() {
        datepickerShow(this)
    });

    $('body').on('click', '[data-toggle=button_datepickerShow]', function() {
        button_datepickerShow(this)
    });

    $('body').on('click', '[data-toggle=addQuestion]', function(e) {
        e.preventDefault();
        addQuestion(this)
    });

    $('body').on('click', '[data-toggle=usageTermsShow]', function(e) {
        e.preventDefault();
        usageTermsShow($(this).data('title'))
    });

    $('body').on('click', '[data-toggle=login2step_change]', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        login2step_change(this)
    });

    $('body').on('click', '[data-toggle=changeAvatar][data-url]', function(e) {
        e.preventDefault();
        changeAvatar($(this).data('url'))
    });

    $('body').on('click', '[data-toggle=deleteAvatar][data-obj][data-ss]', function(e) {
        e.preventDefault();
        deleteAvatar($(this).data('obj'), $(this).data('ss'), this)
    });

    $('body').on('click', '[data-toggle=bt_logout]', function(e) {
        e.preventDefault();
        bt_logout(this)
    });

    $('body').on('click', '[data-toggle=addpass]', function(e) {
        e.preventDefault();
        addpass()
    });

    $('body').on('click', '[data-toggle=verkeySend]', function(e) {
        e.preventDefault();
        verkeySend($(this).parents('form'))
    });

    $('body').on('click', '[data-toggle=safekeySend]', function(e) {
        e.preventDefault();
        safekeySend($(this).parents('form'))
    });

    $('body').on('click', '[data-toggle=safe_deactivate_show][data-hide-obj][data-show-obj]', function(e) {
        e.preventDefault();
        $($(this).data('hide-obj')).hide(0);
        $($(this).data('show-obj')).fadeIn()
    });

});
