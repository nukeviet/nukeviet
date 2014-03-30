/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_chang_weight(catid) {
	var nv_timer = nv_settimeout_disable('weight' + catid, 5000);
	var newpos = $("#weight" + catid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'changeweight=1&catid=' + catid + '&new=' + newpos, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		window.location.href = window.location.href;
	});
	return;
}

//  ---------------------------------------

function nv_chang_status(catid) {
	var nv_timer = nv_settimeout_disable('change_status' + catid, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'changestatus=1&catid=' + catid, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			window.location.href = window.location.href;
		}
	});
	return;
}

//  ---------------------------------------

function nv_row_del(catid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'del=1&catid=' + catid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_file_del(fid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(), 'id=' + fid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_chang_file_status(fid) {
	var nv_timer = nv_settimeout_disable('change_status' + fid, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'changestatus=1&id=' + fid, function(res) {
		if (res != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			window.location.href = window.location.href;
		}
	});
	return;
}

//  ---------------------------------------

function nv_filequeue_del(fid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=filequeue&nocache=' + new Date().getTime(), 'del=1&id=' + fid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_filequeue_alldel() {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=filequeue&nocache=' + new Date().getTime(), 'alldel=1', function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_checkfile(mylink, is_myUrl, butt) {
	var nv_timer = nv_settimeout_disable(butt, 5000);
	var link = document.getElementById(mylink).value;
	if (trim(link) == '') {
		document.getElementById(mylink).value = '';
		return false;
	}

	link = rawurlencode(link);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'check=1&url=' + link + '&is_myurl=' + is_myUrl, function(res) {
		alert(res);
	});
	return false;
}

//  ---------------------------------------

function nv_gourl(mylink, is_myUrl, butt) {
	var nv_timer = nv_settimeout_disable(butt, 5000);
	var link = document.getElementById(mylink).value;
	if (trim(link) == '') {
		document.getElementById(mylink).value = '';
		return false;
	}

	if (is_myUrl) {
		link = rawurlencode(link);
		link = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&fdownload=' + link;
		window.location.href = link;

	} else {
		if (! link.match(/^(http|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i)) {
			alert(nv_url);
			document.getElementById(mylink).focus();
		} else {
			var w = window.open(link);
			w.focus();
		}
	}
	return false;
}

//  ---------------------------------------

function nv_delurl(id, item) {
	$("#fileupload_item_" + item).remove();
}

//  ---------------------------------------

function nv_report_del(rid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'del=1&id=' + rid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_report_check(fid) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'linkcheck=1&id=' + fid, function(res) {
		var r_split = res.split("_");
		if (r_split[0] == "OK") {
			var report_check_ok = document.getElementById('report_check_ok').value;
			if (confirm(report_check_ok)) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'del=1&id=' + r_split[1], function(res) {
					if (res == 'OK') {
						window.location.href = window.location.href;
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		} else {
			if (r_split[0] == "NO") {
				var report_check_error = document.getElementById('report_check_error').value;
				if (confirm(report_check_error)) {
					nv_report_edit(r_split[1]);
				}
			} else {
				var report_check_error2 = document.getElementById('report_check_error2').value;
				if (confirm(report_check_error2)) {
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'del=1&id=' + r_split[1], function(res) {
						if (res == 'OK') {
							window.location.href = window.location.href;
						} else {
							alert(nv_is_del_confirm[2]);
						}
					});
				}
			}
		}
	});
	return false;
}

//  ---------------------------------------

function nv_report_edit(fid) {
	window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&edit=1&id=' + fid + '&report=1';
	return false;
}

//  ---------------------------------------

function nv_report_alldel() {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'alldel=1', function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//  ---------------------------------------

function nv_file_additem(id) {
	file_items++;
	var newitem = "<div id=\"fileupload_item_" + file_items + "\"><input readonly=\"readonly\" class=\"w300\" value=\"\" name=\"fileupload[]\" id=\"fileupload" + file_items + "\" maxlength=\"255\" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse_file( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=fileupload" + file_items + "&path=" + file_dir + "&type=file', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id= \"check_fileupload" + file_items + "\" onclick=\"nv_checkfile( 'fileupload" + file_items + "', 1, 'check_fileupload" + file_items + "' ); \" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_gourl + "\" id= \"go_fileupload" + file_items + "\" onclick=\"nv_gourl( 'fileupload" + file_items + "', 1, 'go_fileupload" + file_items + "' ); \" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_delurl + "\" onclick=\"nv_delurl( " + id + ", " + file_items + " ); \" /></div>";
	$("#fileupload_items").append(newitem);
}

//  ---------------------------------------

function nv_file_additem2() {
	var newitem = "<input readonly=\"readonly\" class=\"txt\" value=\"\" name=\"fileupload2[]\" id=\"fileupload2_" + file_items + "\" style=\"width : 300px\" maxlength=\"255\" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse_file( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=fileupload2_" + file_items + "&path=" + file_dir + "&type=file', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id= \"check_fileupload2_" + file_items + "\" onclick=\"nv_checkfile( 'fileupload2_" + file_items + "', 1, 'check_fileupload2_" + file_items + "' ); \" />";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_gourl + "\" id= \"go_fileupload2_" + file_items + "\" onclick=\"nv_gourl( 'fileupload2_" + file_items + "', 1, 'go_fileupload2_" + file_items + "' ); \" /><br />";
	$("#fileupload2_items").append(newitem);
	file_items++;
}

//  ---------------------------------------

function nv_linkdirect_additem() {
	var newitem = "<textarea name=\"linkdirect[]\" id=\"linkdirect" + linkdirect_items + "\" style=\"width : 300px; height : 150px\"></textarea>";
	newitem += "&nbsp;<input type=\"button\" value=\"" + file_checkUrl + "\" id=\"check_linkdirect" + linkdirect_items + "\" onclick=\"nv_checkfile( 'linkdirect" + linkdirect_items + "', 0, 'check_linkdirect" + linkdirect_items + "' ); \" /><br />";
	$("#linkdirect_items").append(newitem);
	linkdirect_items++;
}