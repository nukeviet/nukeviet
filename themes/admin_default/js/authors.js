/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
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
    var checkss = $("input[name='checkss']").val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changeweight=' + mid + '&new_vid=' + new_vid + '&checkss=' + checkss, function(res) {
        $("#main_module").html(res);
    });
    return;
}

function nv_chang_act(mid, act) {
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_act_' + act + '_' + mid, 5000);
        var checkss = $("input[name='checkss']").val();
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changact=' + act + '&mid=' + mid + '&checkss=' + checkss, function(res) {
            nv_set_disable_false('change_act_' + act + '_' + mid);
        });
    } else {
        var sl = document.getElementById('change_act_' + act + '_' + mid);
        sl.checked = (sl.checked == true) ? false : true;
    }
    return;
}

// Xóa hết Oauth của quản trị
function nv_del_oauthall(userid, tokend) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=2step&admin_id=' + userid + '&nocache=' + new Date().getTime(), 'delall=' + tokend, function(res) {
            if (res == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

// Xóa một Oauth của quản trị
function nv_del_oauthone(id, userid, tokend) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=2step&admin_id=' + userid + '&nocache=' + new Date().getTime(), 'del=' + tokend + '&id=' + id, function(res) {
            if (res == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

$(document).ready(function() {
    $('.number').on('input', function() {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    })
});
