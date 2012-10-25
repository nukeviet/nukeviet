/* *
 * @Project NUKEVIET 3.x
 * @Author VINADES ( contact@vinades.vn )
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2 - 10 - 2010 16 : 3
 */
function nv_show_list_mods(){
    if (document.getElementById('list_mods')) {
        nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list&num=' + nv_randomPassword(8), 'list_mods');
    }
    return;
}

//  ---------------------------------------

function nv_chang_in_menu(modname){
    var nv_timer = nv_settimeout_disable('change_inmenu_' + modname, 5000);
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_inmenu&mod=' + modname + '&num=' + nv_randomPassword(8), '', 'nv_chang_in_menu_res');
    return;
}

//  ---------------------------------------

function nv_chang_in_menu_res(res){
    var r_split = res.split("_");
    var sl = document.getElementById('change_inmenu_' + r_split[1]);
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        if (sl.checked == true) 
            sl.checked = false;
        else 
            sl.checked = true;
        clearTimeout(nv_timer);
        sl.disabled = true;
        return;
    }
    return;
}

//  ---------------------------------------

function nv_chang_submenu(modname){
    var nv_timer = nv_settimeout_disable('change_submenu_' + modname, 5000);
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_submenu&mod=' + modname + '&num=' + nv_randomPassword(8), '', 'nv_chang_submenu_res');
    return;
}

//  ---------------------------------------

function nv_chang_submenu_res(res){
    var r_split = res.split("_");
    var sl = document.getElementById('change_submenu_' + r_split[1]);
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        if (sl.checked == true) 
            sl.checked = false;
        else 
            sl.checked = true;
        clearTimeout(nv_timer);
        sl.disabled = true;
        return;
    }
    return;
}

//  ---------------------------------------

function nv_chang_weight(modname){
    var nv_timer = nv_settimeout_disable('change_weight_' + modname, 5000);
    var new_weight = document.getElementById('change_weight_' + modname).options[document.getElementById('change_weight_' + modname).selectedIndex].value;
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&mod=' + modname + '&new_weight=' + new_weight + '&num=' + nv_randomPassword(8), '', 'nv_chang_weight_res');
    return;
}

//  ---------------------------------------

function nv_chang_weight_res(res){
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    nv_show_list_mods();
    return;
}

//  ---------------------------------------

function nv_chang_act(modname){
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_act_' + modname, 5000);
        nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act&mod=' + modname + '&num=' + nv_randomPassword(8), '', 'nv_chang_act_res');
    }
    else {
        var sl = document.getElementById('change_act_' + modname);
        sl.checked = (sl.checked == true) ? false : true;
    }
    return;
}

//  ---------------------------------------

function nv_chang_act_res(res){
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    nv_show_list_mods();
    return;
}

//  ---------------------------------------

function nv_recreate_mod(modname){
    if (confirm(nv_is_recreate_confirm[0])) {
        nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=recreate_mod&mod=' + modname + '&num=' + nv_randomPassword(8), '', 'nv_recreate_mod_res');
    }
    return;
}

//  ---------------------------------------

function nv_recreate_mod_res(res){
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_recreate_confirm[2]);
    }
    else {
        alert(nv_is_recreate_confirm[1]);
        nv_show_list_mods();
    }
    return;
}

//  ---------------------------------------

function nv_mod_del(modname){
    if (confirm(nv_is_del_confirm[0])) {
        nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&mod=' + modname, '', 'nv_mod_del_result');
    }
    return false;
}

//  ---------------------------------------

function nv_mod_del_result(res){
    var r_split = res.split("_");
    if (r_split[0] == 'OK') {
        window.location.href = script_name + '?' + nv_name_variable + '=modules&' + nv_randomPassword(6) + '=' + nv_randomPassword(8);
    }
    else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}

//  ---------------------------------------

function nv_show_funcs(show_id){
    if (document.getElementById(show_id)) {
        nv_ajax("get", strHref, 'aj=show_funcs&num=' + nv_randomPassword(8), show_id);
    }
    return;
}

//  ---------------------------------------

function nv_chang_func_weight(func_id){
    var nv_timer = nv_settimeout_disable('change_weight_' + func_id, 5000);
    var new_weight = document.getElementById('change_weight_' + func_id).options[document.getElementById('change_weight_' + func_id).selectedIndex].value;
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_weight&fid=' + func_id + '&new_weight=' + new_weight + '&num=' + nv_randomPassword(8), '', 'nv_chang_func_weight_res');
    return;
}

//  ---------------------------------------

function nv_chang_func_weight_res(res){
    var r_split = res.split("|");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    nv_show_funcs(r_split[1]);
    return;
}

//  ---------------------------------------

function nv_chang_func_in_submenu(func_id){
    var nv_timer = nv_settimeout_disable('chang_func_in_submenu_' + func_id, 5000);
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&id=' + func_id + '&num=' + nv_randomPassword(8), '', 'nv_chang_func_in_submenu_res');
    return;
}

//  ---------------------------------------

function nv_chang_func_in_submenu_res(res){
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
    return;
}

//  ---------------------------------------

function nv_change_custom_name(func_id, containerid){
    nv_ajax("get", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&id=' + func_id + '&num=' + nv_randomPassword(8), containerid);
    return;
}

//  ---------------------------------------

function nv_change_custom_name_submit(func_id, custom_name_id){
    var new_custom_name = rawurlencode(document.getElementById(custom_name_id).value);
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_custom_name&id=' + func_id + '&save=1&func_custom_name=' + new_custom_name + '&num=' + nv_randomPassword(8), '', 'nv_change_custom_name_res');
    return;
}

//  ---------------------------------------

function nv_change_custom_name_res(res){
    var r_split = res.split("|");
    var sl = document.getElementById('chang_func_in_submenu_' + r_split[1]);
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    else {
        nv_show_funcs(r_split[1]);
        nv_action_cancel(r_split[2]);
    }
    return;
}

//  ---------------------------------------

function nv_action_cancel(containerid){
    document.getElementById(containerid).innerHTML = '';
    return;
}

//  ---------------------------------------

function nv_chang_bl_weight(bl_id){
    var nv_timer = nv_settimeout_disable('change_bl_weight_' + bl_id, 5000);
    var new_weight = document.getElementById('change_bl_weight_' + bl_id).options[document.getElementById('change_bl_weight_' + bl_id).selectedIndex].value;
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_block_weight&id=' + bl_id + '&new_weight=' + new_weight + '&num=' + nv_randomPassword(8), '', 'nv_chang_bl_weight_res');
    return;
}

//  ---------------------------------------

function nv_chang_bl_weight_res(res){
    var r_split = res.split("|");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
    }
    clearTimeout(nv_timer);
    nv_bl_list(r_split[1], r_split[2], r_split[3]);
    return;
}

//  ---------------------------------------

function nv_show_bl(bl_id, containerid){
    nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=show_block&id=' + bl_id + '&num=' + nv_randomPassword(8), containerid);
    return;
}

//  ---------------------------------------

function nv_del_bl(bl_id){
    if (confirm(nv_is_del_confirm[0])) {
        nv_ajax('get', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_block&id=' + bl_id, '', 'nv_del_bl_res');
    }
    return false;
}

//  ---------------------------------------

function nv_del_bl_res(res){
    var r_split = res.split("|");
    if (r_split[0] == 'OK') {
        nv_bl_list(r_split[1], r_split[2], r_split[3]);
    }
    else {
        alert(nv_is_del_confirm[2]);
    }
    return false;
}