/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
            dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
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
            if (!$('#sitemodalTerm').length) {
                $('body').append(`<div id="sitemodalTerm" class="modal fade" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="${nukeviet.i18n.close}"><span aria-hidden="true">&times;</span></button>
                                <div class="modal-title h2"></div>
                            </div>
                            <div class="modal-body"></div>
                        </div>
                    </div>
                </div>`);
            }
            $("#sitemodalTerm").find(".modal-title").html(`<strong>${t}</strong>`);
            $("#sitemodalTerm").find(".modal-body").html(e);
            $("#sitemodalTerm").modal({
                backdrop: "static"
            });
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

function login_form_precheck(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0;
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) {
            c++;
            $(".tooltip-current", a).removeClass("tooltip-current");
            $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess"));
            validErrorShow(this);
            return !1
        }
    });

    return !c
}

function login_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var b = [];
    b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
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
            } else if (d.status == 'remove2step') {
                window.location.href = d.input
            } else if (d.status == "2step") {
                $(a).removeAttr('data-captcha data-recaptcha2 data-recaptcha3');
                $("input,button", a).prop("disabled", !1);

                // Trình duyệt không hỗ trợ passkey
                if (d.method_key && !nukeviet.WebAuthnSupported) {
                    d.pref_method = 'app';
                    d.method_key = 0;
                }

                $('.loginstep2-' + d.pref_method, a).removeClass('hidden');
                $('.loginstep2-methods', a).data('is-key', d.method_key ? 1 : 0);
                if (!d.tfa_recovery) {
                    $('[data-toggle="2fa-choose-recovery"]', a).closest('.item').addClass('hidden');
                } else {
                    $('[data-toggle="2fa-choose-recovery"]', a).closest('.item').removeClass('hidden');
                }
                if (!d.method_key || d.pref_method == 'key') {
                    $('[data-toggle="2fa-choose"][data-method="key"]', a).closest('.item').addClass('hidden');
                } else {
                    $('[data-toggle="2fa-choose"][data-method="key"]', a).closest('.item').removeClass('hidden');
                }
                if (d.pref_method == 'app') {
                    $('[data-toggle="2fa-choose"][data-method="app"]', a).closest('.item').addClass('hidden');
                } else {
                    $('[data-toggle="2fa-choose"][data-method="app"]', a).closest('.item').removeClass('hidden');
                }

                $('.loginstep1, .loginstep2, .loginCaptcha', a).toggleClass('hidden');
            } else if (d.status == 'activation') {
                $(".nv-info", a).html("<a href=\"" + d.input + "\">" + d.mess + "</a>").removeClass("error").removeClass("success").addClass("info").show();
            }
        }
    });
    return !1
}

function reg_form_precheck(a) {
    $(".has-error", a).removeClass("has-error");
    c = 0;
    $(a).find("input.required,input[data-callback],textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) {
            c++;
            $(".tooltip-current", a).removeClass("tooltip-current");
            $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess"));
            validErrorShow(this);
            return !1
        }
    });

    return !c
}

