/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_admin_logout() {
	confirm(nv_admlogout_confirm[0]) && $.get(nv_base_siteurl + "index.php?second=admin_logout&js=1&nocache=" + (new Date).getTime(), function(b) {
		1 == b && (alert(nv_admlogout_confirm[1]), window.location.href = 1 == nv_area_admin ? nv_base_siteurl + "index.php" : strHref)
	});
	return !1
}
function nv_sh(b, a) {
	3 == $("#" + b).val() ? nv_show_hidden(a, 1) : nv_show_hidden(a, 0);
	return !1
}
$(function() {
	if ("undefined" != typeof drag_block && 0 != drag_block) {
		$("a.delblock").click(function() {
			confirm(block_delete_confirm) && $.post(post_url + "blocks_del", "bid=" + $(this).attr("name"), function(a) {
				alert(a);
				window.location.href = selfurl
			})
		});
		$("a.outgroupblock").click(function() {
			confirm(block_outgroup_confirm) && $.post(post_url + "front_outgroup", "func_id=" + func_id + "&bid=" + $(this).attr("name"), function(a) {
				alert(a)
			})
		});
		$("a.block_content").click(function() {
			nv_open_browse(post_url + "block_content&selectthemes=" + module_theme + "&tag=" + $(this).attr("id") + "&bid=" + $(this).attr("name") + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no")
		});
		var b = !1;
		$(".column").sortable({
			connectWith: ".column",
			opacity: .8,
			cursor: "move",
			receive: function() {
				b = !0;
				$.post(post_url + "sort_order", $(this).sortable("serialize") + "&position=" + $(this).attr("id") + "&func_id=" + func_id, function(a) {
					a == "OK_" + func_id ? $("div#toolbar>ul.info").html('<li><span style="color:#ff0000;padding-left:150px;font-weight:700;">' + blocks_saved + "</span></li>").fadeIn(1E3) : alert(blocks_saved_error)
				})
			},
			stop: function() {
				0 == b && $.post(post_url + "sort_order", $(this).sortable("serialize") + "&func_id=" + func_id, function(a) {
					a == "OK_" + func_id ? $("div#toolbar>ul.info").html('<span style="color:#ff0000;padding-left:150px;font-weight:700;">' + blocks_saved + "</span>").fadeIn(1E3) : alert(blocks_saved_error)
				})
			}
		});
		$(".column").disableSelection()
	}
});