/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function sendrating(id, point, newscheckss) {
	if(point==1 || point==2 || point==3 || point==4 || point==5){
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&id=' + id + '&checkss=' + newscheckss + '&point=' + point, 'stringrating', '');
	}
}

function sendcommment(id, newscheckss, gfx_count) {
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
		error = nv_error_seccode.replace( /\[num\]/g, gfx_count );
		alert(error);
		commentseccode.focus();
	} else if (commentcontent == "") {
		alert(nv_content);
		document.getElementById('commentcontent').focus();
	} else {
		var sd = document.getElementById('buttoncontent');
		sd.disabled = true;
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=postcomment&id=' + id + '&checkss=' + newscheckss + '&name=' + commentname.value + '&email=' + commentemail.value + '&code=' + commentseccode.value + '&content=' + encodeURIComponent(commentcontent), '', 'nv_commment_result');
	}
	return;
}

function nv_commment_result(res) {
	nv_change_captcha('vimg', 'commentseccode_iavim');
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		document.getElementById('commentcontent').value = "";
		nv_show_hidden('showcomment', 1);
		nv_show_comment(r_split[1], r_split[2], r_split[3]);
		alert(r_split[4]);
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_content_failed);
	}
	nv_set_disable_false('buttoncontent');
	return false;
}

function nv_show_comment(id, checkss, page) {
	nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&id=' + id + '&checkss=' + checkss + '&page=' + page, 'showcomment', '');
}

function remove_text() {
	document.getElementById('to_date').value = "";
	document.getElementById('from_date').value = "";
}

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', base_adminurl + 'index.php', nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&id=' + id + '&checkss=' + checkss, '', 'nv_del_content_result');
	}
	return false;
}
function nv_del_content_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		window.location.href = strHref;
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}