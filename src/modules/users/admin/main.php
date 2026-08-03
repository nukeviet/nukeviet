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

$page_title = $table_caption = $nv_Lang->getModule('list_module_title');

if (empty($access_admin['access_viewlist'][$admin_info['level']])) {
    $contents = nv_theme_alert($nv_Lang->getGlobal('site_info'), $nv_Lang->getModule('viewlist_error_permission'), 'warning');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$usactive = ($global_config['idsite']) ? 3 : -1;
$usactive_old = $nv_Request->get_int('usactive', 'cookie', $usactive);
$usactive = $nv_Request->get_int('usactive', 'post,get', $usactive_old);
$method = $nv_Request->get_string('method', 'post,get', '');

if ($usactive_old != $usactive) {
    $nv_Request->set_Cookie('usactive', $usactive);
}
$_arr_where = [];
$params = [];
if ($global_config['idsite'] > 0) {
    $_arr_where[] = '(tb1.idsite = :idsite OR tb1.userid = :admin_id)';
    $params[':idsite'] = $global_config['idsite'];
    $params[':admin_id'] = $admin_info['admin_id'];
}
if ($usactive == -3) {
    $_arr_where[] = 'tb1.group_id != 7';
} elseif ($usactive == -2) {
    $_arr_where[] = 'tb1.group_id = 7';
} else {
    if ($usactive > -1) {
        $_arr_where[] = 'tb1.active = :active';
        $params[':active'] = ($usactive % 2);
    }
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&usactive=' . $usactive;

$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => ['tb1.userid'],
        'value' => $nv_Lang->getModule('search_id'),
        'selected' => false
    ],
    'username' => [
        'key' => 'username',
        'sql' => ['tb1.username'],
        'value' => $nv_Lang->getModule('search_account'),
        'selected' => false
    ],
    'fullname' => [
        'key' => 'fullname',
        'sql' => [$global_config['name_show'] == 0 ? "concat(tb1.last_name,' ',tb1.first_name)" : "concat(tb1.first_name,' ',tb1.last_name)"],
        'value' => $nv_Lang->getModule('search_name'),
        'selected' => false
    ],
    'email' => [
        'key' => 'email',
        'sql' => ['tb1.email'],
        'value' => $nv_Lang->getModule('search_mail'),
        'selected' => false
    ],
    'oauth' => [
        'key' => 'oauth',
        'sql' => ['tb2.id', 'tb2.email'],
        'value' => $nv_Lang->getModule('search_oauth'),
        'selected' => false
    ]
];

$methodvalue = $nv_Request->get_string('value', 'post,get', '');

$orders = ['userid', 'username', 'full_name', 'email', 'regdate'];
$orderby = $nv_Request->get_string('sortby', 'get', 'userid');
$ordertype = $nv_Request->get_string('sorttype', 'get', 'DESC');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}
$method = (!empty($method) and isset($methods[$method])) ? $method : '';
$join = '';

if (!empty($methodvalue)) {
    if (empty($method)) {
        $join = 'LEFT JOIN ' . NV_MOD_TABLE . '_openid tb2 ON tb1.userid = tb2.userid';
    } elseif ($method == 'oauth') {
        $join = 'INNER JOIN ' . NV_MOD_TABLE . '_openid tb2 ON tb1.userid = tb2.userid';
    }

    if (empty($method)) {
        $array_like = [];
        $i = 0;
        foreach ($methods as $method_i) {
            foreach ($method_i['sql'] as $method_sql) {
                $pname = ':methodvalue' . $i;
                $array_like[] = $method_sql . ' LIKE ' . $pname;
                $params[$pname] = '%' . $methodvalue . '%';
                ++$i;
            }
        }
        $_arr_where[] = '(' . implode(' OR ', $array_like) . ')';
    } else {
        $array_like = [];
        $i = 0;
        foreach ($methods[$method]['sql'] as $method_sql) {
            $pname = ':methodvalue' . $i;
            $array_like[] = $method_sql . ' LIKE ' . $pname;
            $params[$pname] = '%' . $methodvalue . '%';
            ++$i;
        }
        $_arr_where[] = '(' . implode(' OR ', $array_like) . ')';
        $methods[$method]['selected'] = true;
    }
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $table_caption = $nv_Lang->getModule('search_page_title');
}

