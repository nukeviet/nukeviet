/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

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
		return;
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
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid, function(res) {
		nv_del_cat_result(res);
	});
	return false;
}

function nv_del_cat_result(res) {
	var r_split = res.split('_');
	if (r_split[0] == 'OK') {
		var parentid = parseInt(r_split[1]);
		nv_show_list_cat(parentid);
	} else if (r_split[0] == 'CONFIRM') {
		if (confirm(nv_is_del_confirm[0])) {
			var catid = r_split[1];
			var delallcheckss = r_split[2];
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
				nv_del_cat_result(res);
			});
		}
	} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
		alert(r_split[2]);
	} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
		if (confirm(r_split[4])) {
			var catid = r_split[2];
			var delallcheckss = r_split[3];
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
				$("#edit").html(res);
			});
			parent.location = '#edit';
		}
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

function nv_chang_topic(topicid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + topicid, 5000);
	var new_vid = $('#id_' + mod + '_' + topicid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_topic();
	});
	return;
}

function nv_show_list_topic() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_topic&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_topic(topicid) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid, function(res) {
		nv_del_topic_result(res);
	});
}

function nv_del_topic_result(res) {
	var r_split = res.split('_');
	if (r_split[0] == 'OK') {
		nv_show_list_topic();
	} else if (r_split[0] == 'ERR') {
		if (r_split[1] == 'ROWS') {
			if (confirm(r_split[4])) {
				var topicid = r_split[2];
				var checkss = r_split[3];
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_topic&nocache=' + new Date().getTime(), 'topicid=' + topicid + '&checkss=' + checkss, function(res) {
					nv_del_topic_result(res);
				});
			}
		} else {
			alert(r_split[1]);
		}
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

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
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_source();
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

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
	if (mod == 'delete' && !confirm(nv_is_del_confirm[0])) {
		return false;
	}
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
		if (confirm(nv_is_del_confirm[0])) {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'del_list=' + del_list + '&bid=' + bid, function(res) {
				nv_chang_block_result(res);
			});
		}
	}
}

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
		} else {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '='+action+'&listid=' + listid + '&checkss=' + checkss;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_del_content(id, checkss, base_adminurl, detail) {
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
		window.location.href = window.location.href;
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

function get_alias(mod, id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&mod=' + mod + '&id=' + id, function(res) {
			if (res != "") {
				document.getElementById('idalias').value = res;
			} else {
				document.getElementById('idalias').value = '';
			}
		});
	}
	return false;
}

function nv_search_tag(tid) {
	$("#module_show_list").html('<p class="text-center"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></p>').load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tags&q=" + rawurlencode($("#q").val()) + "&num=" + nv_randomPassword(10));
	return false;
}

function nv_del_tags(tid) {
	if (confirm(nv_is_del_confirm[0])) {
		$("#module_show_list").html('<p class="text-center"><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="Waiting..."/></p>').load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tags&del_tid=" + tid + "&num=" + nv_randomPassword(10));
	}
	return false;
}

function checkallfirst() {
    $(this).one("click", checkallsecond);
	$('input:checkbox').each(function() {
		$(this).attr('checked', 'checked');
	});
}

function checkallsecond() {
    $(this).one("click", checkallfirst);
	$('input:checkbox').each(function() {
		$(this).removeAttr('checked');
	});
}

function check_add_first() {
	$(this).one("dblclick", check_add_second);
	$("input[name='add_content[]']:checkbox").prop("checked", true);
}

function check_add_second() {
	$(this).one("dblclick", check_add_first);
	$("input[name='add_content[]']:checkbox").prop("checked", false);
}

function check_app_first() {
	$(this).one("dblclick", check_app_second);
	$("input[name='app_content[]']:checkbox").prop("checked", true);
}

function check_app_second() {
	$(this).one("dblclick", check_app_first);
	$("input[name='app_content[]']:checkbox").prop("checked", false);
}

function check_pub_first() {
	$(this).one("dblclick", check_pub_second);
	$("input[name='pub_content[]']:checkbox").prop("checked", true);
}

