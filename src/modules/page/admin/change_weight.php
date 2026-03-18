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

$id = $nv_Request->get_int('id', 'post', 0);
$_csrf_key = $module_name . '_' . $admin_info['admin_id'] . '_' . $id;

if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $_csrf_key)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($id) or empty($new_weight)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong data!'
    ]);
}

$sth = $db->prepare('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id= :id');
$sth->bindParam(':id', $id, PDO::PARAM_INT);
$sth->execute();
$row_data = $sth->fetch();
if (empty($row_data)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not exists!'
    ]);
}

$sth = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id != :id ORDER BY weight ASC');
$sth->bindParam(':id', $id, PDO::PARAM_INT);
$sth->execute();

$weight = 0;
while ($row = $sth->fetch()) {
    ++$weight;
    if ($weight == $new_weight) {
        ++$weight;
    }

    $sth2 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $weight . ' WHERE id= :id');
    $sth2->bindParam(':id', $row['id'], PDO::PARAM_INT);
    $sth2->execute();
}

$sth2 = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $new_weight . ' WHERE id= :id');
$sth2->bindParam(':id', $id, PDO::PARAM_INT);
$sth2->execute();

$nv_Cache->delMod($module_name);
nv_insert_logs(NV_LANG_DATA, $module_name, 'Change weight ID: ' . $row_data['id'] . ': ' . $row_data['title'], $weight . ' -> ' . $new_weight, $admin_info['userid']);
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
