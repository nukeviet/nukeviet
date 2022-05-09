/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var gEInterval,
    tip_active = !1,
    ftip_active = !1,
    tip_autoclose = !0,
    ftip_autoclose = !0,
    winX = 0,
    winY = 0,
    oldWinX = 0,
    oldWinY = 0,
    cRangeX = 0,
    cRangeY = 0,
    docX = 0,
    docY = 0,
    scrt = 0,
    scrh = 0,
    oldScrt = 0,
    scrtRangeY = 0,
    scrtRangeOffset = 1,
    didScroll = !1,
    wrapWidth = 0,
    headerH = 0,
    footerH = 0,
    winHelp = !1,
    breadcrumbs = $(".breadcrumbs"),
    subbreadcrumbs = $(".sub-breadcrumbs"),
    tempbreadcrumbs = $(".temp-breadcrumbs"),
    isSafari = /^((?!chrome).)*safari/i.test(navigator.userAgent),
    reCapIDs = [];

function winResize() {
    oldWinX = winX;
    oldWinY = winY;
    winX = $(window).width();
    winY = $(window).height();
    docX = $(document).width();
    docY = $(document).height();
    cRangeX = Math.abs(winX - oldWinX);
    cRangeY = Math.abs(winY - oldWinY);
    scrh = $(window).scrollHeight;
    headerH = $("header.first-child").outerHeight();
    footerH = $("footer#footer").outerHeight()
}

function winHelpShow() {
    winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
    $("body").css({
        'padding-right': '17px',
        'overflow': 'hidden'
    });
    $("#winHelp").find(".logo-small").html($(".logo").html());
    $("#winHelp").show(0);
    winHelp = !0
}

function winHelpHide() {
    winHelp = !1;
    $("#winHelp").hide();
    $("body").css({
        'padding-right': '',
        'overflow': ''
    })
}

function contentScrt() {
    winHelp && winHelpHide();
    scrt = $(window).scrollTop();
    scrtRangeY = scrt - oldScrt;
    0 >= scrt ? $(".bttop").find("em").removeClass("fa-chevron-up").toggleClass("fa-refresh", !0) : $(".bttop").find("em").removeClass("fa-refresh").toggleClass("fa-chevron-up", !0);
    Math.abs(scrtRangeY) <= scrtRangeOffset || (scrt > oldScrt && scrt > headerH ? $("header.first-child").removeClass("header-down").addClass("header-up") : scrt + winY < docY && $("header.first-child").removeClass("header-up").addClass("header-down"), docY - (scrt + winY + (isSafari ? 44 : 0)) <= footerH || 0 >= scrt ? $("#footer").removeClass("footer-down").addClass("footer-up") : $("#footer").removeClass("footer-up").addClass("footer-down"), oldScrt = scrt)
}

function checkAll(a) {
    $(".checkAll", a).is(":checked") ? $(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !0)
    }) : $(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !1)
    });
    return !1
}

function checkSingle(a) {
    var b = 0,
        c = 0;
    $(".checkSingle", a).each(function() {
        $(this).is(":checked") ? b++ : c++
    });
    0 != b && 0 == c ? $(".checkAll", a).prop("checked", !0) : $(".checkAll", a).prop("checked", !1);
    return !1
}

function tipHide() {
    $("[data-toggle=tip]").attr("data-click", "y").removeClass("active");
    $("#tip").hide();
    tip_active = !1;
    tipAutoClose(!0)
}

function ftipHide() {
    $("[data-toggle=ftip]").attr("data-click", "y").removeClass("active");
    $("#ftip").hide();
    ftip_active = !1;
    ftipAutoClose(!0)
}

function tipAutoClose(a) {
    1 != a && (a = !1);
    tip_autoclose = a
}

function ftipAutoClose(a) {
    1 != a && (a = !1);
    ftip_autoclose = a
}

