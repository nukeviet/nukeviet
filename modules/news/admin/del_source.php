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
if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$sourceid = $nv_Request->get_int('sourceid', 'post', 0);

$contents = 'NO_' . $sourceid;
list($sourceid, $title, $logo_old) = $db->query('SELECT sourceid, title, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid)->fetch(3);
if ($sourceid > 0) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_source', $title, $admin_info['userid']);
    $result = $db->query('SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE sourceid = ' . $sourceid);
    while ($row = $result->fetch()) {
        $arr_catid = explode(',', $row['listcatid']);
        foreach ($arr_catid as $catid_i) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET sourceid = 0 WHERE id =' . $row['id']);
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET sourceid = 0 WHERE id =' . $row['id']);
    }
    $result->closeCursor();
    $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid);

    if (!empty($logo_old)) {
        $_count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid != ' . $sourceid . ' AND logo =' . $db->quote(basename($logo_old)))->fetchColumn();
        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/source/' . $logo_old);

            $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old)))->fetchColumn();
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($logo_old)));
        }
    }
    nv_fix_source();
    $nv_Cache->delMod($module_name);
    $contents = 'OK_' . $sourceid;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
