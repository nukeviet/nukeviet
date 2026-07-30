<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

// Eg: $id = nv_insert_logs('lang','module name','name key','note',1, 'link acess');

$page_title = $nv_Lang->getModule('logs_title');

// Xóa 1 dòng, nhiều dòng log
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('delete', 'post') and csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('delete') . ' ' . $nv_Lang->getModule('logs_title'), implode(', ', $listid), $admin_info['userid']);

    $stmt = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_logs WHERE id = :id');

    foreach ($listid as $id) {
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('logs.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;
$data = $array_userid = $where = [];
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['from'] = $nv_Request->get_title('from', 'get', '');
$array_search['to'] = $nv_Request->get_title('to', 'get', '');
$array_search['lang'] = $nv_Request->get_title('lang', 'get', '');
$array_search['module'] = $nv_Request->get_title('module', 'get', '');
$array_search['user'] = $nv_Request->get_title('user', 'get', '');

$keyword_binds = [];
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $where[] = '(
        name_key LIKE :keyword1 OR
        note_action LIKE :keyword2 OR
        log_ip LIKE :keyword3 OR
        log_remote_addr LIKE :keyword4
    )';
    $keyword_binds = [':keyword1', ':keyword2', ':keyword3', ':keyword4'];
}

$from = nv_d2u_get($array_search['from']);
if ($from != 0) {
    $where[] = 'log_time >= :from_time';
    $base_url .= '&amp;from=' . urlencode($array_search['from']);
} else {
    $array_search['from'] = '';
}

$to = nv_d2u_get($array_search['to'], 23, 59, 59);
if ($to != 0) {
    $where[] = 'log_time <= :to_time';
    $base_url .= '&amp;to=' . urlencode($array_search['to']);
} else {
    $array_search['to'] = '';
}

if (!empty($array_search['lang'])) {
    if (in_array($array_search['lang'], array_keys($language_array), true)) {
        $where[] = 'lang = :lang_val';
        $base_url .= '&amp;lang=' . $array_search['lang'];
    }
}

if (!empty($array_search['module'])) {
    $where[] = 'module_name = :module_name';
    $base_url .= '&amp;module=' . $array_search['module'];
}

$user_tmp = 0;
if (!empty($array_search['user'])) {
    $user_tmp = ($array_search['user'] == 'system') ? 0 : (int) $array_search['user'];
    $where[] = 'userid = :userid';
    $base_url .= '&amp;user=' . $array_search['user'];
}

// Xóa hết kết quả lọc
if (defined('NV_IS_GODADMIN') and $nv_Request->isset_request('truncate', 'post') and csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_logs';
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sth = $db->prepare($sql);
    if (!empty($keyword_binds)) {
        $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';
        foreach ($keyword_binds as $keyword_bind) {
            $sth->bindValue($keyword_bind, $keyword, PDO::PARAM_STR);
        }
    }
    if ($from != 0) {
        $sth->bindValue(':from_time', $from, PDO::PARAM_INT);
    }
    if ($to != 0) {
        $sth->bindValue(':to_time', $to, PDO::PARAM_INT);
    }
    if (!empty($array_search['lang']) and in_array($array_search['lang'], array_keys($language_array), true)) {
        $sth->bindValue(':lang_val', $array_search['lang'], PDO::PARAM_STR);
    }
    if (!empty($array_search['module'])) {
        $sth->bindValue(':module_name', $array_search['module'], PDO::PARAM_STR);
    }
    if (!empty($array_search['user'])) {
        $sth->bindValue(':userid', $user_tmp, PDO::PARAM_INT);
    }
    $sth->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('log_empty_log'), 'All filter', $admin_info['userid']);

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

$array_order = [];
$array_order['field'] = $nv_Request->get_title('of', 'get', '');
$array_order['value'] = $nv_Request->get_title('ov', 'get', '');
$base_url_order = $base_url;
if ($page > 1) {
    $base_url_order .= '&amp;page=' . $page;
}

$order_fields = ['lang', 'module_name', 'log_time'];
$order_values = ['asc', 'desc'];

if (!in_array($array_order['field'], $order_fields)) {
    $array_order['field'] = '';
}
if (!in_array($array_order['value'], $order_values)) {
    $array_order['value'] = '';
}

$sql = 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_logs';
if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sth = $db->prepare($sql);
if (!empty($keyword_binds)) {
    $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';
    foreach ($keyword_binds as $keyword_bind) {
        $sth->bindValue($keyword_bind, $keyword, PDO::PARAM_STR);
    }
}
if ($from != 0) {
    $sth->bindValue(':from_time', $from, PDO::PARAM_INT);
}
if ($to != 0) {
    $sth->bindValue(':to_time', $to, PDO::PARAM_INT);
}
if (!empty($array_search['lang']) and in_array($array_search['lang'], array_keys($language_array), true)) {
    $sth->bindValue(':lang_val', $array_search['lang'], PDO::PARAM_STR);
}
if (!empty($array_search['module'])) {
    $sth->bindValue(':module_name', $array_search['module'], PDO::PARAM_STR);
}
if (!empty($array_search['user'])) {
    $sth->bindValue(':userid', $user_tmp, PDO::PARAM_INT);
}
$sth->execute();
$num_items = $sth->fetchColumn();

