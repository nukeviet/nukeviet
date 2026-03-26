<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_BANNER_CLIENT')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Các quảng cáo của khách hàng
$stmt = $db_slave->prepare('SELECT id, title FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act = 1 AND clid = :clid ORDER BY id ASC');
$stmt->bindValue(':clid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();

$ads = [];
while ($row = $stmt->fetch()) {
    $ads[] = $row;
}
$stmt->closeCursor();

$contents = nv_banner_theme_stats($ads);

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$page_title = $nv_Lang->getModule('client_stats');
$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
