/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

// NukeViet Default Custom JS
$(document).ready(function(){
	// Modify all empty link
	$('a[href="#"], a[href=""]').attr('href','javascript:void(0);');

	// Smooth scroll to top
	$('#totop').click(function(){
		$('body').animate({scrollTop : 0}, 'slow');
		return false;
	});

	// Trigger tooltip
	$('.form-tooltip').tooltip({
		selector: "[data-toggle=tooltip]",
		container: "body"
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
	
	$('#bttop').click(function(){
		$('body,html').animate({scrollTop:0},800);
	});
});