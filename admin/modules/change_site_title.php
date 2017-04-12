<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/7/2010 2:23
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

if (! $nv_Request->isset_request('id', 'post,get')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);

$sql = 'SELECT f.func_name AS func_title,f.func_site_title AS func_site_title,m.custom_title AS mod_custom_title FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (empty($row)) {
    die('NO_' . $id);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $func_site_title = $nv_Request->get_title('func_site_title', 'post', '');
    
    $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET func_site_title= :func_site_title WHERE func_id=' . $id);
    $sth->bindParam(':func_site_title', $func_site_title, PDO::PARAM_STR);
    $sth->execute();
    
    $nv_Cache->delMod('modules');

    die('OK|show_funcs|action');
} else {
    $func_site_title = $row['func_site_title'];
}

$contents = array();
$contents['caption'] = sprintf($lang_module['change_func_sitetitle'], $row['func_title'], $row['mod_custom_title']);
$contents['func_site_title'] = array( $lang_module['site_title'], $func_site_title, 255, 'func_site_title' );
$contents['submit'] = array( $lang_global['submit'], "nv_change_site_title_submit( " . $id . ",'func_site_title' );" );
$contents['cancel'] = array( $lang_global['cancel'], "nv_action_cancel('action');" );

$contents = call_user_func('change_site_title_theme', $contents);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';