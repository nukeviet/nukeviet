/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

// Xu ly cat ---------------------------------------
function nv_chang_cat(catid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + catid, 5000);
	var new_vid = $('#id_' + mod + '_' + catid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		var parentid = parseInt(r_split[1]);
		nv_show_list_cat(parentid);
	});
	return;
}

function nv_show_list_cat(parentid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_cat&parentid=' + parentid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_cat(catid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				var parentid = parseInt(r_split[1]);
				nv_show_list_cat(parentid);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
				alert(r_split[2]);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
				if (confirm(r_split[4])) {
					var catid = r_split[2];
					var delallcheckss = r_split[3];
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
						$("#cat-delete-area").html(res);
					});
				}
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//Xu ly group ---------------------------------------
function nv_chang_group(groupid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + groupid, 5000);
	var new_vid = $('#id_' + mod + '_' + groupid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_group&nocache=' + new Date().getTime(), 'groupid=' + groupid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		var parentid = parseInt(r_split[1]);
		nv_show_list_group(parentid);
	});
	return;
}

function nv_show_list_group(parentid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_group&parentid=' + parentid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_group(catid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_group&nocache=' + new Date().getTime(), 'groupid=' + catid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				var parentid = parseInt(r_split[1]);
				nv_show_list_group(parentid);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'CAT') {
				alert(r_split[2]);
			} else if (r_split[0] == 'ERR' && r_split[1] == 'ROWS') {
				if (confirm(r_split[4])) {
					var groupid = r_split[2];
					var delallcheckss = r_split[3];
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_group&nocache=' + new Date().getTime(), 'groupid=' + catid + '&delallcheckss=' + delallcheckss, function(res) {
						$("#group-delete-area").html(res);
					});
				}
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

//Xu ly location ---------------------------------------
function nv_chang_location(locationid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + locationid, 5000);
	var new_vid = $('#id_' + mod + '_' + locationid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_location&nocache=' + new Date().getTime(), 'locationid=' + locationid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		var parentid = parseInt(r_split[1]);
		nv_show_list_location(parentid);
	});
	return;
}

function nv_show_list_location(parentid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_location&parentid=' + parentid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_location(locationid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_location&nocache=' + new Date().getTime(), 'locationid=' + locationid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				var parentid = parseInt(r_split[1]);
				nv_show_list_location(parentid);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

// Xu ly block ---------------------------------------

function nv_del_block_cat(bid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_block_cat();
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_chang_block_cat(bid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + bid, 5000);
	var new_vid = $('#id_' + mod + '_' + bid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=chang_block_cat&nocache=' + new Date().getTime(), 'bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_block_cat();
	});
	return;
}

function nv_show_list_block_cat() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block_cat&nocache=' + new Date().getTime());
	}
	return;
}

function nv_chang_block(bid, id, mod) {
	var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
	var new_vid = $('#id_weight_' + id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'id=' + id + '&bid=' + bid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		nv_chang_block_result(res);
	});
	return;
}

function nv_chang_block_result(res) {
	var r_split = res.split('_');
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
	}
	var bid = parseInt(r_split[1]);
	nv_show_list_block(bid);
	return;
}

function nv_show_list_block(bid) {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_block&bid=' + bid + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_block_list(oForm, bid) {
	var del_list = '';
	var fa = oForm['idcheck[]'];
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				del_list = del_list + ',' + fa[i].value;
			}
		}
	} else {
		if (fa.checked) {
			del_list = del_list + ',' + fa.value;
		}
	}
	if (del_list != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block&nocache=' + new Date().getTime(), 'del_list=' + del_list + '&bid=' + bid, function(res) {
			nv_chang_block_result(res);
		});
	}
}

// Xu ly main ---------------------------------------

function nv_main_action(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}
	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
					nv_del_content_result(res);
				});
			}
		} else if (action == 'addtoblock') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=block&listid=' + listid + '#add';
		} else if (action == 'publtime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=publtime&listid=' + listid + '&checkss=' + checkss;
		} else if (action == 'exptime') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exptime&listid=' + listid + '&checkss=' + checkss;
		} else if (action == 'warehouse') {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=warehouse&listid=' + listid + '&checkss=' + checkss;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
			nv_del_content_result(res);
		});
	}
	return false;
}

