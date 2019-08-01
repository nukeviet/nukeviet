<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 15:48
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$act_modules = $deact_modules = $bad_modules = $weight_list = [];
$modules_exit = array_flip(nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']));

// Lấy danh sách các plugin có trong hệ thống
$array_plugins = [];
/**
 * Để đoạn này thì nếu module khác có HOOK của module này => module này không xóa được
$sql = 'SELECT plugin_module_name, hook_module FROM ' . $db_config['prefix'] . '_plugin';
$result = $db->query($sql);

while ($row = $result->fetch()) {
    if (!isset($array_plugins[$row['hook_module']])) {
        $array_plugins[$row['hook_module']] = [];
    }
    $array_plugins[$row['hook_module']][] = $row['plugin_module_name'];
}
 */

// Lay danh sach cac module co trong he thong
$new_modules = [];

$sql = 'SELECT title, basename, is_sys, version FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type=\'module\' ORDER BY title ASC';
$result = $db->query($sql);

$is_delCache = false;
$act2 = [];
while (list($m, $mod_file, $is_sys, $version) = $result->fetch(3)) {
    $new_modules[$m] = array(
        'module_file' => $mod_file,
        'is_sys' => $is_sys,
        'version' => $version
    );

    if (!isset($modules_exit[$mod_file])) {
        $act2[] = $m;
    }
}

if (!empty($act2)) {
    $act2 = "'" . implode("','",$act2) . "'";
    $db->query("UPDATE " . NV_MODULES_TABLE . " SET act=2 WHERE title IN (".$act2.")");
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
    $_module_file = strtolower($row['title']);

    if (!isset($new_modules[$_module_file])) {
        $row['act'] == 2;
        $row['is_sys'] = 0;
        $row['version'] = '';
    } else {
        $row['is_sys'] = $new_modules[$_module_file]['is_sys'];
        $row['version'] = $new_modules[$_module_file]['version'];
    }

    if ($row['title'] == $global_config['site_home_module']) {
        $row['is_sys'] = 1;
    }

    $weight_list[] = $row['weight'];

    $mod['title'] = $row['title'];
    $mod['version'] = preg_replace_callback('/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/', 'nv_parse_vers', $row['version']);
    $mod['custom_title'] = $row['custom_title'];
    $mod['weight'] = $row['weight'];
    $mod['act'] = $row['act'];
    $mod['is_sys'] = $row['is_sys'];

    $mod['link_show'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=show&amp;mod=' . $row['title'];
    $mod['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;mod=' . $row['title'];
    $mod['allowed_del'] = (($row['is_sys'] == 0 or $row['title'] != $_module_file) and !isset($array_plugins[$row['title']])) ? true : false;

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

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$tpl->assign('ARR_MODULES', [
    ['act', $nv_Lang->getModule('caption_actmod'), $act_modules],
    ['deact', $nv_Lang->getModule('caption_deactmod'), $deact_modules],
    ['bad', $nv_Lang->getModule('caption_badmod'), $bad_modules]
]);
$tpl->assign('WEIGHT_LIST', $weight_list);

$contents = $tpl->fetch('list.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