function tipShow(a, b, callback) {
    winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
    $("[data-toggle=tip]").removeClass("active");
    $(a).attr("data-click", "n").addClass("active");
    if (typeof callback != "undefined") {
        $("#tip").attr("data-content", b).show("fast", function() {
            if (callback == "recaptchareset") {
                if ($('[data-toggle=recaptcha]', this).length) {
                    reCaptcha2Recreate(this);
                    "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
                } else if ($("[data-recaptcha3]", this).length) {
                    "undefined" != typeof grecaptcha ? reCaptcha3OnLoad() : reCaptcha3ApiLoad()
                }
            } else if (typeof window[callback] === "function") {
                window[callback]()
            }
        });
    } else {
        $("#tip").attr("data-content", b).show("fast");
    }
    tip_active = !0
}

function ftipShow(a, b, callback) {
    if ($(a).is(".qrcode") && "yes" != $(a).attr("data-load")) {
        return qrcodeLoad(a), !1;
    }
    if ($(a).is("#contactButton") && "yes" != $(a).attr("data-load")) {
        return ctbtLoad($(a)), !1;
    }
    winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
    $("[data-toggle=ftip]").removeClass("active");
    $(a).attr("data-click", "n").addClass("active");
    if (typeof callback != "undefined") {
        $("#ftip").attr("data-content", b).show("fast", function() {
            if (callback == "recaptchareset") {
                if ($('[data-toggle=recaptcha]', this).length) {
                    reCaptcha2Recreate(this);
                    "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
                } else if ($("[data-recaptcha3]", this).length) {
                    "undefined" != typeof grecaptcha ? reCaptcha3OnLoad() : reCaptcha3ApiLoad()
                }
            } else if (typeof window[callback] === "function") {
                window[callback]()
            }
        });
    } else {
        $("#ftip").attr("data-content", b).show("fast");
    }
    ftip_active = !0
}

//Contact Button
function ctbtLoad(a) {
    var b = $(a.data("target") + " .panel-body");
    "yes" != a.attr("data-load") && $.ajax({
        type: "POST",
        cache: !1,
        url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + a.attr("data-module"),
        data: "loadForm=1&checkss=" + a.attr("data-cs"),
        dataType: "html",
        success: function(c) {
            b.html(c);
            change_captcha();
            a.attr("data-load", "yes").click()
        }
    })
}

