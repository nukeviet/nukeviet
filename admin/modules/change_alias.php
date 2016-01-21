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

$sql = 'SELECT f.func_name AS func_title,f.func_custom_name AS func_custom_title,f.alias AS fun_alias, m.custom_title AS mod_custom_title FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (! isset($row['func_title']) or $row['func_title']=='main') {
    die('NO_' . $id);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $fun_alias = $nv_Request->get_title('fun_alias', 'post', '', 1);

    if (empty($fun_alias)) {
        $fun_alias = $row['func_title'];
    }
    $fun_alias = strtolower(change_alias($fun_alias));

    $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET alias= :alias WHERE func_id=' . $id);
    $sth->bindParam(':alias', $fun_alias, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('modules');

    die('OK|show_funcs|action');
} else {
    $fun_alias = $row['fun_alias'];
}

$contents = array();
$contents['caption'] = sprintf($lang_module['change_fun_alias'], $row['func_title'], $row['mod_custom_title']);
$contents['func_custom_name'] = array( $lang_module['funcs_alias'], $fun_alias, 255, 'fun_alias' );
$contents['submit'] = array( $lang_global['submit'], "nv_change_alias_submit( " . $id . ",'fun_alias' );" );
$contents['cancel'] = array( $lang_global['cancel'], "nv_action_cancel('action');" );

$contents = change_custom_name_theme($contents);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';