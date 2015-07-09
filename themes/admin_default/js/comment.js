/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_change_active( cid )
{
	var new_status = $('#change_active_' + cid).is(':checked') ? 1 : 0;
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_active_' + cid, 3000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_active&nocache=' + new Date().getTime(), 'change_active=1&cid=' + cid + '&new_status=' + new_status, function(res) {

		});
	}
	else
	{
		$('#change_active_' + cid).prop('checked', new_status ? false : true );
	}
}