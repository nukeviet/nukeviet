/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

function nv_sortcomm(base_url_comm) {
	var new_sort = document.getElementById('sortcomm').options[document.getElementById('sortcomm').selectedIndex].value;
	document.location = base_url_comm + '&sortcomm=' + new_sort;
}

function sendcommment(module, id, allowed, newscheckss, gfx_count) {
	var commentname = document.getElementById('commentname');
	var commentemail = document.getElementById('commentemail_iavim');
	var commentseccode = document.getElementById('commentseccode_iavim');
	var commentcontent = strip_tags(document.getElementById('commentcontent').value);
	if (commentname.value == "") {
		alert(nv_fullname);
		commentname.focus();
	} else if (!nv_email_check(commentemail)) {
		alert(nv_error_email);
		commentemail.focus();
	} else if (!nv_name_check(commentseccode)) {
		error = nv_error_seccode.replace(/\[num\]/g, gfx_count);
		alert(error);
		commentseccode.focus();
	} else if (commentcontent == "") {
		alert(nv_content);
		document.getElementById('commentcontent').focus();
	} else {
		var sd = document.getElementById('buttoncontent');
		sd.disabled = true;
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=post&module=' + module + '&id=' + id + '&allowed=' + allowed + '&checkss=' + newscheckss + '&name=' + commentname.value + '&email=' + commentemail.value + '&code=' + commentseccode.value + '&content=' + encodeURIComponent(commentcontent), '', 'nv_commment_result');
	}
	return;
}

function nv_commment_result(res) {
	var rs = res.split("_");
	if (rs[0] == 'OK') {
		document.location = document.location;
		alert(rs[1]);
	} else if (rs[0] == 'ERR') {
		alert(rs[1]);
	} else {
		alert(nv_content_failed);
	}
	return false;
}

function nv_like(cid, checkss, like) {
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=like&cid=' + cid + '&like=' + like + '&checkss=' + checkss, '', 'nv_like_result');
}

function nv_like_result(res) {
	var rs = res.split("_");
	if (rs[0] == 'OK') {
		$("#" + rs[1]).text(rs[2]);
	} else if (rs[0] == 'ERR') {
		alert(rs[1]);
	}
	return false;
}

function nv_delete(cid, checkss) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delete&cid=' + cid + '&checkss=' + checkss, '', 'nv_delete_result');
	}
}

function nv_delete_result(res) {
	var rs = res.split("_");
	if (rs[0] == 'OK') {
		document.location = document.location;
	} else if (rs[0] == 'ERR') {
		alert(rs[1]);
	}
	return false;
}
