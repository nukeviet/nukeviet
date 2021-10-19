/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19/3/2010 22:58
 */

var myTimerPage = "",
    myTimersecField = "",
    reCapIDs = [];

// Load multiliple js,css files
function getFiles(files, callback) {
    var progress = 0;
    files.forEach(function(fileurl) {
        var dtype = fileurl.substring(fileurl.lastIndexOf('.') + 1) == 'js' ? 'script' : 'text',
            attrs = "undefined" !== typeof site_nonce ? {
                'nonce': site_nonce
            } : {};
        $.ajax({
            url: fileurl,
            cache: true,
            dataType: dtype,
            scriptAttrs: attrs,
            success: function() {
                if (dtype == 'text') {
                    $("<link/>", {
                        rel: "stylesheet",
                        href: fileurl
                    }).appendTo("head")
                }
                if (++progress == files.length) {
                    if ("function" === typeof callback) {
                        callback()
                    }
                }
            }
        })
    })
}

// timeoutsesscancel
function timeoutsesscancel() {
    $("#timeoutsess").slideUp("slow", function() {
        clearInterval(myTimersecField);
        myTimerPage = setTimeout(function() {
            timeoutsessrun()
        }, nv_check_pass_mstime)
    })
}

// timeoutsessrun
function timeoutsessrun() {
    clearInterval(myTimerPage);
    $("#secField").text("60");
    $("#timeoutsess").show();
    var b = (new Date).getTime();
    myTimersecField = setInterval(function() {
        var a = (new Date).getTime();
        a = 60 - Math.round((a - b) / 1E3);
        0 <= a ? $("#secField").text(a) : -3 > a && (clearInterval(myTimersecField), $(window).unbind(), $.ajax({
            type: "POST",
            cache: !1,
            url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=users&" + nv_fc_variable + "=logout",
            data: "nv_ajax_login=1&system=1"
        }).done(function() {
            location.reload();
        }));
    }, 1E3);
}

// Hide Cookie Notice Popup
function cookie_notice_hide() {
    nv_setCookie(nv_cookie_prefix + '_cn', '1', 365);
    $(".cookie-notice").hide()
}

//Toggle Password Visibility
function togglePassShow(btn) {
    var input = $(btn).parent().find("[type=password],[type=text]"),
        togglePassHide = function() {
            $("[type=text][data-type=password]").each(function() {
                $(this).removeAttr('data-type').prop("type", "password").next().removeClass('show');
                clearTimeout(resetPass)
            })
        };
    if ('password' == input.prop('type')) {
        input.attr('data-type', 'password');
        input.prop("type", "text");
        $(btn).addClass("show");
        clearTimeout(resetPass);
        resetPass = setTimeout(function() {
            togglePassHide()
        }, 2E4);
    } else {
        togglePassHide()
    }
}

// enterToEvent
function enterToEvent(e, obj, objEvent) {
    13 != e.which || e.shiftKey || (e.preventDefault(), $(obj).trigger(objEvent))
}

// checkAll
function checkAll(a) {
    $(".checkAll", a).is(":checked") ? ($(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !0)
    }), $(".checkBtn", a).length && $(".checkBtn", a).prop("disabled", !1)) : ($(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !1)
    }), $(".checkBtn", a).length && $(".checkBtn", a).prop("disabled", !0))
}

// checkSingle
function checkSingle(a) {
    var checked = 0,
        unchecked = 0;
    $(".checkSingle", a).not(":disabled").each(function() {
        $(this).is(":checked") ? checked++ : unchecked++
    });
    0 != checked && 0 == unchecked ? $(".checkAll", a).prop("checked", !0) : $(".checkAll", a).prop("checked", !1);
    $(".checkBtn", a).length && (checked ? $(".checkBtn", a).prop("disabled", !1) : $(".checkBtn", a).prop("disabled", !0))
}

// locationReplace
function locationReplace(url) {
    if (history.pushState) {
        history.pushState(null, null, url);
    }
}

