/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */
var tip_active = !1,
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
	wrapWidth = 0,
	winHelp = !1;

function winResize() {
	oldWinX = winX;
	oldWinY = winY;
	winX = $(window).width();
	winY = $(window).height();
	docX = $(document).width();
	docY = $(document).height();
	cRangeX = Math.abs(winX - oldWinX);
	cRangeY = Math.abs(winY - oldWinY);
	scrh = $(".wrap")[0].scrollHeight
}

function winHelpShow() {
	if (0 != winHelp) return !1;
	tip_active && tipHide();
	ftip_active && ftipHide();
	winHelp = !0;
	$("#winHelp").find(".logo-small").html($(".logo").html());
	$("#winHelp").show(0)
}

function winHelpHide() {
	if (1 != winHelp) return !1;
	winHelp = !1;
	$("#winHelp").hide()
}

function contentScrt() {
	winHelp && winHelpHide();
	tip_active && tipHide();
	ftip_active && ftipHide();
	oldScrt = scrt;
	scrt = $(".wrap").scrollTop();
	scrtRangeY = scrt - oldScrt;
	0 < scrtRangeY ? ($(".footer").toggleClass("pos-rel", !0), $("#mobilePage").is(".fixed") && $("#mobilePage").removeClass("fixed")) : ($(".footer").removeClass("pos-rel"), $("#mobilePage").not(".fixed") && $("#mobilePage").toggleClass("fixed", !0));
	0 == scrt ? $(".bttop").find("em").removeClass("fa-chevron-up").toggleClass("fa-refresh", !0) : $(".bttop").find("em").removeClass("fa-refresh").toggleClass("fa-chevron-up", !0)
}

