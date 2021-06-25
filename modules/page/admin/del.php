<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);
$checkss = $nv_Request->get_string('checkss', 'post', '');

if (md5($id . NV_CHECK_SESSION) == $checkss) {
    $content = 'NO_' . $id;

    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id;
    if ($db->exec($sql)) {
        // Xóa bình luận
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote($module_name) . ' AND id = ' . $id);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete', 'ID: ' . $id, $admin_info['userid']);

        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        $nv_Cache->delMod($module_name);

        $content = 'OK_' . $id;
    }
} else {
    $content = 'ERR_' . $id;
}
exit($content);