// ModalShow
function modalShow(title, content, callback) {
    var md = $("#sitemodal");
    if ('undefined' != typeof title && "" != title) {
        $(".modal-content", md).prepend('<div class="modal-header"><div class="modal-title">' + title + '</div></div>')
    }
    $(".modal-body", md).html(content);
    var scrollTop = false;
    if ("undefined" != typeof callback) {
        if (callback == "recaptchareset") {
            scrollTop = $(window).scrollTop();
            md.on('show.bs.modal', function() {
                loadCaptcha(this)
            })
        } else if ("function" != typeof callback) {
            callback()
        }
    }

    md.on('hidden.bs.modal', function() {
        $(this).find(".modal-header").remove()
    });

    if (scrollTop) {
        md.on('hide.bs.modal', function() {
            $("html,body").animate({
                scrollTop: scrollTop
            }, 200);
        });
        $("html,body").animate({
            scrollTop: 0
        }, 200);
        md.modal({
            backdrop: "static"
        });
    } else {
        md.modal({
            backdrop: "static"
        });
    }

    md.modal('show')
}

function modalShowByObj(a, callback) {
    var b = $(a).attr("title"),
        c = $(a).html();
    modalShow(b, c, callback)
}

//Form Ajax-login
function loginForm(redirect) {
    if (nv_is_user == 1) {
        return !1;
    }

    var url = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=login';
    if (!!redirect) {
        url += '&nv_redirect=' + redirect;
    }
    $.ajax({
        type: 'POST',
        url: url,
        cache: !1,
        data: '&nv_ajax=1',
        dataType: "html"
    }).done(function(a) {
        modalShow('', a, 'recaptchareset')
    });
    return !1
}

// Load Captcha
function loadCaptcha(obj) {
    if ("undefined" === typeof obj) {
        obj = $('body')
    }
    if ($('[data-toggle=recaptcha]', obj).length) {
        reCaptcha2Recreate(obj);
        "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha2]", obj).length && "undefined" == typeof grecaptcha) {
        reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha3]", obj).length && "undefined" == typeof grecaptcha) {
        reCaptcha3ApiLoad()
    }
}

// Change Captcha
function change_captcha(a) {
    loadCaptcha();
    if ($("img.captchaImg").length) {
        $("img.captchaImg").attr("src", nv_base_siteurl + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
        "undefined" != typeof a && "" != a && $(a).val("");
    }
    return !1
}

// Form change captcha
function formChangeCaptcha(form) {
    var btn = $("[onclick*=change_captcha], [data-toggle=change_captcha]", form);
    btn.length && btn.trigger('click');
    if ($('[data-toggle=recaptcha]', form).length || $("[data-recaptcha2], [data-recaptcha3]", $(form).parent()).length) {
        change_captcha()
    }
}

function isRecaptchaCheck() {
    if (nv_recaptcha_sitekey == '') return 0;
    return (nv_recaptcha_ver == 2 || nv_recaptcha_ver == 3) ? nv_recaptcha_ver : 0
}

function reCaptcha2Recreate(obj) {
    $('[data-toggle=recaptcha]', $(obj)).each(function() {
        var callFunc = $(this).data('callback'),
            pnum = $(this).data('pnum'),
            btnselector = $(this).data('btnselector'),
            size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '';
        var id = "recaptcha" + (new Date().getTime()) + nv_randomPassword(8);
        if (callFunc) {
            $(this).replaceWith('<div id="' + id + '" data-toggle="recaptcha" data-callback="' + callFunc + '" data-size="' + size + '"></div>');
        } else {
            $(this).replaceWith('<div id="' + id + '" data-toggle="recaptcha" data-pnum="' + pnum + '" data-btnselector="' + btnselector + '" data-size="' + size + '"></div>')
        }
    })
}

function btnClickSubmit(event, form) {
    event.preventDefault();
    if ($(form).data('recaptcha3')) {
        reCaptchaExecute(form, function() {
            $(form).submit()
        })
    } else if ($(form).data('recaptcha2')) {
        reCaptcha2Execute(form, function() {
            $(form).submit()
        })
    } else if ($(form).data('captcha')) {
        captchaExecute(form, function() {
            $(form).submit()
        })
    } else {
        $(form).submit()
    }
}

function captchaCallFuncLoad(callFunc) {
    if ("function" === typeof callFunc) {
        callFunc()
    } else if ('string' == typeof callFunc && "function" === typeof window[callFunc]) {
        window[callFunc]()
    }
}

var reCaptcha2OnLoad = function() {
    $('[data-toggle=recaptcha]').each(function() {
        var id = $(this).attr('id'),
            callFunc = $(this).data('callback'),
            size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '';

        if (typeof window[callFunc] === 'function') {
            if (typeof reCapIDs[id] === "undefined") {
                reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'size': size,
                    'callback': callFunc
                })
            } else {
                grecaptcha.reset(reCapIDs[id])
            }
        } else {
            if (typeof reCapIDs[id] === "undefined") {
                var pnum = parseInt($(this).data('pnum')),
                    btnselector = $(this).data('btnselector'),
                    btn = $('#' + id),
                    k = 1;

                for (k; k <= pnum; k++) {
                    btn = btn.parent();
                }
                btn = $(btnselector, btn);
                if (btn.length) {
                    btn.prop('disabled', true);
                }

                reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'size': size,
                    'callback': function() {
                        reCaptcha2Callback(id, false)
                    },
                    'expired-callback': function() {
                        reCaptcha2Callback(id, true)
                    },
                    'error-callback': function() {
                        reCaptcha2Callback(id, true)
                    }
                })
            } else {
                grecaptcha.reset(reCapIDs[id])
            }
        }
    })
}

