<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 9:32
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('module', 'post')) {
    $module_name = $nv_Request->get_title('module', 'post');
    $is_setup = $nv_Request->get_int('setup', 'post', 0);

    $contents = array(
        'status' => 'error',
        'module' => $module_name,
        'message' => array( 0 => 'Module not exists' ),
        'code' => 0
    );

    if (! empty($module_name) and preg_match($global_config['check_module'], $module_name)) {
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

        if (! empty($module_file)) {
            $contents['status'] = 'success';
            $contents['message'][0] = $lang_module['reinstall_note1'];

            // Check sample data file
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . NV_LANG_DATA . '.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note3'];
                $contents['code'] = 1;
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note4'];
                $contents['code'] = 1;
            }
        }
    }

    nv_jsonOutput($contents);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);