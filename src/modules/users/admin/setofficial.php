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

$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_main';
if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}


if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$userid = $nv_Request->get_int('userid', 'post', 0);

if (!$userid or $admin_info['admin_id'] == $userid) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'NO'
    ]);
}

$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (!empty($row)) {
    $row['in_groups'] = array_map('intval', explode(',', $row['in_groups']));

    if ($row['group_id'] != 7 and !in_array(7, $row['in_groups'], true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'NO'
        ]);
    }

    if ($row['group_id'] == 7) {
        $row['group_id'] = 4;
    }
    $row['in_groups'] = array_diff($row['in_groups'], [7]);

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET group_id = :gid, in_groups = :in_groups, last_update = :last_update WHERE userid = :userid');
    $stmt->bindValue(':gid', $row['group_id'], PDO::PARAM_INT);
    $stmt->bindValue(':in_groups', implode(',', $row['in_groups']), PDO::PARAM_STR);
    $stmt->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    try {
        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers - 1 WHERE group_id = 7');
    } catch (Throwable $e) {
        trigger_error($e);
    }
    try {
        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers + 1 WHERE group_id = 4');
    } catch (Throwable $e) {
        trigger_error($e);
    }

    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'OK'
    ]);
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => 'NO'
]);
