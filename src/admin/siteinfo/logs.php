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
if (defined('NV_IS_GODADMIN') and $nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('delete') . ' ' . $nv_Lang->getModule('logs_title'), implode(', ', $listid), $admin_info['userid']);

    foreach ($listid as $id) {
        $sql = "DELETE FROM " . $db_config['prefix'] . "_logs WHERE id=" . $id;
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('logs.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

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

$db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_logs');

$check_like = false;
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $where[] = '( name_key LIKE :keyword1 OR note_action LIKE :keyword2 )';
    $check_like = true;
}

$from = nv_d2u_get($array_search['from']);
if ($from != 0) {
    $where[] = 'log_time >= ' . $from;
    $base_url .= '&amp;from=' . urlencode($array_search['from']);
} else {
    $array_search['from'] = '';
}

$to = nv_d2u_get($array_search['to'], 23, 59, 59);
if ($to != 0) {
    $where[] = 'log_time <= ' . $to;
    $base_url .= '&amp;to=' . urlencode($array_search['to']);
} else {
    $array_search['to'] = '';
}

if (!empty($array_search['lang'])) {
    if (in_array($array_search['lang'], array_keys($language_array), true)) {
        $where[] = 'lang=' . $db->quote($array_search['lang']);
        $base_url .= '&amp;lang=' . $array_search['lang'];
    }
}

if (!empty($array_search['module'])) {
    $where[] = 'module_name=' . $db->quote($array_search['module']);
    $base_url .= '&amp;module=' . $array_search['module'];
}

if (!empty($array_search['user'])) {
    $user_tmp = ($array_search['user'] == 'system') ? 0 : (int) $array_search['user'];
    $where[] = 'userid=' . $user_tmp;
    $base_url .= '&amp;user=' . $array_search['user'];
}

// Xóa hết kết quả lọc
if (defined('NV_IS_GODADMIN') and $nv_Request->get_title('truncate', 'post', '') === NV_CHECK_SESSION) {
    $sql = "DELETE FROM " . $db_config['prefix'] . "_logs";
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sth = $db->prepare($sql);
    if ($check_like) {
        $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';

        $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
        $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
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

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$sth = $db->prepare($db->sql());
if ($check_like) {
    $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';

    $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
    $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
}
$sth->execute();
$num_items = $sth->fetchColumn();

if (!empty($array_order['field']) and !empty($array_order['value'])) {
    $order = $array_order['field'] . ' ' . $array_order['value'];
} else {
    $order = 'id DESC';
}
$db->select('*')->order($order)->limit($per_page)->offset(($page - 1) * $per_page);

$sql = $db->sql();
$sth = $db->prepare($sql);
if ($check_like) {
    $keyword = '%' . addcslashes($array_search['q'], '_%') . '%';

    $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
    $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
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
    $result_users = $db->query('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $array_userid) . ')');
    while ($row = $result_users->fetch()) {
        $data_users[$row['userid']] = $row['username'];
    }
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
