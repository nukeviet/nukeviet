/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var tip_active = false,
    ftip_active = false,
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
    isSafari = /^((?!chrome).)*safari/i.test(navigator.userAgent);

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

function tipHide() {
    $("[data-toggle=tip]").attr("data-click", "y").removeClass("active");
    $("#tip").hide();
    tip_active = false;
}

function ftipHide() {
    $("[data-toggle=ftip]").attr("data-click", "y").removeClass("active");
    $("#ftip").hide();
    ftip_active = false;
}

function tipShow(a, b, callback) {
    winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
    $("[data-toggle=tip]").removeClass("active");
    $(a).attr("data-click", "n").addClass("active");
    $("#tip").attr("data-content", b);
    if (typeof callback != "undefined") {
        $("#tip").show("fast", function() {
            if (callback == "recaptchareset") {
                loadCaptcha(this)
            } else if (typeof window[callback] === "function") {
                window[callback]()
            }
        });
    } else {
        $("#tip").show("fast");
    }
    tip_active = true
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
    $("#ftip").attr("data-content", b);
    if (typeof callback != "undefined") {
        $("#ftip").show("fast", function() {
            if (callback == "recaptchareset") {
                loadCaptcha(this)
            } else if (typeof window[callback] === "function") {
                window[callback]()
            }
        });
    } else {
        $("#ftip").show("fast");
    }
    ftip_active = true
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
    b.src = nv_base_siteurl + "index.php?second=qr&u=" + encodeURIComponent($(a).data("url")) + "&l=" + $(a).data("level") + "&ppp=" + $(a).data("ppp") + "&of=" + $(a).data("of")
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

// Lắng nghe message đăng nhập Oauth
window.addEventListener('message', function(event) {
    if (event.origin !== location.origin) return;
    const data = event.data;
    if (data && data.type === 'oauthLoginCallback') {
        $('#openidResult')
            .attr('data-redirect', data.redirect)
            .attr('data-result', data.result)
            .html(data.message)
            .addClass(data.statusClass);

        $('#openidBt').click();
    }
});

// NukeViet Default Custom JS
$(function() {
    winResize();

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

    $(document).on('click', function(event) {
        if (tip_active && !($(event.target).closest("[data-toggle=tip]", this).length || $(event.target).closest("#tip", this).length || $(event.target).closest(".modal").length)) {
            tipHide()
        } else if (ftip_active && !($(event.target).closest("[data-toggle=ftip]", this).length || $(event.target).closest("#ftip", this).length || $(event.target).closest(".modal").length)) {
            ftipHide()
        } else if (winHelp && !($(event.target).closest("[data-toggle=winHelp]", this).length || $(event.target).closest(".winHelp", this).length || $(event.target).closest(".modal").length)) {
            winHelpHide()
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

    $('body').on('click', '[data-toggle=showSubBreadcrumbs]', function(e) {
        showSubBreadcrumbs(this, e)
    });

    //Search form
    $(".headerSearch button").on("click", function() {
        if ("n" == $(this).attr("data-click")) {
            return !1;
        }
        $(this).attr("data-click", "n");
        var a = $(".headerSearch input"),
            c = a.attr("maxlength"),
            b = trim(strip_tags(a.val()).replace(/[\'\"\<\>\\]/gi, '')),
            d = $(this).attr("data-minlength");
        a.val(b).parent().removeClass("has-error");
        "" == b || b.length < d || b.length > c ? (a.parent().addClass("has-error"), a.val(b).focus(), $(this).attr("data-click", "y")) : window.location.href = $(this).attr("data-url") + rawurlencode(b);
        return !1
    });
    $(".headerSearch input").on('input', function() {
        return $(this).val($(this).val().replace(/[\'\"\<\>\\]/gi, ''))
    }).on("keypress", function(a) {
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
        var script = $('<script' + ("undefined" !== typeof site_nonce ? ' nonce="' + site_nonce + '"' : '') + '>').attr("src", nv_base_siteurl + "themes/mobile_default/js/contact.js");
        $("body").append(script)
    }
    // Change site lang
    $(".nv_change_site_lang").change(function() {
        document.location = $(this).val();
    });

    //OpenID
    $("#openidBt").on("click", function() {
        openID_result();
        return !1
    });

    // Google map
    if ($('.company-address').length) {
        $('.company-map-modal').on('shown.bs.modal', function() {
            if (!$('iframe', this).length) {
                $('.modal-body', this).html('<iframe class="company-map" frameborder="0" src="' + $(this).data('src') +'" allowfullscreen></iframe>')
            }
        })
    };

    $('body').on('click', '[data-toggle=headerSearchSubmit]', function(e) {
        e.preventDefault();
        headerSearchSubmit(this)
    })
});

$(window).on("resize", function() {
    winResize();
    nvbreadcrumbs();
    //if (150 < cRangeX || 150 < cRangeY) tip_active && tipHide(), winHelp && winHelpHide()
});

// Load Social script - lasest
$(window).on('load', function() {
    nvbreadcrumbs();
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
