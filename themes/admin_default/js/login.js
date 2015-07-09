/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19/3/2010 22:58
 */

var seccodecheck = /^([a-zA-Z0-9])+$/;

if ( typeof (jsi) == 'undefined')
	var jsi = new Array();
if (!jsi[0])
	jsi[0] = 'vi';
if (!jsi[1])
	jsi[1] = './';
if (!jsi[2])
	jsi[2] = 0;
if (!jsi[3])
	jsi[3] = 6;

var strHref = window.location.href;
if (strHref.indexOf("?") > -1) {
	var strHref_split = strHref.split("?");
	var script_name = strHref_split[0];
	var query_string = strHref_split[1];
} else {
	var script_name = strHref;
	var query_string = '';
}

function nv_checkadminlogin_seccode(seccode) {
	return (seccode.value.length == jsi[3] && seccodecheck.test(seccode.value)) ? true : false;
}

function nv_randomPassword(plength) {
	var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	var pass = "";
	for (var z = 0; z < plength; z++) {
		pass += chars.charAt(Math.floor(Math.random() * 62));
	}
	return pass;
}

function nv_checkadminlogin_submit() {
	if (jsi[2] == 1) {
		var seccode = document.getElementById('seccode');
		if (!nv_checkadminlogin_seccode(seccode)) {
			alert(login_error_security);
			seccode.focus();
			return false;
		}
	}
	var login = document.getElementById('login');
	var password = document.getElementById('password');
	if (login.value != '' && password.value != '') {
		return true;
	} else {
		return false;
	}
}

function nv_change_captcha() {
	var vimg = document.getElementById('vimg');
	nocache = nv_randomPassword(10);
	vimg.src = jsi[1] + 'index.php?scaptcha=captcha&nocache=' + nocache;
	document.getElementById('seccode').value = '';
	return false;
}