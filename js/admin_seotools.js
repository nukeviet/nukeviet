/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2013 5 : 12
 */

function nv_chang_googleplus(gid) {
	var nv_timer = nv_settimeout_disable('id_weight_' + gid, 5000);
	var new_vid = document.getElementById( 'id_weight_' + gid ).options[document.getElementById('id_weight_' + gid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&changeweight=1&gid=' + gid + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_googleplus_result');
	return;
}

function nv_chang_googleplus_result(res) {
	if (res != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_googleplus();
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
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&edit=1&gid=' + gid + '&title=' + new_title.value + '&num=' + nv_randomPassword(8), '', 'nv_save_title_result');
	return;
}

function nv_save_title_result(res) {
	if (res != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_googleplus();
	return;
}

function nv_show_list_googleplus() {
	if (document.getElementById('module_show_list')) {
		nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&qlist=1&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

function nv_del_googleplus(gid) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&del=1&gid=' + gid, '', 'nv_del_googleplus_result');
	}
	return false;
}

function nv_del_googleplus_result(res) {
	if (res == 'OK') {
		nv_show_list_googleplus();
	} else {
		alert(nv_is_del_confirm[2]);
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

	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&add=1&idprofile=' + new_profile.value + '&title=' + new_title.value + '&num=' + nv_randomPassword(8), '', 'nv_add_googleplus_result');
	return;
}

function nv_add_googleplus_result(res) {
	if (res == 'OK') {
		nv_show_list_googleplus();
	} else {
		alert(nv_content);
	}
	return false;
}

function nv_mod_googleplus(title) {
	var nv_timer = nv_settimeout_disable('id_mod_' + title, 5000);
	var gid = document.getElementById( 'id_mod_' + title ).options[document.getElementById('id_mod_' + title).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=googleplus&changemod=' + title + '&gid=' + gid + '&num=' + nv_randomPassword(8), '', 'nv_mod_googleplus_result');
	return;
}

function nv_mod_googleplus_result(res) {
	if (res != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_googleplus();
	return;
}

