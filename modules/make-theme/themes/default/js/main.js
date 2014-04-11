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
});