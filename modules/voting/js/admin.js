/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_del_content(vid, checkss) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(), 'vid=' + vid + '&checkss=' + checkss, function(res) {
			var r_split = res.split("_");
			if (r_split[0] == 'OK') {
				window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_vote_add_item(mess) {
	items++;
	var newitem = '<tr>';
	newitem += '	<td class="right">' + mess + ' ' + items + '</td>';
	newitem += '	<td><input class="form-control" type="text" value="" name="answervotenews[]"></td>';
	newitem += '	<td><input class="form-control" type="text" value="" name="urlvotenews[]"></td>';
	newitem += '	</tr>';
	$("#items").append(newitem);
}

$(document).ready(function() {
	$("#publ_date,#exp_date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
});