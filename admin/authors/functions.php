<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

unset($page_title, $select_options);

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_authors')
];
define('NV_IS_FILE_AUTHORS', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#quản_trị';
$array_url_instruction['add'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#them_quản_trị';
$array_url_instruction['module'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#quyền_hạn_quản_ly_module';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:users#cấu_hinh';

/**
 * nv_admin_add_result()
 *
 * @param mixed $result
 */
function nv_admin_add_result($result)
{
    global $module_name, $nv_Lang, $page_title, $global_config;
    if (!defined('NV_IS_GODADMIN')) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    //parse content
    $xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');

    $lev = ($result['lev'] == 2) ? $nv_Lang->getGlobal('level2') : $nv_Lang->getGlobal('level3');
    $lev_expired = !empty($result['lev_expired']) ? $result['lev_expired'] : $nv_Lang->getModule('unlimited');
    $contents = [];
    $contents['admin_id'] = $result['admin_id'];
    $contents['title'] = $nv_Lang->getModule('nv_admin_add_title');
    $contents['info'] = [];
    $contents['info']['lev'] = [$nv_Lang->getModule('lev'), $lev];
    $contents['info']['modules'] = [$nv_Lang->getModule('nv_admin_modules'), $result['modules']];
    $contents['info']['lev_expired'] = [$nv_Lang->getModule('lev_expired'), $lev_expired];
    if ($result['downgrade_to_modadmin']) {
        $inf = !empty($result['after_modules']) ? ': ' . $result['after_modules'] : ''; 
        $contents['info']['after_exp_action'] = [$nv_Lang->getModule('after_exp_action'), $nv_Lang->getModule('downgrade_to_modadmin') . $inf];
    }
    $contents['info']['position'] = [$nv_Lang->getModule('position'), $result['position']];
    $contents['info']['editor'] = [$nv_Lang->getModule('editor'), (!empty($result['editor']) ? $result['editor'] : $nv_Lang->getModule('not_use'))];
    $contents['info']['allow_files_type'] = [$nv_Lang->getModule('allow_files_type'), (!empty($result['allow_files_type']) ? implode(', ', $result['allow_files_type']) : $nv_Lang->getGlobal('no'))];
    $contents['info']['allow_modify_files'] = [$nv_Lang->getModule('allow_modify_files'), ($result['allow_modify_files'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))];
    $contents['info']['allow_create_subdirectories'] = [$nv_Lang->getModule('allow_create_subdirectories'), ($result['allow_create_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))];
    $contents['info']['allow_modify_subdirectories'] = [$nv_Lang->getModule('allow_modify_subdirectories'), ($result['allow_modify_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))];
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add';
    $contents['go_edit'] = [$nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id']];
    $contents['go_home'] = [$nv_Lang->getModule('main'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name];

    $xtpl->assign('TITLE', $contents['title']);
    foreach ($contents['info'] as $value) {
        if (!empty($value[1])) {
            $xtpl->assign('VALUE0', $value[0]);
            $xtpl->assign('VALUE1', $value[1]);
            $xtpl->parse('add_result.loop');
        }
    }
    $xtpl->assign('ACTION', $contents['action']);
    $xtpl->assign('ADM_ID', $contents['admin_id']);
    $xtpl->assign('EDIT_HREF', $contents['go_edit'][1]);
    $xtpl->assign('EDIT', $contents['go_edit'][0]);
    $xtpl->assign('HOME_HREF', $contents['go_home'][1]);
    $xtpl->assign('HOME', $contents['go_home'][0]);

    $page_title = $nv_Lang->getModule('nv_admin_add_result');

    $xtpl->parse('add_result');
    $contents = $xtpl->text('add_result');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_admin_edit_result()
 *
 * @param mixed $result
 */
function nv_admin_edit_result($result)
{
    global $nv_Lang, $page_title, $module_name, $global_config;
    $xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');
    $contents = [];
    $contents['title'] = $nv_Lang->getModule('nv_admin_edit_result_title', $result['login']);

    $contents['thead'] = [$nv_Lang->getModule('field'), $nv_Lang->getModule('old_value'), $nv_Lang->getModule('new_value')];

    $contents['change'] = $result['change'];
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'];
    $contents['download'] = $nv_Lang->getModule('nv_admin_add_download');
    $contents['sendmail'] = $nv_Lang->getModule('nv_admin_add_sendmail');
    $contents['go_home'] = [$nv_Lang->getModule('main'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name];
    $contents['go_edit'] = [$nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id']];

    $page_title = $nv_Lang->getModule('nv_admin_edit_result', $result['login']);

    $xtpl->assign('TITLE', $contents['title']);
    $xtpl->assign('THEAD0', $contents['thead'][0]);
    $xtpl->assign('THEAD1', $contents['thead'][1]);
    $xtpl->assign('THEAD2', $contents['thead'][2]);

    foreach ($contents['change'] as $value) {
        $xtpl->assign('VALUE0', $value[0]);
        $xtpl->assign('VALUE1', $value[1]);
        $xtpl->assign('VALUE2', $value[2]);
        $xtpl->parse('edit_resuilt.loop');
    }

    $xtpl->assign('DOWNLOAD', $contents['download']);
    $xtpl->assign('SENDMAIL', $contents['sendmail']);
    $xtpl->assign('EDIT_NAME', $contents['go_edit'][0]);
    $xtpl->assign('EDIT_HREF', $contents['go_edit'][1]);
    $xtpl->assign('HOME_NAME', $contents['go_home'][0]);
    $xtpl->assign('HOME_HREF', $contents['go_home'][1]);

    $xtpl->parse('edit_resuilt');
    $contents = $xtpl->text('edit_resuilt');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}
