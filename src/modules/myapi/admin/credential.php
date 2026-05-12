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

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if ($nv_Request->isset_request('changeAuth', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $userid = $nv_Request->get_int('changeAuth', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_error')
        ]);
    }

    $stmt = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    $username = $stmt->fetchColumn();

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

        [$ident, $secret] = createAuth($method, $userid);
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

        $iplist = json_encode($api_ips, NV_JSON_ENCODE);
        ipsUpdate($iplist, $method, $userid);
        nv_jsonOutput([
            'status' => 'OK',
            'ips' => implode(', ', $api_ips),
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }

    $api_user = get_api_user($userid);
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('credential-auth.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('USERID', $userid);
    $tpl->assign('API_USER', $api_user);

    $methods = [
        'password_verify' => $nv_Lang->getModule('admin_auth_method_password_verify'),
        'md5_verify' => $nv_Lang->getModule('auth_method_md5_verify'),
        'none' => $nv_Lang->getModule('auth_method_none')
    ];
    foreach ($methods as $key => $name) {
        $method = $api_user[$key] ?? [];
        $method['key'] = $key;
        $method['name'] = $name;
        $methods[$key] = $method;
    }
    $tpl->assign('METHODS', $methods);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));

    $contents = $tpl->fetch('credential-auth.tpl');

    nv_jsonOutput([
        'status' => 'OK',
        'title' => $username,
        'body' => $contents
    ]);
}

[$rolecount, $rolelist] = getRoleList('', '', 0, 0);

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

    $page = $nv_Request->get_page('page', 'post', 1);

    $where = '(tb1.username LIKE :q0 OR tb1.email LIKE :q1 OR tb1.first_name LIKE :q2 OR tb1.last_name LIKE :q3) AND tb1.userid NOT IN (SELECT tb2.userid FROM ' . $db_config['prefix'] . '_api_role_credential tb2 WHERE tb2.role_id = :role_id)';
    if ($rolelist[$role_id]['role_object'] == 'admin') {
        $where .= ' AND tb1.userid IN (SELECT tb3.admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' tb3)';
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' tb1 WHERE ' . $where);
    $q_val = '%' . $q . '%';
    $stmt->bindValue(':q0', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q1', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();
    $total_count = $stmt->fetchColumn();

    $stmt = $db->prepare('SELECT tb1.userid, tb1.username FROM ' . NV_USERS_GLOBALTABLE . ' tb1 WHERE ' . $where . ' ORDER BY tb1.username ASC LIMIT ' . (int) ($page - 1) * 30 . ', 30');
    $stmt->bindValue(':q0', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q1', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();

    $array_data = [
        'total_count' => $total_count,
        'results' => []
    ];

    while ($_scratch = $stmt->fetch(PDO::FETCH_NUM)) {
        [$userid, $username] = $_scratch;
        $array_data['results'][] = [
            'id' => $userid,
            'title' => $username
        ];
    }
    $stmt->closeCursor();

    nv_jsonOutput($array_data);
}

// Thay đổi trạng thái quyền truy cập API-role
if ($action == 'changeStatus' and $nv_Request->isset_request('userid', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $userid = $nv_Request->get_int('userid', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $stmt = $db->prepare('SELECT userid, status FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid AND role_id = :role_id');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();
    [$userid, $status] = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();

    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $status = $status ? 0 : 1;
    $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET status = :status WHERE userid = :userid AND role_id = :role_id');
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

// Xóa quyền truy cập
if ($action == 'del' and $nv_Request->isset_request('userid', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $userid = $nv_Request->get_int('userid', 'post', 0);
    if (empty($userid)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid AND role_id = :role_id');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();

    if (!$stmt->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('api_role_credential_unknown')
        ]);
    }

    $stmt = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid AND role_id = :role_id');
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();
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

        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt->fetchColumn()) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        }

        if ($rolelist[$role_id]['role_object'] == 'admin') {
            $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :userid');
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->execute();

            if (!$stmt->fetchColumn()) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('api_role_credential_error')
                ]);
            }
        }

        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid AND role_id = :role_id');
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetchColumn();

        if ($isAdd and $exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        } elseif (!$isAdd and !$exists) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        }

        $adddate = $nv_Request->get_title('adddate', 'post', '');
        $addhour = $nv_Request->get_int('addhour', 'post', 0);
        $addmin = $nv_Request->get_int('addmin', 'post', 0);
        $enddate = $nv_Request->get_title('enddate', 'post', '');
        $endhour = $nv_Request->get_int('endhour', 'post', 0);
        $endmin = $nv_Request->get_int('endmin', 'post', 0);

        $addtime = (int) nv_d2u_post($adddate, $addhour, $addmin, 0);
        $endtime = (int) nv_d2u_post($enddate, $endhour, $endmin, 0);

        $quota = $nv_Request->get_int('quota', 'post', 0);

        if ($isAdd) {
            $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_api_role_credential (userid, role_id, addtime, endtime, quota) VALUES (:userid, :role_id, :addtime, :endtime, :quota)');
        } else {
            $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_api_role_credential SET addtime = :addtime, endtime = :endtime, quota = :quota WHERE userid = :userid AND role_id = :role_id');
        }
        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->bindValue(':addtime', $addtime, PDO::PARAM_INT);
        $stmt->bindValue(':endtime', $endtime, PDO::PARAM_INT);
        $stmt->bindValue(':quota', $quota, PDO::PARAM_INT);
        $stmt->execute();

        nv_jsonOutput([
            'status' => 'OK',
            'refresh' => 1
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
            $stmt = $db->prepare('SELECT addtime, endtime, quota FROM ' . $db_config['prefix'] . '_api_role_credential WHERE userid = :userid AND role_id = :role_id');
            $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt->bindValue(':role_id', $role_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt->closeCursor();

            if (!empty($row)) {
                $credential_data['userid'] = $userid;

                $credential_data['adddate'] = nv_u2d_post($row['addtime']);
                $credential_data['addhour'] = (int) date('H', $row['addtime']);
                $credential_data['addmin'] = (int) date('i', $row['addtime']);

                if (!empty($row['endtime'])) {
                    $credential_data['enddate'] = nv_u2d_post($row['endtime']);
                    $credential_data['endhour'] = (int) date('H', $row['endtime']);
                    $credential_data['endmin'] = (int) date('i', $row['endtime']);
                }

                $credential_data['quota'] = !empty($row['quota']) ? (int) $row['quota'] : '';
            } else {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('api_role_credential_error')
                ]);
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('api_role_credential_error')
            ]);
        }
    }
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('credential-add.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CREDENTIAL', $credential_data);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    if (!$credential_data['userid']) {
        $tpl->assign('ROLE_ID', $role_id);
        $tpl->assign('ROLE_OBJECT', $rolelist[$role_id]['role_object']);
    }
    nv_jsonOutput([
        'status' => 'OK',
        'html' => $tpl->fetch('credential-add.tpl')
    ]);
}

