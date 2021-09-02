<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$page_title = $lang_module['xcopyblock'];

$selectthemes = $nv_Request->get_string('selectthemes', 'cookie', '');
$op = $nv_Request->get_string(NV_OP_VARIABLE, 'get', '');

$xtpl = new XTemplate('xcopyblock.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('CHECKSS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']));
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);

$result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
while (list($theme) = $result->fetch(3)) {
    if (in_array($theme, $theme_list, true)) {
        $xtpl->assign('THEME_FROM', $theme);
        $xtpl->parse('main.theme_from');

        $xtpl->assign('THEME_TO', ['key' => $theme, 'selected' => ($selectthemes == $theme and $selectthemes != 'default') ? ' selected="selected"' : '']);
        $xtpl->parse('main.theme_to');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
