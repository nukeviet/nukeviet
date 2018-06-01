/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */

function nv_link_settitle(alias, module) {
	var nv_timer = nv_settimeout_disable('module_sub_menu', 2000);
	var new_status = $("#module_sub_menu").val();
	if (new_status != 0) {
		$('input#module').val(module);
		$('input#op').val(new_status);
		$('input#link').val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module + "&" + nv_fc_variable + "=" + new_status);
		var new_text = document.getElementById('module_sub_menu').options[document.getElementById('module_sub_menu').selectedIndex].text;
		$('input#title').val(trim(new_text));
	}
	return;
}

function nv_link_module(module) {
	$('#module_name_' + module ).attr( 'readonly', 'readonly' );
	var new_status = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].value;
	var new_text = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].text;

	$('input#title').val(trim(new_text));
	if (new_status != 0) {
		$('input#link').val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + new_status);
		$('input#module').val(new_status);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=link_module&nocache=' + new Date().getTime(), 'module=' + new_status, function(res) {
			$('#thu').html(res);
			$('#module_sub_menu').select2();
		});
	} else {
		$('input#link').val('');
		$('#thu').hide();
	}
	setTimeout(function(){
		$('#module_name_' + module ).removeAttr( 'readonly' );
	}, 1000);
}

function nv_link_menu(blog_menu, parentid) {
	var nv_timer = nv_settimeout_disable('item_menu_' + blog_menu, 2000);
	var new_status = document.getElementById('item_menu_' + blog_menu).options[document.getElementById('item_menu_' + blog_menu).selectedIndex].value;
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=link_menu&nocache=' + new Date().getTime(), 'mid=' + new_status + '&parentid=' + parentid, function(res) {
		$('#parentid').html(res);
		$('#parentid').select2();
	});
}

function nv_menu_delete(id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_chang_weight_item(id, mid, parentid) {
	var nv_timer = nv_settimeout_disable('change_weight_' + id, 3000);
	var new_weight = document.getElementById('change_weight_' + id).options[document.getElementById('change_weight_' + id).selectedIndex].value;
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight_row&nocache=' + new Date().getTime(), 'id=' + id + '&mid=' + mid + '&parentid=' + parentid + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split('_');
		if (r_split[0] == 'OK') {
			window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rows&mid=' + r_split[2] + '&parentid=' + r_split[3];
		} else {
			alert(nv_is_del_confirm[2]);
		}
	});
	return;
}

function nv_menu_item_delete(id, mid, parentid, num) {
	var del_confirm = 0;
    if (num && confirm(cat + num + caton ) ) {
		del_confirm = 1;
	}else if (confirm(nv_is_del_confirm[0])) {
		del_confirm = 1;
	}
    if( del_confirm == 1 ){
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_row&nocache=' + new Date().getTime(), 'id=' + id + '&parentid=' + parentid + '&mid=' + mid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rows&mid=' + r_split[2] + '&parentid=' + r_split[3];
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
    }
	return false;
}

function nv_change_active( id )
{
	var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_active&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

		});
	}
	else
	{
		$('#change_active_' + id).prop('checked', new_status ? false : true );
	}
}

function nv_main_action(oForm, msgnocheck) {
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
				return true;
			}
		} else {
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '='+action+'&listid=' + listid + '&checkss=' + checkss;
		}
	} else {
		alert(msgnocheck);
	}
	return false;
}

function nv_menu_reload( mid, id, parentid, lang_confirm ){
	if (confirm( lang_confirm ) ) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rows&nocache=' + new Date().getTime(), 'reload=1&mid=' + mid + '&id=' + id, function(res) {
			var r_split = res.split('_');
			alert( r_split[1] );
			if (r_split[0] == 'OK') {
				window.location.href = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rows&mid=' + mid + '&parentid=' + r_split[3];
			}
		});
	}
}

$(document).ready(function(){
	$(".selectimg").click(function(){
		var area = $(this).data('area');
		var path = CFG.upload_current;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
});