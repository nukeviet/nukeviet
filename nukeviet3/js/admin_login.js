/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 19/3/2010 22:58
 */

var mailfilter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var numcheck = /^([0-9])+$/;
var namecheck = /^([a-zA-Z0-9_-])+$/;
var passcheck = /^([a-zA-Z0-9_\.\-])+$/;

if (typeof (jsi) == 'undefined')
	var jsi = new Array();
if (!jsi[0])
	jsi[0] = 'vi';
if (!jsi[1])
	jsi[1] = './';
if (!jsi[2])
	jsi[2] = 0;
if (!jsi[3])
	jsi[3] = 15;
if (!jsi[4])
	jsi[4] = 5;
if (!jsi[5])
	jsi[5] = 15;
if (!jsi[6])
	jsi[6] = 5;
if (!jsi[7])
	jsi[7] = 6;

var strHref = window.location.href;
if (strHref.indexOf("?") > -1) {
	var strHref_split = strHref.split("?");
	var script_name = strHref_split[0];
	var query_string = strHref_split[1];
} else {
	var script_name = strHref;
	var query_string = '';
}

// ---------------------------------------

function nv_checkadminlogin_login(login) {
	return (login.value.length >= jsi[4] && login.value.length <= jsi[3] && namecheck.test(login.value)) ? true : false;
}

// ---------------------------------------

function nv_checkadminlogin_password(password) {
	return (password.value.length >= jsi[6] && password.value.length <= jsi[5] && passcheck.test(password.value)) ? true : false;
}

// ---------------------------------------

function nv_checkadminlogin_seccode(seccode) {
	return (seccode.value.length == jsi[7] && namecheck.test(seccode.value)) ? true : false;
}

// ---------------------------------------

function nv_randomPassword(plength) {
	var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	var pass = "";
	for ( var z = 0; z < plength; z++) {
		pass += chars.charAt(Math.floor(Math.random() * 62));
	}
	return pass;
}

// ---------------------------------------

function nv_checkadminlogin_submit() {
	var login = document.getElementById('login');
	var password = document.getElementById('password');
	if (!nv_checkadminlogin_login(login)) {
		alert(login_error_account);
		login.focus();
		return false;
	}
	if (!nv_checkadminlogin_password(password)) {
		alert(login_error_password);
		password.focus();
		return false;
	}
	if (jsi[2] == 1) {
		var seccode = document.getElementById('seccode');
		if (!nv_checkadminlogin_seccode(seccode)) {
			alert(login_error_security);
			seccode.focus();
			return false;
		}
	}
	return true;
}

// ---------------------------------------

function nv_change_captcha() {
	var vimg = document.getElementById('vimg');
	nocache = nv_randomPassword(10);
	vimg.src = jsi[1] + 'index.php?scaptcha=captcha&nocache=' + nocache;
	document.getElementById('seccode').value = '';
	return false;
}

function nv_change_lang_login(newslang) {
	top.location.href = script_name + '?langinterface=' + newslang
}