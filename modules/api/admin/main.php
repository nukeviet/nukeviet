<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Tạo xác thực
if ($nv_Request->isset_request('createAuth', 'post')) {
    $method = $nv_Request->get_title('createAuth', 'post', '');
    if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['auth_method_select']
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
    $api_ips = $nv_Request->get_title('ipsUpdate', 'post', '');
    $api_ips = array_map('trim', explode(',', $api_ips));
    $api_ips = array_filter($api_ips, function($ip) {
        global $ips;
        return ($ips->isIp4($ip) or $ips->isIp6($ip));
    });

    $iplist = json_encode($api_ips);
    ipsUpdate($iplist);
    nv_htmlOutput(implode(', ', $api_ips));
}

// Kích hoạt/hủy kích hoạt quyền truy cập
if ($nv_Request->isset_request('changeActivate', 'post')) {
    $role_id = $nv_Request->get_int('changeActivate', 'post', 0);
    if (empty($role_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_select']
        ]);
    }

    $array_post = getRoleDetails($role_id, false);
    if (empty($array_post)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_select']
        ]);
    }

    if ($array_post['role_type'] != 'public') {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_type_private_error']
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

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

list($roleCount, $roleList) = myApiRoleList($type, $page, $per_page);
$generate_page = nv_generate_page($base_url, $roleCount, $per_page, $page);

$api_user = get_api_user();

$page_title = $lang_module['main_title'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('PAGE_URL', $page_url);
$xtpl->assign('TYPE_PUBLIC', [
    'active' => $type == 'public' ? 'active' : '',
    'url' => $page_url,
    'name' => $lang_module['api_role_type_public2']
]);
$xtpl->assign('TYPE_PRIVATE', [
    'active' => $type == 'private' ? 'active' : '',
    'url' => $page_url . '&amp;type=private',
    'name' => $lang_module['api_role_type_private2']
]);
$xtpl->assign('AUTH_INFO', empty($api_user) ? $lang_module['not_access_authentication'] : $lang_module['recreate_access_authentication_info']);

if (empty($global_config['remote_api_access'])) {
    $xtpl->parse('main.remote_api_off');
}

if (empty($api_user)) {
    $xtpl->parse('main.not_access_authentication');
    $xtpl->parse('main.not_access_authentication2');
} else {
    $xtpl->assign('API_USER', $api_user);
    $xtpl->parse('main.created_access_authentication');
}

if (defined('NV_IS_SPADMIN')) {
    $xtpl->parse('main.auth_method_none');
}

if (empty($roleCount)) {
    $xtpl->parse('main.role_empty');
} else {
    if ($type == 'public') {
        $xtpl->parse('main.rolelist.is_public');
    }

    foreach ($roleList as $role) {
        $role['object'] = $lang_module['api_role_object_' . $role['role_object']];
        $role['status'] = !empty($role['status']) ? $lang_module['active'] : $lang_module['inactive'];
        $role['credential_status'] = (int) $role['credential_status'];
        $role['credential_status_format'] = $role['credential_status'] === 1 ? $lang_module['activated'] : ($role['credential_status'] === 0 ? $lang_module['suspended'] : $lang_module['not_activated']);
        $role['credential_addtime'] = $role['credential_addtime'] > 0 ? nv_date('d/m/Y H:i', $role['credential_addtime']) : '';
        $role['credential_access_count'] = $role['credential_access_count'] >= 0 ? $role['credential_access_count'] : '';
        $role['credential_last_access'] = $role['credential_last_access'] > 0 ? nv_date('d/m/Y H:i', $role['credential_last_access']) : '';
        $xtpl->assign('ROLE', $role);

        if ($role['credential_status'] !== 1) {
            $xtpl->parse('main.rolelist.role.credential_status_not_activated');
        }

        if (!empty($role['role_description'])) {
            $xtpl->parse('main.rolelist.role.description');
        }

        // List API hệ thống
        if (!empty($role['apis'][''])) {
            foreach ($role['apis'][''] as $cat_data) {
                $xtpl->assign('CAT_DATA', $cat_data);

                foreach ($cat_data['apis'] as $api_data) {
                    $xtpl->assign('API_DATA', $api_data);
                    $xtpl->parse('main.rolelist.role.catsys.loop');
                }

                $xtpl->parse('main.rolelist.role.catsys');
            }
        }

        // List API theo ngôn ngữ
        if (!empty($role['apis'][NV_LANG_DATA])) {
            foreach ($role['apis'][NV_LANG_DATA] as $mod_title => $mod_data) {
                $xtpl->assign('MOD_TITLE', $site_mods[$mod_title]['custom_title']);

                foreach ($mod_data as $cat_data) {
                    $xtpl->assign('CAT_DATA', $cat_data);

                    foreach ($cat_data['apis'] as $api_data) {
                        $xtpl->assign('API_DATA', $api_data);
                        $xtpl->parse('main.rolelist.role.apimod.mod.loop');
                    }

                    if (!empty($cat_data['title'])) {
                        $xtpl->parse('main.rolelist.role.apimod.mod.title');
                    }

                    $xtpl->parse('main.rolelist.role.apimod.mod');
                }

                $xtpl->parse('main.rolelist.role.apimod');
            }
        }

        if ($type == 'public') {
            if ($role['credential_status'] === 1) {
                $xtpl->parse('main.rolelist.role.is_public.deactivate');
            } elseif ($role['credential_status'] === -1) {
                $xtpl->parse('main.rolelist.role.is_public.activate');
            }
            $xtpl->parse('main.rolelist.role.is_public');
        }

        $xtpl->parse('main.rolelist.role');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.rolelist.generate_page');
    }
    $xtpl->parse('main.rolelist');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
