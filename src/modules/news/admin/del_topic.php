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

$topicid = $nv_Request->get_int('topicid', 'post', 0);
$checkss = $nv_Request->get_string('checkss', 'post');

$contents = 'NO_' . $topicid;

list($topicid, $image) = $db->query('SELECT topicid, image FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . (int) $topicid)->fetch(3);
if ($topicid > 0) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_topic', 'topicid ' . $topicid, $admin_info['userid']);
    $check_del_topicid = false;

    $query = $db->query('SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid = ' . $topicid);
    $_rows = $query->fetchAll();
    $check_rows = sizeof($_rows);

    if ($check_rows > 0 and $checkss == md5($topicid . NV_CHECK_SESSION)) {
        foreach ($_rows as $row) {
            $arr_catid = explode(',', $row['listcatid']);
            foreach ($arr_catid as $catid_i) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET topicid = 0 WHERE id =' . $row['id']);
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid = 0 WHERE id =' . $row['id']);
        }
        $check_del_topicid = true;
    } elseif ($check_rows > 0) {
        $contents = 'ERR_ROWS_' . $topicid . '_' . md5($topicid . NV_CHECK_SESSION) . '_' . sprintf($lang_module['deltopic_msg_rows'], $check_rows);
    } else {
        $check_del_topicid = true;
    }
    if ($check_del_topicid) {
        $query = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . $topicid;
        if ($db->exec($query)) {
            nv_fix_topic();
            if (is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $image)) {
                nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $image);
            }
            $contents = 'OK_' . $topicid;
        }
    }
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
