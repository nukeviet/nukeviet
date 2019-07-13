<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$datacustom_form = '';

$cid = $nv_Request->get_int('cid', 'get', 0);
$cat_form = $global_array_shops_cat[$cid]['form'];
$cat_forms = empty($cat_form) ? [] : explode(',', $cat_form);

if ($cid and !empty($cat_forms)) {
    $id = $nv_Request->get_int('id', 'get', 0);
    $where = [];
    foreach ($cat_forms as $cat_form) {
        $where[] = "alias=" . $db->quote(preg_replace("/[\_]/", "-", $cat_form));
    }
    $cat_templates = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE ' . implode(' OR ', $where) . ' ORDER BY weight ASC')->fetchAll();

    foreach ($cat_templates as $cat_template) {
        $custom = [];
        $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_field_value_" . NV_LANG_DATA . " WHERE rows_id=" . $id);
        while ($row = $result->fetch()) {
            $custom[$row['field_id']] = $row['field_value'];
        }

        $datacustom_form .= nv_show_custom_form($id, str_replace('-', '_', $cat_template['alias']), $custom);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $datacustom_form;
include NV_ROOTDIR . '/includes/footer.php';
