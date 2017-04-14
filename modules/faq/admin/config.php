<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

$array_config = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config['type_main'] = $nv_Request->get_int('type_main', 'post', 0);
	$array_config['user_post'] = $nv_Request->get_int('user_post', 'post', 0);
    foreach ($array_config as $config_name => $config_value) {
        $query = "REPLACE INTO " . NV_PREFIXLANG . "_" . $module_data . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
        $db->query($query);
    }

    $nv_Cache->delMod($module_name);

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

$array_config['type_main'] = 0;

$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$result = $db->query($sql);
while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config[$c_config_name] = $c_config_value;
}

//
$type_main = $array_config['type_main'];
$array_config['type_main'] = array();
$array_config['type_main'][] = array( //
    'key' => 0, //
    'title' => $lang_module['config_type_main_0'], //
    'selected' => 0 == $type_main ? " selected=\"selected\"" : "" //
);
$array_config['type_main'][] = array( //
    'key' => 1, //
    'title' => $lang_module['config_type_main_1'], //
    'selected' => 1 == $type_main ? " selected=\"selected\"" : "" //
);
$array_config['type_main'][] = array( //
    'key' => 2, //
    'title' => $lang_module['config_type_main_2'], //
    'selected' => 2 == $type_main ? " selected=\"selected\"" : "" //
);

$xtpl = new XTemplate("config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('USERPOST', $array_config['user_post'] ? ' checked="checked"' : '');

//
foreach ($array_config['type_main'] as $type_main) {
    $xtpl->assign('TYPE_MAIN', $type_main);
    $xtpl->parse('main.type_main');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
