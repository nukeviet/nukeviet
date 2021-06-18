/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

var nv_loading = '<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>';

function start_tooltip(){
	$('[data-toggle="tooltip"]').tooltip();
}

$(document).ready(function() {
	$("body").delegate( "#sysUpdRefresh", "click", function(){
		$("#sysUpd").html(nv_loading).load("index.php?" + nv_name_variable + "=webtools&" + nv_fc_variable + "=checkupdate&i=sysUpdRef&num=" + nv_randomPassword(10), function(){
			start_tooltip();
		});
	});
	$("body").delegate( "#extUpdRefresh", "click", function(){
		$("#extUpd").html(nv_loading).load("index.php?" + nv_name_variable + "=webtools&" + nv_fc_variable + "=checkupdate&i=extUpdRef&num=" + nv_randomPassword(10), function(){
			start_tooltip();
		});
	});
	$("body").delegate( ".ninfo", "click", function(){
		$(".ninfo").each(function() {
			$(this).show()
		});
		$(".wttooltip").each(function() {
			$(this).hide()
		});
		$(this).hide().next(".wttooltip").show();
		return false
	});
	$("body").delegate( ".wttooltip", "click", function(){
		$(this).hide().prev(".ninfo").show();
	});
});
