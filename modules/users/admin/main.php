<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $table_caption = $lang_module['list_module_title'];

if (empty($access_admin['access_viewlist'][$admin_info['level']])) {
    $contents = nv_theme_alert($lang_global['site_info'], $lang_module['viewlist_error_permission'], 'warning');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$usactive = ($global_config['idsite']) ? 3 : -1;
$usactive_old = $nv_Request->get_int('usactive', 'cookie', $usactive);
$usactive = $nv_Request->get_int('usactive', 'post,get', $usactive_old);
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');

if ($usactive_old != $usactive) {
    $nv_Request->set_Cookie('usactive', $usactive);
}
$_arr_where = [];
if ($global_config['idsite'] > 0) {
    $_arr_where[] = '(idsite=' . $global_config['idsite'] . ' OR userid = ' . $admin_info['admin_id'] . ')';
}
if ($usactive == -3) {
    $_arr_where[] = 'group_id!=7';
} elseif ($usactive == -2) {
    $_arr_where[] = 'group_id=7';
} else {
    if ($usactive > -1) {
        $_arr_where[] = 'active=' . ($usactive % 2);
    }
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&usactive=' . $usactive;

$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => 'userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ],
    'username' => [
        'key' => 'username',
        'sql' => 'username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ],
    'fullname' => [
        'key' => 'fullname',
        'sql' => $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ],
    'email' => [
        'key' => 'email',
        'sql' => 'email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    ]
];

$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = ['userid', 'username', 'full_name', 'email', 'regdate'];
$orderby = $nv_Request->get_string('sortby', 'get', 'userid');
$ordertype = $nv_Request->get_string('sorttype', 'get', 'DESC');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}
$method = (!empty($method) and isset($methods[$method])) ? $method : '';

if (!empty($methodvalue)) {
    if (empty($method)) {
        $array_like = [];
        foreach ($methods as $method_i) {
            $array_like[] = $method_i['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
        }
        $_arr_where[] = '(' . implode(' OR ', $array_like) . ')';
    } else {
        $_arr_where[] = ' (' . $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%')";
        $methods[$method]['selected'] = ' selected="selected"';
    }
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $table_caption = $lang_module['search_page_title'];
}

// Default group is all

$group_global = $nv_Request->get_int('group', 'post,get', 6);
if (!empty($group_global) && $group_global != '6') {
    $_arr_where[] = '(FIND_IN_SET(' . $group_global . ', in_groups) OR group_id = ' . $group_global . ')';
    $base_url .= '&amp;group=' . $group_global;
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_MOD_TABLE);

if (!empty($_arr_where)) {
    $db->where(implode(' AND ', $_arr_where));
}

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
if (!empty($orderby) and in_array($orderby, $orders, true)) {
    $orderby_sql = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? "concat(first_name,' ',last_name)" : "concat(last_name,' ',first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result2 = $db->query($db->sql());

$users_list = [];
$admin_in = [];
$is_edit = (in_array('edit', $allow_func, true)) ? true : false;
$is_delete = (in_array('del', $allow_func, true)) ? true : false;
$is_setactive = (in_array('setactive', $allow_func, true) and !defined('NV_IS_USER_FORUM')) ? true : false;
$array_userids = $array_users = [];

while ($row = $result2->fetch()) {
    $row['in_groups'] = array_map('intval', explode(',', $row['in_groups']));

    // Thông tin tài khoản, xác thực email
    if ($row['email_verification_time'] == -3) {
        $info_verify = $lang_module['emailverify_sys1'];
    } elseif ($row['email_verification_time'] == -2) {
        $info_verify = $lang_module['emailverify_sys2'];
    } elseif ($row['email_verification_time'] == -1) {
        $info_verify = $lang_module['emailverify_sys3'];
    } elseif ($row['email_verification_time'] == 0) {
        $info_verify = $lang_module['emailverify_sys4'];
    } elseif ($row['email_verification_time'] > 0) {
        $info_verify = sprintf($lang_module['emailverify_sys5'], nv_date('H:i d/m/Y', $row['email_verification_time']));
    } else {
        // Cái này để debug trong trường hợp lỗi CSDL
        $info_verify = 'Error verification data';
    }

    if (is_numeric($row['active_obj'])) {
        $array_userids[$row['active_obj']] = $row['active_obj'];
    }

    $users_list[$row['userid']] = [
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'regdate' => date('d/m/Y H:i', $row['regdate']),
        'checked' => $row['active'] ? ' checked="checked"' : '',
        'disabled' => ($is_setactive) ? ' onclick="nv_chang_status(' . $row['userid'] . ');"' : ' disabled="disabled"',
        'is_edit' => $is_edit,
        'is_delete' => $is_delete,
        'level' => $lang_module['level0'],
        'is_admin' => false,
        'info_verify' => $info_verify,
        'active_obj' => $row['active_obj'],
        'is_newuser' => ($row['group_id'] == 7 or in_array(7, $row['in_groups'], true)),
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=memberlist/' . change_alias($row['username']) . '-' . $row['md5username']
    ];
    if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite']) {
        $users_list[$row['userid']]['is_edit'] = false;
        $users_list[$row['userid']]['is_delete'] = false;
    }
    $admin_in[] = $row['userid'];
}

// Lấy tên các thành viên kích hoạt tài khoản
if (!empty($array_userids)) {
    $sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_MOD_TABLE . ' WHERE userid IN(' . implode(',', $array_userids) . ')';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_users[$row['userid']] = [
            'username' => $row['username'],
            'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username'])
        ];
    }
}

