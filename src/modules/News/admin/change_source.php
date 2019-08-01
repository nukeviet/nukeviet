<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$sourceid = $nv_Request->get_int('sourceid', 'post', 0);
$mod = $nv_Request->get_string('mod', 'post', '');
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);

if (empty($sourceid)) {
    die('NO_' . $sourceid);
}
$content = 'NO_' . $sourceid;

if ($mod == 'weight' and $new_vid > 0) {
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid;
    $numrows = $db->query($sql)->fetchColumn();
    if ($numrows != 1) {
        die('NO_' . $sourceid);
    }

    $sql = 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid!=' . $sourceid . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight=' . $weight . ' WHERE sourceid=' . $row['sourceid'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight=' . $new_vid . ' WHERE sourceid=' . $sourceid;
    $db->query($sql);

    $content = 'OK_' . $sourceid;
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';