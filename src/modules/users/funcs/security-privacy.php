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

/**
 * @param array $login
 * @return array
 */
function _getRow(array $login): array
{
    $browserInfo = new NukeViet\Client\Browser($login['agent']);

    return [
        'browser_key' => $browserInfo->getBrowserKey(),
        'browser_name' => $browserInfo->getBrowser(),
        'os_family' => $browserInfo->getPlatformFamily(),
        'os_name' => $browserInfo->getPlatform(),
        'ip' => $login['ip'],
        'current_login' => $login['logtime'],
        'current_login_text' => nv_datetime_format($login['logtime'], 1),
        'id' => $login['id']
    ];
}

$array = [];
$checkss = md5('security_privacy.' . NV_CHECK_SESSION);
$array['loadmorelogins'] = (bool) $nv_Request->get_bool('loadmorelogins', 'post', false);
$array['checkss'] = $nv_Request->get_title('checkss', 'post', '');

if ($array['loadmorelogins'] and !hash_equals($checkss, $array['checkss'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Wrong session!!!'
    ]);
}

// Xác định các phiên đăng nhập
$login_offset = $nv_Request->get_absint('login_offset', 'post', 0);
$array_logins = [];
$limit = 6;
if (empty($login_offset) and defined('NV_IS_ADMIN')) {
    // Phiên đăng nhập quản trị
    $limit--;
    $browserInfo = new NukeViet\Client\Browser($admin_info['current_agent'] ?? NV_USER_AGENT);
    $array_logins[] = [
        'browser_key' => $browserInfo->getBrowserKey(),
        'browser_name' => $browserInfo->getBrowser(),
        'os_family' => $browserInfo->getPlatformFamily(),
        'os_name' => $browserInfo->getPlatform(),
        'ip' => $admin_info['current_ip'],
        'current_login' => $admin_info['current_login'],
        'current_login_text' => nv_datetime_format($admin_info['current_login'], 1),
        'is_current' => 1,
        'is_admin' => 1,
        'id' => 0
    ];
} elseif (empty($login_offset)) {
    // Phiên người dùng hiện tại
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND clid=" . $db->quote($client_info['clid']);
    $current_login = $db->query($sql)->fetch();
    if (!empty($current_login)) {
        $row = _getRow($current_login);
        $row['is_current'] = 1;
        $row['is_admin'] = 0;
        $array_logins[] = $row;
        $limit--;
    }
}
// Các phiên người dùng khác
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND clid!=" . $db->quote($client_info['clid']);
if ($login_offset > 0) {
    $sql .= " AND id <= " . $login_offset;
}
$sql .= " ORDER BY id DESC LIMIT " . $limit;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $row = _getRow($row);
    $row['is_current'] = 0;
    $row['is_admin'] = 0;
    $array_logins[] = $row;
}

$contents = user_security_privacy($array, $array_logins);

if ($array['loadmorelogins']) {
    nv_jsonOutput([
        'status' => 'ok',
        'contents' => $contents,
        'more' => count($array_logins) >= 6,
        'next_offset' => end($array_logins)['id'] ?? 0
    ]);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

