/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_chang_status(vid) {
	var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
	var new_status = $("#change_status_" + vid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_status&nocache=' + new Date().getTime(), 'id=' + vid + '&new_status=' + new_status, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			window.location.href = strHref;
		}
	});
	return;
}

function nv_del_department(vid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_department&nocache=' + new Date().getTime(), 'id=' + vid, function(res) {
			if (res == 'OK') {
				window.location.href = strHref;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_del_submit(oForm, cbName) {
	var ts = 0;

	if (oForm[cbName].length) {
		for (var i = 0; i < oForm[cbName].length; i++) {
			if (oForm[cbName][i].checked == true) {
				ts = 1;
				break;
			}
		}
	} else {
		if (oForm[cbName].checked == true) {
			ts = 1;
		}
	}

	if (ts) {
		if (confirm(nv_is_del_confirm[0])) {
			oForm.submit();
		}
	}
	return false;
}

function nv_delall_submit() {
	if (confirm(nv_is_del_confirm[0])) {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&t=3';
	}
	return false;
}

function nv_del_mess(mid) {
	if (confirm(nv_is_del_confirm[0])) {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&t=1&id=' + mid;
	}
	return false;
}