// Default group is all
$selgroup = $nv_Request->get_int('group', 'post,get', 6);
if (!empty($selgroup) and $selgroup != 6) {
    $_arr_where[] = '(FIND_IN_SET(:selgroup, tb1.in_groups) OR tb1.group_id = :selgroup)';
    $params[':selgroup'] = $selgroup;
    $base_url .= '&amp;group=' . $selgroup;
}

//active2step
$active2step = $nv_Request->get_title('active2step', 'post,get', '');
if ($active2step == 'disabled') {
    $_arr_where[] = 'tb1.active2step = 0';
    $base_url .= '&amp;active2step=disabled';
} elseif ($active2step == 'enabled') {
    $_arr_where[] = 'tb1.active2step > 0';
    $base_url .= '&amp;active2step=enabled';
} elseif ($active2step == 'request') {
    $_arr_where[] = 'tb1.active2step = 2';
    $base_url .= '&amp;active2step=request';
}

$reg_from = $nv_Request->get_title('reg_from', 'post,get', '');
$reg_from_t = nv_d2u_get($reg_from);
if ($reg_from_t != 0) {
    $_arr_where[] = 'tb1.regdate >= :reg_from_t';
    $params[':reg_from_t'] = $reg_from_t;
    $base_url .= '&amp;reg_from=' . $reg_from;
} else {
    $reg_from = '';
}

$reg_to = $nv_Request->get_title('reg_to', 'post,get', '');
$reg_to_t = nv_d2u_get($reg_to, 23, 59, 59);
if ($reg_to_t != 0) {
    $_arr_where[] = 'tb1.regdate <= :reg_to_t';
    $params[':reg_to_t'] = $reg_to_t;
    $base_url .= '&amp;reg_to=' . $reg_to;
} else {
    $reg_to = '';
}

$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 30;

$query_cnt = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' tb1';
$query_list = 'SELECT tb1.* FROM ' . NV_MOD_TABLE . ' tb1';

if (!empty($join)) {
    $query_cnt .= ' ' . $join;
    $query_list .= ' ' . $join;
}

if (!empty($_arr_where)) {
    $query_where = ' WHERE ' . implode(' AND ', $_arr_where);
    $query_cnt .= $query_where;
    $query_list .= $query_where;
}

$stmt = $db->prepare($query_cnt);
foreach ($params as $pname => $pvalue) {
    $stmt->bindValue($pname, $pvalue);
}
$stmt->execute();
$num_items = $stmt->fetchColumn();

$page_url = $base_url;

if (!empty($orderby) and in_array($orderby, $orders, true)) {
    $orderby_sql = $orderby != 'full_name' ? 'tb1.' . $orderby : ($global_config['name_show'] == 0 ? "concat(tb1.first_name,' ',tb1.last_name)" : "concat(tb1.last_name,' ',tb1.first_name)");
    $query_list .= ' ORDER BY ' . $orderby_sql . ' ' . $ordertype;
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$query_list .= ' LIMIT :limit OFFSET :offset';
$stmt = $db->prepare($query_list);
foreach ($params as $pname => $pvalue) {
    $stmt->bindValue($pname, $pvalue);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();
$result2 = $stmt;

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
        $info_verify = $nv_Lang->getModule('emailverify_sys1');
    } elseif ($row['email_verification_time'] == -2) {
        $info_verify = $nv_Lang->getModule('emailverify_sys2');
    } elseif ($row['email_verification_time'] == -1) {
        $info_verify = $nv_Lang->getModule('emailverify_sys3');
    } elseif ($row['email_verification_time'] == 0) {
        $info_verify = $nv_Lang->getModule('emailverify_sys4');
    } elseif ($row['email_verification_time'] > 0) {
        $info_verify = $nv_Lang->getModule('emailverify_sys5', nv_datetime_format($row['email_verification_time'], 1));
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
        'avata' => (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) ? NV_BASE_SITEURL . $row['photo'] : '',
        'avatar_letters' => nv_user_avatar_letters($row['first_name'], $row['last_name'], $row['username']),
        'avatar_color' => nv_user_avatar_color($row['username']),
        'email' => $row['email'],
        'regdate' => nv_datetime_format($row['regdate']),
        'active' => (bool) $row['active'],
        'setactive' => $is_setactive,
        'is_edit' => $is_edit,
        'is_delete' => $is_delete,
        'level' => $nv_Lang->getModule('level0'),
        'is_admin' => false,
        'info_verify' => $info_verify,
        'active_obj' => $row['active_obj'],
        'is_newuser' => ($row['group_id'] == 7 or in_array(7, $row['in_groups'], true)),
        'link' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=memberlist/' . change_alias($row['username']) . '-' . $row['md5username'], true),
        'delete_at' => $row['delete_at']
    ];
    if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite']) {
        $users_list[$row['userid']]['is_edit'] = false;
        $users_list[$row['userid']]['is_delete'] = false;
    }
    $admin_in[] = $row['userid'];
}

