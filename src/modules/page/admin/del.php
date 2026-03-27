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

$id = $nv_Request->get_int('id', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

if ($id > 0) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_page', 'pageid ' . $id, $admin_info['userid']);
    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Xóa bình luận
        $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module = :module AND id = :id');
        $stmt->bindValue(':module', $module_name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC');
        $stmt->execute();
        $weight = 0;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight = :weight WHERE id = :id');
        while ($row = $stmt->fetch()) {
            ++$weight;
            $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $stmt->closeCursor();
        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'success' => 1,
        ]);
    }
}

nv_jsonOutput([
    'success' => 0,
    'text' => $nv_Lang->getModule('page_delete_unsuccess')
]);