var reCaptcha2Callback = function(id, val) {
    var btn = $('#' + id),
        pnum = parseInt(btn.data('pnum')),
        btnselector = btn.data('btnselector'),
        k = 1;
    for (k; k <= pnum; k++) {
        btn = btn.parent();
    }
    btn = $(btnselector, btn);
    if (btn.length) {
        btn.prop('disabled', val);
    }
}

// Image-captcha Execute
var captchaExecute = function(obj, callFunc) {
    var name = $(obj).data('captcha'),
        md = $("#modal-img-captcha");
    $('.is-invalid, .has-error', md).removeClass("is-invalid has-error");
    $('.invalid-feedback', md).text('');
    change_captcha('#modal-captcha-value');
    md.on('show.bs.modal', function() {
        $('#modal-captcha-button').off('click').on('click', function(event) {
            event.preventDefault();
            var captcha_val = trim(strip_tags($('#modal-captcha-value').val()));
            if (captcha_val.length < parseInt($('#modal-captcha-value').attr('maxlength'))) {
                $('#modal-captcha-value').addClass('is-invalid').parent().addClass('has-error');
                $('.invalid-feedback', md).text(nv_code);
                $('#modal-captcha-value').focus()
            } else {
                md.modal('hide');
                $('[name=' + name + ']', obj).length ? $('[name=' + name + ']', obj).val(captcha_val) : (captcha_val = $('<input type="hidden" name="' + name + '" value="' + captcha_val + '"/>'), $(obj).append(captcha_val));
                setTimeout(function() {
                    captchaCallFuncLoad(callFunc)
                }, 100)
            }
        })
    }).on('shown.bs.modal', function() {
        $('#modal-captcha-value').focus()
    }).modal('show')
}