if (!empty($admin_in)) {
    $admin_in = implode(',', $admin_in);
    $sql = 'SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id IN (' . $admin_in . ')';
    $query = $db->query($sql);
    while ($row = $query->fetch()) {
        $users_list[$row['admin_id']]['is_delete'] = false;
        if ($row['lev'] == 1) {
            $users_list[$row['admin_id']]['level'] = $lang_global['level1'];
            $users_list[$row['admin_id']]['img'] = 'admin1';
        } elseif ($row['lev'] == 2) {
            $users_list[$row['admin_id']]['level'] = $lang_global['level2'];
            $users_list[$row['admin_id']]['img'] = 'admin2';
        } else {
            $users_list[$row['admin_id']]['level'] = $lang_global['level3'];
            $users_list[$row['admin_id']]['img'] = 'admin3';
        }

        $users_list[$row['admin_id']]['is_admin'] = true;
        if ($users_list[$row['admin_id']]['is_edit']) {
            if (defined('NV_IS_GODADMIN')) {
                $users_list[$row['admin_id']]['is_edit'] = true;
            } elseif (defined('NV_IS_SPADMIN') and !($row['lev'] == 1 or $row['lev'] == 2)) {
                $users_list[$row['admin_id']]['is_edit'] = true;
            } else {
                $users_list[$row['admin_id']]['is_edit'] = false;
            }
        }
        if (!$users_list[$row['admin_id']]['is_edit']) {
            $users_list[$row['admin_id']]['disabled'] = ' disabled="disabled"';
        }
    }
    if (isset($users_list[$admin_info['admin_id']])) {
        $users_list[$admin_info['admin_id']]['disabled'] = ' disabled="disabled"';
        $users_list[$admin_info['admin_id']]['is_edit'] = true;
    }
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = [];
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $global_config['name_show'] == 0 ? $lang_module['lastname_firstname'] : $lang_module['firstname_lastname'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $lang_module['register_date'];
$head_tds['regdate']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=regdate&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);
$xtpl->assign('HEAD', $head_tds);
$xtpl->assign('CHECKSESS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op));

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
}

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}
$_bg = (defined('NV_CONFIG_DIR') and $global_config['idsite'] == 0) ? 3 : 1;
for ($i = $_bg; $i >= 0; --$i) {
    $m = [
        'key' => $i,
        'selected' => ($i == $usactive) ? ' selected="selected"' : '',
        'value' => $lang_module['usactive_' . $i]
    ];
    $xtpl->assign('USACTIVE', $m);
    $xtpl->parse('main.usactive');
}
$xtpl->assign('SELECTED_NEW_USERS', $usactive == -2 ? ' selected="selected"' : '');