// Lấy tên các thành viên kích hoạt tài khoản
if (!empty($array_userids)) {
    $in_userids = implode(',', array_fill(0, count($array_userids), '?'));
    $stmt = $db->prepare('SELECT userid, username, first_name, last_name FROM ' . NV_MOD_TABLE . ' WHERE userid IN (' . $in_userids . ')');
    foreach (array_values($array_userids) as $k => $uid) {
        $stmt->bindValue(($k + 1), $uid, PDO::PARAM_INT);
    }
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $array_users[$row['userid']] = [
            'username' => $row['username'],
            'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username'])
        ];
    }
    $stmt->closeCursor();
}

if (!empty($admin_in)) {
    $in_admin = implode(',', array_fill(0, count($admin_in), '?'));
    $stmt = $db->prepare('SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id IN (' . $in_admin . ')');
    foreach (array_values($admin_in) as $k => $aid) {
        $stmt->bindValue(($k + 1), $aid, PDO::PARAM_INT);
    }
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $users_list[$row['admin_id']]['is_delete'] = false;
        if ($row['lev'] == 1) {
            $users_list[$row['admin_id']]['level'] = $nv_Lang->getGlobal('level1');
            $users_list[$row['admin_id']]['img'] = 'admin1';
        } elseif ($row['lev'] == 2) {
            $users_list[$row['admin_id']]['level'] = $nv_Lang->getGlobal('level2');
            $users_list[$row['admin_id']]['img'] = 'admin2';
        } else {
            $users_list[$row['admin_id']]['level'] = $nv_Lang->getGlobal('level3');
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
            $users_list[$row['admin_id']]['setactive'] = false;
        }
    }
    $stmt->closeCursor();
    if (isset($users_list[$admin_info['admin_id']])) {
        $users_list[$admin_info['admin_id']]['setactive'] = false;
        $users_list[$admin_info['admin_id']]['is_edit'] = true;
    }
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = [];
$head_tds['userid']['title'] = $nv_Lang->getModule('userid');
$head_tds['userid']['href'] = $page_url . '&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $nv_Lang->getGlobal('username');
$head_tds['username']['href'] = $page_url . '&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $global_config['name_show'] == 0 ? $nv_Lang->getModule('lastname_firstname') : $nv_Lang->getModule('firstname_lastname');
$head_tds['full_name']['href'] = $page_url . '&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $nv_Lang->getModule('email');
$head_tds['email']['href'] = $page_url . '&amp;sortby=email&amp;sorttype=ASC';
$head_tds['regdate']['title'] = $nv_Lang->getModule('register_date');
$head_tds['regdate']['href'] = $page_url . '&amp;sortby=regdate&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = $page_url . '&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = $page_url . '&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

// Build usactive dropdown options
$usactive_options = [
    ['key' => -1, 'value' => '---' . $nv_Lang->getModule('usactive') . '---', 'selected' => ($usactive == -1)],
    ['key' => -2, 'value' => $nv_Lang->getGlobal('level7'), 'selected' => ($usactive == -2)],
];
$_bg = (defined('NV_CONFIG_DIR') and $global_config['idsite'] == 0) ? 3 : 1;
for ($i = $_bg; $i >= 0; --$i) {
    $usactive_options[] = ['key' => $i, 'value' => $nv_Lang->getModule('usactive_' . $i), 'selected' => ($i == $usactive)];
}

// Build groups dropdown
$groups_list = [];
$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_groups AS g LEFT JOIN ' . NV_MOD_TABLE . '_groups_detail d ON (g.group_id = d.group_id AND d.lang = :lang) WHERE g.idsite = :idsite OR (g.idsite = 0 AND g.group_id > 3 AND g.siteus = 1) ORDER BY g.idsite, g.weight ASC');
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->bindValue(':idsite', $global_config['idsite'], PDO::PARAM_INT);
$stmt->execute();
while ($group = $stmt->fetch()) {
    $groups_list[] = [
        'group_id' => $group['group_id'],
        'title' => ($group['group_id'] < 10) ? $nv_Lang->getGlobal('level' . $group['group_id']) : $group['title'],
        'selected' => ($group['group_id'] == $selgroup)
    ];
}

