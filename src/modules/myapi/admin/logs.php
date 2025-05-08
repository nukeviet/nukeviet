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
    $dbkeyhtml = $db->dblikeescape($q);

    $page = $nv_Request->get_page('page', 'post', 1);

    $where = "(tb1.username LIKE '%" . $dbkeyhtml . "%' OR tb1.email LIKE '%" . $dbkeyhtml . "%' OR tb1.first_name like '%" . $dbkeyhtml . "%' OR tb1.last_name like '%" . $dbkeyhtml . "%') AND tb1.userid IN (SELECT tb2.userid FROM " . $db_config['prefix'] . '_api_role_logs tb2)';

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
    while ([$userid, $username] = $result->fetch(3)) {
        $array_data['results'][] = [
            'id' => $userid,
            'title' => $username
        ];
    }

    nv_jsonOutput($array_data);
}

// Xóa log
if (defined('MANUALL_DEL_API_LOG') and MANUALL_DEL_API_LOG === true) {
    if ($nv_Request->isset_request('delLog', 'post')) {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if ($checkss != md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }
        $id = $nv_Request->get_int('delLog', 'post', 0);
        if (!empty($id)) {
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE id=' . $id);
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
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if ($checkss != md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }
        $ids = $nv_Request->get_title('delLogs', 'post', '');
        if (!empty($ids)) {
            $ids = preg_replace('/[^0-9\,]+/', '', $ids);
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_api_role_logs WHERE id IN (' . $ids . ')');
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
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if ($checkss != md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
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
$get_data = [];
$get_data['role_id'] = $nv_Request->get_absint('role_id', 'get', 0);
if (!empty($get_data['role_id']) and !empty($roles[$get_data['role_id']])) {
    $page_url .= '&amp;role_id=' . $get_data['role_id'];
    $where[] = 'tb1.role_id = ' . $get_data['role_id'];
} else {
    $get_data['role_id'] = 0;
}

$get_data['command'] = $nv_Request->get_title('command', 'get', '');
if (!empty($get_data['command']) and in_array($get_data['command'], $apis, true)) {
    $page_url .= '&amp;command=' . $get_data['command'];
    $where[] = 'tb1.command = ' . $db->quote($get_data['command']);
} else {
    $get_data['command'] = '';
}

$get_data['userid'] = $nv_Request->get_absint('userid', 'get', 0);
$get_data['username'] = '';
if (!empty($get_data['userid'])) {
    $get_data['username'] = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $get_data['userid'])->fetchColumn();
    if (!empty($get_data['username'])) {
        $page_url .= '&amp;userid=' . $get_data['userid'];
        $where[] = 'tb1.userid = ' . $get_data['userid'];
    } else {
        $get_data['userid'] = 0;
    }
}

$get_data['fromdate'] = nv_d2u_get($nv_Request->get_title('fromdate', 'get', ''));
if ($get_data['fromdate'] !== false) {
    $page_url .= '&amp;fromdate=' . nv_u2d_get($get_data['fromdate']);
    $where[] = 'tb1.log_time >= ' . $get_data['fromdate'];
}

$get_data['todate'] = nv_d2u_get($nv_Request->get_title('todate', 'get', ''), 23, 59, 59);
if ($get_data['todate'] !== false) {
    $page_url .= '&amp;todate=' . nv_u2d_get($get_data['todate']);
    $where[] = 'tb1.log_time <= ' . $get_data['todate'];
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_api_role_logs tb1')
    ->join('INNER JOIN ' . $db_config['prefix'] . '_api_role tb2 ON (tb1.role_id=tb2.role_id) INNER JOIN ' . NV_USERS_GLOBALTABLE . ' tb3 ON (tb1.userid=tb3.userid)');
if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}
$all_pages = $db->query($db->sql())
    ->fetchColumn();
$data = [];
$generate_page = '';
if ($all_pages) {
    $db->select('tb1.*, tb2.role_title, tb2.role_type, tb2.role_object, tb3.username')
        ->order('tb1.log_time DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $row['log_time'] = nv_datetime_format($row['log_time']);
        $row['role_type'] = $nv_Lang->getModule('api_role_type_' . $row['role_type']);
        $row['role_object'] = $nv_Lang->getModule('api_role_object_' . $row['role_object']);
        $data[$row['id']] = $row;
    }
    $generate_page = nv_generate_page($page_url, $all_pages, $per_page, $page);
}

$page_title = $nv_Lang->getModule('logs');
$get_data['fromdate'] = nv_u2d_get($get_data['fromdate']);
$get_data['todate'] = nv_u2d_get($get_data['todate']);
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('logs.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('GET_DATA', $get_data);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']));

$tpl->assign('ROLES', $roles);
$tpl->assign('APIS', $apis);
$tpl->assign('DATA', $data);
$tpl->assign('GENERATE_PAGE', $generate_page);

$contents = $tpl->fetch('logs.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