function reg_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = [];
    c.type = $(a).prop("method");
    c.url = $(a).prop("action");
    c.data = $(a).serialize();
    formErrorHidden(a);
    $(a).find("input,button,select,textarea").prop("disabled", !0);
    $.ajax({
        type: c.type,
        cache: !1,
        url: c.url,
        data: c.data,
        dataType: "json",
        success: function(b) {
            formChangeCaptcha(a);
            if (typeof b.timeout == "undefined") b.timeout = 6000;

            if ("error" == b.status) {
                $("input,button,select,textarea", a).prop("disabled", !1);
                $(".tooltip-current", a).removeClass("tooltip-current");
                ("" != b.input && $("[name=\"" + b.input + "\"]:visible", a).length) ? $(a).find('[name="' + b.input + '"]:visible').each(function() {
                    $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                    validErrorShow(this)
                }): ($(".nv-info", a).html(b.mess).addClass("error").show(), $("html, body").animate({
                    scrollTop: $(".nv-info", a).offset().top
                }, 800));
                return;
            }

            $(".nv-info", a).html(b.mess + (b.timeout > 0 ? '<span class="load-bar"></span>' : '')).removeClass("error").addClass("success").show();

            if ("ok" == b.input) {
                setTimeout(function() {
                    $(".nv-info", a).fadeOut();
                    $("input,button,select,textarea", a).prop("disabled", !1);
                    $("[onclick*=validReset]", a).click()
                }, b.timeout);
                return;
            }

            $(".form-detail", a).hide();
            $("html, body").animate({
                scrollTop: $(".nv-info", a).offset().top
            }, 800);
            b.timeout > 0 && setTimeout(function() {
                if ("" != b.input) {
                    window.location.href = b.input;
                    return;
                }
                location.reload();
            }, b.timeout);
        },
        error: function(b, d, f) {
            window.console.log ? console.log(b.status + ": " + f) : alert(b.status + ": " + f)
        }
    });

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

function remove2step_submit(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            if (d.status == "error") {
                if ("undefined" != typeof d.redirect && "" != d.redirect) {
                    $(".nv-info", a).html('<a href="' + d.redirect + '">' + d.mess + '<span class="load-bar"></span></a>').removeClass("error").removeClass("info").addClass("success").show();
                    $(".form-detail", a).hide();
                    setTimeout(function() {
                        window.location.href = d.redirect
                    }, 5E3)
                } else {
                    $("input,button", a).not("[type=submit]").prop("disabled", !1);
                    $(".tooltip-current", a).removeClass("tooltip-current");
                    ("" != d.input && $("[name=\"" + d.input + "\"]:visible", a).length) ? $(a).find("[name=\"" + d.input + "\"]:visible").each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                        validErrorShow(this)
                    }): $(".nv-info", a).html(d.mess).removeClass("success").removeClass("info").addClass("error").show();
                    setTimeout(function() {
                        $("[type=submit]", a).prop("disabled", !1)
                    }, 1E3)
                }
            } else if (d.status == "OK") {
                $(".nv-info", a).html('<a href="' + d.redirect + '">' + d.mess + '<span class="load-bar"></span></a>').removeClass("error").removeClass("info").addClass("success").show();
                $(".form-detail", a).hide();
                setTimeout(function() {
                    window.location.href = d.redirect
                }, 5E3)
            } else if (d.status == "failed") {
                $(".nv-info", a).html('<a href="' + d.redirect + '">' + d.mess + '<span class="load-bar"></span></a>').removeClass("success").removeClass("info").addClass("error").show();
                $(".form-detail", a).hide();
                setTimeout(function() {
                    window.location.href = d.redirect
                }, 5E3)
            } else if (d.status == "step2") {
                $('[name=email_sent]', a).val(1);
                $("input,button", a).not("[type=submit]").prop("disabled", !1);
                $(".step1", a).hide(), $(".step2", a).show();
                $(".nv-info", a).html(d.mess).removeClass("error").removeClass("success").addClass("info").show();
                setTimeout(function() {
                    $("[type=submit]", a).prop("disabled", !1)
                }, 1E3)
            }
        }
    }));
    return !1
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

