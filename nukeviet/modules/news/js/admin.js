/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_chang_cat(catid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + catid, 5000);
	var new_vid = document.getElementById('id_' + mod + '_' + catid).options[document.getElementById('id_' + mod + '_' + catid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_cat_result');
	return;
}

// ---------------------------------------

function nv_chang_cat_result(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	var parentid = parseInt(r_split[1]);
	nv_show_list_cat(parentid);
	return;
}

// ---------------------------------------

function nv_show_list_cat(parentid) {
	if (document.getElementById('module_show_list')) {
		nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_cat&parentid=' + parentid + '&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

// ---------------------------------------

function nv_del_cat(catid) {
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&catid=' + catid, '', 'nv_del_cat_result');
	return false;
}

// ---------------------------------------

function nv_del_cat_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		var parentid = parseInt(r_split[1]);
		nv_show_list_cat(parentid);
	} else if (r_split[0] == 'CONFIRM') {
		if (confirm(nv_is_del_confirm[0])) {
			var catid = r_split[1];
			var delallcheckss = r_split[2];
			nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&catid=' + catid + '&delallcheckss=' + delallcheckss, '', 'nv_del_cat_result');
		}
	} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
		alert(r_split[2]);
	} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
		if (confirm(r_split[4])) {
			var catid = r_split[2];
			var delallcheckss = r_split[3];
			nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&catid=' + catid + '&delallcheckss=' + delallcheckss, 'edit', '');
			parent.location='#edit';			
		}
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_chang_topic(topicid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + topicid, 5000);
	var new_vid = document.getElementById('id_' + mod + '_' + topicid).options[document.getElementById('id_' + mod + '_' + topicid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_topic&topicid=' + topicid + '&mod=' + mod + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_topic_result');
	return;
}

// ---------------------------------------

function nv_chang_topic_result(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_topic();
	return;
}

// ---------------------------------------

function nv_show_list_topic() {
	if (document.getElementById('module_show_list')) {
		nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_topic&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

// ---------------------------------------

function nv_del_topic(topicid) {
	nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&topicid=' + topicid, '', 'nv_del_topic_result');
}

// ---------------------------------------

function nv_del_topic_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		nv_show_list_topic();
	} else if (r_split[0] == 'ERR') {
		if (r_split[1] == 'ROWS') {
			if (confirm(r_split[4])) {
				var topicid = r_split[2];
				var checkss = r_split[3];
				nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&topicid=' + topicid + '&checkss=' + checkss, '', 'nv_del_topic_result');
			}
		} else {
			alert(r_split[1]);
		}
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_chang_sources(sourceid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + sourceid, 5000);
	var new_vid = document.getElementById('id_' + mod + '_' + sourceid).options[document.getElementById('id_' + mod + '_' + sourceid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&sourceid=' + sourceid + '&mod=' + mod + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_sources_result');
	return;
}

// ---------------------------------------

function nv_chang_sources_result(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_source();
	return;
}

// ---------------------------------------

function nv_show_list_source() {
	if (document.getElementById('module_show_list')) {
		nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_source&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

// ---------------------------------------

function nv_del_source(sourceid) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source&sourceid=' + sourceid, '', 'nv_del_source_result');
	}
	return false;
}

// ---------------------------------------

function nv_del_source_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		nv_show_list_source();
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_del_block_cat(bid) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block_cat&bid=' + bid, '', 'nv_del_block_cat_result');
	}
	return false;
}

// ---------------------------------------

function nv_del_block_cat_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		nv_show_list_block_cat();
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function nv_chang_block_cat(bid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + bid, 5000);
	var new_vid = document.getElementById('id_' + mod + '_' + bid).options[document.getElementById('id_' + mod + '_' + bid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=chang_block_cat&bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_block_cat_result');
	return;
}

// ---------------------------------------

function nv_chang_block_cat_result(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	clearTimeout(nv_timer);
	nv_show_list_block_cat();
	return;
}

// ---------------------------------------

function nv_show_list_block_cat() {
	if (document.getElementById('module_show_list')) {
		nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block_cat&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

// ---------------------------------------

function nv_chang_block(bid, id, mod) {
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var new_vid = document.getElementById('id_weight_' + id).options[document.getElementById('id_weight_' + id).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&id=' + id + '&bid=' + bid + '&&mod=' + mod + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_block_result');
	return;
}

// ---------------------------------------

function nv_chang_block_result(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	var bid = parseInt(r_split[1]);
	nv_show_list_block(bid);
	return;
}

// ---------------------------------------

function nv_show_list_block(bid) {
	if (document.getElementById('module_show_list')) {
		nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block&bid=' + bid + '&num=' + nv_randomPassword(8), 'module_show_list');
	}
	return;
}

// ---------------------------------------

function nv_del_block_list(oForm, bid) {
	var del_list = '';
	var fa = oForm['idcheck[]'];
	if (fa.length) {
		for ( var i = 0; i < fa.length; i++) {
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
		nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&del_list=' + del_list + '&bid=' + bid + '&num=' + nv_randomPassword(8), '', 'nv_chang_block_result');
	}
}

// ---------------------------------------

function nv_main_action(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for ( var i = 0; i < fa.length; i++) {
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
				nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&listid=' + listid + '&checkss=' + checkss, '', 'nv_del_content_result');
			}
		} else if (action == 'addtoblock') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=block&listid=' + listid + '#add';
		} else if (action == 'publtime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=publtime&listid=' + listid + '&checkss=' + checkss;
		} else if (action == 'exptime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exptime&listid=' + listid + '&checkss=' + checkss;
		} else {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addtotopics&listid=' + listid;
		}
	} else {
		alert(msgnocheck);
	}
}

// ---------------------------------------

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&id=' + id + '&checkss=' + checkss, '', 'nv_del_content_result');
	}
	return false;
}

