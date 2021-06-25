<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if (!$nv_Request->isset_request('id', 'post,get')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);

$sql = 'SELECT f.func_name AS func_title,f.func_custom_name AS func_custom_title,m.custom_title AS mod_custom_title FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (empty($row)) {
    exit('NO_' . $id);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $func_custom_name = $nv_Request->get_title('func_custom_name', 'post', '', 1);

    if (empty($func_custom_name)) {
        $func_custom_name = ucfirst($row['func_name']);
    }

    $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET func_custom_name= :func_custom_name WHERE func_id=' . $id);
    $sth->bindParam(':func_custom_name', $func_custom_name, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('modules');

    exit('OK|show_funcs|action');
}
    $func_custom_name = $row['func_custom_title'];

$contents = [];
$contents['caption'] = sprintf($lang_module['change_func_name'], $row['func_title'], $row['mod_custom_title']);
$contents['func_custom_name'] = [$lang_module['funcs_custom_title'], $func_custom_name, 255, 'func_custom_name'];
$contents['submit'] = [$lang_global['submit'], 'nv_change_custom_name_submit( ' . $id . ",'func_custom_name' );"];
$contents['cancel'] = [$lang_global['cancel'], "nv_action_cancel('action');"];

$contents = call_user_func('change_custom_name_theme', $contents);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
