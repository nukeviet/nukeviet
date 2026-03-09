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
    brcb = $('.breadcrumbs-wrap'),
    siteMenu = $("#menu-site-default"),
    NVIsMobileMenu = false;

function winResize() {
    oldWinX = winX;
    oldWinY = winY;
    winX = $(window).width();
    winY = $(window).height();
    docX = $(document).width();
    docY = $(document).height();
    cRangeX = Math.abs(winX - oldWinX);
    cRangeY = Math.abs(winY - oldWinY);
}

function fix_banner_center() {
    var a = Math.round((winX - 1330) / 2);
    0 <= a ? ($("div.fix_banner_left").css("left", a + "px"), $("div.fix_banner_right").css("right", a + "px"), a = Math.round((winY - $("div.fix_banner_left").height()) / 2), 0 >= a && (a = 0), $("div.fix_banner_left").css("top", a + "px"), a = Math.round((winY - $("div.fix_banner_right").height()) / 2), 0 >= a && (a = 0), $("div.fix_banner_right").css("top", a + "px"), $("div.fix_banner_left").show(), $("div.fix_banner_right").show()) : ($("div.fix_banner_left").hide(), $("div.fix_banner_right").hide())
}

function checkWidthMenu() {
    NVIsMobileMenu = (theme_responsive && "absolute" == $("#menusite").css("position"));
    NVIsMobileMenu ? (
        $("li.dropdown ul", siteMenu).removeClass("dropdown-menu").addClass("dropdown-submenu"),
        $("li.dropdown a", siteMenu).addClass("dropdown-mobile"),
        $("ul li a.dropdown-toggle", siteMenu).addClass("dropdown-mobile"),
        $("li.dropdown ul li a", siteMenu).removeClass("dropdown-mobile")
    ) : (
        $("li.dropdown ul", siteMenu).addClass("dropdown-menu").removeClass("dropdown-submenu"),
        $("li.dropdown a", siteMenu).removeClass("dropdown-mobile"),
        $("li.dropdown ul li a", siteMenu).removeClass("dropdown-mobile"),
        $("ul li a.dropdown-toggle", siteMenu).removeClass("dropdown-mobile")
    )
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
    tip_active = true;
}

function ftipShow(a, b, callback) {
    if ($(a).is(".qrcode") && "no" == $(a).attr("data-load")) {
        return qrcodeLoad(a), !1;
    }
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
    ftip_active = true;
};

function openID_result() {
    var resElement = $("#openidResult");
    resElement.fadeIn();
    setTimeout(function() {
        if (resElement.data('redirect') != '') {
            window.location.href = resElement.data('redirect');
        } else if (resElement.data('result') == 'success') {
            location.reload();
        } else {
            resElement.hide(0).html('').data('result', '').data('redirect', '');
        }
    }, 5000);
}

// QR-code
function qrcodeLoad(a) {
    var b = new Image,
        c = $(a).data("img");
    $(b).on('load', function() {
        $(c).attr("src", b.src);
        $(a).attr("data-load", "yes").click()
    });
    b.src = nv_base_siteurl + "index.php?second=qr&u=" + encodeURIComponent($(a).data("url"))
};

// Switch tab
function switchTab(a) {
    if ($(a).is(".current")) {
        return !1;
    }
    var b = $(a).data("switch").split(/\s*,\s*/),
        c = $(a).data("obj");
    $(c + " [data-switch]").removeClass("current");
    $(a).addClass("current");
    $(c + " " + b[0]).removeClass("hidden");
    for (i = 1; i < b.length; i++) {
        $(c + " " + b[i]).addClass("hidden")
    }
};

// Breadcrumbs
function nvbreadcrumbs() {
    if (brcb.length) {
        var g = $(".display", brcb).innerWidth() - 40,
            b = $(".breadcrumbs", brcb),
            h = $(".temp-breadcrumbs", brcb),
            e = $(".subs-breadcrumbs", brcb),
            f = $(".show-subs-breadcrumbs", brcb),
            a = [],
            c = !1;
        h.find("a").each(function() {
            a.push([$(this).attr("title"), $(this).attr("href")]);
        });
        b.html("");
        e.html("");
        for (i = a.length - 1; 0 <= i; i--) {
            if (!c) {
                var d = 0;
                b.prepend('<li id="brcr_' + i + '"><a href="' + a[i][1] + '"><span>' + a[i][0] + "</span></a></li>");
                b.find("li").each(function() {
                    d += $(this).outerWidth(!0);
                });
                d > g && (c = !0, $("#brcr_" + i, b).remove());
            }
            c && e.append('<li><a href="' + a[i][1] + '"><span><em class="fa fa-long-arrow-up"></em> ' + a[i][0] + "</span></a></li>");
        }
        c ? f.removeClass("hidden") : f.addClass("hidden");
    }
}

