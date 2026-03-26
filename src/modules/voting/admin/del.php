<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$vid = $nv_Request->get_int('vid', 'post', 0);

if ($vid > 0) {
    $_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_del_' . $vid;
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_vote', 'votingid ' . $vid, $admin_info['userid']);
    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid = :vid');
    $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount()) {
        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = :vid');
        $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voted WHERE vid = :vid');
        $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
        $stmt->execute();
        $nv_Cache->delMod($module_name);

        $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs';
        $pattern = '/^vo' . $vid . '_/';
        $logs = nv_scandir($dir, $pattern);
        if (!empty($logs)) {
            foreach ($logs as $file) {
                nv_deletefile($dir . '/' . $file);
            }
        }

        nv_jsonOutput([
            'success' => 1
        ]);
    }
}

nv_jsonOutput([
    'success' => 0,
    'text' => $nv_Lang->getModule('voting_delete_unsuccess')
]);
