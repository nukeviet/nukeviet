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

if (empty($id)) {
    exit('Stop!!!');
}

$stmt = $db->prepare('SELECT act FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();
if (empty($row)) {
    exit('Stop!!!');
}

$act = $row['act'] ? 0 : 1;

$stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_plans SET act = :act WHERE id = :id');
$stmt->bindValue(':act', $act, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$return = $stmt->rowCount() ? 'OK' : 'NO';

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();

if ($return === 'OK') {
    nv_jsonOutput(['status' => 'OK', 'mess' => '', 'refresh' => true]);
} else {
    nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('delfile_error')]);
}
