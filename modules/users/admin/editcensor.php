<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $table_caption = $lang_module['editcensor'];

// Hủy bỏ thông tin chỉnh sửa
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid;
    $db->exec($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Denied User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Xác nhận thông tin chỉnh sửa
if ($nv_Request->isset_request('approved', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_edit tb1, ' . NV_MOD_TABLE . ' tb2 WHERE tb1.userid=tb2.userid AND tb1.userid=' . $userid;
    $row = $db->query($sql)->fetch();

    if (!empty($row)) {
        print_r($row);
        die();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Approved User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_htmlOutput('OK');
}

$reviewuid = $nv_Request->get_int('reviewuid', 'get', 0);
if (!empty($reviewuid)) {
    // FIXME
    die('Review');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$methods = array(
    'userid' => array(
        'key' => 'userid',
        'sql' => 'tb2.userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ),
    'username' => array(
        'key' => 'username',
        'sql' => 'tb2.username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ),
    'full_name' => array(
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(tb2.last_name,' ',tb2.first_name)" : "concat(tb2.first_name,' ',tb2.last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ),
    'email' => array(
        'key' => 'email',
        'sql' => 'tb2.email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    )
);
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');
$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = array(
    'userid',
    'username',
    'full_name',
    'email',
    'lastedit'
);
$orderby = $nv_Request->get_string('sortby', 'get', '');
$ordertype = $nv_Request->get_string('sorttype', 'get', '');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}

$db->sqlreset()
    ->select('COUNT(tb1.userid)')
    ->from(NV_MOD_TABLE . '_edit tb1, ' . NV_MOD_TABLE . ' tb2');

$where = [];
$where[] = 'tb1.userid=tb2.userid';
if (!empty($method) and isset($methods[$method]) and !empty($methodvalue)) {
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $methods[$method]['selected'] = ' selected="selected"';
    $table_caption = $lang_module['search_page_title'];
    $where[] = $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
}

$db->where(implode(' AND ', $where));
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('tb1.userid, tb1.lastedit, tb2.username, tb2.first_name, tb2.last_name, tb2.email')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

if (!empty($orderby) and in_array($orderby, $orders)) {
    $orderby_sql = $orderby != 'full_name' ? (($orderby != 'lastedit' ? 'tb2.' : 'tb1.') . $orderby) : ($global_config['name_show'] == 0 ? "concat(tb2.first_name,' ',tb2.last_name)" : "concat(tb2.last_name,' ',tb2.first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result = $db->query($db->sql());

$users_list = array();
while ($row = $result->fetch()) {
    $users_list[$row['userid']] = array(
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'lastedit' => date('d/m/Y H:i', $row['lastedit'])
    );
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = array();
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=email&amp;sorttype=ASC';
$head_tds['lastedit']['title'] = $lang_module['editcensor_lastedit'];
$head_tds['lastedit']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=lastedit&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

$xtpl = new XTemplate('editcensor.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
}

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}

foreach ($head_tds as $head_td) {
    $xtpl->assign('HEAD_TD', $head_td);
    $xtpl->parse('main.head_td');
}

foreach ($users_list as $u) {
    $xtpl->assign('CONTENT_TD', $u);
    $xtpl->assign('VIEW_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;reviewuid=' . $u['userid']);
    $xtpl->parse('main.xusers');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
