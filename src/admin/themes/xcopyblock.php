<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 08-19-2010 12:55
 */

if (!defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('xcopyblock');

$selectthemes = $nv_Request->get_string('selectthemes', 'cookie', '');
$op = $nv_Request->get_string(NV_OP_VARIABLE, 'get', '');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

$theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);

$result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
$theme_dbs = [];
while (list($theme) = $result->fetch(3)) {
    if (in_array($theme, $theme_list)) {
        $theme_dbs[] = $theme;
    }
}

$tpl->assign('THEME_LIST', $theme_list);
$tpl->assign('THEME_DBS', $theme_dbs);
$tpl->assign('SELECTTHEME', $selectthemes);

$contents = $tpl->fetch('xcopyblock.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
