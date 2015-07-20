/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */
// NukeViet Default Custom JS
var myTimerPage = "",
	myTimersecField = "",
	tip_active = !1,
	winX = 0,
	winY = 0,
	docX = 0,
	docY = 0;

function winResize() {
	winX = $(window).width();
	winY = $(window).height();
	docX = $(document).width();
	docY = $(document).height()
}

function fix_banner_center() {
	var a = Math.round((winX - 1330) / 2);
	0 <= a ? ($("div.fix_banner_left").css("left", a + "px"), $("div.fix_banner_right").css("right", a + "px"), a = Math.round((winY - $("div.fix_banner_left").height()) / 2), 0 >= a && (a = 0), $("div.fix_banner_left").css("top", a + "px"), a = Math.round((winY - $("div.fix_banner_right").height()) / 2), 0 >= a && (a = 0), $("div.fix_banner_right").css("top", a + "px"), $("div.fix_banner_left").show(), $("div.fix_banner_right").show()) : ($("div.fix_banner_left").hide(), $("div.fix_banner_right").hide())
}

function timeoutsesscancel() {
	clearInterval(myTimersecField);
	$.ajax({
		url: nv_siteroot + "index.php?second=statimg",
		cache: !1
	}).done(function() {
		$("#timeoutsess").hide();
		myTimerPage = setTimeout(function() {
			timeoutsessrun()
		}, nv_check_pass_mstime)
	})
}

function timeoutsessrun() {
	clearInterval(myTimerPage);
	document.getElementById("secField").innerHTML = 60;
	jQuery("#timeoutsess").show();
	var a = (new Date).getTime();
	myTimersecField = setInterval(function() {
		var b = (new Date).getTime(),
			b = 60 - Math.round((b - a) / 1E3);
		0 <= b ? document.getElementById("secField").innerHTML = b : -3 > b && (clearInterval(myTimersecField), $(window).unbind(), window.location.reload())
	}, 1E3)
}

function checkWidthMenu() {
	theme_responsive && "absolute" == $("#menusite").css("position") ? ($("li.dropdown ul").removeClass("dropdown-menu"), $("li.dropdown ul").addClass("dropdown-submenu"), $("li.dropdown a").addClass("dropdown-mobile"), $("#menu-site-default ul li a.dropdown-toggle").addClass("dropdown-mobile"), $("li.dropdown ul li a").removeClass("dropdown-mobile")) : ($("li.dropdown ul").addClass("dropdown-menu"), $("li.dropdown ul").removeClass("dropdown-submenu"), $("li.dropdown a").removeClass("dropdown-mobile"), $("li.dropdown ul li a").removeClass("dropdown-mobile"), $("#menu-site-default ul li a.dropdown-toggle").removeClass("dropdown-mobile"));
	$("#menu-site-default .dropdown").hover(function() {
		$(this).addClass("open")
	}, function() {
		$(this).removeClass("open")
	})
}

function tipHide() {
	$("[data-toggle=tip]").attr("data-click", "y").removeClass("active");
	$("#tip").hide();
	tip_active = !1
}

function tipShow(a, b) {
	$("[data-toggle=tip]").removeClass("active");
	$(a).attr("data-click", "n").addClass("active");
	$("#tip").attr("data-content", b).show("fast");
	tip_active = !0
}
$(function() {
	winResize();
	fix_banner_center();
	// Modify all empty link
	$('a[href="#"], a[href=""]').attr("href", "javascript:void(0);");
	// Smooth scroll to top
	$("#totop,#bttop,.bttop").click(function() {
		$("html,body").animate({
			scrollTop: 0
		}, 800);
		return !1
	});
	//Search form
	$(".headerSearch button").on("click", function() {
		if ("n" == $(this).attr("data-click")) return !1;
		$(this).attr("data-click", "n");
		var a = $(".headerSearch input"),
			b = strip_tags(a.val()),
			c = a.attr("maxlength"),
			d = $(this).attr("data-minlength");
		a.parent().removeClass("has-error");
		"" == b || b.length < d || b.length > c ? (a.parent().addClass("has-error"), a.val(b).focus(), $(this).attr("data-click", "y")) : window.location.href = $(this).attr("data-url") + rawurlencode(b);
		return !1
	});
	$(".headerSearch input").on("keypress", function(a) {
		13 != a.which || a.shiftKey || (a.preventDefault(), $(".headerSearch button").trigger("click"))
	});
	// Show messger timeout login users 
	nv_is_user && (myTimerPage = setTimeout(function() {
		timeoutsessrun()
	}, nv_check_pass_mstime));
	// Show confirm message on leave, reload page
	$("form.confirm-reload").change(function() {
		$(window).bind("beforeunload", function() {
			return nv_msgbeforeunload
		})
	});
	// Trigger tooltip
	$(".form-tooltip").tooltip({
		selector: "[data-toggle=tooltip]",
		container: "body"
	});
	// Change site lang
	$(".nv_change_site_lang").change(function() {
		document.location = $(this).val()
	});
	// Menu bootstrap
	$("#menu-site-default a").hover(function() {
		$(this).attr("rel", $(this).attr("title"));
		$(this).removeAttr("title")
	}, function() {
		$(this).attr("title", $(this).attr("rel"));
		$(this).removeAttr("rel")
	});
	//Tip
	/*$("[data-toggle=collapse]").click(function(a) {
		tipHide();
		$(".header-nav").is(".hidde768") ? setTimeout(function() {
			$(".header-nav").removeClass("hidde768")
		}, 500) : $(".header-nav").addClass("hidde768")
	});*/
	$(document).on("keydown", function(a) {
		27 === a.keyCode && tip_active && tipHide()
	});
	$(document).on("click", function() {
		tip_active && tipHide()
	});
	$("#tip").on("click", function(a) {
		a.stopPropagation()
	});
	$("[data-toggle=tip]").click(function() {
		var a = $(this).attr("data-target"),
			b = $(a).html(),
			c = $("#tip").attr("data-content");
		a != c ? ("" != c && $("[data-target=" + c + "]").attr("data-click", "y"), $("#tip .bg").html(b), tipShow(this, a)) : "n" == $(this).attr("data-click") ? tipHide() : tipShow(this, a);
		return !1
	})
});
$(window).on("resize", function() {
	winResize();
	fix_banner_center();
	tipHide()
})
$(window).load(function() {
	var a = $("#QR-code");
	a && a.attr("src", nv_siteroot + "index.php?second=qr&u=" + encodeURIComponent(a.data("url")) + "&l=" + a.data("level") + "&ppp=" + a.data("ppp") + "&of=" + a.data("of"))
});