/*Change Captcha*/
function change_captcha(a) {
	$("img.captchaImg").attr("src", nv_base_siteurl + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
	"undefined" != typeof a && "" != a && $(a).val("");
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

function tipShow(a, b) {
	$(a).is(".pa") && switchTab(".guest-sign", a);
	winHelp && winHelpHide();
	tip_active && tipHide();
	ftip_active && ftipHide();
	$("[data-toggle=tip]").removeClass("active");
	$(a).attr("data-click", "n").addClass("active");
	$("#tip").attr("data-content", b).show("fast");
	tip_active = !0
}

function ftipShow(a, b) {
	if ($(a).is(".qrcode") && "yes" != $(a).attr("data-load")) return qrcodeLoad(a), !1;
    if ($(a).is("#contactButton") && "yes" != $(a).attr("data-load")) return ctbtLoad($(a)), !1;
	winHelp && winHelpHide();
	tip_active && tipHide();
	ftip_active && ftipHide();
	$("[data-toggle=ftip]").removeClass("active");
	$(a).attr("data-click", "n").addClass("active");
	$("#ftip").attr("data-content", b).show("fast");
	ftip_active = !0
}

//Contact Button
function ctbtLoad(a) {
	var b = $(a.data("target") + " .panel-body");
	"yes" != a.attr("data-load") && $.ajax({
		type: "POST",
		cache: !1,
		url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=contact",
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
	var s = $(this).attr("src");
	nv_open_browse(a, "NVOPID", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,location=no,status=no");
	return !1;
}

function openID_result() {
	$("#openidResult").fadeIn();
	setTimeout(function() {
		"" != $("#openidResult").attr("data-redirect") ? window.location.href = $("#openidResult").attr("data-redirect") : "success" == $("#openidResult").attr("data-result") ? window.location.href = window.location.href : $("#openidResult").hide(0).text("").attr("data-result", "").attr("data-redirect", "")
	}, 5E3);
	return !1
}

// QR-code
function qrcodeLoad(a) {
	var b = new Image,
		c = $(a).data("img");
	$(b).load(function() {
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

//Form Ajax-login
function loginForm()
{
    if(nv_is_user == 1) return!1;
    $.ajax({
        type: 'POST',
		url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=login',
		cache: !1,
        data: '&nv_ajax=1',
		dataType: "html",
	}).done(function(a) {
		modalShow('', a)
	});
    return!1
}

// ModalShow
function modalShow(a, b) {
	"" != a && 'undefined' != typeof a && $("#sitemodal .modal-content").prepend('<div class="modal-header"><h2 class="modal-title">' + a + '</h2></div>');
	$("#sitemodal").find(".modal-title").html(a);
	$("#sitemodal").find(".modal-body").html(b);
    $('#sitemodal').on('hidden.bs.modal', function () {
            $("#sitemodal .modal-content").find(".modal-header").remove()
		});
    $("#sitemodal").modal({backdrop: "static"})
}

function modalShowByObj(a)
{
    var b = $(a).attr("title"), c = $(a).html();
    modalShow(b, c)
}

// Build google map for block Company Info
function initializeMap() {
	var a, b, c, d, e;
	a = parseFloat($("#company-map").data("clat"));
	b = parseFloat($("#company-map").data("clng"));
	c = parseFloat($("#company-map").data("lat"));
	d = parseFloat($("#company-map").data("lng"));
	e = parseInt($("#company-map").data("zoom"));
	a = new google.maps.Map(document.getElementById("company-map"), {
		zoom: e,
		center: {
			lat: a,
			lng: b
		}
	});
	new google.maps.Marker({
		map: a,
		position: new google.maps.LatLng(c, d),
		draggable: !1,
		animation: google.maps.Animation.DROP
	})
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

// NukeViet Default Custom JS
$(function() {
	winResize();
    // Modify all empty link
	$('a[href="#"], a[href=""]').attr("href", "javascript:void(0);");
    // Smooth scroll to top
	$(".bttop").click(function() {
    	if($(this).find("em").is(".fa-chevron-up"))
        {
            $(".wrap").animate({scrollTop: 0}, 800);
        }
        else if($(this).find("em").is(".fa-refresh"))
        {
            window.location.href = window.location.href
        }
    	return !1
    });
	$(document).on("keydown", function(a) {
		27 === a.keyCode && (tip_active && tip_autoclose && tipHide(), ftip_active && ftip_autoclose && ftipHide(), winHelp && winHelpHide())
	});
	$(document).on("click", function() {
		tip_active && tip_autoclose && tipHide();
        ftip_active && ftip_autoclose && ftipHide();
		winHelp && winHelpHide()
	});
	$("#tip, #ftip, #winHelp .winHelp").on("click", function(a) {
		a.stopPropagation()
	});
	$("[data-toggle=tip], [data-toggle=ftip]").click(function() {
		var a = $(this).attr("data-target"),
			c = $(a).html(),
            d = $(this).attr("data-toggle"),
			b = "tip" == d ? $("#tip").attr("data-content") : $("#ftip").attr("data-content");
		a != b ? ("" != b && $('[data-target="' + b + '"]').attr("data-click", "y"), "#metismenu" == a && (c = $("#headerSearch").html() + c), "tip" == d ? ($("#tip").html(c), "#metismenu" == a && $("#tip .metismenu ul").metisMenu({
			toggle: !1
		}), tipShow(this, a)) : ($("#ftip").html(c), ftipShow(this, a))) : "n" == $(this).attr("data-click") ? "tip" == d ? tipHide() : ftipHide() : "tip" == d ? tipShow(this, a) : ftipShow(this, a);
		return !1
	});
	$("[data-toggle=winHelp]").click(function() {
		winHelp ? winHelpHide() : winHelpShow();
		return !1
	});
    //Search form
	$(".headerSearch button").on("click", function() {
		if ("n" == $(this).attr("data-click")) return !1;
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
	$(".wrap").on("scroll", function() {
		contentScrt()
	});
    //FeedBack Button
    if( $('#contactButton').length ){
        var script = $('<script type="text/javascript">').attr("src",nv_base_siteurl + "themes/mobile_default/js/contact.js");
        $("body").append(script);
    }
    // Google map
	if( $('#company-address').length ){
		$('#company-address').click(function(e){
			e.preventDefault();
			$('#company-map-modal').modal('show');
		});
		$('#company-map-modal').on('shown.bs.modal', function(){
			if( ! $('#googleMapAPI').length ){
				var script = document.createElement('script');
				script.type = 'text/javascript';
				script.id = 'googleMapAPI';
				script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initializeMap';
				document.body.appendChild(script);
			}else{
				initializeMap();
			}
		})
	};
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
	$("[data-dismiss=alert]").on("click", function(a) {
		$(this).is(".close") && $(this).parent().remove()
	});
	//OpenID
	$("#openidBt").on("click", function() {
		openID_result();
		return !1
	})
});

// Fix bootstrap multiple modal
$(document).on({
	'show.bs.modal': function () {
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
	if (150 < cRangeX || 150 < cRangeY) tip_active && tipHide(), winHelp && winHelpHide()
});

// Load Social script - lasest
$(window).load(function() {
    (0 < $(".fb-share-button").length || 0 < $(".fb-like").length) && (1 > $("#fb-root").length && $("body").append('<div id="fb-root"></div>'), function(a, b, c) {
        var d = a.getElementsByTagName(b)[0];
        var fb_app_id = ( $('[property="fb:app_id"]').length > 0 ) ? '&appId=' + $('[property="fb:app_id"]').attr("content") : '';
        var fb_locale = ( $('[property="og:locale"]').length > 0 ) ? $('[property="og:locale"]').attr("content") : ((nv_lang_data=="vi") ? 'vi_VN' : 'en_US');
        a.getElementById(c) || (a = a.createElement(b), a.id = c, a.src = "//connect.facebook.net/" + fb_locale + "/all.js#xfbml=1" + fb_app_id, d.parentNode.insertBefore(a, d));
    }(document, "script", "facebook-jssdk"));

    0 < $(".g-plusone").length && (window.___gcfg = {
        lang: nv_lang_data
    }, function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.async = !0;
        a.src = "https://apis.google.com/js/plusone.js";
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }());
    
    0 < $(".twitter-share-button").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "http://platform.twitter.com/widgets.js";
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();
});