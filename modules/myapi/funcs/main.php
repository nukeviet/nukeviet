<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_API_MOD')) {
    exit('Stop!!!');
}

// Xóa xác thực
if ($nv_Request->isset_request('delAuth', 'post')) {
    $method = $nv_Request->get_title('delAuth', 'post', '');
    if (empty($method) or !in_array($method, $methods, true)) {
        nv_jsonOutput([
            'status' => 'error'
        ]);
    }

    delAuth($method);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Tạo xác thực
if ($nv_Request->isset_request('createAuth', 'post')) {
    $method = $nv_Request->get_title('createAuth', 'post', '');
    if (empty($method) or !in_array($method, $methods, true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('auth_method_select')
        ]);
    }

    list($ident, $secret) = createAuth($method);
    nv_jsonOutput([
        'status' => 'OK',
        'ident' => $ident,
        'secret' => $secret
    ]);
}

// Lưu IP được phép truy cập
if ($nv_Request->isset_request('ipsUpdate', 'post')) {
    $method = $nv_Request->get_title('method', 'post', '');
    if (empty($method) or !in_array($method, $methods, true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('auth_method_select')
        ]);
    }
    $api_ips = $nv_Request->get_title('ipsUpdate', 'post', '');
    $api_ips = array_map('trim', explode(',', $api_ips));
    $api_ips = array_filter($api_ips, function($ip) {
        global $ips;
        return ($ips->isIp4($ip) or $ips->isIp6($ip));
    });

    $iplist = json_encode($api_ips);
    ipsUpdate($iplist, $method);
    nv_jsonOutput([
        'status' => 'OK',
        'ips' => implode(', ', $api_ips)
    ]);
}

// Kích hoạt/hủy kích hoạt quyền truy cập
if ($nv_Request->isset_request('changeActivate', 'post')) {
    $role_id = $nv_Request->get_int('changeActivate', 'post', 0);
    if (empty($role_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_select')
        ]);
    }

    $array_post = getRoleDetails($role_id, false);
    if (empty($array_post)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_select')
        ]);
    }

    if ($array_post['role_object'] != 'user' or  $array_post['role_type'] != 'public') {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_type_private_error')
        ]);
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $user_info['userid'] . ' AND role_id = ' . $role_id)->fetchColumn();
    if ($exists) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $user_info['userid'] . ' AND role_id = ' . $role_id);
    } else {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_api_role_credential (userid, role_id, addtime) VALUES (' . $user_info['userid'] . ', ' . $role_id . ', ' . NV_CURRENTTIME . ')');
    }
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$type = $nv_Request->get_title('type', 'get', 'public');
$type != 'private' && $type = 'public';
if ($type == 'private') {
    $page_url .= '&amp;type=private';
}
$base_url = $page_url;
$page = $nv_Request->get_int('page', 'get', 1);
if ($page > 1) {
    $page_url .= '&amp;page=' . $page;
}
$per_page = 30;

list($roleCount, $roleList) = myApiRoleList($type, $page, $per_page);
// Không cho tùy ý đánh số page + xác định trang trước, trang sau
betweenURLs($page, ceil($roleCount / $per_page), $base_url, '&amp;page=', $prevPage, $nextPage);
$generate_page = nv_generate_page($base_url, $roleCount, $per_page, $page);

$api_user = get_api_user();

$page_title = $nv_Lang->getModule('main_title');
$key_words = $module_info['keywords'];

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = main_theme($type, $roleCount, $roleList, $api_user, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