// reCaptcha2 Execute
var reCaptcha2Execute = function(obj, callFunc) {
    if ("undefined" === typeof grecaptcha) {
        reCaptcha2ApiLoad();
        setTimeout(function() {
            $('[type=submit]', obj).trigger('click')
        }, 2E3);
        return !1
    }

    var id = $(obj).attr('data-recaptcha2'),
        res = $("[name=g-recaptcha-response]", obj).val();
    if (id.length == 16 && typeof reCapIDs[id] !== "undefined" && !!res && grecaptcha.getResponse(reCapIDs[id]) == res) {
        captchaCallFuncLoad(callFunc)
    } else {
        if (id.length != 16) {
            id = nv_randomPassword(16);
            $(obj).attr('data-recaptcha2', id);
        }
        if (!$("#modal-" + id).length) {
            var header = $.fn.tooltip.Constructor.VERSION.substr(0, 1) == '3' ? '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="modal-title">' + verify_not_robot + '</div>' : '<div class="modal-title">' + verify_not_robot + '</div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            $("body").append($('<div id="modal-' + id + '" class="modal fade" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header">' + header + '</div><div class="modal-body"><div class="nv-recaptcha-default"><div id="' + id + '" data-toggle="recaptcha"></div></div></div></div></div></div>'));
        }
        var md = $("#modal-" + id);
        md.on('show.bs.modal', function() {
            if (typeof reCapIDs[id] === "undefined") {
                reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'callback': function(response) {
                        md.modal('hide');
                        $("[name=g-recaptcha-response]", obj).length ? $("[name=g-recaptcha-response]", obj).val(response) : (response = $('<input type="hidden" name="g-recaptcha-response" value="' + response + '"/>'), $(obj).append(response));
                        setTimeout(function() {
                            captchaCallFuncLoad(callFunc)
                        }, 100);
                    }
                })
            } else {
                grecaptcha.reset(reCapIDs[id])
            }
        }).modal('show')
    }
}

// reCaptcha v2 load
var reCaptcha2ApiLoad = function() {
    if (isRecaptchaCheck() == 2) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.defer = true;
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        a.src = "//www.google.com/recaptcha/api.js?hl=" + nv_lang_interface + "&onload=reCaptcha2OnLoad&render=explicit";
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

// reCaptcha v3: reCaptcha Execute
var reCaptchaExecute = function(obj, callFunc) {
    grecaptcha.execute(nv_recaptcha_sitekey, {
        action: "formSubmit"
    }).then(function(a) {
        $("[name=g-recaptcha-response]", obj).length ? $("[name=g-recaptcha-response]", obj).val(a) : (a = $('<input type="hidden" name="g-recaptcha-response" value="' + a + '"/>'), $(obj).append(a));
        captchaCallFuncLoad(callFunc)
    })
}

// reCaptcha v3 API load
var reCaptcha3ApiLoad = function() {
    if (isRecaptchaCheck() == 3) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.defer = true;
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        a.src = "//www.google.com/recaptcha/api.js?render=" + nv_recaptcha_sitekey;
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

$(function() {

    // Modify all empty link
    $('body').on("click", 'a[href="#"], a[href=""]', function(e) {
        e.preventDefault();
    });

    if ($('a[data-target]').length) {
        $('a[data-target]').each(function() {
            $(this).attr('target', $(this).data('target'))
        })
    }

    // Add rel="noopener noreferrer nofollow" to all external links
    $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').not('[rel*=dofollow]').attr({
        target: "_blank",
        rel: "noopener noreferrer nofollow"
    });

    // Show messger timeout login users
    nv_is_user && (myTimerPage = setTimeout(function() {
        timeoutsessrun()
    }, nv_check_pass_mstime));

    // Windows commands
    $('body').on('click', '[data-toggle=winCMD][data-cmd]', function(e) {
        e.preventDefault();
        if ($(this).data('cmd') == 'print') {
            window.print()
        } else if ($(this).data('cmd') == 'close') {
            window.close()
        } else if ($(this).data('cmd') == 'open') {
            window.open($(this).data('url'), $(this).data('win-name'), $(this).data('win-opts'))
        }
    });

    // timeoutsesscancel
    $('[data-toggle=timeoutsesscancel]').on('click', function(e) {
        e.preventDefault();
        timeoutsesscancel()
    });

    // Hide Cookie Notice Popup
    $('[data-toggle=cookie_notice_hide]').on('click', function(e) {
        e.preventDefault();
        cookie_notice_hide()
    });

    // JS của nv_generate_page
    $('body').on('click', '[data-toggle=gen-page-js][data-func][data-href][data-obj]', function(e) {
        e.preventDefault();
        if ('function' === typeof window[$(this).data('func')]) {
            window[$(this).data('func')]($(this).data('href'), $(this).data('obj'))
        }
    });

    // Gọi modal đăng nhập thành viên
    $('body').on('click', '[data-toggle=loginForm]', function(e) {
        e.preventDefault();
        $(this).data('redirect') ? loginForm($(this).data('redirect')) : loginForm()
    });

    //Captcha
    $('body').on('click', '[data-captcha] [type=submit], [data-recaptcha2] [type=submit], [data-recaptcha3] [type=submit]', function(e) {
        btnClickSubmit(e, $(this).parents('form'))
    });

    // Thay Captcha hình mới
    $('body').on('click', '[data-toggle=change_captcha]', function(e) {
        e.preventDefault();
        $(this).data('obj') ? change_captcha($(this).data('obj')) : change_captcha()
    });

    // Ẩn/hiển thị mật khẩu
    $('body').on('click', '[data-toggle=togglePassShow]', function(e) {
        e.preventDefault();
        togglePassShow(this)
    });

    // enterToEvent
    $('body').on('keyup', '[data-toggle=enterToEvent][data-obj][data-obj-event]', function(e) {
        enterToEvent(e, $(this).data('obj'), $(this).data('obj-event'))
    });

    // checkAll
    $('body').on('click', '[data-toggle=checkAll]', function() {
        checkAll($(this).parents('form'))
    });

    // checkSingle
    $('body').on('click', '[data-toggle=checkSingle]', function() {
        checkSingle($(this).parents('form'))
    });

    //Change Localtion
    $("[data-location]").on("click", function() {
        if (window.location.origin + $(this).data("location") != window.location.href) {
            locationReplace($(this).data("location"))
        }
    });

    // modalShowByObj
    $('body').on('click', '[data-toggle=modalShowByObj]', function(e) {
        e.preventDefault();
        var obj = $(this).data('obj') ? $(this).data('obj') : $(this),
            callback = $(this).data('callback');
        callback ? modalShowByObj(obj, callback) : modalShowByObj(obj)
    });

    // maxLength for textarea
    $("textarea").on("input propertychange", function() {
        var a = $(this).prop("maxLength");
        if (!a || "number" != typeof a) {
            var a = $(this).attr("maxlength"),
                b = $(this).val();
            b.length > a && $(this).val(b.substr(0, a))
        }
    });

    //Alerts
    $("[data-dismiss=alert]").on("click", function() {
        $(this).is(".close") && $(this).parent().remove()
    });
});

