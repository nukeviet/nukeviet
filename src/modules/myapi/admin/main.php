<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Xóa xác thực
if ($nv_Request->isset_request('delAuth', 'post')) {
    $method = $nv_Request->get_title('delAuth', 'post', '');
    if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true) or ($method == 'none' and !defined('NV_IS_SPADMIN'))) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('auth_method_select')
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
    if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true) or ($method == 'none' and !defined('NV_IS_SPADMIN'))) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('auth_method_select')
        ]);
    }

    [$ident, $secret] = createAuth($method);
    nv_jsonOutput([
        'status' => 'OK',
        'ident' => $ident,
        'secret' => $secret
    ]);
}

// Lưu IP được phép truy cập
if ($nv_Request->isset_request('ipsUpdate', 'post')) {
    $method = $nv_Request->get_title('method', 'post', '');
    if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true) or ($method == 'none' and !defined('NV_IS_SPADMIN'))) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('auth_method_select')
        ]);
    }
    $api_ips = $nv_Request->get_title('ipsUpdate', 'post', '');
    $api_ips = array_map('trim', explode(',', $api_ips));
    $api_ips = array_filter($api_ips, function ($ip) {
        global $ips;

        return $ips->isIp4($ip) or $ips->isIp6($ip);
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

    if ($array_post['role_type'] != 'public') {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_type_private_error')
        ]);
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $admin_info['admin_id'] . ' AND role_id = ' . $role_id)->fetchColumn();
    if ($exists) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $admin_info['admin_id'] . ' AND role_id = ' . $role_id);
    } else {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_api_role_credential (userid, role_id, addtime) VALUES (' . $admin_info['admin_id'] . ', ' . $role_id . ', ' . NV_CURRENTTIME . ')');
    }
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$base_url = $page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$type = $nv_Request->get_title('type', 'get', 'public');
$type != 'private' && $type = 'public';
if ($type == 'private') {
    $base_url .= '&amp;type=private';
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

[$roleCount, $roleList] = myApiRoleList($type, $page, $per_page);
$generate_page = nv_generate_page($base_url, $roleCount, $per_page, $page);

$api_user = get_api_user();

$page_title = $nv_Lang->getModule('main_title');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('PAGE_URL', $page_url);
$tpl->assign('ROLE_COUNT', $roleCount);
$tpl->assign('GENERATE_PAGE', $generate_page);
$tpl->assign('SITE_MOD', $site_mods);
$tpl->assign('TYPE', $type);
$tpl->assign('GCONFIG', $global_config);
$methods = [
    'password_verify' => $nv_Lang->getModule('admin_auth_method_password_verify'),
    'md5_verify' => $nv_Lang->getModule('auth_method_md5_verify'),
    'none' => $nv_Lang->getModule('auth_method_none')
];
foreach ($methods as $key => $name) {
    $method = $api_user[$key] ?? [];
    $method['key'] = $key;
    $method['name'] = $name;
    $method['active'] = $key == 'password_verify' ? 'active' : '';
    if (empty($api_user[$key])) {
        $method['not_access_authentication'] = true;
    } else {
        $method['not_access_authentication'] = false;
    }
    $methods[$key] = $method;
}
$tpl->assign('METHODS', $methods);
$tpl->assign('ROLE_LIST', $roleList);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'nnum_format', 'nv_number_format');
$tpl->registerPlugin('modifier', 'intval', 'intval');

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