// Form xác nhận mật khẩu để làm 1 việc nào quan trọng
function verify_password_precheck(form) {
    if (trim($('[name="password"]', form).val()) == '') {
        $('[name="password"]', form).focus();
        return false;
    }
    return true;
}
function verify_password_validForm(form) {
    const data = {};
    data.type = $(form).prop("method");
    data.url = $(form).prop("action");
    data.data = $(form).serialize();
    formErrorHidden(form);

    $(form).find("input,button,select,textarea").prop("disabled", true);

    $.ajax({
        type: data.type,
        cache: false,
        url: data.url,
        data: data.data,
        dataType: "json",
        success: function(res) {
            formChangeCaptcha(form);

            if ("error" == res.status) {
                $("input,button,select,textarea", form).prop("disabled", false);
                $(".tooltip-current", form).removeClass("tooltip-current");

                if (res.input && "" != res.input && $("[name='" + res.input + "']:visible", form).length) {
                    $(form).find('[name="' + res.input + '"]:visible').each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", res.mess);
                        validErrorShow(this);
                    });
                    return;
                }

                $(".nv-info", form).html(res.mess).addClass("error").show();
                $("html, body").animate({
                    scrollTop: $(".nv-info", form).offset().top
                }, 200);
                return;
            }
            if (res.redirect) {
                window.location.href = res.redirect;
                return;
            }
            location.reload();
        }
    });

    return false;
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

    $('body').on('submit', '#remove2step', function(e) {
        e.preventDefault();
        return remove2step_submit(this)
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
        $($(this).data('show-obj')).hide(0);
        $($(this).data('hide-obj')).fadeIn()
    });

    $('[data-toggle=addfilebtn]').on('click', function() {
        var filelist = $(this).parents('.filelist'),
            filenum = $('[name^=custom_fields]', filelist).length,
            maxnum = parseInt(filelist.data('maxnum')),
            that = $(this),
            setAddFileBtn = function(num) {
                if (maxnum && num >= maxnum) {
                    that.hide();
                } else {
                    that.show();
                }
            };

        var modalObj = $('#' + $(this).data('modal')),
            fileAccept = modalObj.data('accept'),
            maxsize = parseInt(modalObj.data('maxsize')),
            updateFileInput = function() {
                var input = $('<input type="file"/>');
                if (fileAccept != '') {
                    input.attr('accept', fileAccept)
                }
                input.on('change', function() {
                    var sFileName = $(this).val();
                    if (sFileName.length > 0) {
                        // Check extension
                        if (fileAccept != '') {
                            var fileAcceptArr = fileAccept.split(',');
                            var blnValid = false;
                            for (var j = 0; j < fileAcceptArr.length; j++) {
                                var sCurExtension = fileAcceptArr[j];
                                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                    blnValid = true;
                                    break;
                                }
                            }
                            if (!blnValid) {
                                updateFileInput();
                                alert(modalObj.data('ext-error') + ' ' + fileAcceptArr.join(', '));
                                return !1;
                            }
                        }

                        // Check file size
                        if (typeof ($(this)[0].files) != "undefined") {
                            if ($(this)[0].files[0].size > maxsize) {
                                var maxsizeKB = parseFloat(maxsize / 1024).toFixed(2),
                                    sizeKB = parseFloat($(this)[0].files[0].size / 1024).toFixed(2);
                                updateFileInput();
                                alert(modalObj.data('size-error') + ' (' + sizeKB + ' KB) ' + modalObj.data('size-error2') + ' (' + maxsizeKB + ' KB)');
                                return !1
                            }

                            data = new FormData();
                            data.append('file', $(this)[0].files[0]);
                            data.append('field', modalObj.data('field'));
                            data.append('_csrf', modalObj.data('csrf'));
                            data.append('field_fileupload', 1);
                            $.ajax({
                                type: 'POST',
                                url: modalObj.data('url'),
                                enctype: 'multipart/form-data',
                                data: data,
                                cache: false,
                                processData: false,
                                contentType: false,
                                dataType: "json"
                            }).done(function(a) {
                                if (a.status == 'error') {
                                    updateFileInput();
                                    alert(a.mess);
                                    return !1
                                } else if(a.status == 'OK') {
                                    var newfile = $('<li><input type="checkbox" name="custom_fields[' + filelist.data('field') + '][]" value="' + a.file_key + '" class="' + filelist.data('oclass') + '" checked> ' + a.file_value + ' (<a href="javascript:void(0)" data-toggle="userfile_del">'+ modalObj.data('delete') + '</a>)</li>');
                                    $('[data-toggle=userfile_del]', newfile).on('click', function(e) {
                                        $.ajax({
                                            type: 'POST',
                                            cache: !1,
                                            url: modalObj.data('url'),
                                            data: {
                                                'file': a.file_key,
                                                '_csrf': a.csrf,
                                                'field_filedel': 1
                                            },
                                            dataType: 'json',
                                            success: function(e) {
                                                if (e.status == 'error') {
                                                    alert(a.mess)
                                                } else if (e.status == 'OK') {
                                                    newfile.remove();
                                                    --filenum;
                                                    setAddFileBtn(filenum)
                                                }
                                            }
                                        });
                                    });
                                    $('.items', filelist).append(newfile);
                                    modalObj.modal('hide');
                                    ++filenum;
                                    setAddFileBtn(filenum)
                                }
                            })
                        }
                    }
                });
                $('.fileinput', modalObj).html(input)
            };
        updateFileInput();
        modalObj.modal('show')
    });

    $('[data-toggle=thisfile_del]').on('click', function() {
        var filelist = $(this).parents('.filelist');
        $(this).parents('li').remove();
        if ($('[data-toggle=addfilebtn]', filelist).length) {
            var maxnum = parseInt(filelist.data('maxnum'));
            if (maxnum && $('[name^=custom_fields]', filelist).length >= maxnum) {
                $('[data-toggle=addfilebtn]', filelist).hide();
            } else {
                $('[data-toggle=addfilebtn]', filelist).show();
            }
        }
    });

    $('.btn-file').on('click', function() {
        var url = $(this).data('url');
        if ($(this).is('.type-image, .type-pdf')) {
            nv_open_browse(url, "NVImg", 650, 430, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
        } else {
            window.location.href = url;
        }
        return !1;
    });

    $('[data-toggle="validReset2fa"]').on('click', function() {
        location.reload();
    });

    $('body').on('submit', '[data-toggle=verify_password_validForm]', function() {
        return verify_password_validForm(this);
    });

    // Xử lý cảnh báo của sổ WebView trên tất cả các form đăng nhập
    if (isInAppBrowser()) {
        $('form[data-toggle="userLogin"]').each(function() {
            const form = $(this);
            const thirdPartyLogin = ($('[data-toggle="openID_load"]', form).length > 0 || $('.g_id_signin', form).length > 0);
            const ele = $('[data-toggle="webview-warning"]', form);
            let message = '';
            if (thirdPartyLogin && nukeviet.WebAuthnSupported) {
                message = form.data('note-webview1');
            } else if (nukeviet.WebAuthnSupported && !thirdPartyLogin) {
                message = form.data('note-webview2');
            } else if (thirdPartyLogin && !nukeviet.WebAuthnSupported) {
                message = form.data('note-webview3');
            }
            if (message) {
                ele.removeClass('hidden');
                ele.html(message);
            }
        });
    }

    // Chọn phương thức xác thực 2 bước khi đăng nhập
    $('[data-toggle="2fa-choose"]').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const methods = $('.loginstep2-methods', form);
        $('[data-toggle="2fa-choose"]', methods).closest('.item').removeClass('hidden');
        if (!methods.data('is-key')) {
            $('[data-toggle="2fa-choose"][data-method="key"]', methods).closest('.item').addClass('hidden');
        }
        $(this).closest('.item').addClass('hidden');

        const tstepCtn = $('.loginstep2', form);
        $('[type="text"]', tstepCtn).val('').each(function() {
            validErrorHidden(this);
        });
        $('[name="auth_assertion"]', form).val('');
        $('[data-toggle="passkey-error"]', form).text('').addClass('hidden');
        $('.loginstep2-item', tstepCtn).addClass('hidden');
        $('.loginstep2-' + $(this).data('method'), tstepCtn).removeClass('hidden');
    });

    // Khôi phục 2FA khi không đăng nhập được
    $('[data-toggle="2fa-choose-recovery"]').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        $('[name="cant_do_2step"]', form).val('1');
        form.submit();
    });

    // Xử lý passkey trên toàn bộ các form đăng nhập
    $('form[data-toggle="userLogin"]').each(function() {
        const form = $(this);
        if (form.data('passkey-initialized') || !nukeviet.WebAuthnSupported) {
            return;
        }
        form.data('passkey-initialized', true);

        const ctn = $('[data-toggle="passkey-ctn"]', form);
        const link = $('[data-toggle="passkey-link"]', ctn);
        const btn = $('[data-toggle="passkey-btn"]', ctn);
        const err = $('[data-toggle="passkey-error"]', ctn);
        const icon = $('i', btn);

        ctn.removeClass('hidden');

        if (nv_getCookie(nv_cookie_prefix + '_pkey') == 1) {
            btn.removeClass('hidden');
        } else {
            link.removeClass('hidden');
        }

        link.on('click', function(e) {
            e.preventDefault();
            link.addClass('hidden');
            btn.removeClass('hidden').trigger('click');
        });

        // Đăng nhập bằng passkey
        btn.on('click', function(e) {
            e.preventDefault();
            if (icon.is('.fa-spinner')) {
                return;
            }
            err.text('').addClass('hidden');
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: {
                    login_with_passkey: 1,
                    checkss: $('[name="_csrf"]', form).val(),
                    create_challenge: 1,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status != 'ok') {
                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                        err.text(response.mess || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                        return;
                    }

                    let requestOptions = JSON.parse(response.requestOptions);
                    requestOptions.challenge = base64UrlToArrayBuffer(requestOptions.challenge);

                    try {
                        navigator.credentials.get({
                            publicKey: requestOptions
                        }).then(assertion => {
                            const data = {
                                login_with_passkey: 1,
                                checkss: $('[name="_csrf"]', form).val(),
                                auth_assertion: 1,
                                nv_redirect: $('[name="nv_redirect"]', form).val(),
                                assertion: JSON.stringify({
                                    id: assertion.id,
                                    type: assertion.type,
                                    rawId: arrayBufferToBase64Url(assertion.rawId),
                                    response: {
                                        clientDataJSON: arrayBufferToBase64Url(assertion.response.clientDataJSON),
                                        authenticatorData: arrayBufferToBase64Url(assertion.response.authenticatorData),
                                        signature: arrayBufferToBase64Url(assertion.response.signature),
                                        userHandle: arrayBufferToBase64Url(assertion.response.userHandle),
                                    }
                                }),
                            };
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: data,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.status != 'ok') {
                                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                        err.text(response.mess).removeClass('hidden');
                                        return;
                                    }
                                    nv_setCookie(nv_cookie_prefix + '_pkey', 1, 3650, true, 'Strict');
                                    $(".nv-info", form).html(response.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                                    $(".form-detail", form).hide();
                                    $("#other_form").hide();
                                    setTimeout(function() {
                                        if ("undefined" != typeof response.redirect && "" != response.redirect) {
                                            window.location.href = response.redirect;
                                        } else {
                                            $('#sitemodal').modal('hide');
                                            location.reload();
                                        }
                                    }, 3000);
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr, status, error);
                                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                    err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                                }
                            });
                        }).catch(error => {
                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                            err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                        });
                    } catch (error) {
                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                        err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                    err.text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                }
            });
        });
    });

    // Xử lý xác thực 2FA bằng WebAuthn trên tất cả các form đăng nhập
    $('form[data-toggle="userLogin"]').each(function() {
        const form = $(this);
        if (form.data('passkey-verify-initialized') || !nukeviet.WebAuthnSupported) {
            return;
        }
        form.data('passkey-verify-initialized', true);

        const ctn = $('.loginstep2-key', form);
        const btn = $('[data-toggle="passkey-verify"]', ctn);
        const err = $('[data-toggle="passkey-error"]', ctn);
        const icon = $('i', btn);

        btn.on('click', function(e) {
            e.preventDefault();
            if (icon.is('.fa-spinner')) {
                return;
            }
            $('[name="auth_assertion"]', form).val('');
            err.text('').addClass('hidden');
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');

            const formData = new FormData(form[0]);
            formData.append('create_challenge', 1);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                success: function(response) {
                    if (response.status != 'ok') {
                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                        err.text(response.mess || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                        return;
                    }

                    let requestOptions = JSON.parse(response.requestOptions);
                    requestOptions.challenge = base64UrlToArrayBuffer(requestOptions.challenge);
                    if (requestOptions.allowCredentials.length > 0) {
                        requestOptions.allowCredentials = requestOptions.allowCredentials.map(credential => {
                            credential.id = base64UrlToArrayBuffer(credential.id);
                            return credential;
                        });
                    }

                    try {
                        navigator.credentials.get({
                            publicKey: requestOptions
                        }).then(assertion => {
                            $('[name="auth_assertion"]', form).val(JSON.stringify({
                                id: assertion.id,
                                type: assertion.type,
                                rawId: arrayBufferToBase64Url(assertion.rawId),
                                response: {
                                    clientDataJSON: arrayBufferToBase64Url(assertion.response.clientDataJSON),
                                    authenticatorData: arrayBufferToBase64Url(assertion.response.authenticatorData),
                                    signature: arrayBufferToBase64Url(assertion.response.signature),
                                    userHandle: arrayBufferToBase64Url(assertion.response.userHandle),
                                }
                            }));
                            const formData = new FormData(form[0]);
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    if (response.status != 'ok') {
                                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                        err.text(response.mess).removeClass('hidden');
                                        return;
                                    }
                                    $(".nv-info", form).html(response.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                                    $(".form-detail", form).hide();
                                    $("#other_form").hide();
                                    setTimeout(function() {
                                        if ("undefined" != typeof response.redirect && "" != response.redirect) {
                                            window.location.href = response.redirect;
                                        } else {
                                            $('#sitemodal').modal('hide');
                                            location.reload();
                                        }
                                    }, 3000);
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr, status, error);
                                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                    err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                                }
                            });
                        }).catch(error => {
                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                            err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                        });
                    } catch (error) {
                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                        err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                    }
                },
                error: function (xhr, text, err) {
                    console.log(xhr, text, err);
                    err.text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                }
            });
        });
    });

    /**
     * Trang bảo mật và quyền riêng tư
     */
    const privacyForm = $('#security-privacy-page');
    if (privacyForm.length) {
        const autoToast = privacyForm.data('auto-toast');
        if (autoToast.length > 0) {
            nvToast(autoToast, 'success');
        }

        // Load thêm phiên đăng nhập
        $('[data-toggle="login-more"]', privacyForm).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            btn.prop('disabled', true);
            privacyForm.data('page', privacyForm.data('page') + 1);
            $.ajax({
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                type: 'POST',
                data: {
                    checkss: privacyForm.data('checkss'),
                    loadmorelogins: 1,
                    login_offset: privacyForm.data('next-offset'),
                    page: privacyForm.data('page')
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    btn.prop('disabled', false);
                    if (response.status != 'ok') {
                        nvToast(response.mess, 'error');
                        return;
                    }

                    $('[data-toggle="logins-ctn"]', privacyForm).append(response.contents);
                    if (response.more) {
                        privacyForm.data('next-offset', response.next_offset);
                    } else {
                        $('[data-toggle="login-more-ctn"]', privacyForm).remove();
                        privacyForm.data('next-offset', 0);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr, status, error);
                    nvToast(error, 'error');
                    btn.prop('disabled', false);
                }
            });
        });

        // Xóa toàn bộ phiên đăng nhập
        $('[data-toggle="login-remove-all"]', privacyForm).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            nukeviet.confirm(nukeviet.i18n.confirmAction, () => {
                btn.prop('disabled', true);
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    type: 'POST',
                    data: {
                        checkss: privacyForm.data('checkss'),
                        delloginall: 1
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        btn.prop('disabled', false);
                        if (response.status == 'not_verified') {
                            window.location.href = response.redirect;
                            return;
                        }
                        if (response.status != 'ok') {
                            nvToast(response.mess, 'error');
                            return;
                        }

                        nvToast(response.mess, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr, status, error);
                        nvToast(error, 'error');
                        btn.prop('disabled', false);
                    }
                });
            });
        });

        // Xóa một phiên đăng nhập
        $(privacyForm).on('click', '[data-toggle="login-remove"]', function(e) {
            e.preventDefault();
            const btn = $(this);
            nukeviet.confirm(nukeviet.i18n.confirmAction, () => {
                btn.prop('disabled', true);
                $.ajax({
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                    type: 'POST',
                    data: {
                        checkss: privacyForm.data('checkss'),
                        dellogin: 1,
                        idlogin: btn.data('idlogin'),
                        page: privacyForm.data('page')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        btn.prop('disabled', false);
                        if (response.status == 'not_verified') {
                            window.location.href = response.redirect;
                            return;
                        }
                        if (response.status != 'ok') {
                            nvToast(response.mess, 'error');
                            return;
                        }

                        nvToast(response.mess, 'success');
                        setTimeout(() => {
                            response.redirect ? window.location.href = response.redirect : location.reload();
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr, status, error);
                        nvToast(error, 'error');
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    }

    // Trang yêu cầu xóa dữ liệu cá nhân
    const dataDeletionForm = $('#user-request-deletion-page');
    if (dataDeletionForm.length) {
        // Xác nhận đã đọc kỹ các thông tin
        $('[name="i_confirmed"]', dataDeletionForm).on('change', function() {
            const btn = $('[type="submit"]', dataDeletionForm);
            if ($(this).is(':checked')) {
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
            }
        });

        // Đếm ngược đồng hồ, gửi lại mã xác nhận
        const countdownEle = $('[data-toggle="timer-code"]', dataDeletionForm);
        const countdownVal = $('[data-toggle="time-code-remain"]', dataDeletionForm);
        const resendEle = $('[data-toggle="request-new-code"]', dataDeletionForm);

        function updateCountdown() {
            let timeRemain = parseInt(countdownVal.text());
            if (timeRemain > 0) {
                --timeRemain;
                countdownVal.text(timeRemain);
                setTimeout(updateCountdown, 1000);
            } else {
                countdownEle.hide();
                resendEle.show();
            }
        }
        if (countdownEle.length && countdownEle.is(':visible')) {
            updateCountdown();
        }
        resendEle.on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            $('[data-toggle="recode-loader"]', dataDeletionForm).show();
            $.ajax({
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                type: 'POST',
                data: {
                    checkss: dataDeletionForm.data('checkss'),
                    resend_code: 1
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    $('[data-toggle="recode-loader"]', dataDeletionForm).hide();

                    if (response.status != 'ok') {
                        nvToast(response.mess, 'error');
                        resendEle.show();
                        return;
                    }

                    nvToast(response.mess, 'success');
                    setTimeout(() => {
                        countdownVal.text('120');
                        countdownEle.show();
                        updateCountdown();
                    }, 500);
                },
                error: function (xhr, status, error) {
                    console.log(xhr, status, error);
                    nvToast(error, 'error');
                    btn.prop('disabled', false);
                }
            });
        });

        // Nhập đủ mã mới cho phép submit
        $('[name="verification_code"]', dataDeletionForm).on('input', function() {
            const btn = $('[type="submit"]', dataDeletionForm);
            if ($(this).val().length >= 10) {
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
            }
        });
    }
});
