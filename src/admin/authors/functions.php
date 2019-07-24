<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
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
 * @return array[]
 */
function nv_get_api_actions()
{
    global $nv_Lang, $sys_mods;

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
                $cat_title = $nv_Lang->getModule('api_' . $class_cat);
                $api_title = $nv_Lang->getModule('api_' . $class_cat . '_' . $class_name);
                if (!isset($array_apis[''][$class_cat])) {
                    $array_apis[''][$class_cat] = [
                        'title' => $nv_Lang->getModule('api_' . $class_cat),
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

    // Các API của module cung cấp
    foreach ($sys_mods as $module_name => $module_info) {
        $module_file = $module_info['module_file'];
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/Api')) {
            // Đọc ngôn ngữ tạm của module
            $nv_Lang->loadModule($module_file, false, true);

            // Lấy các API
            $files = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/Api', '/(.*?)/');
            foreach ($files as $file) {
                if (preg_match('/^([^0-9]+[a-z0-9\_]{0,})\.php$/', $file, $m)) {
                    $class_name = $m[1];
                    $class_namespaces = 'NukeViet\\Module\\' . $module_file . '\\Api\\' . $class_name;
                    if (nv_class_exists($class_namespaces)) {
                        $class_cat = $class_namespaces::getCat();
                        $cat_title = $class_cat ? $nv_Lang->getModule('api_' . $class_cat) : '';
                        $api_title = $class_cat ? $nv_Lang->getModule('api_' . $class_cat . '_' . $class_name) : $nv_Lang->getModule('api_' . $class_name);

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

            // Xóa ngôn ngữ tạm
            $nv_Lang->changeLang();
        }
    }

    return [$array_apis, $array_keys, $array_cats];
}
