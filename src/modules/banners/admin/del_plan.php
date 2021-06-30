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

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$id = $db->query($sql)->fetchColumn();

if (empty($id)) {
    exit('Stop!!!');
}

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_plan', 'planid ' . $id, $admin_info['userid']);

$banners_id = [];
$sql = 'SELECT id, file_name, imageforswf FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid=' . $id;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    if (!empty($row['file_name']) and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'])) {
        @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name']);
    }
    if (!empty($row['imageforswf']) and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'])) {
        @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf']);
    }
    $banners_id[] = $row['id'];
}

if (!empty($banners_id)) {
    $banners_id = implode(',', $banners_id);

    $sql = 'DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid IN (' . $banners_id . ')';
    $db->query($sql);

    $sql = 'DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid = ' . $id;
    $db->query($sql);
}

$sql = 'DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = ' . $id;
$db->query($sql);

nv_CreateXML_bannerPlan();

$db->query('OPTIMIZE TABLE ' . NV_BANNERS_GLOBALTABLE . '_plans');
$db->query('OPTIMIZE TABLE ' . NV_BANNERS_GLOBALTABLE . '_click');
$db->query('OPTIMIZE TABLE ' . NV_BANNERS_GLOBALTABLE . '_rows');

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK|plans_list|plans_list';
include NV_ROOTDIR . '/includes/footer.php';
