/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_admin_logout() {
	if (confirm(nv_admlogout_confirm[0])) {
		$.get(nv_siteroot + 'index.php?second=admin_logout&js=1&nocache=' + new Date().getTime(), function(res) {
			if (res == 1) {
				alert(nv_admlogout_confirm[1]);
				if (nv_area_admin == 1) {
					window.location.href = nv_siteroot + 'index.php';
				} else {
					window.location.href = strHref;
				}
			}
		});
	}
	return false;
}

function nv_sh(sl_id, div_id) {
	var new_opt = $("#" + sl_id).val();
	if (new_opt == 3)
		nv_show_hidden(div_id, 1);
	else
		nv_show_hidden(div_id, 0);
	return false;
}