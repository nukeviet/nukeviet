<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 9:32
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('module', 'post')) {
    $module_name = $nv_Request->get_title('module', 'post');
    $is_setup = $nv_Request->get_int('setup', 'post', 0);

    $contents = [
        'status' => 'error',
        'module' => $module_name,
        'message' => ['Module not exists'],
        'code' => 0,
        'ishook' => false,
        'hookerror' => '',
        'hookfiles' => [],
        'hookmgs' => []
    ];

    if (!empty($module_name) and preg_match($global_config['check_module'], $module_name)) {
        $sth = $db->prepare('SELECT module_file FROM ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_modules WHERE title= :title');
        $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
        $sth->execute();
        list($module_file) = $sth->fetch(3);

        if (empty($module_file)) {
            $sth = $db->prepare('SELECT basename FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=:title AND type=\'module\'');
            $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
            $sth->execute();
            list($module_file) = $sth->fetch(3);

            if (empty($module_file) and file_exists(NV_ROOTDIR . '/modules/' . $module_name . '/version.php')) {
                $module_file = $module_name;
            }
        }

        if (!empty($module_file)) {
            $contents['status'] = 'success';
            $contents['message'][0] = $nv_Lang->getModule('reinstall_note1');

            // Kiểm tra
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . NV_LANG_DATA . '.php')) {
                $contents['message'][1] = $nv_Lang->getModule('reinstall_note2');
                $contents['message'][2] = $nv_Lang->getModule('reinstall_note3');
                $contents['code'] = 1;
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php')) {
                $contents['message'][1] = $nv_Lang->getModule('reinstall_note2');
                $contents['message'][2] = $nv_Lang->getModule('reinstall_note4');
                $contents['code'] = 1;
            }

            // Quét các hook của module
            if (is_dir(NV_ROOTDIR . '/modules/' . $module_file . '/hooks')) {
                $hooks = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/hooks', '/^[a-zA-Z0-9\_]+\.php$/');
                if (!empty($hooks)) {
                    $missing_modules = [];
                    $contents['ishook'] = true;

                    foreach ($hooks as $hook) {
                        /*
                         * Xác định module xảy ra sự kiện (event).
                         * Có thể rỗng (hệ thống) hoặc là module_file
                         */
                        $require_module = nv_get_hook_require(NV_ROOTDIR . '/modules/' . $module_file . '/hooks/' . $hook);

                        $contents['hookfiles'][$hook] = [];

                        if (empty($require_module)) {
                            $contents['hookmgs'][$hook] = $nv_Lang->getModule('select_hook_sys', $hook);
                            $contents['hookfiles'][$hook][] = [
                                'title' => '',
                                'custom_title' => $nv_Lang->get('system')
                            ];
                        } else {
                            $contents['hookmgs'][$hook] = $nv_Lang->getModule('select_hook_module', $hook);
                            foreach ($sys_mods as $module => $mod) {
                                if ($mod['module_file'] == $require_module) {
                                    $contents['hookfiles'][$hook][] = [
                                        'title' => $module,
                                        'custom_title' => $mod['custom_title']
                                    ];
                                }
                            }

                            if (empty($contents['hookfiles'][$hook])) {
                                $missing_modules[] = $require_module;
                            }
                        }
                    }

                    if (!empty($missing_modules)) {
                        $contents['hookerror'] = $nv_Lang->getModule('error_no_hook_module', implode(', ', $missing_modules));
                    }
                }
            }
        }
    }

    nv_jsonOutput($contents);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