function showSubBreadcrumbs(a) {
    var c = $(".subs-breadcrumbs", brcb);
    $("em", a).is(".fa-angle-right") ? $("em", a).removeClass("fa-angle-right").addClass("fa-angle-down") : $("em", a).removeClass("fa-angle-down").addClass("fa-angle-right");
    c.toggleClass("open");
    $(document).on("click", function() {
        $("em", a).is(".fa-angle-down") && ($("em", a).removeClass("fa-angle-down").addClass("fa-angle-right"), c.removeClass("open"));
    });
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

$(function() {
    winResize();
    fix_banner_center();

    // Smooth scroll to top
    $("#totop,#bttop,.bttop").click(function() {
        $("html,body").animate({
            scrollTop: 0
        }, 800);
        return !1
    });

    $('body').on('click', '[data-toggle=showSubBreadcrumbs]', function(e) {
        e.preventDefault();
        showSubBreadcrumbs(this)
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
    $(".headerSearch input").on('input', function(e) {
        return $(this).val($(this).val().replace(/[\'\"\<\>\\]/gi, ''))
    }).on("keypress", function(a) {
        13 != a.which || a.shiftKey || (a.preventDefault(), $(".headerSearch button").trigger("click"))
    });

    // Show confirm message on leave, reload page
    $("form.confirm-reload").change(function() {
        $(window).bind("beforeunload", function() {
            return nv_msgbeforeunload
        })
    });

    // Tooltip
    $(".form-tooltip").tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
    $("[data-rel='tooltip'][data-content!='']").removeAttr("title").tooltip({
        container: "body",
        html: !0,
        title: function() {
            return ("" == $(this).data("img") || !$(this).data("img") ? "" : '<img class="img-thumbnail pull-left" src="' + $(this).data("img") + '" width="90" />') + $(this).data("content")
        }
    });

    // Change site lang
    $(".nv_change_site_lang").change(function() {
        document.location = $(this).val()
    });

    // Xử lý menu bootstrap nếu có
    if (siteMenu.length) {
        $(".dropdown .caret", siteMenu).on('click', function(e) {
            if (NVIsMobileMenu) {
                e.preventDefault();
                var cMenu = $(this).parent().parent();
                var cMenuOpen = cMenu.is('.open');
                $(".dropdown", siteMenu).removeClass("open");
                if (!cMenuOpen) {
                    cMenu.addClass("open");
                }
            }
        });

        $(".dropdown", siteMenu).hover(function() {
            if (!NVIsMobileMenu) {
                $(this).addClass("open");
            }
        }, function() {
            if (!NVIsMobileMenu) {
                $(this).removeClass("open");
            }
        });

        $("a", siteMenu).hover(function() {
            $(this).attr("rel", $(this).attr("title"));
            $(this).removeAttr("title")
        }, function() {
            $(this).attr("title", $(this).attr("rel"));
            $(this).removeAttr("rel")
        });
    }

    $(document).on('click', function(event) {
        if (tip_active && !(
            $(event.target).closest("[data-toggle=tip]", this).length ||
            $(event.target).closest("#tip", this).length ||
            $(event.target).closest(".modal").length ||
            $(event.target).closest(".cr-md").length ||
            $(event.target).closest(".cr-cap").length
        )) {
            tipHide();
        } else if (ftip_active && !(
            $(event.target).closest("[data-toggle=ftip]", this).length ||
            $(event.target).closest("#ftip", this).length ||
            $(event.target).closest(".modal").length ||
            $(event.target).closest(".cr-md").length ||
            $(event.target).closest(".cr-cap").length
        )) {
            ftipHide();
        }
    });

    $("[data-toggle=tip], [data-toggle=ftip]").click(function(e) {
        e.preventDefault();
        var a = $(this).attr("data-target"),
            b = $(this).attr("data-toggle"),
            c = $("#" + b).attr("data-content");
        var callback = $(this).data("callback");
        if (a != c) {
            "" != c && $('[data-target="' + c + '"]').attr("data-click", "y");
            $("#" + b + " .bg").html($(a).html());
            "tip" == b ? tipShow(this, a, callback) : ftipShow(this, a, callback)
        } else {
            if ("n" == $(this).attr("data-click")) {
                "tip" == b ? tipHide() : ftipHide()
            } else {
                "tip" == b ? tipShow(this, a) : ftipShow(this, a)
            }
        }
    });

    //OpenID
    $("#openidBt").on("click", function() {
        openID_result();
        return !1
    });

    // Chọn giao diện
    $('[data-toggle="nvchoosetheme"]').on('change', function() {
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=nv-choose-theme',
            cache: false,
            data: {
                theme: $(this).val(),
                tokend: $(this).data('tokend')
            },
            dataType: "html"
        }).done(function() {
            location.reload();
        });
    });

    // Google map
    if ($('.company-address').length) {
        $('.company-map-modal').on('shown.bs.modal', function() {
            if (!$('iframe', this).length) {
                $('.modal-body', this).html('<iframe class="company-map" frameborder="0" src="' + $(this).data('src') +'" allowfullscreen></iframe>')
            }
        })
    };

    // Bật hiệu ứng CSS3
    $('body').addClass('enable-animate');
});

$(window).on("resize", function() {
    winResize();
    fix_banner_center();
    nvbreadcrumbs();
    //if (150 < cRangeX || 150 < cRangeY) tipHide(), ftipHide()
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
