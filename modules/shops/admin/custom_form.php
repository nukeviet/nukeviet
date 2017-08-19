<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$datacustom_form = '';

$cid = $nv_Request->get_int('cid', 'get', 0);
$cat_form = $global_array_shops_cat[$cid]['form'];

if ($cid and ! empty($cat_form)) {
    $id = $nv_Request->get_int('id', 'get', 0);

    $custom = array();
    $idtemplate = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template where alias = "' . preg_replace("/[\_]/", "-", $cat_form) . '"')->fetchColumn();
    if ($idtemplate) {
        $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_field_value_" . NV_LANG_DATA . " WHERE rows_id=" . $id);
        while ($row = $result->fetch()) {
            $custom[] = $row['field_id'];
        }
    }

    $datacustom_form = nv_show_custom_form($id, $cat_form, $custom);
}

include NV_ROOTDIR . '/includes/header.php';
echo $datacustom_form;
include NV_ROOTDIR . '/includes/footer.php';
