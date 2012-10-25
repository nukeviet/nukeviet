/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 9:36
 */

function nv_admin_add_result(form_id, go, tp){
    var formid = document.getElementById(form_id);
    var input_go = document.getElementById(go);
    input_go.value = (tp == 2) ? "sendmail" : "savefile";
    formid.submit();
    return false;
}

function nv_admin_edit_result(form_id, go, tp){
    var formid = document.getElementById(form_id);
    var input_go = document.getElementById(go);
    input_go.value = (tp == 2) ? "sendmail" : "savefile";
    formid.submit();
    return false;
}