// ---------------------------------------

function nv_check_movecat(oForm, msgnocheck) {
	var fa = oForm['catidnews'];
	if (fa.value == 0) {
		alert(msgnocheck);
		return false;
	}
}

// ---------------------------------------

function nv_del_content_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// ---------------------------------------

function create_keywords() {
	var content = strip_tags(document.getElementById('keywords').value);
	if (content != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=keywords&content=' + encodeURIComponent(content), '', 'res_keywords');
	}
	return false;
}

// ---------------------------------------

function res_keywords(res) {
	if (res != "n/a") {
		document.getElementById('keywords').value = res;
	} else {
		document.getElementById('keywords').value = '';
	}
	return false;
}

//---------------------------------------
function get_alias(mod,id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&title=' + encodeURIComponent(title)+'&mod='+mod+'&id='+id, '', 'res_get_alias');
	}
	return false;
}

function res_get_alias(res) {
	if (res != "") {
		document.getElementById('idalias').value = res;
	} else {
		document.getElementById('idalias').value = '';
	}
	return false;
}

// autocomplete function

function findValue(li) {
	if (li == null)
		return alert("No match!");

	if (!!li.extra)
		var sValue = li.extra[0];

	else
		var sValue = li.selectValue;
	return sValue;
}

// ---------------------------------------

function selectItem(li) {
	sValue = findValue(li);
}

// ---------------------------------------

function formatItem(row) {
	return row[0] + " (" + row[1] + ")";
}

// ---------------------------------------

// end autocomplete function

// collapse Div
$(document).ready(function() {

	// hide message_body after the first one
		$(".message_list .message_body:gt(1)").hide();

		// hide message li after the 5th
		$(".message_list li:gt(5)").hide();

		// toggle message_body
		$(".message_head").click(function() {
			$(this).next(".message_body").slideToggle(500)
			return false;
		});

		// collapse all messages
		$(".collpase_all_message").click(function() {
			$(".message_body").slideUp(500)
			return false;
		});

		// Show all messages
		$(".show_all_message").click(function() {
			$(".message_body").slideDown(1000)
			return false;
		});
	}

// ---------------------------------------

		);
// End collapse Div
