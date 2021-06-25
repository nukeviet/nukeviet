<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
    'custom_title' => $lang_global['mod_authors']
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
 * @return
 */
function nv_admin_add_result($result)
{
    global $module_name, $lang_global, $lang_module, $page_title, $global_config;
    if (!defined('NV_IS_GODADMIN')) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    //parse content
    $xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');

    $lev = ($result['lev'] == 2) ? $lang_global['level2'] : $lang_global['level3'];
    $contents = [];
    $contents['admin_id'] = $result['admin_id'];
    $contents['title'] = $lang_module['nv_admin_add_title'];
    $contents['info'] = [];
    $contents['info']['lev'] = [$lang_module['lev'], $lev];
    $contents['info']['modules'] = [$lang_module['nv_admin_modules'], $result['modules']];
    $contents['info']['position'] = [$lang_module['position'], $result['position']];
    $contents['info']['editor'] = [$lang_module['editor'], (!empty($result['editor']) ? $result['editor'] : $lang_module['not_use'])];
    $contents['info']['allow_files_type'] = [$lang_module['allow_files_type'], (!empty($result['allow_files_type']) ? implode(', ', $result['allow_files_type']) : $lang_global['no'])];
    $contents['info']['allow_modify_files'] = [$lang_module['allow_modify_files'], ($result['allow_modify_files'] ? $lang_global['yes'] : $lang_global['no'])];
    $contents['info']['allow_create_subdirectories'] = [$lang_module['allow_create_subdirectories'], ($result['allow_create_subdirectories'] ? $lang_global['yes'] : $lang_global['no'])];
    $contents['info']['allow_modify_subdirectories'] = [$lang_module['allow_modify_subdirectories'], ($result['allow_modify_subdirectories'] ? $lang_global['yes'] : $lang_global['no'])];
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add';
    $contents['go_edit'] = [$lang_global['edit'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id']];
    $contents['go_home'] = [$lang_module['main'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name];

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

    $page_title = $lang_module['nv_admin_add_result'];

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
    global $lang_module, $lang_global, $page_title, $module_name, $global_config;
    $xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');
    $contents = [];
    $contents['title'] = sprintf($lang_module['nv_admin_edit_result_title'], $result['login']);

    $contents['thead'] = [$lang_module['field'], $lang_module['old_value'], $lang_module['new_value']];

    $contents['change'] = $result['change'];
    $contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id'];
    $contents['download'] = $lang_module['nv_admin_add_download'];
    $contents['sendmail'] = $lang_module['nv_admin_add_sendmail'];
    $contents['go_home'] = [$lang_module['main'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name];
    $contents['go_edit'] = [$lang_global['edit'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $result['admin_id']];

    $page_title = sprintf($lang_module['nv_admin_edit_result'], $result['login']);

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

/**
 * @return array[]
 */
function nv_get_api_actions()
{
    global $lang_module, $sys_mods;

    $array_apis = [
        '' => []
    ];
    $array_keys = $array_cats = $array_apis;

    // Các API của hệ thống
    $files = nv_scandir(NV_ROOTDIR . '/includes/api', '/(.*?)/');
    foreach ($files as $file) {
        if (preg_match('/^([^0-9]+[a-z0-9\_]{0,})\.php$/', $file, $m)) {
            $class_name = $m[1];
            $class_namespaces = 'NukeViet\\Api\\' . $class_name;
            if (nv_class_exists($class_namespaces)) {
                $class_cat = $class_namespaces::getCat();
                $cat_title = isset($lang_module['api_' . $class_cat]) ? $lang_module['api_' . $class_cat] : $class_cat;
                $api_title = isset($lang_module['api_' . $class_cat . '_' . $class_name]) ? $lang_module['api_' . $class_cat . '_' . $class_name] : $class_cat . '_' . $class_name;
                if (!isset($array_apis[''][$class_cat])) {
                    $array_apis[''][$class_cat] = [
                        'title' => isset($lang_module['api_' . $class_cat]) ? $lang_module['api_' . $class_cat] : $class_cat,
                        'apis' => []
                    ];
                }
                $array_apis[''][$class_cat]['apis'][$class_name] = [
                    'title' => $api_title,
                    'cmd' => $class_name
                ];
                $array_keys[''][$class_name] = $class_name;
                $array_cats[''][$class_name] = [
                    'key' => $class_cat,
                    'title' => $cat_title,
                    'api_title' => $api_title
                ];
            }
        }
    }

    $lang_module_backup = $lang_module;

    // Các API của module cung cấp
    foreach ($sys_mods as $module_name => $module_info) {
        $module_file = $module_info['module_file'];
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/Api')) {
            // Đọc ngôn ngữ tạm của module
            $lang_module = [];
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
                include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php')) {
                include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
            }

            // Lấy các API
            $files = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/Api', '/(.*?)/');
            foreach ($files as $file) {
                if (preg_match('/^([^0-9]+[a-z0-9\_]{0,})\.php$/', $file, $m)) {
                    $class_name = $m[1];
                    $class_namespaces = 'NukeViet\\Module\\' . $module_file . '\\Api\\' . $class_name;
                    if (nv_class_exists($class_namespaces)) {
                        $class_cat = $class_namespaces::getCat();
                        $cat_title = $class_cat ? $lang_module['api_' . $class_cat] : '';
                        $api_title = $class_cat ? $lang_module['api_' . $class_cat . '_' . $class_name] : $lang_module['api_' . $class_name];

                        // Xác định key
                        if (!isset($array_keys[$module_name])) {
                            $array_keys[$module_name] = [];
                        }
                        $array_keys[$module_name][$class_name] = $class_name;

                        // Xác định cây thư mục
                        if (!isset($array_apis[$module_name])) {
                            $array_apis[$module_name] = [];
                        }
                        if (!isset($array_apis[$module_name][$class_cat])) {
                            $array_apis[$module_name][$class_cat] = [
                                'title' => $cat_title,
                                'apis' => []
                            ];
                        }
                        $array_apis[$module_name][$class_cat]['apis'][$class_name] = [
                            'title' => $api_title,
                            'cmd' => $class_name
                        ];

                        // Phân theo cat
                        if (!isset($array_cats[$module_name])) {
                            $array_cats[$module_name] = [];
                        }
                        $array_cats[$module_name][$class_name] = [
                            'key' => $class_cat,
                            'title' => $cat_title,
                            'api_title' => $api_title
                        ];
                    }
                }
            }
        }
    }

    $lang_module = $lang_module_backup;

    return [$array_apis, $array_keys, $array_cats];
}