function openID_load(a) {
    winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
    nv_open_browse($(a).attr("href"), "NVOPID", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,location=no,status=no");
    return !1
}

function openID_result() {
    var a = $("#openidResult");
    a.fadeIn();
    setTimeout(function() {
        "" != a.data("redirect") ? window.location.href = a.data("redirect") : "success" == a.data("result") ? location.reload() : a.hide(0).html("").data("result", "").data("redirect", "")
    }, 5E3)
}

// QR-code
function qrcodeLoad(a) {
    var b = new Image,
        c = $(a).data("img");
    $(b).on("load", function() {
        $(c).attr("src", b.src);
        $(a).attr("data-load", "yes").click()
    });
    b.src = nv_base_siteurl + "index.php?second=qr&u=" + encodeURIComponent($(a).data("url"))
}

// Switch tab
function switchTab(a) {
    if ($(a).is(".current")) return !1;
    var b = $(a).data("switch").split(/\s*,\s*/),
        c = $(a).data("obj");
    $(c + " [data-switch]").removeClass("current");
    $(a).addClass("current");
    $(c + " " + b[0]).removeClass("hidden");
    for (i = 1; i < b.length; i++) $(c + " " + b[i]).addClass("hidden")
}

//Form Ajax-login
function loginForm() {
    if (1 == nv_is_user) return !1;
    $.ajax({
        type: "POST",
        url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=users&" + nv_fc_variable + "=login",
        cache: !1,
        data: "&nv_ajax=1",
        dataType: "html"
    }).done(function(a) {
        modalShow("", a)
    });
    return !1
}

// ModalShow
function modalShow(a, b, callback) {
    "" != a && 'undefined' != typeof a && $("#sitemodal .modal-content").prepend('<div class="modal-header"><h2 class="modal-title">' + a + '</h2></div>');
    $("#sitemodal").find(".modal-title").html(a);
    $("#sitemodal").find(".modal-body").html(b);
    var scrollTop = false;
    if (typeof callback != "undefined") {
        if (callback == "recaptchareset") {
            scrollTop = $(window).scrollTop();
            $('#sitemodal').on('show.bs.modal', function() {
                if ($('[data-toggle=recaptcha]', this).length) {
                    reCaptcha2Recreate(this);
                    "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
                } else if ($("[data-recaptcha3]", this).length) {
                    "undefined" != typeof grecaptcha ? reCaptcha3OnLoad() : reCaptcha3ApiLoad()
                }
            });
        }
    }
    if (scrollTop) {
        $("html,body").animate({
            scrollTop: 0
        }, 200, function() {
            $("#sitemodal").modal({
                backdrop: "static"
            });
        });
        $('#sitemodal').on('hide.bs.modal', function() {
            $("html,body").animate({
                scrollTop: scrollTop
            }, 200);
        });
    } else {
        $("#sitemodal").modal({
            backdrop: "static"
        });
    }
    $('#sitemodal').on('hidden.bs.modal', function() {
        $("#sitemodal .modal-content").find(".modal-header").remove();
    });
}

function modalShowByObj(a, callback) {
    var b = $(a).attr("title"),
        c = $(a).html();
    modalShow(b, c, callback)
}

function headerSearchSubmit(a) {
    if ("n" == $(a).attr("data-click")) return !1;
    $(a).attr("data-click", "n");
    var b = $(".headerSearch input"),
        c = b.attr("maxlength"),
        d = strip_tags(b.val()),
        e = $(a).attr("data-minlength");
    b.parent().removeClass("has-error");
    "" == d || d.length < e || d.length > c ? (b.parent().addClass("has-error"), b.val(d).focus(), $(a).attr("data-click", "y")) : window.location.href = $(a).attr("data-url") + rawurlencode(d);
    return !1
}

function headerSearchKeypress(a) {
    13 != a.which || a.shiftKey || (a.preventDefault(), $("#tip .headerSearch button").trigger("click"));
    return !1
}

function showSubBreadcrumbs(a, b) {
    b.preventDefault();
    b.stopPropagation();
    $("em", a).is(".fa-angle-right") ? $("em", a).removeClass("fa-angle-right").addClass("fa-angle-down") : $("em", a).removeClass("fa-angle-down").addClass("fa-angle-right");
    subbreadcrumbs.toggleClass("open");
    $(document).on("click", function() {
        $("em", a).is(".fa-angle-down") && ($("em", a).removeClass("fa-angle-down").addClass("fa-angle-right"), subbreadcrumbs.removeClass("open"))
    })
}

function nvbreadcrumbs() {
    var a = $(".breadcrumb", breadcrumbs),
        b = $(".toggle", breadcrumbs),
        c = breadcrumbs.innerWidth() - 75,
        d = [],
        e = !1,
        f;
    if (a.length && subbreadcrumbs.length && tempbreadcrumbs.length)
        for (a.html(""), subbreadcrumbs.html(""), tempbreadcrumbs.find("a").each(function() {
                d.push([$(this).attr("title"), $(this).attr("href")])
            }), i = d.length - 1; 0 <= i; i--) e || (f = 0, a.prepend('<li id="brcr_' + i + '"><a href="' + d[i][1] + '"><span>' + d[i][0] + "</span></a></li>"), a.find("li").each(function() {
            f += $(this).outerWidth(!0)
        }), f > c && ($("#brcr_" + i, a).remove(), e = !0)), e ? (b.show(), subbreadcrumbs.append('<li><a href="' + d[i][1] + '"><span><em class="fa fa-long-arrow-up"></em> ' + d[i][0] + "</span></a></li>")) : b.hide()
}

// locationReplace
function locationReplace(url) {
    var uri = window.location.href.substr(window.location.protocol.length + window.location.hostname.length + 2);
    if (url != uri && history.pushState) {
        history.pushState(null, null, url)
    }
}

// Hide Cookie Notice Popup
function cookie_notice_hide() {
    nv_setCookie(nv_cookie_prefix + '_cn', '1', 365);
    $(".cookie-notice").hide()
}

// Change Captcha
function change_captcha(a) {
    $("[data-toggle=recaptcha]").length ? "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad() : $("[data-recaptcha3]").length && ("undefined" != typeof grecaptcha ? reCaptcha3OnLoad() : reCaptcha3ApiLoad());
    $("img.captchaImg").length && ($("img.captchaImg").attr("src", nv_base_siteurl + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10)), "undefined" != typeof a && "" != a && $(a).val(""));
    return !1
}

