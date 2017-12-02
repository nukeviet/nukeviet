/* *
 * @Project NUKEVIET 4.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

function nv_add_files(nv_admin_baseurl, nv_files_dir, nv_lang_delete, nv_lang_select) {
    nv_num_files++;
    $('#filearea').append('<div id="fileitem_' + nv_num_files + '" style="margin-bottom: 5px">' + '<input class="form-control pull-left w400" style="margin: 4px 4px 0 0;" type="text" name="files[]" id="fileupload_' + nv_num_files + '" value="" />' + '<input onclick="nv_open_browse( \'' + nv_admin_baseurl + 'index.php?' + nv_name_variable + '=upload&popup=1&area=fileupload_' + nv_num_files + '&path=' + nv_files_dir + '&type=file\', \'NVImg\', \'850\', \'500\', \'resizable=no,scrollbars=no,toolbar=no,location=no,status=no\' );return false;" type="button" value="Browse server" class="selectfile btn btn-primary" style="margin-right: 3px" />' + '<input onclick="nv_delete_datacontent(\'fileitem_' + nv_num_files + '\');return false;" type="button" value="' + nv_lang_delete + '" class="selectfile btn btn-danger" />' + '</div>');
    
    return false;
}

function nv_delete_datacontent(content) {
    $('#' + content).remove();
    return false;
}

function nv_change_status(id) {
    var nv_timer = nv_settimeout_disable('status_' + id, 4000);
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'changestatus=1&id=' + id, function(res) {
        if (res != 'OK') {
            alert(nv_is_change_act_confirm[2]);
            window.location.href = window.location.href;
        }
        return;
    });
    return;
}

function nv_delete_law(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            if (res == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_delete_signer(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=signer&nocache=' + new Date().getTime(), 'del=1&id=' + id, function(res) {
            if (res == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_chang_cat(catid, mod) {
    var nv_timer = nv_settimeout_disable('id_' + mod + '_' + catid, 5000);
    var new_vid = $('#id_' + mod + '_' + catid).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        if (res != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        clearTimeout(nv_timer);
        window.location.href = window.location.href;
        return;
    });
    return;
}

function check_add_first() {
    $(this).one("dblclick", check_add_second);
    $("input[name='add_content[]']:checkbox").prop("checked", true);
}

function check_add_second() {
    $(this).one("dblclick", check_add_first);
    $("input[name='add_content[]']:checkbox").prop("checked", false);
}

function check_app_first() {
    $(this).one("dblclick", check_app_second);
    $("input[name='app_content[]']:checkbox").prop("checked", true);
}

function check_app_second() {
    $(this).one("dblclick", check_app_first);
    $("input[name='app_content[]']:checkbox").prop("checked", false);
}

function check_pub_first() {
    $(this).one("dblclick", check_pub_second);
    $("input[name='pub_content[]']:checkbox").prop("checked", true);
}

function check_pub_second() {
    $(this).one("dblclick", check_pub_first);
    $("input[name='pub_content[]']:checkbox").prop("checked", false);
}

function check_edit_first() {
    $(this).one("dblclick", check_edit_second);
    $("input[name='edit_content[]']:checkbox").prop("checked", true);
}

function check_edit_second() {
    $(this).one("dblclick", check_edit_first);
    $("input[name='edit_content[]']:checkbox").prop("checked", false);
}

function check_del_first() {
    $(this).one("dblclick", check_del_second);
    $("input[name='del_content[]']:checkbox").prop("checked", true);
}

function check_del_second() {
    $(this).one("dblclick", check_del_first);
    $("input[name='del_content[]']:checkbox").prop("checked", false);
}

function check_admin_first() {
    $(this).one("dblclick", check_admin_second);
    $("input[name='admin_content[]']:checkbox").prop("checked", true);
}

function check_admin_second() {
    $(this).one("dblclick", check_admin_first);
    $("input[name='admin_content[]']:checkbox").prop("checked", false);
}
