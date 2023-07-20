<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

[$roleCount, $roleList] = myApiRoleList($type, $page, $per_page);
$generate_page = nv_generate_page($base_url, $roleCount, $per_page, $page);

$api_user = get_api_user();

$page_title = $nv_Lang->getModule('main_title');

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('PAGE_URL', $page_url);
$xtpl->assign('TYPE_PUBLIC', [
    'active' => $type == 'public' ? 'active' : '',
    'url' => $page_url,
    'name' => $nv_Lang->getModule('api_role_type_public2')
]);
$xtpl->assign('TYPE_PRIVATE', [
    'active' => $type == 'private' ? 'active' : '',
    'url' => $page_url . '&amp;type=private',
    'name' => $nv_Lang->getModule('api_role_type_private2')
]);

if (empty($global_config['remote_api_access'])) {
    $xtpl->parse('main.remote_api_off');
}

$methods = [
    'password_verify' => $nv_Lang->getModule('admin_auth_method_password_verify'),
    'md5_verify' => $nv_Lang->getModule('auth_method_md5_verify'),
    'none' => $nv_Lang->getModule('auth_method_none')
];
foreach ($methods as $key => $name) {
    $method = $api_user[$key] ?? [];
    $method['key'] = $key;
    $method['name'] = $name;
    $xtpl->assign('METHOD', $method);

    if ($key == 'password_verify') {
        $xtpl->parse('main.method_tab.is_active');
        $xtpl->parse('main.method_panel.is_active');
    }

    if (empty($api_user[$key])) {
        $xtpl->parse('main.method_panel.not_access_authentication');
    }

    if (defined('NV_IS_SPADMIN') or $key == 'password_verify' or $key == 'md5_verify') {
        $xtpl->parse('main.method_panel.isEditLevel');
        $xtpl->parse('main.method_panel.isEditLevel2');
    }
    $xtpl->parse('main.method_tab');
    $xtpl->parse('main.method_panel');
}

if (empty($roleCount)) {
    $xtpl->parse('main.role_empty');
} else {
    foreach ($roleList as $role) {
        $role['object'] = $nv_Lang->getModule('api_role_object_' . $role['role_object']);
        $role['status'] = !empty($role['status']) ? $nv_Lang->getModule('active') : $nv_Lang->getModule('inactive');
        $role['credential_status'] = (int) $role['credential_status'];
        $role['credential_status_format'] = $role['credential_status'] === 1 ? $nv_Lang->getModule('activated') : ($role['credential_status'] === 0 ? $nv_Lang->getModule('suspended') : $nv_Lang->getModule('not_activated'));
        $role['credential_addtime'] = $role['credential_addtime'] > 0 ? nv_date('d/m/Y H:i', $role['credential_addtime']) : '';
        $role['credential_endtime'] = $role['credential_endtime'] > 0 ? nv_date('d/m/Y H:i', $role['credential_endtime']) : ($role['credential_endtime'] == 0 ? $nv_Lang->getModule('indefinitely') : '');
        $role['credential_quota'] = $role['credential_quota'] > 0 ? number_format($role['credential_quota'], 0, '', '.') : ($role['credential_quota'] == 0 ? $nv_Lang->getModule('no_quota') : '');
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

        foreach ($global_config['setup_langs'] as $_lg) {
            $xtpl->assign('FORLANG', [
                'active' => $_lg == NV_LANG_DATA ? 'active' : '',
                'in' => $_lg == NV_LANG_DATA ? ' in active' : '',
                'expanded' => $_lg == NV_LANG_DATA ? 'true' : 'false',
                'langkey' => $_lg,
                'langname' => $language_array[$_lg]['name']
            ]);
            $xtpl->parse('main.rolelist.role.forlang');

            // List API theo ngôn ngữ
            if (!empty($role['apis'][$_lg])) {
                foreach ($role['apis'][$_lg] as $mod_title => $mod_data) {
                    $xtpl->assign('MOD_TITLE', $site_mods[$mod_title]['custom_title']);

                    foreach ($mod_data as $cat_data) {
                        $xtpl->assign('CAT_DATA', $cat_data);

                        foreach ($cat_data['apis'] as $api_data) {
                            $xtpl->assign('API_DATA', $api_data);
                            $xtpl->parse('main.rolelist.role.tabcontent_forlang.apimod.mod.loop');
                        }

                        if (!empty($cat_data['title'])) {
                            $xtpl->parse('main.rolelist.role.tabcontent_forlang.apimod.mod.title');
                        }

                        $xtpl->parse('main.rolelist.role.tabcontent_forlang.apimod.mod');
                    }

                    $xtpl->parse('main.rolelist.role.tabcontent_forlang.apimod');
                }
            }
            $xtpl->parse('main.rolelist.role.tabcontent_forlang');
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
