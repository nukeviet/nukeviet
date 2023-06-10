<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

if ($nv_Request->get_string('checkss', 'post') == NV_CHECK_SESSION) {
    $read_type = $nv_Request->get_int('read_type', 'post', 0);
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $read_type . "' WHERE lang='sys' AND module = 'global' AND config_name = 'read_type'");
    nv_save_file_config_global();

    nv_htmlOutput($nv_Lang->getModule('nv_setting_save'));
}

$a = 1;

$page_title = $nv_Lang->getModule('nv_lang_setting');

$xtpl = new XTemplate('setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

$lang_array_exit = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}+$/');
$lang_array_data_exit = [];

$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $lang_array_data_exit[] = substr($row['field'], 7, 2);
    }
}

$a = 0;
foreach ($language_array as $key => $value) {
    if (file_exists(NV_ROOTDIR . '/includes/language/' . $key . '/global.php')) {
        $arr_lang_func = [];
        $arr_lang_func['read'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=read&amp;dirlang=' . $key . '&amp;checksess=' . md5('readallfile' . NV_CHECK_SESSION);
        if (in_array($key, $lang_array_data_exit, true) and in_array('edit', $allow_func, true)) {
            $arr_lang_func['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=interface&amp;dirlang=' . $key;
        }
        if (in_array($key, $lang_array_data_exit, true) and in_array('write', $allow_func, true)) {
            $arr_lang_func['write'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=write&amp;dirlang=' . $key . '&amp;checksess=' . md5('writeallfile' . NV_CHECK_SESSION);
        }
        $arr_lang_func['download'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=download&amp;dirlang=' . $key . '&amp;checksess=' . md5('downloadallfile' . NV_CHECK_SESSION);
        if (in_array($key, $lang_array_data_exit, true) and in_array('delete', $allow_func, true)) {
            $arr_lang_func['delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=delete&amp;dirlang=' . $key . '&amp;checksess=' . md5('deleteallfile' . NV_CHECK_SESSION);
        }

        $xtpl->assign('LANG_FUNC', $arr_lang_func);

        $xtpl->assign('ROW', [
            'number' => ++$a,
            'key' => $key,
            'language' => $value['language'],
            'name' => $value['name'],
            'arr_lang_func' => implode(' - ', $arr_lang_func)
        ]);

        if (in_array($key, $lang_array_data_exit, true) and in_array('edit', $allow_func, true)) {
            $xtpl->parse('main.loop.edit');
        }
        if (in_array($key, $lang_array_data_exit, true) and in_array('write', $allow_func, true)) {
            $xtpl->parse('main.loop.write');
        }
        if (in_array($key, $lang_array_data_exit, true) and in_array('delete', $allow_func, true)) {
            $xtpl->parse('main.loop.delete');
        }
        $xtpl->parse('main.loop');
    }
}

$array_type = [$nv_Lang->getModule('nv_setting_type_0'), $nv_Lang->getModule('nv_setting_type_1'), $nv_Lang->getModule('nv_setting_type_2')];
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
