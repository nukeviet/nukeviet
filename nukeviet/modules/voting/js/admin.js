/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_del_content(vid, checkss) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&vid=' + vid + '&checkss=' + checkss, '', 'nv_del_content_result');
	}
	return false;
}
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

function nv_vote_additem(mess) {
	items++;
	var nclass = (items % 2 == 0) ? " class=\"second\"" : "";
	var newitem = "<tbody" + nclass + "><tr>";
	newitem += "	<td style=\"text-align:right\">" + mess + " " + items + "</td>";
	newitem += "	<td><input type=\"text\" value=\"\" name=\"answervotenews[]\" style=\"width:300px\"></td>";
	newitem += "	<td><input type=\"text\" value=\"\" name=\"urlvotenews[]\" style=\"width:350px\"></td>";
	newitem += "	</tr>";
	newitem += "</tbody>";
	$("#items").append(newitem);
}
