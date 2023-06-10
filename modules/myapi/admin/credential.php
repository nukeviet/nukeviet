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
            'mess' => $nv_Lang->getModule('api_role_credential_error')
        ]);
    }

    $username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $userid)->fetchColumn();
    if (empty($username)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_error')
        ]);
    }

    if ($nv_Request->isset_request('del', 'post')) {
        $method = $nv_Request->get_title('method', 'post', '');
        if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true)) {
            nv_jsonOutput([
                'status' => 'error'
            ]);
        }

        delAuth($method, $userid);
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    if ($nv_Request->isset_request('save', 'post')) {
        $method = $nv_Request->get_title('method', 'post', '');
        if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('auth_method_select')
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
        $method = $nv_Request->get_title('method', 'post', '');
        if (empty($method) or !in_array($method, ['none', 'password_verify', 'md5_verify'], true)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('auth_method_select')
            ]);
        }
        $api_ips = $nv_Request->get_title('ips', 'post', '');
        $api_ips = array_map('trim', explode(',', $api_ips));
        $api_ips = array_filter($api_ips, function ($ip) {
            global $ips;

            return $ips->isIp4($ip) or $ips->isIp6($ip);
        });

        $iplist = json_encode($api_ips);
        ipsUpdate($iplist, $method, $userid);
        nv_jsonOutput([
            'status' => 'OK',
            'ips' => implode(', ', $api_ips)
        ]);
    }

    $api_user = get_api_user($userid);

    $xtpl = new XTemplate('credential.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PAGE_URL', $page_url);
    $xtpl->assign('USERID', $userid);

    $methods = [
        'password_verify' => $nv_Lang->getModule('admin_auth_method_password_verify'),
        'md5_verify' => $nv_Lang->getModule('auth_method_md5_verify'),
        'none' => $nv_Lang->getModule('auth_method_none')
    ];
    foreach ($methods as $key => $name) {
        $method = isset($api_user[$key]) ? $api_user[$key] : [];
        $method['key'] = $key;
        $method['name'] = $name;
        $xtpl->assign('METHOD', $method);

        if ($key == 'password_verify') {
            $xtpl->parse('changeAuth.method_tab.is_active');
            $xtpl->parse('changeAuth.method_panel.is_active');
        }

        if (empty($api_user[$key])) {
            $xtpl->parse('changeAuth.method_panel.not_access_authentication');
        }

        $xtpl->parse('changeAuth.method_tab');
        $xtpl->parse('changeAuth.method_panel');
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

$page_title = $nv_Lang->getModule('api_role_credential');

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
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    list($userid, $status) = $db->query('SELECT userid, status FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetch(3);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
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
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetchColumn();
    if (!$exists) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

//Thêm quyền truy cập API-role
if ($action == 'credential') {
    if ($nv_Request->isset_request('add', 'post') or $nv_Request->isset_request('edit', 'post')) {
        $isAdd = $nv_Request->isset_request('add', 'post') ? true : false;
        $userid = $nv_Request->get_int('userid', 'post', 0);
        if (empty($userid)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        }

        $exists = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $userid)->fetchColumn();
        if (!$exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        }

        if ($rolelist[$role_id]['role_object'] == 'admin') {
            $exists = $db->query('SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id =' . $userid)->fetchColumn();
            if (!$exists) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('api_role_credential_error')
                ]);
            }
        }

        $exists = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetchColumn();
        if ($isAdd and $exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        } elseif (!$isAdd and !$exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => '1' . $nv_Lang->getModule('api_role_credential_error')
            ]);
        }

        $adddate = $nv_Request->get_title('adddate', 'post', '');
        $addhour = $nv_Request->get_int('addhour', 'post', 0);
        $addmin = $nv_Request->get_int('addmin', 'post', 0);
        $enddate = $nv_Request->get_title('enddate', 'post', '');
        $endhour = $nv_Request->get_int('endhour', 'post', 0);
        $endmin = $nv_Request->get_int('endmin', 'post', 0);

        unset($m);
        if (!empty($adddate) and preg_match('/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})$/', $adddate, $m)) {
            $addtime = mktime($addhour, $addmin, 0, $m[2], $m[1], $m[3]);
        } else {
            $addtime = NV_CURRENTTIME;
        }
        unset($m);
        if (!empty($enddate) and preg_match('/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})$/', $enddate, $m)) {
            $endtime = mktime($endhour, $endmin, 0, $m[2], $m[1], $m[3]);
        } else {
            $endtime = 0;
        }

        $quota = $nv_Request->get_int('quota', 'post', 0);

        if ($isAdd) {
            $db->query('INSERT INTO ' . $db_config['prefix'] . '_api_role_credential (userid, role_id, addtime, endtime, quota) VALUES (' . $userid . ', ' . $role_id . ', ' . $addtime . ', ' . $endtime . ', ' . $quota . ')');
        } else {
            $db->query('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET addtime = ' . $addtime . ', endtime = ' . $endtime . ', quota = ' . $quota . ' WHERE userid = ' . $userid . ' AND role_id = ' . $role_id);
        }

        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    $credential_data = [
        'userid' => 0,
        'adddate' => '',
        'addhour' => 0,
        'addmin' => 0,
        'enddate' => '',
        'endhour' => 23,
        'endmin' => 59,
        'quota' => ''
    ];
    if ($nv_Request->isset_request('edit, userid', 'get')) {
        $userid = $nv_Request->get_absint('userid', 'get', 0);
        if (!empty($userid)) {
            $row = $db->query('SELECT addtime, endtime, quota FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = ' . $userid . ' AND role_id = ' . $role_id)->fetch();
            if (!empty($row)) {
                $credential_data['userid'] = $userid;
                $addtime = explode('|', nv_date('d.m.Y|H|i', $row['addtime']));
                $credential_data['adddate'] = $addtime[0];
                $credential_data['addhour'] = (int) $addtime[1];
                $credential_data['addmin'] = (int) $addtime[2];
                if (!empty($row['endtime'])) {
                    $endtime = explode('|', nv_date('d.m.Y|H|i', $row['endtime']));
                    $credential_data['enddate'] = $endtime[0];
                    $credential_data['endhour'] = (int) $endtime[1];
                    $credential_data['endmin'] = (int) $endtime[2];
                }
                $credential_data['quota'] = !empty($row['quota']) ? (int) $row['quota'] : '';
            }
        }
    }

    $xtpl = new XTemplate('credential.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CREDENTIAL', $credential_data);

    if (!$credential_data['userid']) {
        $xtpl->assign('GET_USER_URL', $page_url . '&role_id=' . $role_id . '&action=getUser');
        $xtpl->assign('CREDENTIAL_ADD_LABEL', $nv_Lang->getModule('api_role_object_' . $rolelist[$role_id]['role_object']));
        $xtpl->parse('add_credential.is_add');
    } else {
        $xtpl->parse('add_credential.is_edit');
    }

    for ($i = 0; $i < 24; ++$i) {
        $val = str_pad($i, 2, '0', STR_PAD_LEFT);
        $xtpl->assign('ADDHOUR', [
            'key' => $i,
            'sel' => $i == $credential_data['addhour'] ? ' selected="selected"' : '',
            'val' => $val
        ]);
        $xtpl->parse('add_credential.addhour');

        $xtpl->assign('ENDHOUR', [
            'key' => $i,
            'sel' => $i == $credential_data['endhour'] ? ' selected="selected"' : '',
            'val' => $val
        ]);
        $xtpl->parse('add_credential.endhour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $val = str_pad($i, 2, '0', STR_PAD_LEFT);
        $xtpl->assign('ADDMIN', [
            'key' => $i,
            'sel' => $i == $credential_data['addmin'] ? ' selected="selected"' : '',
            'val' => $val
        ]);
        $xtpl->parse('add_credential.addmin');

        $xtpl->assign('ENDMIN', [
            'key' => $i,
            'sel' => $i == $credential_data['endmin'] ? ' selected="selected"' : '',
            'val' => $val
        ]);
        $xtpl->parse('add_credential.endmin');
    }

    $xtpl->parse('add_credential');
    nv_htmlOutput($xtpl->text('add_credential'));
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
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('PAGE_URL', $page_url);
$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('ADD_CREDENTIAL_URL', !empty($role_id) ? $base_url . '&action=credential' : '');
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
    $xtpl->assign('REMOTE_API_OFF', $nv_Lang->getModule('api_remote_off', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=config'));
    $xtpl->parse('main.remote_api_off');
}

foreach ($rolelist as $role) {
    $xtpl->assign('ROLE', [
        'role_id' => $role['role_id'],
        'sel' => $role['role_id'] == $role_id ? ' selected="selected"' : '',
        'title' => $role['role_title'] . ' (' . $nv_Lang->getModule('api_role_type') . ': ' . $nv_Lang->getModule('api_role_type_' . $role['role_type']) . '; ' . $nv_Lang->getModule('api_role_object') . ': ' . $nv_Lang->getModule('api_role_object_' . $role['role_object']) . ')'
    ]);
    $xtpl->parse('main.api_role');
}

if (!empty($role_id)) {
    $xtpl->parse('main.add_credential_button');

    if (!$credentialcount) {
        $xtpl->parse('main.is_role.credential_empty');
    } else {
        $xtpl->assign('CREDENTIAL_COUNT', $credentialcount);

        foreach ($credentiallist as $credential) {
            $credential['last_access'] = !empty($credential['last_access']) ? nv_date('d/m/Y H:i', $credential['last_access']) : '';
            $credential['addtime'] = nv_date('d/m/Y H:i', $credential['addtime']);
            $credential['endtime'] = !empty($credential['endtime']) ? nv_date('d/m/Y H:i', $credential['endtime']) : $nv_Lang->getModule('indefinitely');
            $credential['quota'] = !empty($credential['quota']) ? number_format($credential['quota'], 0, '', '.') : $nv_Lang->getModule('no_quota');
            $xtpl->assign('CREDENTIAL', $credential);

            $sts = [$nv_Lang->getModule('suspended'), $nv_Lang->getModule('active')];
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
