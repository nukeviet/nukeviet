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

$modname = $nv_Request->get_title('mod', 'post');
$contents = 'NO_' . $modname;

if (!empty($modname) and preg_match($global_config['check_module'], $modname) and md5(NV_CHECK_SESSION . '_' . $module_name . '_del_' . $modname) == $nv_Request->get_string('checkss', 'post')) {
    $sth = $db->prepare('SELECT is_sys, basename FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title= :title AND type=\'module\'');
    $sth->bindParam(':title', $modname, PDO::PARAM_STR);
    $sth->execute();
    list($is_sys, $module_file) = $sth->fetch(3);

    if ((int) $is_sys != 1) {
        $contents = 'OK_' . $modname;
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['delete'] . ' module "' . $modname . '"', '', $admin_info['userid']);

        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php')) {
            $module_name_action = $module_name;
            $module_name = $modname;

            $sth = $db->prepare('SELECT module_data FROM ' . NV_MODULES_TABLE . ' WHERE title= :title');
            $sth->bindParam(':title', $modname, PDO::PARAM_STR);
            $sth->execute();
            $module_data = $sth->fetchColumn();

            $lang = NV_LANG_DATA;
            $sql_drop_module = [];

            require_once NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php';

            if (!empty($sql_drop_module)) {
                foreach ($sql_drop_module as $sql) {
                    try {
                        $db->query($sql);
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
                        exit('NO_' . $modname);
                    }
                }
            }
            $module_name = $module_name_action;
        }

        // Xoa du lieu tai bang nvx_vi_blocks
        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module)');
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        if (!$sth->execute()) {
            exit('NO_' . $modname);
        }

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module');
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        if (!$sth->execute()) {
            exit('NO_' . $modname);
        }

        $nv_Cache->delMod('themes');
        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id IN (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module)');
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        if (!$sth->execute()) {
            exit('NO_' . $modname);
        }

        // Xoa du lieu tai bang nvx_vi_modfuncs
        $sth = $db->prepare('DELETE FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module');
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        if (!$sth->execute()) {
            exit('NO_' . $modname);
        }

        // Xoa du lieu tai bang nvx_vi_modules
        $sth = $db->prepare('DELETE FROM ' . NV_MODULES_TABLE . ' WHERE title= :module');
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        if (!$sth->execute()) {
            exit('NO_' . $modname);
        }

        // Xoa du lieu tai bang nvx_config
        $sth = $db->prepare('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' AND module= :module");
        $sth->bindParam(':module', $modname, PDO::PARAM_STR);
        $sth->execute();

        $check_exit_mod = false;

        $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language where setup=1');
        while (list($lang_i) = $result->fetch(3)) {
            $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE title= :module');
            $sth->bindParam(':module', $modname, PDO::PARAM_STR);
            $sth->execute();

            if ($sth->fetchColumn()) {
                $check_exit_mod = true;
                break;
            }
        }

        if (!$check_exit_mod) {
            if ($module_file != $modname) {
                $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title= :module AND type=\'module\'');
                $sth->bindParam(':module', $modname, PDO::PARAM_STR);
                $sth->execute();
            }

            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $modname, true);
            nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $modname, true);
            nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $modname, true);
            nv_deletefile(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname, true);

            $sth = $db->prepare('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname= :dirname OR dirname LIKE :dirnamelike');
            $sth->bindValue(':dirname', NV_UPLOADS_DIR . '/' . $modname, PDO::PARAM_STR);
            $sth->bindValue(':dirnamelike', NV_UPLOADS_DIR . '/' . $modname . '/%', PDO::PARAM_STR);
            $sth->execute();
            while (list($did) = $sth->fetch(3)) {
                $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
                $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = ' . $did);
            }
        }

        $nv_Cache->delAll();
    }
}

nv_fix_module_weight();

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