function isRecaptchaCheck() {
    return "" == nv_recaptcha_sitekey ? 0 : 2 == nv_recaptcha_ver || 3 == nv_recaptcha_ver ? nv_recaptcha_ver : 0
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

            if (typeof reCapIDs[id] === "undefined") {
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

// reCaptcha v2 callback
var reCaptcha2Callback = function(id, val) {
    var btn = $("#" + id),
        pnum = parseInt(btn.data("pnum")),
        btnselector = btn.data("btnselector"),
        k;
    for (k = 1; k <= pnum; k++) btn = btn.parent();
    btn = $(btnselector, btn);
    if (btn.length) btn.prop("disabled", val)
}

// reCaptcha v2 load
reCaptcha2ApiLoad = function() {
    if (2 == isRecaptchaCheck()) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "https://www.google.com/recaptcha/api.js?hl=" + nv_lang_interface + "&onload=reCaptcha2OnLoad&render=explicit";
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

// reCaptcha v3: reCaptcha3OnLoad
var reCaptcha3OnLoad = function() {
    grecaptcha.ready(function() {
        $("[data-recaptcha3]").length && (clearInterval(gEInterval), grecaptcha.execute(nv_recaptcha_sitekey, {
            action: "formSubmit"
        }).then(function(a) {
            $("[data-recaptcha3]").each(function() {
                if ($("[name=g-recaptcha-response]", this).length) $("[name=g-recaptcha-response]", this).val(a);
                else {
                    var b = $('<input type="hidden" name="g-recaptcha-response" value="' + a + '"/>');
                    $(this).append(b)
                }
            })
        }), gEInterval = setTimeout(function() {
            reCaptcha3OnLoad()
        }, 12E4))
    })
}

// reCaptcha v3 API load
var reCaptcha3ApiLoad = function() {
    if (isRecaptchaCheck() == 3) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "https://www.google.com/recaptcha/api.js?render=" + nv_recaptcha_sitekey + "&onload=reCaptcha3OnLoad";
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

// NukeViet Default Custom JS
$(function() {
    winResize();
    // Modify all empty link
    $('a[href="#"], a[href=""]').attr("href", "javascript:void(0);");

    // Add rel="noopener noreferrer nofollow" to all external links
    $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').not('[rel*=dofollow]').attr({
        target: "_blank",
        rel: "noopener noreferrer nofollow"
    });

    // Smooth scroll to top
    $(".bttop").click(function() {
        if ($(this).find("em").is(".fa-chevron-up")) {
            $('html,body').animate({
                scrollTop: 0
            }, 200);
        } else if ($(this).find("em").is(".fa-refresh")) {
            window.location.href = window.location.href
        }
        return !1
    });
    $(document).on("keydown", function(a) {
        27 === a.keyCode && (tip_active && tip_autoclose && tipHide(), ftip_active && ftip_autoclose && ftipHide(), winHelp && winHelpHide())
    });
    $("#tip, #ftip, #winHelp .winHelp").bind("click touchstart", function(a) {
        a.stopPropagation();
    });
    $(document).bind("click touchstart", function(e) {
        tip_active && tip_autoclose && tipHide();
        ftip_active && ftip_autoclose && ftipHide();
        if (!$(e.target).is('.modal') && $(e.target).closest('.modal').length <= 0 && $(e.target).closest('[data-toggle=winHelp]').length <= 0) {
            winHelp && winHelpHide();
        }
    });
    $("[data-toggle=tip], [data-toggle=ftip]").click(function() {
        var a = $(this).attr("data-target"),
            c = $(a).html(),
            d = $(this).attr("data-toggle"),
            b = "tip" == d ? $("#tip").attr("data-content") : $("#ftip").attr("data-content");
        var callback = $(this).data("callback");
        a != b ? ("" != b && $('[data-target="' + b + '"]').attr("data-click", "y"), "#metismenu" == a && (c = $("#headerSearch").html() + c), "tip" == d ? ($("#tip").html(c), "#metismenu" == a && $("#tip .metismenu ul").metisMenu({
            toggle: !1
        }), tipShow(this, a, callback)) : ($("#ftip").html(c), ftipShow(this, a, callback))) : "n" == $(this).attr("data-click") ? "tip" == d ? tipHide() : ftipHide() : "tip" == d ? tipShow(this, a) : ftipShow(this, a);
        return !1
    });
    $("[data-toggle=winHelp]").click(function() {
        winHelpShow();
        return !1
    });
    //Search form
    $(".headerSearch button").on("click", function() {
        if ("n" == $(this).attr("data-click")) {
            return !1;
        }
        $(this).attr("data-click", "n");
        var a = $(".headerSearch input"),
            c = a.attr("maxlength"),
            b = strip_tags(a.val()),
            d = $(this).attr("data-minlength");
        a.parent().removeClass("has-error");
        "" == b || b.length < d || b.length > c ? (a.parent().addClass("has-error"), a.val(b).focus(), $(this).attr("data-click", "y")) : window.location.href = $(this).attr("data-url") + rawurlencode(b);
        return !1
    });
    $(".headerSearch input").on("keypress", function(a) {
        13 != a.which || a.shiftKey || (a.preventDefault(), $(".headerSearch button").trigger("click"))
    });
    $(window).scroll(function() {
        didScroll = true;
    });
    setInterval(function() {
        if (didScroll) {
            winResize();
            contentScrt();
            didScroll = false;
        }
    }, 120);
    //FeedBack Button
    if ($('#contactButton').length) {
        var script = $('<script type="text/javascript">').attr("src", nv_base_siteurl + "themes/mobile_default/js/contact.js");
        $("body").append(script)
    }
    // Change site lang
    $(".nv_change_site_lang").change(function() {
        document.location = $(this).val();
    });
    // Google map
    if ($('.company-address').length) {
        $('.company-map-modal').on('shown.bs.modal', function() {
            var iframe = $(this).find('iframe');
            if (!iframe.data('loaded')) {
                iframe.attr('src', iframe.data('src'));
                iframe.data('loaded', true);
            }
        });
    }
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
    //OpenID
    $("#openidBt").on("click", function() {
        openID_result();
        return !1
    });
    //Change Localtion
    $("[data-location]").on("click", function() {
        if (window.location.origin + $(this).data("location") != window.location.href) {
            locationReplace($(this).data("location"))
        }
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

$(window).on("resize", function() {
    winResize();
    nvbreadcrumbs();
    //if (150 < cRangeX || 150 < cRangeY) tip_active && tipHide(), winHelp && winHelpHide()
});

// Load Social script - lasest
$(window).on('load', function() {
    nvbreadcrumbs();
    (0 < $(".fb-like").length) && (1 > $("#fb-root").length && $("body").append('<div id="fb-root"></div>'), function(a, b, c) {
        var d = a.getElementsByTagName(b)[0];
        var fb_app_id = ($('[property="fb:app_id"]').length > 0) ? '&appId=' + $('[property="fb:app_id"]').attr("content") : '';
        var fb_locale = ($('[property="og:locale"]').length > 0) ? $('[property="og:locale"]').attr("content") : ((nv_lang_data == "vi") ? 'vi_VN' : 'en_US');
        a.getElementById(c) || (a = a.createElement(b), a.id = c, a.src = "//connect.facebook.net/" + fb_locale + "/all.js#xfbml=1" + fb_app_id, d.parentNode.insertBefore(a, d));
    }(document, "script", "facebook-jssdk"));
    0 < $(".twitter-share-button").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//platform.twitter.com/widgets.js";
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();
    0 < $(".zalo-share-button, .zalo-follow-only-button, .zalo-follow-button, .zalo-chat-widget").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//sp.zalo.me/plugins/sdk.js";
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();
    if ($('[data-toggle=recaptcha]').length) {
        reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha3]").length) {
        reCaptcha3ApiLoad()
    }
});
