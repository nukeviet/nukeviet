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

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
$id = $db->query($sql)->fetchColumn();
if (empty($id)) {
    exit('NO_' . $id);
}

$new_weight = $nv_Request->get_int('new_weight', 'post', 0);
if (empty($new_weight)) {
    exit('NO_' . $mod);
}

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id!=' . $id . ' ORDER BY weight ASC';
$result = $db->query($sql);

$weight = 0;
while ($row = $result->fetch()) {
    ++$weight;

    if ($weight == $new_weight) {
        ++$weight;
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET weight=' . $weight . ' WHERE id=' . $row['id'];
    $db->query($sql);
}

$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET weight=' . $new_weight . ' WHERE id=' . $id;
$db->query($sql);

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';
