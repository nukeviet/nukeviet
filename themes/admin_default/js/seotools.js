/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2013 5 : 12
 */

function formatStringAsUriComponent(s) {
	// replace html with whitespace
	s = s.replace(/<\/?[^>]*>/gm, " ");

	// remove entities
	s = s.replace(/&[\w]+;/g, "");

	// remove 'punctuation'
	s = s.replace(/[\.\,\"\'\?\!\;\:\#\$\%\&\(\)\*\+\-\/\<\>\=\@\[\]\\^\_\{\}\|\~]/g, "");

	// replace multiple whitespace with single whitespace
	s = s.replace(/\s{2,}/g, " ");

	// trim whitespace at start and end of title
	return s.replace(/^\s+|\s+$/g, "");
}

$(document).ready(function(){
	// RPC ping
	$("#rpc .col3").click(function() {
		var a = $(this).attr("title");
		a != "" && alert(a);
		return !1
	});
});