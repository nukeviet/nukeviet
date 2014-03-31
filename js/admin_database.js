/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_checkForm() {
	var op_name = $("#op_name").val();
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
	var op_name = $("#op_name").val();
	if (op_name == 'optimize') {
		var tabs = "";
		for (var i = 0; i < oForm[cbName].length; i++) {
			if (oForm[cbName][i].checked) {
				tabs += (tabs != "") ? "," : "";
				tabs += oForm[cbName][i].value;
			}
		}

		var subm_form = document.getElementById('subm_form');
		subm_form.disabled = true;

		$.post(oForm.action + '&nocache=' + new Date().getTime(), nv_fc_variable + '=' + op_name + '&tables=' + tabs, function(res) {
			alert(res);
			nv_show_dbtables();
			return;
		});
	} else {
		oForm.submit();
	}
	return;
}

function nv_show_dbtables() {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'show_tabs=1', function(res) {
		$("#show_tables").html(res);
	});
}

function nv_show_highlight(tp) {
	$.post(window.location.href + '&nocache=' + new Date().getTime(), 'show_highlight=' + tp, function(res) {
		$("#my_highlight").html(res);
	});
	return false;
}