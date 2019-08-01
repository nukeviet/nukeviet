<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_LANG')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_lang_setting');

$array_type = [$nv_Lang->getModule('nv_setting_type_0'), $nv_Lang->getModule('nv_setting_type_1'), $nv_Lang->getModule('nv_setting_type_2')];

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('BASE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE);
$tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

$is_saved = false;
if ($nv_Request->get_string('checkss', 'post') == NV_CHECK_SESSION) {
    $read_type = $nv_Request->get_int('read_type', 'post', 0);

    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $read_type . "' WHERE lang='sys' AND module = 'global' AND config_name = 'read_type'");

    nv_save_file_config_global();

    $tpl->assign('URL_REDIRECT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting');
    $is_saved = true;
}

if (!$is_saved) {
    $lang_array_exit = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}+$/');
    $lang_array_data_exit = [];

    $columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
    foreach ($columns_array as $row) {
        if (substr($row['field'], 0, 7) == 'author_') {
            $lang_array_data_exit[] = substr($row['field'], 7, 2);
        }
    }

    $array_lang_setup = [];
    $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1');
    while ($row = $result->fetch()) {
        $array_lang_setup[] = trim($row['lang']);
    }

    $array = [];
    foreach ($language_array as $key => $value) {
        if (file_exists(NV_ROOTDIR . '/includes/language/' . $key . '/global.php')) {
            $funcs = [];
            $funcs['read'] = "read&amp;dirlang=" . $key . "&amp;checksess=" . md5("readallfile" . NV_CHECK_SESSION);
            if (in_array($key, $lang_array_data_exit) and in_array('write', $allow_func)) {
                $funcs['write'] = "write&amp;dirlang=" . $key . "&amp;checksess=" . md5("writeallfile" . NV_CHECK_SESSION);
            }
            $funcs['download'] = "download&amp;dirlang=" . $key . "&amp;checksess=" . md5("downloadallfile" . NV_CHECK_SESSION);
            if (!empty($funcs) and in_array('delete', $allow_func)) {
                $funcs['delete'] = "delete&amp;dirlang=" . $key . "&amp;checksess=" . md5("deleteallfile" . NV_CHECK_SESSION);
            }

            $array[] = [
                'key' => $key,
                'language' => $value['language'],
                'name' => $value['name'],
                'funcs' => $funcs
            ];
        }
    }

    $tpl->assign('ARRAYS', $array);
    $tpl->assign('READTYPES', $array_type);
    $tpl->assign('CONFIG', $global_config);
}

$tpl->assign('IS_SAVED', $is_saved);

$contents = $tpl->fetch('setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
