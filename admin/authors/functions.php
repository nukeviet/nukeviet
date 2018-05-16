<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

unset($page_title, $select_options);

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_authors')
);
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
 * @return
 */
function nv_admin_add_result($result)
{
    global $module_name, $page_title, $global_config, $nv_Lang;
    if (! defined('NV_IS_GODADMIN')) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    //parse content
    $xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');

    $lev = ($result['lev'] == 2) ? $nv_Lang->getGlobal('level2') : $nv_Lang->getGlobal('level3');
    $contents = array();
    $contents['admin_id'] = $result['admin_id'];
    $contents['title'] = $nv_Lang->getModule('nv_admin_add_title');
    $contents['info'] = array();
    $contents['info']['lev'] = array( $nv_Lang->getModule('lev'), $lev );
    $contents['info']['modules'] = array( $nv_Lang->getModule('nv_admin_modules'), $result['modules'] );
    $contents['info']['position'] = array( $nv_Lang->getModule('position'), $result['position'] );
    $contents['info']['editor'] = array( $nv_Lang->getModule('editor'), (! empty($result['editor']) ? $result['editor'] : $nv_Lang->getModule('not_use')) );
    $contents['info']['allow_files_type'] = array( $nv_Lang->getModule('allow_files_type'), (! empty($result['allow_files_type']) ? implode(', ', $result['allow_files_type']) : $nv_Lang->getGlobal('no')) );
    $contents['info']['allow_modify_files'] = array( $nv_Lang->getModule('allow_modify_files'), ($result['allow_modify_files'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')) );
    $contents['info']['allow_create_subdirectories'] = array( $nv_Lang->getModule('allow_create_subdirectories'), ($result['allow_create_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')) );
    $contents['info']['allow_modify_subdirectories'] = array( $nv_Lang->getModule('allow_modify_subdirectories'), ($result['allow_modify_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')) );
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add';
    $contents['go_edit'] = array( $nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'] );
    $contents['go_home'] = array( $nv_Lang->getModule('main'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );

    $xtpl->assign('TITLE', $contents['title']);
    foreach ($contents['info'] as $value) {
        if (! empty($value[1])) {
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
 * @return
 */
function nv_admin_edit_result($result)
{
    global $page_title, $module_name, $global_config, $nv_Lang;
    $xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');
    $contents = array();
    $contents['title'] = sprintf($nv_Lang->getModule('nv_admin_edit_result_title'), $result['login']);

    $contents['thead'] = array( $nv_Lang->getModule('field'), $nv_Lang->getModule('old_value'), $nv_Lang->getModule('new_value') );

    $contents['change'] = $result['change'];
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'];
    $contents['download'] = $nv_Lang->getModule('nv_admin_add_download');
    $contents['sendmail'] = $nv_Lang->getModule('nv_admin_add_sendmail');
    $contents['go_home'] = array( $nv_Lang->getModule('main'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
    $contents['go_edit'] = array( $nv_Lang->getGlobal('edit'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'] );

    $page_title = sprintf($nv_Lang->getModule('nv_admin_edit_result'), $result['login']);

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

// Đây là ví dụ về API action. Sau này sẽ phát triển sau
$array_api_actions = array();
$array_api_actions['system'] = array(
    'add_baned_ip',
    'send_mail',
);
$array_api_actions['user'] = array(
    'add_user',
);
$array_api_actions['admin'] = array(
    'add_admin',
);
$array_api_actions['theme'] = array(
    'active_theme',
);
$array_api_actions['extension'] = array(
    'delete_extension',
);
$array_api_actions['file'] = array(
    'delete_file',
    'add_file',
    'add_folder',
    'delete_folder',
);
$array_api_actions['module'] = array(
    'delete_module',
    'change_status_module',
    'recreat_module',
);
