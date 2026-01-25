<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if (count($site_mods) < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup');
}

$page_title = $nv_Lang->getModule('main');

$act_modules = $deact_modules = $bad_modules = $weight_list = [];
$modules_exit = array_flip(nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']));

// Danh sách module có trong hệ thống
$new_modules = [];

$sql = 'SELECT title, basename, is_sys, version FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type=\'module\' ORDER BY title ASC';
$result = $db->query($sql);

$is_delCache = false;
$act2 = [];
while ([$m, $mod_file, $is_sys, $version] = $result->fetch(3)) {
    $new_modules[$m] = [
        'module_file' => $mod_file,
        'is_sys' => $is_sys,
        'version' => $version
    ];

    if (!isset($modules_exit[$mod_file])) {
        $act2[] = $m;
    }
}

if (!empty($act2)) {
    $act2 = "'" . implode("','", $act2) . "'";
    $db->query('UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title IN (' . $act2 . ')');
    $is_delCache = true;
}

// Danh sách module đã cài trên ngôn ngữ data hiện tại
$iw = 0;
$sql = 'SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
$result = $db->query($sql);

while ($row = $result->fetch()) {
    ++$iw;
    if ($iw != $row['weight']) {
        $row['weight'] = $iw;
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $row['weight'] . ' WHERE title= :title');
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->execute();
        $is_delCache = true;
    }

    $mod = [];
    $m = $row['module_file'];
    $mf = $row['module_file'];

    if (!isset($new_modules[$mf])) {
        $row['act'] == 2;
        $row['is_sys'] = '';
        $row['version'] = '';
    } else {
        $row['is_sys'] = $new_modules[$row['module_file']]['is_sys'];
        $row['version'] = $new_modules[$row['module_file']]['version'];
    }

    if ($row['title'] == $global_config['site_home_module']) {
        $row['is_sys'] = 1;
        $mod['act_allowed'] = 0;
    } else {
        $mod['act_allowed'] = 1;
    }

    $weight_list[] = $row['weight'];

    $mod['title'] = $row['title'];
    $mod['version'] = preg_replace_callback('/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/', 'nv_parse_vers', $row['version']);
    $mod['custom_title'] = $row['custom_title'];
    $mod['weight'] = $row['weight'];
    $mod['act'] = $row['act'];
    $mod['act_checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_change_act_' . $row['title']);
    $mod['del_checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_del_' . $row['title']);
    $mod['edit'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;mod=' . $row['title'], $nv_Lang->getGlobal('edit')];
    $mod['del'] = ($row['is_sys'] == 0 or $row['title'] != $row['module_file']) ? 1 : 0;

    if ($row['act'] == 1) {
        $act_modules[$row['title']] = $mod;
    } elseif ($row['act'] == 2) {
        $bad_modules[$row['title']] = $mod;
    } elseif ($row['act'] == 0) {
        $deact_modules[$row['title']] = $mod;
    }
}
$result->closeCursor();

if ($is_delCache) {
    $nv_Cache->delMod('modules');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('ARRAY', [
    'act' => $act_modules,
    'deact' => $deact_modules,
    'bad' => $bad_modules
]);
$tpl->assign('WEIGHT_LIST', $weight_list);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
