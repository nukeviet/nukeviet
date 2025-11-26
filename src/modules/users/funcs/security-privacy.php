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
$array['dellogin'] = (bool) $nv_Request->get_bool('dellogin', 'post', false);
$array['idlogin'] = $nv_Request->get_absint('idlogin', 'post', 0);
$array['delloginall'] = (bool) $nv_Request->get_bool('delloginall', 'post', false);
$array['checkss'] = $nv_Request->get_title('checkss', 'post', '');
$array['page'] = $nv_Request->get_page('page', 'get,post', 1);
$array['checkss_auto'] = false;
$array['auto_toast'] = '';

// Kiểm tra đã xác nhận mật khẩu
$confirm_pwd = is_verified_password('security_privacy');

// Lấy peding_action nếu không post và đã xác nhận mật khẩu
$pending_action = $nv_Request->get_string('pending_action', 'session', '');
$pending_action = $pending_action ? json_decode($pending_action, true) : [];
if (
    $confirm_pwd and is_array($pending_action) and ($pending_action['module'] ?? '') == $module_name and
    ($pending_action['area'] ?? '') == 'security_privacy' and isset($pending_action['time']) and
    (NV_CURRENTTIME - $pending_action['time'] < 1800) and hash_equals($checkss, $pending_action['checkss'] ?? '')
) {
    if (!empty($pending_action['delloginall'])) {
        $array['delloginall'] = 1;
        $array['checkss_auto'] = true;
    } elseif (!empty($pending_action['dellogin']) and !empty($pending_action['idlogin'])) {
        $array['dellogin'] = 1;
        $array['idlogin'] = intval($pending_action['idlogin']);
        $array['checkss_auto'] = true;
    }
    if ($array['checkss_auto']) {
        $nv_Request->unset_request('pending_action', 'session');
        $array['page'] = intval($pending_action['page'] ?? 1);
        $array['page'] = ($array['page'] < 1 or $array['page'] > 9999) ? 1 : $array['page'];
    }
}

// Kiểm tra CSRF
if (($array['loadmorelogins'] or $array['dellogin'] or $array['delloginall']) and !$array['checkss_auto'] and !hash_equals($checkss, $array['checkss'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Wrong session!!!'
    ]);
}
// Kiểm tra xác nhận mật khẩu
if (($array['dellogin'] or $array['delloginall']) and !$confirm_pwd) {
    $pending_action = [
        'module' => $module_name,
        'area' => 'security_privacy',
        'time' => NV_CURRENTTIME,
        'checkss' => $checkss,
        'dellogin' => $array['dellogin'],
        'idlogin' => $array['idlogin'],
        'delloginall' => $array['delloginall'],
        'page' => $array['page']
    ];
    $nv_Request->set_Session('pending_action', json_encode($pending_action));
    nv_jsonOutput([
        'status' => 'not_verified',
        'redirect' => go_verified_password('security_privacy', $page_url, false)
    ]);
}

// Xóa toàn bộ phiên đăng nhập
if ($array['delloginall']) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_logout_all', 'userid ' . $user_info['userid'], $user_info['userid']);

    $sql = "DELETE FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'];
    if (!defined('NV_IS_ADMIN')) {
        $sql .= " AND clid!=" . $db->quote($client_info['clid']);
    }
    $db->exec($sql);

    if ($array['checkss_auto']) {
        $array['auto_toast'] = $nv_Lang->getModule('active_success');
    } else {
        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('active_success')
        ]);
    }
}

// Xóa phiên đăng nhập cụ thể
if ($array['dellogin'] and $array['idlogin'] > 0) {
    $sql = "DELETE FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND id=" . $array['idlogin'];
    $num = $db->exec($sql);

    if ($num > 0) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_logout', 'userid ' . $user_info['userid'] . ' idlogin ' . $array['idlogin'], $user_info['userid']);
        $mess = $nv_Lang->getModule('active_success');
    } else {
        $mess = 'Wrong data!!!';
    }
    if ($array['checkss_auto']) {
        $array['auto_toast'] = $mess;
    } else {
        $redirect = nv_url_rewrite($page_url . ($array['page'] > 1 ? '&page=' . $array['page'] : '' ), true);
        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $mess,
            'redirect' => $redirect
        ]);
    }
}

$limit = $per_page = $array['loadmorelogins'] ? 6 : ($array['page'] * 5 + 1);

// Xác định các phiên đăng nhập
$login_offset = $nv_Request->get_absint('login_offset', 'post,get', 0);
$array_logins = [];

if (!$array['loadmorelogins'] and defined('NV_IS_ADMIN')) {
    // Phiên đăng nhập quản trị
    $per_page--;
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
} elseif (!$array['loadmorelogins']) {
    // Phiên người dùng hiện tại
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND clid=" . $db->quote($client_info['clid']);
    $current_login = $db->query($sql)->fetch();
    if (!empty($current_login)) {
        $row = _getRow($current_login);
        $row['is_current'] = 1;
        $row['is_admin'] = 0;
        $array_logins[] = $row;
        $per_page--;
    }
}
// Các phiên người dùng khác
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $user_info['userid'] . " AND clid!=" . $db->quote($client_info['clid']);
if ($login_offset > 0) {
    $sql .= " AND id <= " . $login_offset;
}
$sql .= " ORDER BY id DESC LIMIT " . $per_page;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $row = _getRow($row);
    $row['is_current'] = 0;
    $row['is_admin'] = 0;
    $array_logins[] = $row;
}

$array['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=datadeletion';

$contents = user_security_privacy($array, $array_logins);

if ($array['loadmorelogins']) {
    nv_jsonOutput([
        'status' => 'ok',
        'contents' => $contents,
        'more' => count($array_logins) >= $limit,
        'next_offset' => end($array_logins)['id'] ?? 0
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
