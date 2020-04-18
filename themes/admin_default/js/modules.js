/**
 * @Project NUKEVIET 4.x
 * @Author VINADES ( contact@vinades.vn )
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2 - 10 - 2010 16 : 3
 */

function nv_show_list_mods() {
	if (document.getElementById('list_mods')) {
		$("#list_mods").load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list&nocache=' + new Date().getTime());
	}
	return;
}

function nv_chang_func_in_submenu(func_id) {
	var nv_timer = nv_settimeout_disable('chang_func_in_submenu_' + func_id, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&nocache=' + new Date().getTime(), 'id=' + func_id, function(res) {
		var r_split = res.split("_");
		var sl = document.getElementById('chang_func_in_submenu_' + r_split[1]);
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			if (sl.checked == true)
				sl.checked = false;
			else
				sl.checked = true;
			clearTimeout(nv_timer);
			sl.disabled = true;
		}
	});
	return;
}

function nv_chang_weight(modname) {
	var nv_timer = nv_settimeout_disable('change_weight_' + modname, 5000);
	var new_weight = $("#change_weight_" + modname).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&nocache=' + new Date().getTime(), 'mod=' + modname + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("_");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_mods();
	});
	return;
}

function nv_chang_act(modname) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_act_' + modname, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act&nocache=' + new Date().getTime(), 'mod=' + modname, function(res) {
			var r_split = res.split("_");
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			nv_show_list_mods();
		});
	} else {
		var sl = document.getElementById('change_act_' + modname);
		sl.checked = (sl.checked == true) ? false : true;
	}
	return;
}

function nv_mod_del(modname) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(), 'mod=' + modname, function(res) {
			var r_split = res.split("_");
			if (r_split[0] == 'OK') {
				window.location.href = script_name + '?' + nv_name_variable + '=modules&' + nv_randomPassword(6) + '=' + nv_randomPassword(8);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_show_funcs(show_id) {
	if (document.getElementById(show_id)) {
        var href = strHref;
        if (href.indexOf("#") > -1) {
        	var strHref_split = href.split("#");
        	href = strHref_split[0];
        }
		$("#" + show_id).load(href + "&aj=show_funcs&nocache=" + new Date().getTime());
	}
	return;
}

function nv_chang_func_weight(func_id) {
	var nv_timer = nv_settimeout_disable('change_weight_' + func_id, 5000);
	var new_weight = $("#change_weight_" + func_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_weight&nocache=' + new Date().getTime(), 'fid=' + func_id + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_funcs(r_split[1]);
	});
	return;
}

function nv_chang_func_in_submenu(func_id) {
	var nv_timer = nv_settimeout_disable('chang_func_in_submenu_' + func_id, 5000);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&nocache=' + new Date().getTime(), 'id=' + func_id, function(res) {
		var r_split = res.split("_");
		var sl = document.getElementById('chang_func_in_submenu_' + r_split[1]);
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			if (sl.checked == true)
				sl.checked = false;
			else
				sl.checked = true;
			clearTimeout(nv_timer);
			sl.disabled = true;
		}
	});
	return;
}

function nv_change_custom_name(func_id, containerid) {
	$("#" + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&id=' + func_id + '&nocache=' + new Date().getTime());
	return;
}

function nv_change_custom_name_submit(func_id, custom_name_id) {
	var new_custom_name = rawurlencode(document.getElementById(custom_name_id).value);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&nocache=' + new Date().getTime(), 'id=' + func_id + '&save=1&func_custom_name=' + new_custom_name, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		} else {
			nv_show_funcs(r_split[1]);
			nv_action_cancel(r_split[2]);
		}
	});
	return;
}

function nv_change_site_title(func_id, containerid) {
	$("#" + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_site_title&id=' + func_id + '&nocache=' + new Date().getTime());
	return;
}

function nv_change_site_title_submit(func_id, site_title_id) {
	var new_site_title = rawurlencode(document.getElementById(site_title_id).value);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_site_title&nocache=' + new Date().getTime(), 'id=' + func_id + '&save=1&func_site_title=' + new_site_title, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		} else {
			nv_show_funcs(r_split[1]);
			nv_action_cancel(r_split[2]);
		}
	});
	return;
}

function nv_change_alias(func_id, containerid) {
	$("#" + containerid).load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_alias&id=' + func_id + '&nocache=' + new Date().getTime());
	return;
}

