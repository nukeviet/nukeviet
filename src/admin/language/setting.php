<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_lang_setting');

// Lưu cấu hình đọc ngôn ngữ giao diện
if ($nv_Request->get_string('checkss', 'post') == NV_CHECK_SESSION) {
    $read_type = $nv_Request->get_int('read_type', 'post', 0);
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $read_type . "' WHERE lang='sys' AND module = 'global' AND config_name = 'read_type'");
    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getModule('nv_setting_save')
    ]);
}

$lang_array_exit = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}+$/');
$lang_array_data_exit = [];

$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $lang_array_data_exit[] = substr($row['field'], 7, 2);
    }
}

$array = [];
foreach ($language_array as $key => $value) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $key . '/global.php')) {
        $array[] = [
            'key' => $key,
            'language' => $value['language'],
            'name' => $value['name'],
            'allowed_edit' => (in_array($key, $lang_array_data_exit, true) and in_array('edit', $allow_func, true)),
            'allowed_write' => (in_array($key, $lang_array_data_exit, true) and in_array('write', $allow_func, true)),
            'allowed_delete' => (in_array($key, $lang_array_data_exit, true) and in_array('delete', $allow_func, true)),
            'allowed_delete_files' => (!in_array($key, $global_config['setup_langs'], true) and in_array('delete', $allow_func, true)),
            'checkss_read' => md5('readallfile' . NV_CHECK_SESSION),
            'checkss_write' => md5('writeallfile' . NV_CHECK_SESSION),
            'checkss_download' => md5('downloadallfile' . NV_CHECK_SESSION),
            'checkss_delete' => md5('deleteallfile' . NV_CHECK_SESSION)
        ];
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('setting.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ROWS', $array);

$contents = $tpl->fetch('setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
