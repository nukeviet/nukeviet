<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$modname = $nv_Request->get_title('mod', 'post');
if (empty($modname) or !preg_match($global_config['check_module'], $modname)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong data!'
    ]);
}

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key . '_' . $modname)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$sth = $db->prepare('SELECT is_sys, basename FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title= :title AND type=\'module\'');
$sth->bindParam(':title', $modname, PDO::PARAM_STR);
$sth->execute();
[$is_sys, $module_file] = $sth->fetch(3);
if ((int) $is_sys == 1) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not allowed!'
    ]);
}

nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('delete') . ' module "' . $modname . '"', '', $admin_info['userid']);

if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php')) {
    $module_name_action = $module_name;
    $module_name = $modname;

    $sth = $db->prepare('SELECT module_data FROM ' . NV_MODULES_TABLE . ' WHERE title= :title');
    $sth->bindParam(':title', $modname, PDO::PARAM_STR);
    $sth->execute();
    $module_data = $sth->fetchColumn();

    $lang = NV_LANG_DATA;
    $sql_drop_module = [];

    if (!defined('NV_MODULE_DELETE')) {
        define('NV_MODULE_DELETE', true);
    }
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php';

    if (!empty($sql_drop_module)) {
        foreach ($sql_drop_module as $sql) {
            try {
                $db->query($sql);
            } catch (Throwable $e) {
                trigger_error($e->getMessage());
                nv_jsonOutput([
                    'success' => 0,
                    'text' => $e->getMessage()
                ]);
            }
        }
    }
    $module_name = $module_name_action;
}

// Xoa du lieu tai bang nvx_vi_blocks
$sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module)');
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
if (!$sth->execute()) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Error delete blocks set!'
    ]);
}

$sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module');
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
if (!$sth->execute()) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Error delete block groups!'
    ]);
}

$nv_Cache->delMod('themes');
$sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id IN (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module)');
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
if (!$sth->execute()) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Error delete module theme!'
    ]);
}

// Xoa du lieu tai bang nvx_vi_modfuncs
$sth = $db->prepare('DELETE FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module');
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
if (!$sth->execute()) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Error delete module function!'
    ]);
}

// Xoa du lieu tai bang nvx_vi_modules
$sth = $db->prepare('DELETE FROM ' . NV_MODULES_TABLE . ' WHERE title= :module');
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
if (!$sth->execute()) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Error delete module!'
    ]);
}

// Xoa du lieu tai bang nvx_config
$sth = $db->prepare('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' AND module= :module");
$sth->bindParam(':module', $modname, PDO::PARAM_STR);
$sth->execute();

// Xóa vị trí block tùy chỉnh
nv_purge_blocks($modname);

$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$langs = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

// Kiểm tra module trùng tên trên ngôn ngữ khác
$check_exit_mod = false;
foreach ($langs as $lang_i) {
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
    $sth_file = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = :did');
    $sth_dir_del = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = :did');
    while ($_scratch = $sth->fetch(3)) {
        [$did] = $_scratch;
        unset($_scratch);
        $sth_file->bindParam(':did', $did, PDO::PARAM_INT);
        $sth_file->execute();
        $sth_dir_del->bindParam(':did', $did, PDO::PARAM_INT);
        $sth_dir_del->execute();
    }

    $plugin_deleted = 0;
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_plugins WHERE plugin_lang=' . $db->quote(NV_LANG_DATA) . " AND plugin_module_file!='' AND plugin_module_name=" . $db->quote($modname);
    $plugins = $db->query($sql)->fetchAll();
    foreach ($plugins as $plugin) {
        if ($db->exec('DELETE FROM ' . $db_config['prefix'] . '_plugins WHERE pid=' . $plugin['pid'])) {
            ++$plugin_deleted;
            // Sắp xếp lại thứ tự
            $sql = 'SELECT pid FROM ' . $db_config['prefix'] . '_plugins WHERE (plugin_lang=' . $db->quote(NV_LANG_DATA) . ' OR plugin_lang=\'all\') AND plugin_area=' . $db->quote($plugin['plugin_area']) . ' AND hook_module=' . $db->quote($plugin['hook_module']) . ' ORDER BY weight ASC';
            $result = $db->query($sql);
            $weight = 0;
            $sth_weight = $db->prepare('UPDATE ' . $db_config['prefix'] . '_plugins SET weight = :weight WHERE pid = :pid');
            while ($row = $result->fetch()) {
                ++$weight;
                $sth_weight->bindParam(':weight', $weight, PDO::PARAM_INT);
                $sth_weight->bindParam(':pid', $row['pid'], PDO::PARAM_INT);
                $sth_weight->execute();
            }
        }
    }
    if ($plugin_deleted > 0) {
        nv_save_file_config_global();
    }
}

// Xóa các mẫu email
$sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_emailtemplates WHERE lang = :lang AND module_name = :module_name');
$sth->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$sth->bindParam(':module_name', $modname, PDO::PARAM_STR);
$sth->execute();

$nv_Cache->delAll();
nv_fix_module_weight();
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
