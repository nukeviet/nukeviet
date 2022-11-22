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

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if ($nv_Request->isset_request('changeAuth', 'post')) {
    $userid = $nv_Request->get_int('changeAuth', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_error']
        ]);
    }

    $username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $userid)->fetchColumn();
    if (empty($username)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_error']
        ]);
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $method = $nv_Request->get_title('method', 'post', '');
        if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['auth_method_select']
            ]);
        }

        list($ident, $secret) = createAuth($method, $userid);
        nv_jsonOutput([
            'status' => 'OK',
            'ident' => $ident,
            'secret' => $secret
        ]);
    }

    if ($nv_Request->isset_request('ips', 'post')) {
        $api_ips = $nv_Request->get_title('ips', 'post', '');
        $api_ips = array_map('trim', explode(',', $api_ips));
        $api_ips = array_filter($api_ips, function ($ip) {
            global $ips;

            return $ips->isIp4($ip) or $ips->isIp6($ip);
        });

        $iplist = json_encode($api_ips);
        ipsUpdate($iplist, $userid);
        nv_htmlOutput(implode(', ', $api_ips));
    }

    $api_user = get_api_user($userid);

    $xtpl = new XTemplate('credential.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PAGE_URL', $page_url);

    $xtpl->assign('AUTH_INFO', empty($api_user) ? $lang_module['not_access_authentication'] : $lang_module['recreate_access_authentication_info']);
    $xtpl->assign('API_USER', $api_user);
    $xtpl->assign('USERID', $userid);

    if (empty($api_user)) {
        $xtpl->parse('changeAuth.not_access_authentication');
        $xtpl->parse('changeAuth.not_access_authentication2');
    } else {
        $xtpl->assign('API_USER', $api_user);
        $xtpl->parse('changeAuth.created_access_authentication');
    }

    $xtpl->parse('changeAuth');
    $contents = $xtpl->text('changeAuth');

    nv_jsonOutput([
        'status' => 'OK',
        'title' => $username,
        'body' => $contents
    ]);
}

list($rolecount, $rolelist) = getRoleList('', '', 0, 0);

$page_title = $lang_module['api_role_credential'];

$role_id = $nv_Request->get_int('role_id', 'get', 0);
$role_id === 0 && $role_id = array_key_first($rolelist);
$role_id === -1 && $role_id = 0;
if (!empty($role_id) and !isset($rolelist[$role_id])) {
    nv_redirect_location($page_url);
}

$action = $nv_Request->get_title('action', 'get', '');

// Tìm kiếm admin/user để thêm vào quyền truy cập API-role
if ($action == 'getUser' and $nv_Request->isset_request('q', 'post')) {
    $q = $nv_Request->get_title('q', 'post', '');
    $q = str_replace('+', ' ', $q);
    $q = nv_htmlspecialchars($q);
    $dbkeyhtml = $db->dblikeescape($q);

    $page = $nv_Request->get_int('page', 'post', 1);

    $where = "(tb1.username LIKE '%" . $dbkeyhtml . "%' OR tb1.email LIKE '%" . $dbkeyhtml . "%' OR tb1.first_name like '%" . $dbkeyhtml . "%' OR tb1.last_name like '%" . $dbkeyhtml . "%') AND tb1.userid NOT IN (SELECT tb2.userid FROM " . $db_config['prefix'] . '_api_role_credential tb2 WHERE tb2.role_id=' . $role_id . ')';
    if ($rolelist[$role_id]['role_object'] == 'admin') {
        $where .= ' AND tb1.userid IN (SELECT tb3.admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' tb3)';
    }

    $array_data = [];
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_USERS_GLOBALTABLE . ' tb1')
        ->where($where);
    $array_data['total_count'] = $db->query($db->sql())->fetchColumn();
    $db->select('tb1.userid, tb1.username')
        ->order('tb1.username ASC')
        ->limit(30)
        ->offset(($page - 1) * 30);
    $result = $db->query($db->sql());
    $array_data['results'] = [];
    while (list($userid, $username) = $result->fetch(3)) {
        $array_data['results'][] = [
            'id' => $userid,
            'title' => $username
        ];
    }

    nv_jsonOutput($array_data);
}

