/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */

function nv_link_settitle(alias, module) {
	var nv_timer = nv_settimeout_disable('item_name_' + alias, 2000);
	var new_status = $("#item_name_" + alias).val();
	if (new_status != 0) {
		$('input#module').val(module);
		$('input#op').val(new_status);
		$('input#link').val(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + module + "&" + nv_fc_variable + "=" + new_status);
		var new_text = document.getElementById('item_name_' + alias).options[document.getElementById('item_name_' + alias).selectedIndex].text;
		$('input#title').val(trim(new_text));
	}
	return;
}

function nv_link_module(module) {
	var nv_timer = nv_settimeout_disable('module_name_' + module, 2000);
	var new_status = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].value;
	var new_text = document.getElementById('module_name_' + module).options[document.getElementById('module_name_' + module).selectedIndex].text;

	$('input#title').val(trim(new_text));
	if (new_status != 0) {
		$('input#link').val(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + new_status);
		$('input#module').val(new_status);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=link_module&nocache=' + new Date().getTime(), 'module=' + new_status, function(res) {
			$('#thu').html(res);
		});
	} else {
		$('input#link').val('');
		$('#thu').hide();
	}
}

function nv_link_menu(blog_menu) {
	var nv_timer = nv_settimeout_disable('item_menu_' + blog_menu, 2000);
	var new_status = document.getElementById('item_menu_' + blog_menu).options[document.getElementById('item_menu_' + blog_menu).selectedIndex].value;
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=link_menu&nocache=' + new Date().getTime(), 'mid=' + new_status, function(res) {
		$('#parentid').html(res);
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
	if (num) {
		alert(cat + num + caton);
	} else if (confirm(nv_is_del_confirm[0])) {
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