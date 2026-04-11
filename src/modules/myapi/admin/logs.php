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

// Tìm kiếm user
if ($nv_Request->isset_request('getUser, q', 'post')) {
    $q = $nv_Request->get_title('q', 'post', '');
    $q = str_replace('+', ' ', $q);
    $q = nv_htmlspecialchars($q);

    $page = $nv_Request->get_page('page', 'post', 1);

    $where = '(tb1.username LIKE :q0 OR tb1.email LIKE :q1 OR tb1.first_name LIKE :q2 OR tb1.last_name LIKE :q3) AND tb1.userid IN (SELECT tb2.userid FROM ' . $db_config['prefix'] . '_api_role_logs tb2)';

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' tb1 WHERE ' . $where);
    $q_val = '%' . $q . '%';
    $stmt->bindValue(':q0', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q1', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $q_val, PDO::PARAM_STR);
    $stmt->execute();
    $total_count = $stmt->fetchColumn();

    $stmt = $db->prepare('SELECT tb1.userid, tb1.username FROM ' . NV_USERS_GLOBALTABLE . ' tb1 WHERE ' . $where . ' ORDER BY tb1.username ASC LIMIT ' . (int) ($page - 1) * 30 . ', 30');
    $stmt->bindValue(':q0', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q1', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q2', $q_val, PDO::PARAM_STR);
    $stmt->bindValue(':q3', $q_val, PDO::PARAM_STR);
    $stmt->execute();

    $array_data = [
        'total_count' => $total_count,
        'results' => []
    ];

    while ($_row = $stmt->fetch()) {
        $array_data['results'][] = [
            'id' => $_row['userid'],
            'title' => $_row['username']
        ];
    }
    $stmt->closeCursor();

    nv_jsonOutput($array_data);
}

// Xóa log
if (defined('MANUALL_DEL_API_LOG') and MANUALL_DEL_API_LOG === true) {
    if ($nv_Request->isset_request('delLog', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }
        $id = $nv_Request->get_int('delLog', 'post', 0);
        if (!empty($id)) {
            $stmt = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }

    // Xóa nhiều log
    if ($nv_Request->isset_request('delLogs', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }
        $ids = $nv_Request->get_title('delLogs', 'post', '');
        if (!empty($ids)) {
            $ids_arr = array_map('intval', explode(',', preg_replace('/[^0-9\,]+/', '', $ids)));
            $ids_str = implode(',', $ids_arr);
            if (!empty($ids_str)) {
                $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE id IN (' . $ids_str . ')');
            }
        } else {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }

    // Xóa tất cả log
    if ($nv_Request->isset_request('delAllLogs', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }
        $db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_api_role_logs');
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

// Lấy danh sách các api-role
$sql = 'SELECT tb1.role_id, tb2.role_title FROM ' . $db_config['prefix'] . '_api_role_logs tb1 INNER JOIN ' . $db_config['prefix'] . '_api_role tb2 ON (tb1.role_id=tb2.role_id) GROUP BY tb1.role_id';
$result = $db->query($sql);
$roles = [];
while ($row = $result->fetch()) {
    $roles[$row['role_id']] = $row['role_title'];
}

// Lấy danh sách các api
$sql = 'SELECT command FROM ' . $db_config['prefix'] . '_api_role_logs GROUP BY command';
$result = $db->query($sql);
$apis = [];
while ($row = $result->fetch()) {
    $apis[] = $row['command'];
}

$where = [];
$params = [];
$get_data = [];
$get_data['role_id'] = $nv_Request->get_absint('role_id', 'get', 0);
if (!empty($get_data['role_id']) and !empty($roles[$get_data['role_id']])) {
    $page_url .= '&amp;role_id=' . $get_data['role_id'];
    $where[] = 'tb1.role_id = :role_id';
    $params[':role_id'] = [$get_data['role_id'], PDO::PARAM_INT];
} else {
    $get_data['role_id'] = 0;
}

$get_data['command'] = $nv_Request->get_title('command', 'get', '');
if (!empty($get_data['command']) and in_array($get_data['command'], $apis, true)) {
    $page_url .= '&amp;command=' . $get_data['command'];
    $where[] = 'tb1.command = :command';
    $params[':command'] = [$get_data['command'], PDO::PARAM_STR];
} else {
    $get_data['command'] = '';
}

$get_data['userid'] = $nv_Request->get_absint('userid', 'get', 0);
$get_data['username'] = '';
if (!empty($get_data['userid'])) {
    $stmt = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
    $stmt->bindValue(':userid', $get_data['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $get_data['username'] = $stmt->fetchColumn();

    if (!empty($get_data['username'])) {
        $page_url .= '&amp;userid=' . $get_data['userid'];
        $where[] = 'tb1.userid = :userid';
        $params[':userid'] = [$get_data['userid'], PDO::PARAM_INT];
    } else {
        $get_data['userid'] = 0;
    }
}

$get_data['fromdate'] = $nv_Request->get_title('fromdate', 'get', '');
if (!empty($get_data['fromdate'])) {
    $fromdate = nv_d2u_get($get_data['fromdate']);
    if ($fromdate !== false) {
        $page_url .= '&amp;fromdate=' . nv_u2d_get($fromdate);
        $where[] = 'tb1.log_time >= :fromdate';
        $params[':fromdate'] = [$fromdate, PDO::PARAM_INT];
    }
}

$get_data['todate'] = $nv_Request->get_title('todate', 'get', '');
if (!empty($get_data['todate'])) {
    $todate = nv_d2u_get($get_data['todate'], 23, 59, 59);
    if ($todate !== false) {
        $page_url .= '&amp;todate=' . nv_u2d_get($todate);
        $where[] = 'tb1.log_time <= :todate';
        $params[':todate'] = [$todate, PDO::PARAM_INT];
    }
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

$where_str = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';
$join = ' INNER JOIN ' . $db_config['prefix'] . '_api_role tb2 ON (tb1.role_id = tb2.role_id) INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON (tb1.userid = tb3.userid)';

$stmt = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_api_role_logs tb1' . $join . $where_str);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val[0], $val[1]);
}
$stmt->execute();
$all_pages = $stmt->fetchColumn();

$data = [];
$generate_page = '';
if ($all_pages) {
    $sql = 'SELECT tb1.*, tb2.role_title, tb2.role_type, tb2.role_object, tb3.username FROM ' . $db_config['prefix'] . '_api_role_logs tb1' . $join . $where_str . ' ORDER BY tb1.log_time DESC LIMIT ' . (int) ($page - 1) * $per_page . ',' . (int) $per_page;
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val[0], $val[1]);
    }
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $row['log_time'] = nv_datetime_format($row['log_time']);
        $row['role_type'] = $nv_Lang->getModule('api_role_type_' . $row['role_type']);
        $row['role_object'] = $nv_Lang->getModule('api_role_object_' . $row['role_object']);
        $data[$row['id']] = $row;
    }
    $stmt->closeCursor();
    $generate_page = nv_generate_page($page_url, $all_pages, $per_page, $page);
}

$page_title = $nv_Lang->getModule('logs');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('logs.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('GET_DATA', $get_data);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$tpl->assign('ROLES', $roles);
$tpl->assign('APIS', $apis);
$tpl->assign('DATA', $data);
$tpl->assign('GENERATE_PAGE', $generate_page);

$contents = $tpl->fetch('logs.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
