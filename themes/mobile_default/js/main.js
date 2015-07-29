/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */
/*Change Captcha*/
function change_captcha(a) {
    $("img.captchaImg").attr("src",nv_siteroot + "index.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
	$(a).val("");
	return !1
};

// NukeViet Default Custom JS
$(document).ready(function(){
	// Modify all empty link
	$('a[href="#"], a[href=""]').attr('href','javascript:void(0);');

	// Smooth scroll to top
	$(".bttop").click(function() {
		$("html,body").animate({
			scrollTop: 0
		}, 800);
		return !1
	});

	$('#btn-search').click(function(){
		if( $('#search').css('display') == 'none' ){
			$('#search').slideDown('fast');
			$('#nav').slideUp('fast');
			$('#topmenu_search_query').focus();
		}
		else{
			$('#search').slideUp('fast');
		}
	});

	$('#btn-bars').click(function(){
		if( $('#nav').css('display') == 'none' ){
			$('#nav').slideDown('fast');
			$('#search').slideUp('fast');
		}
		else{
			$('#nav').slideUp('fast');
		}
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
});