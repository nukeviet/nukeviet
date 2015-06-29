/**
 * @Project NUKEVIET 4.x
 * @Author VINADES ( contact@vinades.vn )
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2 - 10 - 2010 16 : 3
 */

function nv_show_list_mods() {
	if (document.getElementById('list_mods')) {
		$("#list_mods").load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list&nocache=' + new Date().getTime());
	}
	return;
}

function nv_chang_func_in_submenu(func_id) {
	var nv_timer = nv_settimeout_disable('chang_func_in_submenu_' + func_id, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&nocache=' + new Date().getTime(), 'id=' + func_id, function(res) {
		var r_split = res.split("_");
		var sl = document.getElementById('chang_func_in_submenu_' + r_split[1]);
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			if (sl.checked == true)
				sl.checked = false;
			else
				sl.checked = true;
			clearTimeout(nv_timer);
			sl.disabled = true;
		}
	});
	return;
}

function nv_chang_weight(modname) {
	var nv_timer = nv_settimeout_disable('change_weight_' + modname, 5000);
	var new_weight = $("#change_weight_" + modname).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&nocache=' + new Date().getTime(), 'mod=' + modname + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("_");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_mods();
	});
	return;
}

function nv_chang_act(modname) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_act_' + modname, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act&nocache=' + new Date().getTime(), 'mod=' + modname, function(res) {
			var r_split = res.split("_");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			nv_show_list_mods();
		});
	} else {
		var sl = document.getElementById('change_act_' + modname);
		sl.checked = (sl.checked == true) ? false : true;
	}
	return;
}

function nv_recreate_mod(modname) {
	if (confirm(nv_is_recreate_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=recreate_mod&nocache=' + new Date().getTime(), 'mod=' + modname, function(res) {
			var r_split = res.split("_");
			if (r_split[0] != 'OK') {
				alert(nv_is_recreate_confirm[2]);
			} else {
				alert(nv_is_recreate_confirm[1]);
				nv_show_list_mods();
			}
		});
	}
	return;
}

function nv_mod_del(modname) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(), 'mod=' + modname, function(res) {
			var r_split = res.split("_");
			if (r_split[0] == 'OK') {
				window.location.href = script_name + '?' + nv_name_variable + '=modules&' + nv_randomPassword(6) + '=' + nv_randomPassword(8);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_show_funcs(show_id) {
	if (document.getElementById(show_id)) {
		$("#" + show_id).load(strHref + "&aj=show_funcs&nocache=" + new Date().getTime());
	}
	return;
}

function nv_chang_func_weight(func_id) {
	var nv_timer = nv_settimeout_disable('change_weight_' + func_id, 5000);
	var new_weight = $("#change_weight_" + func_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_weight&nocache=' + new Date().getTime(), 'fid=' + func_id + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_funcs(r_split[1]);
	});
	return;
}

function nv_chang_func_in_submenu(func_id) {
	var nv_timer = nv_settimeout_disable('chang_func_in_submenu_' + func_id, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&nocache=' + new Date().getTime(), 'id=' + func_id, function(res) {
		var r_split = res.split("_");
		var sl = document.getElementById('chang_func_in_submenu_' + r_split[1]);
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			if (sl.checked == true)
				sl.checked = false;
			else
				sl.checked = true;
			clearTimeout(nv_timer);
			sl.disabled = true;
		}
	});
	return;
}

function nv_change_custom_name(func_id, containerid) {
	$("#" + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&id=' + func_id + '&nocache=' + new Date().getTime());
	return;
}

function nv_change_custom_name_submit(func_id, custom_name_id) {
	var new_custom_name = rawurlencode(document.getElementById(custom_name_id).value);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&nocache=' + new Date().getTime(), 'id=' + func_id + '&save=1&func_custom_name=' + new_custom_name, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		} else {
			nv_show_funcs(r_split[1]);
			nv_action_cancel(r_split[2]);
		}
	});
	return;
}

function nv_change_alias(func_id, containerid) {
	$("#" + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_alias&id=' + func_id + '&nocache=' + new Date().getTime());
	return;
}

function nv_change_alias_submit(func_id, custom_name_id) {
	var new_custom_name = rawurlencode(document.getElementById(custom_name_id).value);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_alias&nocache=' + new Date().getTime(), 'id=' + func_id + '&save=1&fun_alias=' + new_custom_name, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		} else {
			nv_show_funcs(r_split[1]);
			nv_action_cancel(r_split[2]);
		}
	});
	return;
}

function nv_action_cancel(containerid) {
	document.getElementById(containerid).innerHTML = '';
	return;
}

function nv_chang_bl_weight(bl_id) {
	var nv_timer = nv_settimeout_disable('change_bl_weight_' + bl_id, 5000);
	var new_weight = $("#change_bl_weight_" + bl_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block_weight&nocache=' + new Date().getTime(), 'id=' + bl_id + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_bl_list(r_split[1], r_split[2], r_split[3]);
	});
	return;
}

function nv_show_bl(bl_id, containerid) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=show_block&nocache=' + new Date().getTime(), 'id=' + bl_id, function(res) {
		$("#" + containerid).html(res);
	});
	return;
}

function nv_del_bl(bl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.get(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block&id=' + bl_id + '&nocache=' + new Date().getTime(), function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				nv_bl_list(r_split[1], r_split[2], r_split[3]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}