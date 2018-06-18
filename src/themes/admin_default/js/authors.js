/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_admin_add_result(form_id, go, tp) {
	var formid = document.getElementById(form_id);
	var input_go = document.getElementById(go);
	input_go.value = (tp == 2) ? "sendmail" : "savefile";
	formid.submit();
	return false;
}

function nv_admin_edit_result(form_id, go, tp) {
	var formid = document.getElementById(form_id);
	var input_go = document.getElementById(go);
	input_go.value = (tp == 2) ? "sendmail" : "savefile";
	formid.submit();
	return false;
}

function nv_chang_weight(mid) {
	var nv_timer = nv_settimeout_disable('id_weight_' + mid, 5000);
	var new_vid = $("#id_weight_" + mid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changeweight=' + mid + '&new_vid=' + new_vid, function(res) {
		$("#main_module").html(res);
	});
	return;
}

function nv_chang_act(mid, act) {
	if (confirm(nv_is_change_act_confirm[0])) {
		var nv_timer = nv_settimeout_disable('change_act_' + act + '_' + mid, 5000);
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changact=' + act + '&mid=' + mid, function(res) {
			nv_set_disable_false('change_act_' + act + '_' + mid);
		});
	} else {
		var sl = document.getElementById('change_act_' + act + '_' + mid);
		sl.checked = (sl.checked == true) ? false : true;
	}
	return;
}

function apiRoleChanged() {
    var totalApis = 0;
    $('[data-toggle="apicat"]').each(function() {
        var $this = $(this);
        var ctnItem = $($this.attr('href'));
        var total = ctnItem.find('[data-toggle="apiroleit"]:checked').length;
        if (total > 0) {
            totalApis = totalApis + total;
            var textEle = $this.find('span');
            if (textEle.length) {
                textEle.html('(' + total + ')');
            } else {
                $this.append(' <span>(' + total + ')</span>');
            }
        } else {
            $this.find('span').remove();
        }
    });
    if (totalApis > 0) {
        var textEle = $('#apiRoleAll').find('span');
        if (textEle.length) {
            textEle.html('(' + totalApis + ')');
        } else {
            $('#apiRoleAll').append(' <span>(' + totalApis + ')</span>');
        }
    } else {
        $('#apiRoleAll').find('span').remove();
    }
}

$("#checkall").click(function(){
	$("input[name='modules[]']:checkbox").prop("checked", true);
});
$("#uncheckall").click(function() {
	$("input[name='modules[]']:checkbox").prop("checked", false);
});

$(document).ready(function() {
    $('[data-toggle="apiroleit"]').change(function() {
        apiRoleChanged();
    });
    $('[data-toggle="apicat"]').click(function(e) {
        e.preventDefault();
        $('[data-toggle="apicat"]').removeClass('active');
        $(this).addClass('active');
        $('[data-toggle="apichid"]').hide();
        $($(this).attr('href')).show();
        $('[name="current_cat"]').val($(this).data('cat'));
    });
    $('[data-toggle="apicheck"]').click(function(e) {
        e.preventDefault();
        $($(this).attr('href')).find('[type="checkbox"]').prop('checked', true);
        apiRoleChanged();
    });
    $('[data-toggle="apiuncheck"]').click(function(e) {
        e.preventDefault();
        $($(this).attr('href')).find('[type="checkbox"]').prop('checked', false);
        apiRoleChanged();
    });
    $('[data-toggle="apiroledetail"]').click(function(e) {
        e.preventDefault();
        modalShow($(this).data('title'), $($(this).attr('href')).html());
    });
    $('[data-toggle="apiroledel"]').click(function(e) {
        e.preventDefault();
    	if (confirm(nv_is_del_confirm[0])) {
    		$.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=api-roles&nocache=' + new Date().getTime(), 'del=1&role_id=' + $(this).data('id'), function(res) {
    			if (res == 'OK') {
    				location.reload();
    			} else {
    				alert(nv_is_del_confirm[2]);
    			}
    		});
    	}
    });
    $('[data-toggle="apicerdel"]').click(function(e) {
        e.preventDefault();
    	if (confirm(nv_is_del_confirm[0])) {
    		$.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=api-credentials&nocache=' + new Date().getTime(), 'del=1&credential_ident=' + $(this).data('id'), function(res) {
    			if (res == 'OK') {
    				location.reload();
    			} else {
    				alert(nv_is_del_confirm[2]);
    			}
    		});
    	}
    });
});
