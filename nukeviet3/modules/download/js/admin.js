/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

 function nv_sh(sl_id, div_id) {
	var new_opt = document.getElementById(sl_id).options[document.getElementById(sl_id).selectedIndex].value;
	if (new_opt == 3)
		nv_show_hidden(div_id, 1);
	else
		nv_show_hidden(div_id, 0);
	return false;
}