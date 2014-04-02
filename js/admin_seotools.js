/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2013 5 : 12
 */

function nv_chang_googleplus(gid) {
	var nv_timer = nv_settimeout_disable('id_weight_' + gid, 5000);
	var new_vid = $("#id_weight_" + gid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'changeweight=1&gid=' + gid + '&new_vid=' + new_vid, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_googleplus();
	});
	return;
}

function nv_save_title(gid) {
	var new_title = document.getElementById('title_' + gid);
	var hidden_title = document.getElementById('hidden_' + gid);

	if (new_title.value == hidden_title.value) {
		return;
	}

	if (new_title.value == '') {
		alert(nv_content);
		new_title.focus();
		return false;
	}

	var nv_timer = nv_settimeout_disable('title_' + gid, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'edit=1&gid=' + gid + '&title=' + new_title.value, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_googleplus();
	});
	return;
}

function nv_show_list_googleplus() {
	if (document.getElementById('module_show_list')) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'qlist=1', function(res) {
			$("#module_show_list").html(res);

		});
	}
	return;
}

function nv_del_googleplus(gid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'del=1&gid=' + gid, function(res) {
			if (res == 'OK') {
				nv_show_list_googleplus();
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_add_googleplus() {
	var new_profile = document.getElementById('new_profile');
	if (new_profile.value == '') {
		alert(nv_content);
		new_profile.focus();
		return false;
	}

	var new_title = document.getElementById('new_title');
	if (new_title.value == '') {
		alert(nv_content);
		new_title.focus();
		return false;
	}

	var nv_timer = nv_settimeout_disable('new_title', 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'add=1&idprofile=' + new_profile.value + '&title=' + new_title.value, function(res) {
		if (res == 'OK') {
			nv_show_list_googleplus();
		} else {
			alert(nv_content);
		}
	});
	return;
}

function nv_mod_googleplus(title) {
	var nv_timer = nv_settimeout_disable('id_mod_' + title, 5000);
	var gid = $("#id_mod_" + title).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&nocache=' + new Date().getTime(), 'changemod=' + title + '&gid=' + gid, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_googleplus();
	});
	return;
}