// Build active2step dropdown options
$active2steps_options = [
    ['val' => '', 'name' => $nv_Lang->getModule('active2step_status'), 'selected' => ($active2step === '')],
    ['val' => 'disabled', 'name' => $nv_Lang->getModule('active2step_status0'), 'selected' => ($active2step === 'disabled')],
    ['val' => 'enabled', 'name' => $nv_Lang->getModule('active2step_status1'), 'selected' => ($active2step === 'enabled')],
    ['val' => 'request', 'name' => $nv_Lang->getModule('active2step_status2'), 'selected' => ($active2step === 'request')],
];

// Xử lý dữ liệu hiển thị cho từng user
$view_user_allowed = nv_user_in_groups($global_config['whoviewuser']);
$has_choose = false;
$set_active_num = 0;
$delete_num = 0;

foreach ($users_list as &$u) {
    // Xử lý active_obj hiển thị
    if ($u['active_obj'] == 'SYSTEM') {
        $u['active_obj'] = $nv_Lang->getModule('active_obj_1');
    } elseif ($u['active_obj'] == 'EMAIL') {
        $u['active_obj'] = $nv_Lang->getModule('active_obj_2');
    } elseif (preg_match('/^OAUTH\:(.*?)$/', $u['active_obj'], $m)) {
        $u['active_obj'] = $nv_Lang->getModule('active_obj_3', $m[1]);
    } elseif (is_numeric($u['active_obj'])) {
        if (isset($array_users[$u['active_obj']])) {
            $u['active_obj'] = $nv_Lang->getModule('active_obj_4', $array_users[$u['active_obj']]['full_name'], $array_users[$u['active_obj']]['username']);
        } else {
            $u['active_obj'] = $nv_Lang->getModule('active_obj_4', 'N/A', 'N/A');
        }
    } else {
        $u['active_obj'] = 'N/A';
    }

    // Xử lý pending deletion
    if (!empty($u['delete_at']) and $u['delete_at'] > NV_CURRENTTIME) {
        $u['is_pending_deletion'] = true;
        $u['delete_at_display'] = $nv_Lang->getModule('datadeletion_pedding_adm', nv_datetime_format($u['delete_at'], 1));
    } else {
        $u['is_pending_deletion'] = false;
        $u['delete_at_display'] = '';
    }

    // Set official
    $u['is_set_official'] = ($u['is_newuser'] and in_array('setofficial', $allow_func, true) and !defined('NV_IS_USER_FORUM'));

    if ($u['setactive'] or $u['is_delete']) {
        $has_choose = true;
    }
    if ($u['setactive']) {
        ++$set_active_num;
    }
    if ($u['is_delete']) {
        ++$delete_num;
    }
}
unset($u);

$array_action = [];
if ($delete_num > 0) {
    $array_action['del'] = $nv_Lang->getModule('delete');
}
if ($set_active_num > 0) {
    $array_action['active'] = $nv_Lang->getModule('memberlist_active');
    $array_action['unactive'] = $nv_Lang->getModule('memberlist_unactive');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$tpl->assign('IS_FORUM', defined('NV_IS_USER_FORUM'));
$tpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$tpl->assign('TABLE_CAPTION', $table_caption);
$tpl->assign('HEAD', $head_tds);
$tpl->assign('REG_FROM', $reg_from);
$tpl->assign('REG_TO', $reg_to);

$tpl->assign('METHODS', array_values($methods));
$tpl->assign('USACTIVE_OPTIONS', $usactive_options);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('ACTIVE2STEPS_OPTIONS', $active2steps_options);
$tpl->assign('ADV_EXPANDED', ($usactive != -1 || $selgroup != 6 || $active2step !== '' || $reg_from !== '' || $reg_to !== ''));

$tpl->assign('USERS_LIST', array_values($users_list));
$tpl->assign('VIEW_USER_ALLOWED', $view_user_allowed);

$tpl->assign('HAS_CHOOSE', $has_choose);
$tpl->assign('ARRAY_ACTION', $array_action);
$tpl->assign('PAGINATION', $generate_page);
$tpl->assign('CAN_EXPORT', in_array('export', $allow_func, true));

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
