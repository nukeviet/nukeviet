<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_LIBRABY')) {
    exit('Stop!!!');
}
$page_title = $module_info['site_title'];
$array_data = [];
$per_page = 1;
$page = $nv_Request->get_page('page', 'post, get', 1);
$offset = ($page - 1) * $per_page;
$sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_rows LIMIT " . $offset . ", " . $per_page;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_data[] = $row;
}

$num_items = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . '_' . $module_data . "_rows")->fetchColumn();
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$generate_page = nv_generate_page($page_url, $num_items, $per_page, $page);
$contents = nv_theme_list($array_data, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
