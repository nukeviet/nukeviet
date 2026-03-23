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

$sth = $db->prepare('SELECT in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id = :id');
$sth->bindParam(':id', $fid, PDO::PARAM_INT);
$sth->execute();
$row = $sth->fetch();
if (empty($row)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not exists!'
    ]);
}

$sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=0 WHERE in_module= :in_module AND show_func = 0');
$sth->bindParam(':in_module', $row['in_module'], PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module = :in_module AND func_id != :id AND show_func = 1 ORDER BY subweight ASC');
$sth->bindParam(':in_module', $row['in_module'], PDO::PARAM_STR);
$sth->bindParam(':id', $fid, PDO::PARAM_INT);
$sth->execute();

$weight = 0;
while ($row = $sth->fetch()) {
    ++$weight;

    if ($weight == $new_weight) {
        ++$weight;
    }

    $sth2 = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight = :weight WHERE func_id = :id');
    $sth2->bindParam(':weight', $weight, PDO::PARAM_INT);
    $sth2->bindParam(':id', $row['func_id'], PDO::PARAM_INT);
    $sth2->execute();
}

$sth2 = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight = :weight WHERE func_id = :id');
$sth2->bindParam(':weight', $new_weight, PDO::PARAM_INT);
$sth2->bindParam(':id', $fid, PDO::PARAM_INT);
$sth2->execute();
$nv_Cache->delMod('modules');
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