// Fix bootstrap multiple modal
$(document).on({
    'show.bs.modal': function() {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    },
    'hidden.bs.modal': function() {
        if ($('.modal:visible').length > 0) {
            setTimeout(function() {
                $(document.body).addClass('modal-open');
            }, 0);
        }
    }
}, '.modal');

$(window).on('load', function() {
    (0 < $(".fb-like").length) && (1 > $("#fb-root").length && $("body").append('<div id="fb-root"></div>'), function(a, b, c) {
        var d = a.getElementsByTagName(b)[0];
        var fb_app_id = ($('[property="fb:app_id"]').length > 0) ? '&appId=' + $('[property="fb:app_id"]').attr("content") : '';
        var fb_locale = ($('[property="og:locale"]').length > 0) ? $('[property="og:locale"]').attr("content") : ((nv_lang_data == "vi") ? 'vi_VN' : 'en_US');
        a.getElementById(c) || (a = a.createElement(b), a.id = c, a.src = "//connect.facebook.net/" + fb_locale + "/all.js#xfbml=1" + fb_app_id, "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce), d.parentNode.insertBefore(a, d));
    }(document, "script", "facebook-jssdk"));
    0 < $(".twitter-share-button").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//platform.twitter.com/widgets.js";
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();
    0 < $(".zalo-share-button, .zalo-follow-only-button, .zalo-follow-button, .zalo-chat-widget").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//sp.zalo.me/plugins/sdk.js";
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();

    if ($('[data-toggle=recaptcha]').length || $("[data-recaptcha2]").length) {
        reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha3]").length) {
        reCaptcha3ApiLoad()
    }
});