// Thay đổi trạng thái quyền truy cập API-role
if ($action == 'changeStatus' and $nv_Request->isset_request('userid', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_unknown']
        ]);
    }

    list($userid, $status) = $db->query('SELECT userid, status FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetch(3);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_unknown']
        ]);
    }

    $status = $status ? 0 : 1;
    $db->query('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET status=' . $status . ' WHERE userid=' . $userid . ' AND role_id = ' . $role_id);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Xóa quyền truy cập
if ($action == 'del' and $nv_Request->isset_request('userid', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_unknown']
        ]);
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetchColumn();
    if (!$exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_unknown']
        ]);
    }

    $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

//Thêm quyền truy cập API-role
if ($action == 'add') {
    $userid = $nv_Request->get_int('userid', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_error']
        ]);
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $userid)->fetchColumn();
    if (!$exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_error']
        ]);
    }

    if ($rolelist[$role_id]['role_object'] == 'admin') {
        $exists = $db->query('SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id =' . $userid)->fetchColumn();
        if (!$exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['api_role_credential_error']
            ]);
        }
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetchColumn();
    if ($exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['api_role_credential_error']
        ]);
    }

    $db->query('INSERT INTO ' . $db_config['prefix'] . '_api_role_credential (userid, role_id, addtime) VALUES (' . $userid . ', ' . $role_id . ', ' . NV_CURRENTTIME . ')');
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$base_url = $page_url;
if (!empty($role_id)) {
    $base_url .= '&role_id=' . $role_id;

    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 30;

    list($credentialcount, $credentiallist) = getCredentialList($role_id, $rolelist[$role_id]['role_object'] == 'admin', $page, $per_page);
    $generate_page = nv_generate_page($base_url, $credentialcount, $per_page, $page);
} else {
    $credentialcount = 0;
    $credentiallist = [];
    $generate_page = '';
}

$xtpl = new XTemplate('credential.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('PAGE_URL', $page_url);
$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('ADD_CREDENTIAL_URL', !empty($role_id) ? $base_url . '&action=add' : '');
$xtpl->assign('ROLE_ID', $role_id);

if (empty($rolecount)) {
    $xtpl->assign('ADD_API_ROLE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=roles&amp;action=role');
    $xtpl->parse('role_empty');
    $contents = $xtpl->text('role_empty');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($global_config['remote_api_access'])) {
    $xtpl->assign('REMOTE_API_OFF', sprintf($lang_module['api_remote_off'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=config'));
    $xtpl->parse('main.remote_api_off');
}

foreach ($rolelist as $role) {
    $xtpl->assign('ROLE', [
        'role_id' => $role['role_id'],
        'sel' => $role['role_id'] == $role_id ? ' selected="selected"' : '',
        'title' => $role['role_title'] . ' (' . $lang_module['api_role_type'] . ': ' . $lang_module['api_role_type_' . $role['role_type']] . '; ' . $lang_module['api_role_object'] . ': ' . $lang_module['api_role_object_' . $role['role_object']] . ')'
    ]);
    $xtpl->parse('main.api_role');
}

if (!empty($role_id)) {
    $xtpl->assign('GET_USER_URL', $base_url . '&action=getUser');
    $xtpl->assign('CREDENTIAL_ADD_LABEL', $lang_module['api_role_object_' . $rolelist[$role_id]['role_object']]);
    $xtpl->parse('main.add_credential_button');

    if (!$credentialcount) {
        $xtpl->parse('main.is_role.credential_empty');
    } else {
        $xtpl->assign('CREDENTIAL_COUNT', $credentialcount);

        foreach ($credentiallist as $credential) {
            $credential['last_access'] = !empty($credential['last_access']) ? nv_date('d/m/Y H:i', $credential['last_access']) : '';
            $credential['addtime'] = nv_date('d/m/Y H:i', $credential['addtime']);
            $xtpl->assign('CREDENTIAL', $credential);

            $sts = [$lang_module['suspended'], $lang_module['active']];
            foreach ($sts as $k => $v) {
                $xtpl->assign('STATUS', [
                    'val' => $k,
                    'sel' => $k == $credential['status'] ? ' selected="selected"' : '',
                    'title' => $v
                ]);
                $xtpl->parse('main.is_role.credentials.loop.status');
            }

            if (!empty($credential['level'])) {
                $xtpl->parse('main.is_role.credentials.loop.is_admin');
            }
            $xtpl->parse('main.is_role.credentials.loop');
        }
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.is_role.credentials.generate_page');
        }
        $xtpl->parse('main.is_role.credentials');
    }
    $xtpl->parse('main.is_role');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
