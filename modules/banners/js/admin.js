/* *
 * @Project NUKEVIET 3.x
 * @Author  VINADES ( contact@vinades.vn )
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3 - 11 - 2010 20 : 50
 */

function nv_show_cl_list(containerid) {
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=cl_list&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_cl_del(cl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=del_client&id=' + cl_id + '&num=' + nv_randomPassword(8), '', 'nv_cl_del_res');
	}
	return false;
}

// ---------------------------------------

function nv_cl_del_res(res) {
	var r_split = res.split("|");
	if (r_split[0] == 'OK') {
		nv_show_cl_list(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_cl_del2(cl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=del_client&id=' + cl_id + '&num=' + nv_randomPassword(8), '', 'nv_cl_del2_res');
	}
	return false;
}

// ---------------------------------------

function nv_cl_del2_res(res) {
	var r_split = res.split("|");
	if (r_split[0] == 'OK') {
		window.location.href = script_name + '?' + nv_name_variable + '=banners&' + nv_fc_variable + '=' + r_split[2];
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_chang_act(cl_id, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_client&id=' + cl_id + '&num=' + nv_randomPassword(8), '', 'nv_chang_act_res');
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

function nv_chang_act_res(res) {
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
	return false;
}

// ---------------------------------------

function nv_chang_act2(cl_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_client&id=' + cl_id + '&num=' + nv_randomPassword(8), '', 'nv_chang_act2_res');
	}
	return;
}

// ---------------------------------------

function nv_chang_act2_res(res) {
	var r_split = res.split("|");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	} else {
		nv_client_info(r_split[2], r_split[3])
	}
	return false;
}

// ---------------------------------------

function nv_client_info(cl_id, containerid) {
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=info_cl&id=' + cl_id + '&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_banners_list(cl_id, containerid) {
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=banners_client&id=' + cl_id + '&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_show_plans_list(containerid) {
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=plist&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_pl_chang_act(pid, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_plan&id=' + pid + '&num=' + nv_randomPassword(8), '', 'nv_pl_chang_act_res');
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

function nv_pl_chang_act_res(res) {
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
	return false;
}

// ---------------------------------------

function nv_pl_del(pid) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=del_plan&id=' + pid + '&num=' + nv_randomPassword(8), '', 'nv_pl_del_res');
	}
	return false;
}

// ---------------------------------------

function nv_pl_del_res(res) {
	var r_split = res.split("|");
	if (r_split[0] == 'OK') {
		nv_show_plans_list(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_plan_info(pid, containerid) {
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=info_pl&id=' + pid + '&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_pl_chang_act2(pid) {
	if (confirm(nv_is_change_act_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_plan&id=' + pid + '&num=' + nv_randomPassword(8), '', 'nv_pl_chang_act2_res');
	}
	return;
}

// ---------------------------------------

function nv_pl_chang_act2_res(res) {
	var r_split = res.split("|");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	} else {
		nv_plan_info(r_split[2], r_split[3])
	}
	return false;
}

// ---------------------------------------

function nv_pl_del2(pid) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=del_plan&id=' + pid + '&num=' + nv_randomPassword(8), '', 'nv_pl_del2_res');
	}
	return false;
}

// ---------------------------------------

function nv_pl_del2_res(res) {
	var r_split = res.split("|");
	if (r_split[0] == 'OK') {
		window.location.href = script_name + '?' + nv_name_variable + '=banners&' + nv_fc_variable + '=' + r_split[2];
	} else {
		alert(nv_is_del_confirm[2]);
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
	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=banners&' + request_query + '&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
	return false;
}

function nv_chang_weight_banners(containerid, clid, pid, act, id) {

	var request_query = nv_fc_variable + '=b_list';
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var weight = document.getElementById('id_weight_' + id).options[document.getElementById('id_weight_' + id).selectedIndex].value;

	if (clid != 0) {
		request_query += '&clid=' + clid;
	} else {
		if (pid != 0)
			request_query += '&pid=' + pid;
	}
	
	request_query += '&act=' + act;
	request_query += '&id=' + id;
	request_query += '&weight=' + weight;

	$('#' + containerid).load(script_name + '?' + nv_name_variable + '=banners&' + request_query + '&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
	return false;
}

// ---------------------------------------

function nv_b_chang_act(id, checkbox_id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_banner&id=' + id + '&num=' + nv_randomPassword(8), '', 'nv_b_chang_act_res');
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

function nv_b_chang_act_res(res) {
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
	return false;
}

// ---------------------------------------

function nv_b_chang_act2(id) {
	if (confirm(nv_is_change_act_confirm[0])) {
		nv_ajax("post", script_name + '?' + nv_name_variable + '=banners', nv_fc_variable + '=change_act_banner&id=' + id + '&num=' + nv_randomPassword(8), '', 'nv_b_chang_act_res');
	}
	return;
}

// ---------------------------------------

function nv_b_chang_act_res(res) {
	var r_split = res.split("|");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	} else {
		window.location.href = window.location.href;
	}
	return false;
}

// ---------------------------------------

function nv_show_stat(bid, select_month, select_ext, button_id, containerid) {
	var nv_timer = nv_settimeout_disable(button_id, 5000);
	var month = document.getElementById(select_month).options[document.getElementById(select_month).selectedIndex].value;
	var ext = document.getElementById(select_ext).options[document.getElementById(select_ext).selectedIndex].value;
	var request_query = nv_fc_variable + '=show_stat&id=' + bid + '&month=' + month + '&ext=' + ext;
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', request_query + '&num=' + nv_randomPassword(8), containerid);
	return false;
}

// ---------------------------------------

function nv_show_list_stat(bid, month, ext, val, containerid, page) {
	var request_query = nv_fc_variable + '=show_list_stat&bid=' + bid + '&month=' + month + '&ext=' + ext + '&val=' + val;
	if (page != '0')
		request_query += '&page=' + page;
	nv_ajax("get", script_name + '?' + nv_name_variable + '=banners', request_query + '&num=' + nv_randomPassword(8), containerid);
}