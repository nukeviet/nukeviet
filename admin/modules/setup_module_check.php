<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('module', 'post')) {
    $modulename = $nv_Request->get_title('module', 'post');
    $is_setup = $nv_Request->get_int('setup', 'post', 0);

    $contents = [
        'status' => 'error',
        'module' => $modulename,
        'message' => ['Module not exists'],
        'checkss' => md5(NV_CHECK_SESSION . '_' . $module_name . '_setup_mod_' . $modulename),
        'code' => 0,
        'ishook' => false,
        'hookerror' => '',
        'hookfiles' => [],
        'hookmgs' => []
    ];

    if (!empty($modulename) and preg_match($global_config['check_module'], $modulename)) {
        $sth = $db->prepare('SELECT module_file FROM ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_modules WHERE title= :title');
        $sth->bindParam(':title', $modulename, PDO::PARAM_STR);
        $sth->execute();
        list($module_file) = $sth->fetch(3);

        if (empty($module_file)) {
            $sth = $db->prepare('SELECT basename FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=:title AND type=\'module\'');
            $sth->bindParam(':title', $modulename, PDO::PARAM_STR);
            $sth->execute();
            list($module_file) = $sth->fetch(3);

            if (empty($module_file) and file_exists(NV_ROOTDIR . '/modules/' . $modulename . '/version.php')) {
                $module_file = $modulename;
            }
        }

        if (!empty($module_file)) {
            $contents['status'] = 'success';
            $contents['message'][0] = $lang_module['reinstall_note1'];

            // Kiểm tra
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . NV_LANG_DATA . '.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note3'];
                $contents['code'] = 1;
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note4'];
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
                            $contents['hookmgs'][$hook] = sprintf($lang_module['select_hook_sys'], $hook);
                            $contents['hookfiles'][$hook][] = [
                                'title' => '',
                                'custom_title' => $lang_global['system']
                            ];
                        } else {
                            $contents['hookmgs'][$hook] = sprintf($lang_module['select_hook_module'], $hook);
                            foreach ($sys_mods as $module => $mod) {
                                if ($mod['module_file'] == $require_module) {
                                    $contents['hookfiles'][$hook][] = [
                                        'title' => $module,
                                        'custom_title' => $module . ' (' . $mod['custom_title'] . ')'
                                    ];
                                }
                            }

                            if (empty($contents['hookfiles'][$hook])) {
                                $missing_modules[] = $require_module;
                            }
                        }
                    }

                    if (!empty($missing_modules)) {
                        $contents['hookerror'] = sprintf($lang_module['error_no_hook_module'], implode(', ', $missing_modules));
                    }
                }
            }
        }
    }

    nv_jsonOutput($contents);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $modulename);