function nv_check_movecat(oForm, msgnocheck) {
	var fa = oForm['catidnews'];
	if (fa.value == 0) {
		alert(msgnocheck);
		return false;
	}
}

function nv_check_movegroup(oForm, msgnocheck) {
	var fa = oForm['groupidnews'];
	if (fa.value == 0) {
		alert(msgnocheck);
		return false;
	}
}

function nv_del_content_result(res) {
	var r_split = res.split('_');
	if (r_split[0] == 'OK') {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items';
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

// Xu ly san pham ---------------------------------------

function create_keywords() {
	var content = strip_tags(document.getElementById('keywords').value);
	if (content != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=keywords&nocache=' + new Date().getTime(), 'content=' + encodeURIComponent(content), function(res) {
			if (res != "n/a") {
				document.getElementById('keywords').value = res;
			} else {
				document.getElementById('keywords').value = '';
			}
		});
	}
	return false;
}

function nv_sh(sl_id, div_id) {
	var new_opt = $("#" + sl_id).val();
	if (new_opt == 3)
		nv_show_hidden(div_id, 1);
	else
		nv_show_hidden(div_id, 0);
	return false;
}

function nv_chang_pays(payid, object, url_change, url_back) {
	var value = $(object).val();
	$.ajax({
		type : 'POST',
		url : url_change,
		data : 'oid=' + payid + '&w=' + value,
		success : function(data) {
			window.location = url_back;
		}
	});
	return;
}

function ChangeActive(idobject, url_active) {
	var id = $(idobject).attr('id');
	$.ajax({
		type : 'POST',
		url : url_active,
		data : 'id=' + id,
		success : function(data) {
			alert(data);
		}
	});
}

function get_alias(mod, id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&mod=' + mod + '&id=' + id, function(res) {
			if (res != "") {
				document.getElementById('idalias').value = res;
			} else {
				document.getElementById('idalias').value = '';
			}
		});
	}
	return false;
}

function nv_add_otherimage() {
//	var newitem = "<div class=\"form-group\"><input class=\"form-control\" value=\"\" name=\"otherimage[]\" id=\"otherimage_" + file_items + "\" style=\"width : 80%\" maxlength=\"255\" />";
	//newitem += "&nbsp;<input type=\"button\" class=\"btn btn-info\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=otherimage_" + file_items + "&path=" + file_dir + "&type=file&currentpath=" + currentpath + "', 'NVImg', 850, 400, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" /></div>";

	var newitem = '';
	newitem += "<div class=\"form-group\">";
	newitem += "<div class=\"input-group\">";
	newitem += "	<input class=\"form-control\" type=\"text\" name=\"otherimage[]\" id=\"otherimage_" + file_items + "\" />";
	newitem += "	<span class=\"input-group-btn\">";
	newitem += "		<button class=\"btn btn-default\" type=\"button\" onclick=\"nv_open_browse( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=otherimage_" + file_items + "&path=" + file_dir + "&type=file&currentpath=" + currentpath + "', 'NVImg', 850, 400, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" >";
	newitem += "			<em class=\"fa fa-folder-open-o fa-fix\">&nbsp;</em></button>";
	newitem += "	</span>";
	newitem += "</div>";
	newitem += "</div>";

	$("#otherimage").append(newitem);
	file_items++;
}

function nv_add_title() {
	var a = "<tr><td><input class=\"form-control\" value=\"\" name=\"custom[title_config][]\" style=\"width : 80%\" maxlength=\"255\"  type=\"text\"/></td>";
	a += "<td><input class=\"form-control\" value=\"\" name=\"custom[content_config][]\" style=\"width : 80%\" maxlength=\"255\"  type=\"text\"/></td></tr>";
	$("#othertitle").append(a);
	file_items++;
}

function nv_getcatalog(obj) {
	var pid = $(obj).val();
	var url = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcatalog&pid=' + pid;
	$.get(url, function(data) {
		if( data == '' )
		{
			$('#cat').hide();
		}
		else
		{
			$('#cat #vcatid').html( data );
			$('#cat').show();
		}
	});
}

