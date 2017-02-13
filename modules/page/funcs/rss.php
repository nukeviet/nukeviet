<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined('NV_IS_MOD_PAGE')) {
    die('Stop!!!');
}

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = ! empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

if ($module_info['rss']) {
    $sql = 'SELECT id, title, alias, image, imagealt, description, add_time FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status=1 ORDER BY weight ASC LIMIT 20';
    $result = $db_slave->query($sql);
    while ($row = $result->fetch()) {
        $rimages = (! empty($row['image'])) ? '<img src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'] . '" width="100" align="left" border="0">' : '';

        $items[] = array(
            'title' => $row['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'],
            'guid' => $module_name . '_' . $row['id'],
            'description' => $rimages . $row['description'],
            'pubdate' => $row['add_time']
        );
    }
}
nv_rss_generate($channel, $items);
die();
