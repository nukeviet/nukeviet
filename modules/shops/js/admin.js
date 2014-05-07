/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

// Xu ly cat ---------------------------------------
function nv_chang_cat(catid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + catid, 5000);
	var new_vid = $('#id_' + mod + '_' + catid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		var parentid = parseInt(r_split[1]);
		nv_show_list_cat(parentid);
	});
	return;
}

function nv_show_list_cat(parentid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_cat&parentid=' + parentid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_cat(catid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				var parentid = parseInt(r_split[1]);
				nv_show_list_cat(parentid);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
				alert(r_split[2]);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
				if (confirm(r_split[4])) {
					var catid = r_split[2];
					var delallcheckss = r_split[3];
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
						$("#cat-delete-area").html(res);
					});
				}
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//Xu ly group ---------------------------------------
function nv_chang_group(groupid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + groupid, 5000);
	var new_vid = $('#id_' + mod + '_' + groupid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_group&nocache=' + new Date().getTime(), 'groupid=' + groupid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		var parentid = parseInt(r_split[1]);
		nv_show_list_group(parentid);
	});
	return;
}

function nv_show_list_group(parentid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_group&parentid=' + parentid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_group(catid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_group&nocache=' + new Date().getTime(), 'groupid=' + catid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				var parentid = parseInt(r_split[1]);
				nv_show_list_group(parentid);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
				alert(r_split[2]);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
				if (confirm(r_split[4])) {
					var groupid = r_split[2];
					var delallcheckss = r_split[3];
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_group&nocache=' + new Date().getTime(), 'groupid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
						$("#group-delete-area").html(res);
					});
				}
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// Xu ly sources ---------------------------------------
function nv_chang_sources(sourceid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + sourceid, 5000);
	var new_vid = $('#id_' + mod + '_' + sourceid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'sourceid=' + sourceid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_source();
	});
	return;
}

function nv_show_list_source() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_source&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_source(sourceid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source&nocache=' + new Date().getTime(), 'sourceid=' + sourceid, function(res) {
			function nv_del_source_result(res) {
				var r_split = res.split('_');
				if (r_split[0] == 'OK') {
					nv_show_list_source();
				} else if (r_split[0] == 'ERR') {
					alert(r_split[1]);
				} else {
					alert(nv_is_del_confirm[2]);
				}
				return false;
			}

		});
	}
	return false;
}

// Xu ly block ---------------------------------------

function nv_del_block_cat(bid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_block_cat();
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_chang_block_cat(bid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + bid, 5000);
	var new_vid = $('#id_' + mod + '_' + bid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=chang_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_block_cat();
	});
	return;
}

function nv_show_list_block_cat() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block_cat&nocache=' + new Date().getTime());
	}
	return;
}

function nv_chang_block(bid, id, mod) {
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var new_vid = $('#id_weight_' + id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'id=' + id + '&bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		nv_chang_block_result(res);
	});
	return;
}

function nv_chang_block_result(res) {
	var r_split = res.split('_');
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	var bid = parseInt(r_split[1]);
	nv_show_list_block(bid);
	return;
}

function nv_show_list_block(bid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block&bid=' + bid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_block_list(oForm, bid) {
	var del_list = '';
	var fa = oForm['idcheck[]'];
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				del_list = del_list + ',' + fa[i].value;
			}
		}
	} else {
		if (fa.checked) {
			del_list = del_list + ',' + fa.value;
		}
	}
	if (del_list != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'del_list=' + del_list + '&bid=' + bid, function(res) {
			nv_chang_block_result(res);
		});
	}
}

// Xu ly main ---------------------------------------

function nv_main_action(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}
	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
					nv_del_content_result(res);
				});
			}
		} else if (action == 'addtoblock') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=block&listid=' + listid + '#add';
		} else if (action == 'publtime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=publtime&listid=' + listid + '&checkss=' + checkss;
		} else if (action == 'exptime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exptime&listid=' + listid + '&checkss=' + checkss;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
			nv_del_content_result(res);
		});
	}
	return false;
}

function nv_check_movecat(oForm, msgnocheck) {
	var fa = oForm['catidnews'];
	if (fa.value == 0) {
		alert(msgnocheck);
		return false;
	}
}

function nv_del_content_result(res) {
	var r_split = res.split('_');
	if (r_split[0] == 'OK') {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items';
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// Xu ly san pham ---------------------------------------

function create_keywords() {
	var content = strip_tags(document.getElementById('keywords').value);
	if (content != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=keywords&nocache=' + new Date().getTime(), 'content=' + encodeURIComponent(content), function(res) {
			if (res != "n/a") {
				document.getElementById('keywords').value = res;
			} else {
				document.getElementById('keywords').value = '';
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

function nv_chang_pays(payid, object, url_change, url_back) {
	var value = $(object).val();
	$.ajax({
		type : 'POST',
		url : url_change,
		data : 'oid=' + payid + '&w=' + value,
		success : function(data) {
			window.location = url_back;
		}
	});
	return;
}

function ChangeActive(idobject, url_active) {
	var id = $(idobject).attr('id');
	$.ajax({
		type : 'POST',
		url : url_active,
		data : 'id=' + id,
		success : function(data) {
			alert(data);
		}
	});
}

function get_alias() {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title), function(res) {
			if (res != "") {
				document.getElementById('idalias').value = res;
			} else {
				document.getElementById('idalias').value = '';
			}
		});
	}
	return false;
}

function nv_add_otherimage() {
	var newitem = "<tr><td><input class=\"form-control\" value=\"\" name=\"otherimage[]\" id=\"otherimage_" + file_items + "\" style=\"width : 80%\" maxlength=\"255\" />";
	newitem += "&nbsp;<input type=\"button\" class=\"btn btn-info\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=otherimage_" + file_items + "&path=" + file_dir + "&type=file&currentpath=" + currentpath + "', 'NVImg', 850, 400, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" /></td></tr>";
	$("#otherimage").append(newitem);
	file_items++;
}

function nv_getcatalog(obj) {
	var pid = $(obj).val();
	var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcatalog&pid=' + pid;
	$('#vcatid').load(url);
}

function nv_getgroup(obj) {
	var cid = $(obj).val();
	var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getgroup&cid=' + cid + "&inrow=" + inrow;
	$('#listgroupid').load(url);
}