/**
 * @Project NUKEVIET 4.x
 * @Author  VINADES ( contact@vinades.vn )
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 - 24 - 2010 23 : 41
 */

function nv_login_info(containerid) {
	$('#' + containerid).load(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=logininfo&nocache=' + new Date().getTime());
	return false;
}

function nv_cl_login_submit(nick_max, nick_min, pass_max, pass_min, gfx_count, gfx_chk, login_id, pass_id, sec_id, button_id) {
	var request_query = 'save=1';

	var login = document.getElementById(login_id);
	var error;

	if (login.value.length > nick_max || login.value.length < nick_min || ! nv_namecheck.test(login.value)) {
		error = nv_error_login.replace(/\[max\]/g, nick_max);
		error = error.replace(/\[min\]/g, nick_min);
		alert(error);
		login.focus();
		return false;
	}
	request_query += '&login=' + login.value;

	var pass = document.getElementById(pass_id);

	if (pass.value.length > pass_max || pass.value.length < pass_min || ! nv_namecheck.test(pass.value)) {
		error = nv_error_password.replace(/\[max\]/g, pass_max);
		error = error.replace(/\[min\]/g, pass_min);
		alert(error);
		pass.focus();
		return false;
	}

	request_query += '&password=' + pass.value;
	if (gfx_chk) {
		var sec = document.getElementById(sec_id);
		if (sec.value.length != gfx_count) {
			error = nv_error_seccode.replace(/\[num\]/g, gfx_count);
			alert(error);
			sec.focus();
			return false;
		}

		request_query += '&seccode=' + sec.value;
	}
	var nv_timer = nv_settimeout_disable(button_id, 5000);
	$.post(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=logininfo&nocache=' + new Date().getTime(), request_query, function(res) {
		if (res == 'OK') {
			window.location.href = window.location.href;
			return false;
		}
		alert(nv_login_failed);
		nv_login_info(res);
	});
	return false;
}

function nv_cl_info(containerid) {
	$('#' + containerid).load(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=clinfo&nocache=' + new Date().getTime());
	return false;
}

function nv_cl_edit(containerid) {
	$('#' + containerid).load(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cledit' + '&nocache=' + new Date().getTime());
	return false;
}

function nv_cl_edit_save(full_name, email, website, location, yim, phone, fax, mobile, pass, re_pass, button_id) {
	var request_query = 'save=1';
	request_query += '&full_name=' + rawurlencode(strip_tags(trim(document.getElementById(full_name).value.replace(/[\s]{2,}/g, ' '))));
	request_query += '&email=' + rawurlencode(strip_tags(document.getElementById(email).value.replace(/[^a-zA-Z0-9\_\-\.\@]/g, '')));
	request_query += '&website=' + rawurlencode(strip_tags(trim(document.getElementById(website).value.replace(/[^a-zA-Z0-9\:\#\!\:\.\?\+\=\&\%\@\-\/\,]/g, ''))));
	request_query += '&location=' + rawurlencode(strip_tags(trim(document.getElementById(location).value.replace(/[\s]{2,}/g, ' '))));
	request_query += '&yim=' + rawurlencode(strip_tags(trim(document.getElementById(yim).value.replace(/[^a-zA-Z0-9\.\-\_]/g, ''))));
	request_query += '&phone=' + rawurlencode(strip_tags(trim(document.getElementById(phone).value.replace(/[^0-9\.\+\-\#\(\) ]/g, '').replace(/[\s]{2,}/g, ' '))));
	request_query += '&fax=' + rawurlencode(strip_tags(trim(document.getElementById(fax).value.replace(/[^0-9\.\+\-\#\(\) ]/g, '').replace(/[\s]{2,}/g, ' '))));
	request_query += '&mobile=' + rawurlencode(strip_tags(trim(document.getElementById(mobile).value.replace(/[^0-9\.\+\-\#\(\) ]/g, '').replace(/[\s]{2,}/g, ' '))));
	request_query += '&pass=' + rawurlencode(strip_tags(trim(document.getElementById(pass).value)));
	request_query += '&re_pass=' + rawurlencode(strip_tags(trim(document.getElementById(re_pass).value)));
	request_query += '&num=' + nv_randomPassword(8);

	var nv_timer = nv_settimeout_disable(button_id, 5000);
	$.post(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cledit&nocache=' + new Date().getTime(), request_query, function(res) {
		var r_split = res.split("|");
		if (r_split[0] == 'OK') {
			nv_cl_info(r_split[1]);
		} else {
			var error = r_split[0].replace(/\&[l|r]dquo\;/g, '');
			alert(error);
			if (r_split[1]) {
				var nif = document.getElementById(r_split[1]);
				nif.focus();
			}
		}
	});
	return false;
}