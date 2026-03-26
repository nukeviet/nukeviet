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

$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_plans-list';
if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$id = $nv_Request->get_int('id', 'post', 0);

$stmt = $db->prepare('SELECT id FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$id = $stmt->fetchColumn();

if (empty($id)) {
    exit('Stop!!!');
}

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_plan', 'planid ' . $id, $admin_info['userid']);

$banners_id = [];
$stmt = $db->prepare('SELECT id, file_name, imageforswf FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
while ($row = $stmt->fetch()) {
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

    $stmt = $db->prepare('DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid IN (' . $banners_id . ')');
    $stmt->execute();

    $stmt = $db->prepare('DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

$stmt = $db->prepare('DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

nv_CreateXML_bannerPlan();

$nv_Cache->delMod($module_name);

nv_jsonOutput([
    'status' => 'OK',
    'mess'   => $nv_Lang->getModule('delfile_success'),
    'refresh' => true
]);