$base_url = $page_url;
if (!empty($role_id)) {
    $base_url .= '&role_id=' . $role_id;
}

// Tham số tìm kiếm
$search = [];
$search['q'] = $nv_Request->get_title('q', 'get', '');
$search['qhtml'] = nv_htmlspecialchars($search['q']);
$search['adv'] = (int) $nv_Request->get_bool('adv', 'get', false);
$search['addtime_from'] = $nv_Request->get_title('add_from', 'get', '');
$search['addtime_to'] = $nv_Request->get_title('add_to', 'get', '');
$search['endtime_from'] = $nv_Request->get_title('end_from', 'get', '');
$search['endtime_to'] = $nv_Request->get_title('end_to', 'get', '');
$search['last_access_from'] = $nv_Request->get_title('last_from', 'get', '');
$search['last_access_to'] = $nv_Request->get_title('last_to', 'get', '');

$search['t_addtime_from'] = nv_d2u_get($search['addtime_from']);
$search['t_addtime_to'] = nv_d2u_get($search['addtime_to'], 23, 59, 59);
$search['t_endtime_from'] = nv_d2u_get($search['endtime_from']);
$search['t_endtime_to'] = nv_d2u_get($search['endtime_to'], 23, 59, 59);
$search['t_last_access_from'] = nv_d2u_get($search['last_access_from']);
$search['t_last_access_to'] = nv_d2u_get($search['last_access_to'], 23, 59, 59);

if (empty($search['t_addtime_from'])) {
    $search['addtime_from'] = '';
}
if (empty($search['t_addtime_to'])) {
    $search['addtime_to'] = '';
}
if (empty($search['t_endtime_from'])) {
    $search['endtime_from'] = '';
}
if (empty($search['t_endtime_to'])) {
    $search['endtime_to'] = '';
}
if (empty($search['t_last_access_from'])) {
    $search['last_access_from'] = '';
}
if (empty($search['t_last_access_to'])) {
    $search['last_access_to'] = '';
}

if (!empty($search['q'])) {
    $base_url .= '&amp;q=' . urlencode($search['q']);
}
$adv_keys = [
    'addtime_from' => 'add_from',
    'addtime_to' => 'add_to',
    'endtime_from' => 'end_from',
    'endtime_to' => 'end_to',
    'last_access_from' => 'last_from',
    'last_access_to' => 'last_to',
];
foreach ($adv_keys as $sk => $gk) {
    if (!empty($search[$sk])) {
        $base_url .= '&amp;' . $gk . '=' . urlencode($search[$sk]);
        $search['adv'] = 1;
    }
}

if (!empty($role_id)) {
    $page = $nv_Request->get_page('page', 'get', 1);
    $per_page = 30;

    [$credentialcount, $credentiallist] = getCredentialList($role_id, $rolelist[$role_id]['role_object'] == 'admin', $page, $per_page, $search);
    $generate_page = nv_generate_page($base_url, $credentialcount, $per_page, $page);
} else {
    $credentialcount = 0;
    $credentiallist = [];
    $generate_page = '';
}
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('credential-list.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('PAGE_URL', $page_url);
$tpl->assign('ROLE_ID', $role_id);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ADD_CREDENTIAL_URL', !empty($role_id) ? $base_url . '&action=credential' : '');
$tpl->assign('ROLE_COUNT', $rolecount);
$tpl->assign('IS_MAIN', true);
$tpl->assign('ROLE_LIST', $rolelist);
$tpl->assign('CREDENTIAL_COUNT', $credentialcount);
$tpl->assign('GENERATE_PAGE', $generate_page);
$tpl->assign('CREDENTIAL_LIST', $credentiallist);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('SEARCH', $search);
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'nnum_format', 'nv_number_format');

$contents = $tpl->fetch('credential-list.tpl');
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
