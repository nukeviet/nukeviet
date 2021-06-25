<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$a = 1;

$page_title = $lang_module['nv_lang_setting'];

$array_type = [$lang_module['nv_setting_type_0'], $lang_module['nv_setting_type_1'], $lang_module['nv_setting_type_2']];

$xtpl = new XTemplate('setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

if ($nv_Request->get_string('checkss', 'post') == NV_CHECK_SESSION) {
    $read_type = $nv_Request->get_int('read_type', 'post', 0);

    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $read_type . "' WHERE lang='sys' AND module = 'global' AND config_name = 'read_type'");

    nv_save_file_config_global();

    $xtpl->assign('INFO', $lang_module['nv_setting_save']);
    $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting');

    $xtpl->parse('info');
    $contents = $xtpl->text('info');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

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

$a = 0;
foreach ($language_array as $key => $value) {
    $arr_lang_func = [];
    $check_lang_exit = false;

    if (file_exists(NV_ROOTDIR . '/includes/language/' . $key . '/global.php')) {
        $check_lang_exit = true;
        $arr_lang_func[] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=read&amp;dirlang=' . $key . '&amp;checksess=' . md5('readallfile' . NV_CHECK_SESSION) . '">' . $lang_module['nv_admin_read_all'] . '</a>';

        if (in_array($key, $lang_array_data_exit, true) and in_array('write', $allow_func, true)) {
            $arr_lang_func[] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=write&amp;dirlang=' . $key . '&amp;checksess=' . md5('writeallfile' . NV_CHECK_SESSION) . '">' . $lang_module['nv_admin_write'] . '</a>';
        }

        if ($check_lang_exit) {
            $arr_lang_func[] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=download&amp;dirlang=' . $key . '&amp;checksess=' . md5('downloadallfile' . NV_CHECK_SESSION) . '">' . $lang_module['nv_admin_download'] . '</a>';
        }

        if (!empty($arr_lang_func) and in_array('delete', $allow_func, true)) {
            $arr_lang_func[] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=delete&amp;dirlang=' . $key . '&amp;checksess=' . md5('deleteallfile' . NV_CHECK_SESSION) . '">' . $lang_module['nv_admin_delete'] . '</a>';
        }

        $xtpl->assign('ROW', [
            'number' => ++$a,
            'key' => $key,
            'language' => $value['language'],
            'name' => $value['name'],
            'arr_lang_func' => implode(' - ', $arr_lang_func)
        ]);
        $xtpl->parse('main.loop');
    }
}

foreach ($array_type as $key => $value) {
    $xtpl->assign('TYPE', [
        'key' => $key,
        'checked' => $global_config['read_type'] == $key ? ' checked="checked"' : '',
        'title' => $value
    ]);

    $xtpl->parse('main.type');
}

$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
