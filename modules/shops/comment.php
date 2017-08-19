<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$numf = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment where module= ' . $db->quote($row['module']) . ' AND id= ' . $row['id'] . ' AND status=1')->fetchColumn();

$query = 'UPDATE ' . $db_config['prefix'] . '_' . $mod_info['module_data'] . '_rows SET hitscm=' . $numf . ' WHERE id=' . $row['id'];
$db->query($query);
