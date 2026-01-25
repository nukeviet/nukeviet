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

$checkss = $nv_Request->get_string('checkss', 'post');
$id = $nv_Request->get_int('id', 'post', 0);

if ($id > 0 and $checkss == md5($id . NV_CHECK_SESSION)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_page', 'pageid ' . $id, $admin_info['userid']);
    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    if ($db->exec($sql)) {
        // Xóa bình luận
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote($module_name) . ' AND id = ' . $id);

        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
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
