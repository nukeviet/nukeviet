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
    $modname = $nv_Request->get_title('module', 'post');
    $is_setup = $nv_Request->get_int('setup', 'post', 0);

    $contents = [
        'status' => 'error',
        'module' => $modname,
        'message' => [
            0 => 'Module not exists'
        ],
        'checkss' => md5(NV_CHECK_SESSION . '_' . $module_name . '_setup_mod_' . $modname),
        'code' => 0
    ];

    if (!empty($modname) and preg_match($global_config['check_module'], $modname)) {
        $sth = $db->prepare('SELECT module_file FROM ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_modules WHERE title= :title');
        $sth->bindParam(':title', $modname, PDO::PARAM_STR);
        $sth->execute();
        list($modfile) = $sth->fetch(3);

        if (empty($modfile)) {
            $sth = $db->prepare('SELECT basename FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=:title AND type=\'module\'');
            $sth->bindParam(':title', $modname, PDO::PARAM_STR);
            $sth->execute();
            list($modfile) = $sth->fetch(3);

            if (empty($modfile) and file_exists(NV_ROOTDIR . '/modules/' . $modname . '/version.php')) {
                $modfile = $modname;
            }
        }

        if (!empty($modfile)) {
            $contents['status'] = 'success';
            $contents['message'][0] = $lang_module['reinstall_note1'];

            // Check sample data file
            if (file_exists(NV_ROOTDIR . '/modules/' . $modfile . '/language/data_' . NV_LANG_DATA . '.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note3'];
                $contents['code'] = 1;
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $modfile . '/language/data_en.php')) {
                $contents['message'][1] = $lang_module['reinstall_note2'];
                $contents['message'][2] = $lang_module['reinstall_note4'];
                $contents['code'] = 1;
            }
        }
    }

    nv_jsonOutput($contents);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $modname);
