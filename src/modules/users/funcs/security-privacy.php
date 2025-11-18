<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('security_privacy');
$description = $keywords = 'no';
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$array_mod_title[] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('editinfo_pagetitle'),
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo'
];
$array_mod_title[] = [
    'catid' => 1,
    'title' => $page_title,
    'link' => $page_url
];

// Xác định các phiên đăng nhập
$login_offset = $nv_Request->get_title('login_offset', 'post', 0);
$array_logins = [];
// Phiên hiện tại
if (empty($login_offset)) {
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND clid=" . $db->quote($client_info['clid']);
    $current_login = $db->query($sql)->fetch();
    if (!empty($current_login)) {
        $array_logins[] = $current_login;
    }
}
// Các phiên khác
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . "
AND clid!=" . $db->quote($client_info['clid']) . " ORDER BY logtime DESC LIMIT 6";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_logins[] = $row;
}

echo '<pre><code>';
echo htmlspecialchars(print_r($current_login, true));
die('</code></pre>');

$contents = user_security_privacy();

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

