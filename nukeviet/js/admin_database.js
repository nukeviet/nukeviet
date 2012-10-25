/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 9:36
 */

function nv_checkForm() {
	var op_name = document.getElementById('op_name').options[document.getElementById('op_name').selectedIndex].value;
	var type_name = document.getElementById('type_name');
	var ext_name = document.getElementById('ext_name');

	if (op_name == 'optimize') {
		type_name.disabled = true;
		ext_name.disabled = true;
	} else {
		type_name.disabled = false;
		ext_name.disabled = false;
	}
}

function nv_chsubmit(oForm, cbName) {
	var op_name = document.getElementById('op_name').options[document.getElementById('op_name').selectedIndex].value;
	if (op_name == 'optimize') {
		var tabs = "";
		for ( var i = 0; i < oForm[cbName].length; i++) {
			if (oForm[cbName][i].checked) {
				tabs += (tabs != "") ? "," : "";
				tabs += oForm[cbName][i].value;
			}
		}

		var subm_form = document.getElementById('subm_form');
		subm_form.disabled = true;
		nv_ajax("POST", oForm.action, nv_fc_variable + '=' + op_name + '&tables=' + tabs, '', 'nv_submit_res');
	} else {
		oForm.submit();
	}
	return;
}

function nv_submit_res(res) {
	alert(res);
	nv_show_dbtables();
	return;
}

function nv_show_dbtables() {
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&show_tabs=1&num=' + nv_randomPassword(8), 'show_tables');
}

function nv_show_highlight(tp)
{
	nv_ajax( "post", window.location.href, 'show_highlight=' + tp, 'my_highlight' );
	return false;
}