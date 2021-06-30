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
$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

$sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id . ' AND parentid=' . $parentid;
$row = $db->query($sql)->fetch();

if (empty($row) or empty($new_weight)) {
    exit('NO_' . $id);
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

nv_insert_logs(NV_LANG_DATA, $module_name, 'Change weight row menu', 'Row menu id: ' . $id . ', new weight: ' . $new_weight, $admin_info['userid']);
menu_fix_order($mid);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id . '_' . $mid . '_' . $parentid;
include NV_ROOTDIR . '/includes/footer.php';