// get all group

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_groups AS g LEFT JOIN ' . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE g.idsite = " . $global_config['idsite'] . ' OR (g.idsite=0 AND g.group_id>3 AND g.siteus=1) ORDER BY g.idsite, g.weight ASC';
$result = $db->query($sql);
while ($group = $result->fetch()) {

    $group['selected'] = ($group['group_id'] == $group_global) ? ' selected="selected"' : '';
    $group['title'] = ($group['group_id'] < 10) ? $lang_global['level' . $group['group_id']] : $group['title'];
    $xtpl->assign('GROUP', $group);
    $xtpl->parse('main.group');
}

$xtpl->assign('SELECTED_NEW_USERS', $usactive == -2 ? ' selected="selected"' : '');
$view_user_allowed = nv_user_in_groups($global_config['whoviewuser']);
$has_choose = false;

foreach ($users_list as $u) {
    if ($u['active_obj'] == 'SYSTEM') {
        $u['active_obj'] = $lang_module['active_obj_1'];
    } elseif ($u['active_obj'] == 'EMAIL') {
        $u['active_obj'] = $lang_module['active_obj_2'];
    } elseif (preg_match('/^OAUTH\:(.*?)$/', $u['active_obj'], $m)) {
        $u['active_obj'] = sprintf($lang_module['active_obj_3'], $m[1]);
    } elseif (is_numeric($u['active_obj'])) {
        if (isset($array_users[$u['active_obj']])) {
            $u['active_obj'] = sprintf($lang_module['active_obj_4'], $array_users[$u['active_obj']]['full_name'], $array_users[$u['active_obj']]['username']);
        } else {
            $u['active_obj'] = sprintf($lang_module['active_obj_4'], 'N/A', 'N/A');
        }
    } else {
        $u['active_obj'] = 'N/A';
    }
    $xtpl->assign('CONTENT_TD', $u);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);

    if ($u['is_admin']) {
        $xtpl->parse('main.xusers.is_admin');
    }

    if ($view_user_allowed) {
        $xtpl->parse('main.xusers.view');
    } else {
        $xtpl->parse('main.xusers.show');
    }

    if (!defined('NV_IS_USER_FORUM')) {
        if ($u['is_edit']) {
            $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $u['userid']);
            $xtpl->assign('EDIT_2STEP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $u['userid']);
            $xtpl->assign('EDIT_OAUTH_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_oauth&amp;userid=' . $u['userid']);
            $xtpl->parse('main.xusers.edit');
        }
        if ($u['is_delete']) {
            $xtpl->parse('main.xusers.del');
        }
        if ($u['is_newuser'] and in_array('setofficial', $allow_func, true)) {
            $xtpl->parse('main.xusers.set_official');
        }
        if ($is_setactive and $u['is_delete']) {
            $has_choose = true;
            $xtpl->parse('main.xusers.choose');
        }
    }

    $xtpl->parse('main.xusers');
}

$has_footer = false;
$array_action = [
    'del' => $lang_module['delete'],
    'active' => $lang_module['memberlist_active'],
    'unactive' => $lang_module['memberlist_unactive']
];
if ($has_choose) {
    $has_footer = true;
    foreach ($array_action as $action_key => $action_lang) {
        $xtpl->assign('ACTION_KEY', $action_key);
        $xtpl->assign('ACTION_LANG', $action_lang);
        $xtpl->parse('main.footer.action.loop');
    }
    $xtpl->parse('main.footer.action');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.footer.generate_page');
    $has_footer = true;
}

if (in_array('export', $allow_func, true)) {
    $has_footer = true;
    $xtpl->parse('main.footer.exportfile');
}

if ($has_footer) {
    $xtpl->parse('main.footer');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