if (!empty($array_order['field']) and !empty($array_order['value'])) {
    $order = $array_order['field'] . ' ' . $array_order['value'];
} else {
    $order = 'id DESC';
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_logs';
if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY ' . $order . ' LIMIT ' . $per_page . ' OFFSET ' . (($page - 1) * $per_page);

$sth = $db->prepare($sql);
if (!empty($keyword_binds)) {
    $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';
    foreach ($keyword_binds as $keyword_bind) {
        $sth->bindValue($keyword_bind, $keyword, PDO::PARAM_STR);
    }
}
if ($from != 0) {
    $sth->bindValue(':from_time', $from, PDO::PARAM_INT);
}
if ($to != 0) {
    $sth->bindValue(':to_time', $to, PDO::PARAM_INT);
}
if (!empty($array_search['lang']) and in_array($array_search['lang'], array_keys($language_array), true)) {
    $sth->bindValue(':lang_val', $array_search['lang'], PDO::PARAM_STR);
}
if (!empty($array_search['module'])) {
    $sth->bindValue(':module_name', $array_search['module'], PDO::PARAM_STR);
}
if (!empty($array_search['user'])) {
    $sth->bindValue(':userid', $user_tmp, PDO::PARAM_INT);
}
$sth->execute();

while ($row = $sth->fetch()) {
    if ($row['userid'] != 0) {
        if (!in_array((int) $row['userid'], array_map('intval', $array_userid), true)) {
            $array_userid[] = $row['userid'];
        }
    }

    $row['time'] = nv_datetime_format($row['log_time'], 0, 0);
    $data[] = $row;
}
$sth->closeCursor();

// Lấy người thực hiện trong danh sách logs
$data_users = [];
$data_users[0] = 'system';
if (!empty($array_userid)) {
    $stmt = $db->prepare('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(', ', array_map('intval', $array_userid)) . ')');
    $stmt->execute();
    while ($_row = $stmt->fetch()) {
        $data_users[$_row['userid']] = $_row['username'];
    }
    $stmt->closeCursor();
    unset($row, $result_users);
}

// Danh sách ngôn ngữ
$list_lang = nv_siteinfo_getlang();
$array_lang = [];
foreach ($list_lang as $lang) {
    $array_lang[] = [
        'key' => $lang,
        'title' => $language_array[$lang]['name']
    ];
}
$tpl->assign('ARRAY_LANG', $array_lang);

// Danh sách module
$list_module = nv_siteinfo_getmodules();
$array_module = [];
foreach ($list_module as $module) {
    $array_module[] = [
        'key' => $module,
        'title' => isset($site_mods[$module]) ? $site_mods[$module]['custom_title'] : (isset($admin_mods[$module]) ? $admin_mods[$module]['custom_title'] : $module),
    ];
}
$tpl->assign('ARRAY_MODULE', $array_module);

// Danh sách người thực hiện
$list_user = nv_siteinfo_getuser();
$array_user = [];
$array_user[] = [
    'key' => '',
    'title' => $nv_Lang->getModule('filter_user'),
];
$array_user[] = [
    'key' => 'system',
    'title' => $nv_Lang->getModule('filter_system'),
];
foreach ($list_user as $user) {
    $array_user[] = [
        'key' => $user['userid'],
        'title' => $user['username'],
    ];
}
$tpl->assign('ARRAY_USER', $array_user);
$tpl->assign('ALLOWED_DELETE', defined('NV_IS_GODADMIN'));

foreach ($data as $key => $row) {
    if (!empty($data_users[$row['userid']])) {
        $row['username'] = $data_users[$row['userid']];
    } else {
        $row['username'] = 'unknown';
    }

    $row['custom_title'] = isset($site_mods[$row['module_name']]) ? $site_mods[$row['module_name']]['custom_title'] : (isset($admin_mods[$row['module_name']]) ? $admin_mods[$row['module_name']]['custom_title'] : $row['module_name']);

    $data[$key] = $row;
}
$tpl->assign('BASE_URL', $base_url);
$tpl->assign('DATA', $data);
$tpl->assign('PAGINATION', nv_generate_page($base_url, $num_items, $per_page, $page));
$tpl->assign('SEARCH', $array_search);
$tpl->assign('ARRAY_ORDER', $array_order);
$tpl->assign('BASE_URL_ORDER', $base_url_order);

$contents = $tpl->fetch('logs.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
