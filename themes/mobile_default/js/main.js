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
    scrh = $(".wrap")[0].scrollHeight;
    scrh > $(".wrap").height() && $(".bttop").find("em").removeClass("fa-chevron-up").toggleClass("fa-chevron-down",!0)
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
	0 == scrt ? $(".bttop").find("em").removeClass("fa-chevron-up").toggleClass("fa-chevron-down", !0) : $(".bttop").find("em").removeClass("fa-chevron-down").toggleClass("fa-chevron-up", !0)
};

/*Change Captcha*/

function change_captcha(a) {
	$("img.captchaImg").attr("src", nv_siteroot + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
	$(a).val("");
	return !1
};

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
	!0 != a && (a = !1);
	tip_autoclose = a
}

function ftipAutoClose(a)
{
    !0 != a && (a = !1);
    ftip_autoclose = a
}

function tipShow(a, b) {
	if ($(a).is(".pa")) switchTab(".guest-sign",a);
	winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
	$("[data-toggle=tip]").removeClass("active");
	$(a).attr("data-click", "n").addClass("active");
	$("#tip").attr("data-content", b).show("fast");
	tip_active = !0
}

function ftipShow(a, b) {
	if ($(a).is(".qrcode") && "no" == $(a).attr("data-load")) return qrcodeLoad(a), !1;
	winHelp && winHelpHide();
    tip_active && tipHide();
    ftip_active && ftipHide();
	$("[data-toggle=ftip]").removeClass("active");
	$(a).attr("data-click", "n").addClass("active");
	$("#ftip").attr("data-content", b).show("fast");
	ftip_active = !0
};

// QR-code
function qrcodeLoad(a) {
	var b = new Image,
		c = $(a).data("img");
	$(b).load(function() {
		$(c).attr("src", b.src);
		$(a).attr("data-load", "yes").click()
	});
	b.src = nv_siteroot + "index.php?second=qr&u=" + encodeURIComponent($(a).data("url")) + "&l=" + $(a).data("level") + "&ppp=" + $(a).data("ppp") + "&of=" + $(a).data("of")
};

// Switch tab
function switchTab(a) {
	if ($(a).is(".current")) return !1;
	var b = $(a).data("switch").split(/\s*,\s*/),
		c = $(a).data("obj");
	$(c + " [data-switch]").removeClass("current");
	$(a).addClass("current");
	$(c + " " + b[0]).removeClass("hidden");
	for (i = 1; i < b.length; i++) $(c + " " + b[i]).addClass("hidden")
};

// ModalShow
function modalShow(a, b) {
	"" == a && (a = "&nbsp;");
	$("#sitemodal").find(".modal-title").html(a);
	$("#sitemodal").find(".modal-body").html(b);
	$("#sitemodal").modal()
}

// Build google map for block Company Info
function initializeMap(){
	var ele = 'company-map';
	var map, marker, ca, cf, a, f, z;
	ca = parseFloat($('#' + ele).data('clat'));
	cf = parseFloat($('#' + ele).data('clng'));
	a = parseFloat($('#' + ele).data('lat'));
	f = parseFloat($('#' + ele).data('lng'));
	z = parseInt($('#' + ele).data('zoom'));
	
	map = new google.maps.Map(document.getElementById(ele),{
		zoom: z,
		center: {
			lat: ca,
			lng: cf
		}
	});
	
	marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(a,f),
        draggable: false,
        animation: google.maps.Animation.DROP
    });
}

function headerSearchSubmit(t) {
	if ("n" == $(t).attr("data-click")) return !1;
	$(t).attr("data-click", "n");
	var a = $(".headerSearch input"),
		c = a.attr("maxlength"),
		b = strip_tags(a.val()),
		d = $(t).attr("data-minlength");
	a.parent().removeClass("has-error");
	"" == b || b.length < d || b.length > c ? (a.parent().addClass("has-error"), a.val(b).focus(), $(t).attr("data-click", "y")) : window.location.href = $(t).attr("data-url") + rawurlencode(b);
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
    	$(this).find("em").is(".fa-chevron-up") ? $(".wrap").animate({
    		scrollTop: 0
    	}, 800) : $(this).find("em").is(".fa-chevron-down") && $(".wrap").animate({
    		scrollTop: scrh
    	}, 800);
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
    // Google map
	if( $('#company-address').length ){
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
	}
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