function nv_change_alias_submit(func_id, custom_name_id) {
	var new_custom_name = rawurlencode(document.getElementById(custom_name_id).value);
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_alias&nocache=' + new Date().getTime(), 'id=' + func_id + '&save=1&fun_alias=' + new_custom_name, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		} else {
			nv_show_funcs(r_split[1]);
			nv_action_cancel(r_split[2]);
		}
	});
	return;
}

function nv_action_cancel(containerid) {
	document.getElementById(containerid).innerHTML = '';
	return;
}

function nv_chang_bl_weight(bl_id) {
	var nv_timer = nv_settimeout_disable('change_bl_weight_' + bl_id, 5000);
	var new_weight = $("#change_bl_weight_" + bl_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block_weight&nocache=' + new Date().getTime(), 'id=' + bl_id + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("|");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_bl_list(r_split[1], r_split[2], r_split[3]);
	});
	return;
}

function nv_show_bl(bl_id, containerid) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=show_block&nocache=' + new Date().getTime(), 'id=' + bl_id, function(res) {
		$("#" + containerid).html(res);
	});
	return;
}

function nv_del_bl(bl_id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.get(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block&id=' + bl_id + '&nocache=' + new Date().getTime(), function(res) {
			var r_split = res.split("|");
			if (r_split[0] == 'OK') {
				nv_bl_list(r_split[1], r_split[2], r_split[3]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

$(document).ready(function(){
	// Re-install module
	$('body').delegate( '.nv-reinstall-module', 'click', function(e){
		e.preventDefault();
		$('#modal-reinstall-module').data('title', $(this).data('title')).modal('toggle');
	});

	$('#modal-reinstall-module').on('show.bs.modal', function(e){
		var $this = $(this);
		
		$this.find('.load').removeClass('hidden');
		$this.find('.content').addClass('hidden');
		$this.find('.submit').prop('disabled', true);
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=check_sample_data&nocache=' + new Date().getTime(),
			data: 'module=' + $this.data('title'),
			dataType: 'json',
			success: function(e){
				if( e.status == 'success' ){
					var option = $this.find('option');
					option.removeClass('hidden');
					option.prop('selected', false);
					if( e.code != 1 ){
						$this.find('.showoption').addClass('hidden');
						$(option[1]).addClass('hidden');
					}else{
						$this.find('.showoption').removeClass('hidden');
					}
					$this.find('.message').html( e.message.join('. ') + '.' );
					$this.find('.load').addClass('hidden');
					$this.find('.content').removeClass('hidden');
					$this.find('.submit').prop('disabled', false);
				}
			}
		});
	});
	
	// Submit re-install
	$('#modal-reinstall-module .submit').click(function(){
		var $container = $('#modal-reinstall-module');
		var $this = $(this);
		
		$this.prop('disabled', true);
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=recreate_mod&nocache=' + new Date().getTime(),
			data: 'mod=' + $container.data('title') + '&sample=' + $container.find('.option').val(),
			success: function(e){
				$container.modal('hide');
				var r_split = e.split("_");
				if (r_split[0] != 'OK') {
					alert(nv_is_recreate_confirm[2]);
				} else {
					alert(nv_is_recreate_confirm[1]);
					nv_show_list_mods();
				}
			}
		});
	});
	
	// Setup module
	$('.nv-setup-module').click(function(e){
		e.preventDefault();
		
		var $this = $(this);
		var $container = $('#modal-setup-module');
		var link = $this.prop('href');
		
		$('#modal-setup-module').data('link', link);
		
		if( $this.prev().is('.fa-spin') || link == '' || link == '#' || link.match(/javascript\:void/g) ){
			return;
		}
		
		$this.prev().addClass('fa-spin');
		
		$.ajax({
			type: 'POST',
			cache: false,
			url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=check_sample_data&nocache=' + new Date().getTime(),
			data: 'module=' + $this.data('title') + '&setup=1',
			dataType: 'json',
			success: function(e){
				$this.prev().removeClass('fa-spin');
				
				if( e.status == 'success' ){
					if( e.code == 0 ){
						window.location = link;
						return;
					}
					
					$container.find('.message').html( e.message.splice(1, 2).join('. ') + '.' );
					$container.modal('show');
				}
			}
		});
	});
	
	// Submit setup option
	$('#modal-setup-module .submit').click(function(){
		var $this = $('#modal-setup-module');
		$this.modal('hide');
		window.location = $this.data('link') + '&sample=' + $this.find('.option').val();
	});
});