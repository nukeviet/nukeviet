/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_change_weight_res(res) {
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        clearTimeout(nv_timer);
    } else {
        window.location.href = window.location.href;
    }
    return;
}

function nv_change_weight(lang) {
    var nv_timer = nv_settimeout_disable('change_weight_' + lang, 5000);
    var new_weight = $('#change_weight_' + lang).val();
    $.post(
        script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 
        'changeweight=1&keylang=' + lang + '&new_weight=' + new_weight, function(res) {
        nv_change_weight_res(res);
    });
    return;
}