function check_pub_second() {
	$(this).one("dblclick", check_pub_first);
	$("input[name='pub_content[]']:checkbox").prop("checked", false);
}

function check_edit_first() {
	$(this).one("dblclick", check_edit_second);
	$("input[name='edit_content[]']:checkbox").prop("checked", true);
}

function check_edit_second() {
	$(this).one("dblclick", check_edit_first);
	$("input[name='edit_content[]']:checkbox").prop("checked", false);
}

function check_del_first() {
	$(this).one("dblclick", check_del_second);
	$("input[name='del_content[]']:checkbox").prop("checked", true);
}

function check_del_second() {
	$(this).one("dblclick", check_del_first);
	$("input[name='del_content[]']:checkbox").prop("checked", false);
}

function check_admin_first() {
	$(this).one("dblclick", check_admin_second);
	$("input[name='admin_content[]']:checkbox").prop("checked", true);
}

function check_admin_second() {
	$(this).one("dblclick", check_admin_first);
	$("input[name='admin_content[]']:checkbox").prop("checked", false);
}

$(document).ready(function(){
	$('#checkall').click(function() {
		$('input:checkbox').each(function() {
			$(this).attr('checked', 'checked');
		});
	});
	$('#uncheckall').click(function() {
		$('input:checkbox').each(function() {
			$(this).removeAttr('checked');
		});
	});
	
	// Topic
	$('#delete-topic').click(function() {
		var list = [];
		$('input[name=newsid]:checked').each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert(LANG.topic_nocheck);
			return false;
		}
		if (confirm(LANG.topic_delete_confirm)) {
			$.ajax({
				type : 'POST',
				url : 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicdelnews',
				data : 'list=' + list,
				success : function(data) {
					alert(data);
					window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid=' + CFG.topicid;
				}
			});
		}
		return false;
	});

	// Topics
	$("#select-img-topic").click(function() {
		var area = "homeimg";
		var path = CFG.upload_dir;
		var currentpath = CFG.upload_dir;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	
	// Tags
	$("#select-img-tag").click(function() {
		var area = "image";
		var path = CFG.upload_current;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	
	// Sources
	$("#select-img-source").click(function() {
		var area = "logo";
		var path = CFG.upload_path;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	// Setting
	$("#select-img-setting").click(function() {
		var area = "show_no_image";
		var type = "image";
		var path = CFG.path;
		var currentpath = CFG.currentpath;
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	// Groups
	$("#select-img-group").click(function() {
		var area = "image";
		var path = CFG.upload_current;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	// News content
	$("#select-img-post").click(function() {
		var area = "homeimg";
		var alt = "homeimgalt";
		var path = CFG.uploads_dir_user;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$('.submit-post').hover(function(){
		if( $('[name="keywords[]"]').length == 0 ){
			if( $('#message-tags').length == 0 ){
				$('#message').append('<div id="message-tags" class="alert alert-danger">' + LANG.content_tags_empty + '</div>');
			}
		}else{
			$('#message-tags').remove();
		}
		if( $('[name="alias"]').val() == '' ){
			if( $('#message-alias').length == 0 ){
				$('#message').append('<div id="message-alias" class="alert alert-danger">' + LANG.alias_empty_notice + '.</div>');
			}
		}else{
			$('#message-alias').remove();
		}
	});

	// Add to topic
	$('#update-topic').click(function() {
		var listid = [];
		$('input[name=idcheck]:checked').each(function() {
			listid.push($(this).val());
		});
		if (listid.length < 1) {
			alert(LANG.topic_nocheck);
			return false;
		}
		var topic = $('select[name=topicsid]').val();
		$.ajax({
			type : 'POST',
			url : 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addtotopics',
			data : 'listid=' + listid + '&topicsid=' + topic,
			success : function(data) {
				alert(data);
				window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid=' + topic;
			}
		});
		return false;
	});
	
	// Cat
	$("#select-img-cat").click(function() {
		var area = "image";
		var path = CFG.upload_current;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
});