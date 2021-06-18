<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 20-03-2011 20:08
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);
$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

$sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id . ' AND parentid=' . $parentid;
$row = $db->query($sql)->fetch();

if (empty($row) or empty($new_weight)) {
    die('NO_' . $id);
}

$query = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id !=' . $id . ' AND parentid=' . $parentid . ' AND mid=' . $mid . ' ORDER BY weight ASC';
$result = $db->query($query);

$weight = 0;
while ($row = $result->fetch()) {
    ++$weight;
    if ($weight == $new_weight) {
        ++$weight;
    }
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id']);
}

$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $new_weight . ' WHERE id=' . $id . ' AND parentid=' . $parentid);

menu_fix_order($mid);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id . '_' . $mid . '_' . $parentid;
include NV_ROOTDIR . '/includes/footer.php';