function nv_change_catid(obj, id) {
	var cid = $(obj).val();
	var typepriceold = $("#typepriceold").val();
	typeprice = $(obj).find('option:selected').attr("data-label");
	if (typeprice!=typepriceold)
	{
		$('#priceproduct').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getprice&cid=' + cid + "&id=" + id);
		$("#typepriceold").val(typeprice);
	}
	$('#custom_form').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=custom_form&cid=' + cid + "&id=" + id);
	$.get( script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getgroup&cid=' + cid, function( data ) {
		if( data != '' ){
			$('#list_group').show();
			$("#listgroupid").html( data );
		}
		else{
			$('#list_group').hide();
		}
	});
}

function nv_price_config_add_item() {
	var items =  $("input[name^=price_config]").length;
	items = items/2 + 1;
	var newitem = '<tr>';
	newitem += '	<td><input class="form-control" type="number" name="price_config['+items+'][number_to]" value=""/></td>';
	newitem += '	<td><input class="form-control" type="text" name="price_config['+items+'][price]" value="" onkeyup="this.value=FormatNumber(this.value);" style="text-align: right"/></td>';
	newitem += '	</tr>';
	$("#id_price_config").append(newitem);
}

// Review
function nv_review_action(oForm, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}

	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=review&nocache=' + new Date().getTime(), 'del=1&dellist=1&listid=' + listid, function(res) {
					if( res == 'OK' )
					{
						window.location.href = window.location.href;
					}
					else
					{
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		} else if( action == 'review_status_1' || action == 'review_status_0' ) {
			if (confirm(nv_is_change_act_confirm[0]))
			{
				var status = action.split('_');
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=review&nocache=' + new Date().getTime(), 'change_status=1&status='+status[2]+'&listid=' + listid, function(res) {
					if( res == 'OK' )
					{
						window.location.href = window.location.href;
					}
					else
					{
						alert(nv_is_change_act_confirm[2]);
					}
				});
			}
		}
		else{

		}
	} else {
		alert(msgnocheck);
	}
}

function nv_del_review(id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=review&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
			if (res == 'OK') {
				$('#row_' + id).slideUp();
			} else {
				alert(nv_is_del_confirm[2]);
			}

		});
	}
}

function nv_del_files(id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
			if (res == 'OK') {
				$('#row_' + id).slideUp();
			} else {
				alert(nv_is_del_confirm[2]);
			}

		});
	}
}

function nv_change_active_files( id )
{
	var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

		});
	}
	else
	{
		$('#change_active_' + id).prop('checked', new_status ? false : true );
	}
}

function FormatNumber(str) {

	var strTemp = GetNumber(str);
	if (strTemp.length <= 3)
		return strTemp;
	strResult = "";
	for (var i = 0; i < strTemp.length; i++)
		strTemp = strTemp.replace(",", "");
	var m = strTemp.lastIndexOf(".");
	if (m == -1) {
		for (var i = strTemp.length; i >= 0; i--) {
			if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
				strResult = "," + strResult;
			strResult = strTemp.substring(i, i + 1) + strResult;
		}
	} else {
		var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
		var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
		var tam = 0;
		for (var i = strphannguyen.length; i >= 0; i--) {

			if (strResult.length > 0 && tam == 4) {
				strResult = "," + strResult;
				tam = 1;
			}

			strResult = strphannguyen.substring(i, i + 1) + strResult;
			tam = tam + 1;
		}
		strResult = strResult + strphanthapphan;
	}
	return strResult;
}

function GetNumber(str) {
	var count = 0;
	for (var i = 0; i < str.length; i++) {
		var temp = str.substring(i, i + 1);
		if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
			alert(inputnumber);
			return str.substring(0, i);
		}
		if (temp == " ")
			return str.substring(0, i);
		if (temp == ".") {
			if (count > 0)
				return str.substring(0, ipubl_date);
			count++;
		}
	}
	return str;
}

function IsNumberInt(str) {
	for (var i = 0; i < str.length; i++) {
		var temp = str.substring(i, i + 1);
		if (!(temp == "." || (temp >= 0 && temp <= 9))) {
			alert(inputnumber);
			return str.substring(0, i);
		}
		if (temp == ",") {
			return str.substring(0, i);
		}
	}
	return str;
}

$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input', this).clearForm();
		if (type == 'text' || type == 'password' || tag == 'textarea')
			this.value = '';
		else if (tag == 'select')
			this.selectedIndex = 0;
	});
};