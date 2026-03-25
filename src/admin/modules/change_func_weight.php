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

$fid = $nv_Request->get_int('fid', 'post', 0);
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($fid) or empty($new_weight)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong data!'
    ]);
}

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_show')) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$stmt = $db->prepare('SELECT in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id = :id');
$stmt->bindValue(':id', $fid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (empty($row)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not exists!'
    ]);
}

$stmt = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight = 0 WHERE in_module = :in_module AND show_func = 0');
$stmt->bindValue(':in_module', $row['in_module'], PDO::PARAM_STR);
$stmt->execute();

$stmt_sel = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module = :in_module AND func_id != :id AND show_func = 1 ORDER BY subweight ASC');
$stmt_sel->bindValue(':in_module', $row['in_module'], PDO::PARAM_STR);
$stmt_sel->bindValue(':id', $fid, PDO::PARAM_INT);
$stmt_sel->execute();

$weight = 0;
$stmt_upd = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight = :weight WHERE func_id = :id');

while ($row = $stmt_sel->fetch()) {
    ++$weight;

    if ($weight == $new_weight) {
        ++$weight;
    }

    $stmt_upd->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt_upd->bindValue(':id', $row['func_id'], PDO::PARAM_INT);
    $stmt_upd->execute();
}
$stmt_sel->closeCursor();

$stmt_upd = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight = :weight WHERE func_id = :id');
$stmt_upd->bindValue(':weight', $new_weight, PDO::PARAM_INT);
$stmt_upd->bindValue(':id', $fid, PDO::PARAM_INT);
$stmt_upd->execute();
$nv_Cache->delMod('modules');
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
