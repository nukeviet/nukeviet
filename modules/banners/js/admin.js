/**
 * @Project NUKEVIET 4.x
 * @Author  VINADES ( contact@vinades.vn )
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 - 11 - 2010 20 : 50
 */

function nv_show_cl_list(containerid) {
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cl_list&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_cl_del(cl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				nv_show_cl_list(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// ---------------------------------------

function nv_cl_del2(cl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + r_split[2];
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// ---------------------------------------

function nv_chang_act(cl_id, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
				var sl = document.getElementById(r_split[1]);
				if (sl.checked == true)
					sl.checked = false;
				else
					sl.checked = true;
				clearTimeout(nv_timer);
				sl.disabled = true;
			}
		});
	} else {
		var sl = document.getElementById(checkbox_id);
		if (sl.checked == true)
			sl.checked = false;
		else
			sl.checked = true;
	}
	return;
}

// ---------------------------------------

function nv_chang_act2(cl_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			} else {
				nv_client_info(r_split[2], r_split[3]);
			}
		});
	}
	return;
}

// ---------------------------------------

function nv_client_info(cl_id, containerid) {
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=info_cl&id=' + cl_id + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_banners_list(cl_id, containerid) {
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banners_client&id=' + cl_id + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_show_plans_list(containerid) {
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plist&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_pl_chang_act(pid, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
				var sl = document.getElementById(r_split[1]);
				if (sl.checked == true)
					sl.checked = false;
				else
					sl.checked = true;
				clearTimeout(nv_timer);
				sl.disabled = true;
			}
		});
	} else {
		var sl = document.getElementById(checkbox_id);
		if (sl.checked == true)
			sl.checked = false;
		else
			sl.checked = true;
	}
	return;
}

// ---------------------------------------

function nv_pl_del(pid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				nv_show_plans_list(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// ---------------------------------------

function nv_plan_info(pid, containerid) {
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=info_pl&id=' + pid + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_pl_chang_act2(pid) {
	if (confirm(nv_is_change_act_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
			var r_split = res.split("|");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			} else {
				nv_plan_info(r_split[2], r_split[3]);
			}
		});
	}
	return;
}

// ---------------------------------------

function nv_pl_del2(pid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + r_split[2];
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// ---------------------------------------

function nv_show_banners_list(containerid, clid, pid, act) {
	var request_query = nv_fc_variable + '=b_list';
	if (clid != 0) {
		request_query += '&clid=' + clid;
	} else {
		if (pid != 0)
			request_query += '&pid=' + pid;
	}
	request_query += '&act=' + act;
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
	return false;
}

function nv_chang_weight_banners(containerid, clid, pid, act, id) {

	var request_query = nv_fc_variable + '=b_list';
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var weight = $("#id_weight_" + id).val();

	if (clid != 0) {
		request_query += '&clid=' + clid;
	} else {
		if (pid != 0)
			request_query += '&pid=' + pid;
	}

	request_query += '&act=' + act;
	request_query += '&id=' + id;
	request_query += '&weight=' + weight;

	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_b_chang_act(id, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
				var sl = document.getElementById(r_split[1]);
				if (sl.checked == true)
					sl.checked = false;
				else
					sl.checked = true;
				clearTimeout(nv_timer);
				sl.disabled = true;
			} else {
				window.location.href = window.location.href;
			}
		});
	} else {
		var sl = document.getElementById(checkbox_id);
		if (sl.checked == true)
			sl.checked = false;
		else
			sl.checked = true;
	}
	return;
}

// ---------------------------------------

function nv_b_chang_act2(id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
			var r_split = res.split("|");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			} else {
				window.location.href = window.location.href;
			}
		});
	}
	return;
}

// ---------------------------------------

function nv_show_stat(bid, select_month, select_ext, button_id, containerid) {
	var nv_timer = nv_settimeout_disable(button_id, 5000);
	var month = $("#" + select_month).val();
	var ext = $("#" + select_ext).val();
	var request_query = nv_fc_variable + '=show_stat&id=' + bid + '&month=' + month + '&ext=' + ext;
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_show_list_stat(bid, month, ext, val, containerid, page) {
	var request_query = nv_fc_variable + '=show_list_stat&bid=' + bid + '&month=' + month + '&ext=' + ext + '&val=' + val;
	if (page != '0')
		request_query += '&page=' + page;
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&nocache=' + new Date().getTime());
}