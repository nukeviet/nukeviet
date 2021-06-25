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

$act_modules = $deact_modules = $bad_modules = $weight_list = [];
$modules_exit = array_flip(nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']));

// Lay danh sach cac module co trong he thong
$new_modules = [];

$sql = 'SELECT title, basename, is_sys, version FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type=\'module\' ORDER BY title ASC';
$result = $db->query($sql);

$is_delCache = false;
$act2 = [];
while (list($m, $mod_file, $is_sys, $version) = $result->fetch(3)) {
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

// Lay danh sach cac module co trong ngon ngu
$modules_data = [];

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
        $mod['act'][2] = 1;
    }

    $weight_list[] = $row['weight'];

    $mod['title'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=show&amp;mod=' . $row['title'], $row['title']];
    $mod['version'] = preg_replace_callback('/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/', 'nv_parse_vers', $row['version']);
    $mod['custom_title'] = $row['custom_title'];
    $mod['weight'] = [$row['weight'], "nv_chang_weight('" . $row['title'] . "');"];
    $mod['act'] = [
        $row['act'],
        "nv_chang_act('" . $row['title'] . "', '" . md5(NV_CHECK_SESSION . '_' . $module_name . '_change_act_' . $row['title']) . "');"
    ];

    $mod['edit'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;mod=' . $row['title'], $lang_global['edit']];
    $mod['del'] = ($row['is_sys'] == 0 or $row['title'] != $row['module_file']) ? ["nv_mod_del('" . $row['title'] . "', '" . md5(NV_CHECK_SESSION . '_' . $module_name . '_del_' . $row['title']) . "');", $lang_global['delete']] : [];

    if ($row['title'] == $global_config['site_home_module']) {
        $row['is_sys'] = 1;
        $mod['act'][2] = 1;
    }

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

$contents = [];
$contents['caption'] = [$lang_module['caption_actmod'], $lang_module['caption_deactmod'], $lang_module['caption_badmod'], $lang_module['caption_newmod']];
$contents['thead'] = [$lang_module['weight'], $lang_module['module_name'], $lang_module['custom_title'], $lang_module['version'], $lang_global['activate'], $lang_global['actions']];

$contents = list_theme($contents, $act_modules, $deact_modules, $bad_modules, $weight_list);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
