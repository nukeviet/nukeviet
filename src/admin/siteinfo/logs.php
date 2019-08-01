<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-10-2010 14:43
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

// Eg: $id = nv_insert_logs('lang','module name','name key','note',1, 'link acess');

$page_title = $nv_Lang->getModule('logs_title');

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;
$data = [];
$array_userid = [];

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

// Search data
$data_search = array(
    'q' => '',
    'from' => '',
    'to' => '',
    'lang' => '',
    'module' => '',
    'user' => '',
    'is_search' => false
);

$array_where = [];

$check_like = false;
if ($nv_Request->isset_request('checksess', 'get')) {
    $checksess = $nv_Request->get_title('checksess', 'get', '');

    if ($checksess != md5('siteinfo_' . NV_CHECK_SESSION . '_' . $admin_info['userid'])) {
        nv_insert_logs(NV_LANG_DATA, $module_name, sprintf($nv_Lang->getModule('filter_check_log'), $op), $admin_info['username'] . ' - ' . $admin_info['userid'], 0);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $data_search = array(
        'q' => $nv_Request->get_title('q', 'get', ''),
        'from' => $nv_Request->get_title('from', 'get', ''),
        'to' => $nv_Request->get_title('to', 'get', ''),
        'lang' => $nv_Request->get_title('lang', 'get', ''),
        'module' => $nv_Request->get_title('module', 'get', ''),
        'user' => $nv_Request->get_title('user', 'get', ''),
        'is_search' => true
    );

    $base_url .= '&amp;checksess=' . $checksess;

    if (!empty($data_search['q']) and $data_search['q'] != $nv_Lang->getModule('filter_enterkey')) {
        $base_url .= '&amp;q=' . $data_search['q'];
        $array_where[] = "( name_key LIKE :keyword1 OR note_action LIKE :keyword2 )";
        $check_like = true;
    }

    if (!empty($data_search['from'])) {
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $data_search['from'], $match)) {
            $from = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
            $array_where[] = 'log_time >= ' . $from;
            $base_url .= '&amp;from=' . $data_search['from'];
        }
    }

    if (!empty($data_search['to'])) {
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $data_search['to'], $match)) {
            $to = mktime(23, 59, 59, $match[2], $match[1], $match[3]);
            $array_where[] = 'log_time <= ' . $to;
            $base_url .= '&amp;to=' . $data_search['to'];
        }
    }

    if (!empty($data_search['lang'])) {
        if (in_array($data_search['lang'], array_keys($language_array))) {
            $array_where[] = 'lang=' . $db->quote($data_search['lang']);
            $base_url .= '&amp;lang=' . $data_search['lang'];
        }
    }

    if (!empty($data_search['module'])) {
        $array_where[] = 'module_name=' . $db->quote($data_search['module']);
        $base_url .= '&amp;module=' . $data_search['module'];
    }

    if (!empty($data_search['user'])) {
        $user_tmp = ($data_search['user'] == 'system') ? 0 : ( int )$data_search['user'];

        $array_where[] = 'userid=' . $user_tmp;
        $base_url .= '&amp;user=' . $data_search['user'];
    }
}

// Order data
$order = [];
$check_order = array('ASC', 'DESC', 'NO');
$opposite_order = array(
    'NO' => 'ASC',
    'DESC' => 'ASC',
    'ASC' => 'DESC'
);

$lang_order_1 = array(
    'NO' => $nv_Lang->getModule('filter_lang_asc'),
    'DESC' => $nv_Lang->getModule('filter_lang_asc'),
    'ASC' => $nv_Lang->getModule('filter_lang_desc')
);

$lang_order_2 = array(
    'lang' => strtolower($nv_Lang->getModule('log_lang')),
    'module' => strtolower($nv_Lang->getModule('moduleName')),
    'time' => strtolower($nv_Lang->getModule('log_time'))
);

$order['lang']['order'] = $nv_Request->get_title('order_lang', 'get', 'NO');
$order['module']['order'] = $nv_Request->get_title('order_module', 'get', 'NO');
$order['time']['order'] = $nv_Request->get_title('order_time', 'get', 'NO');

foreach ($order as $key => $check) {
    if (!in_array($check['order'], $check_order)) {
        $order[$key]['order'] = 'NO';
    }

    $order[$key]['data'] = array(
        'key' => strtolower($order[$key]['order']),
        'url' => $base_url . '&amp;order_' . $key . '=' . $opposite_order[$order[$key]['order']],
        'title' => sprintf($nv_Lang->getModule('filter_order_by'), $lang_order_2[$key]) . ' ' . $lang_order_1[$order[$key]['order']]
    );
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_logs');
if (!empty($array_where)) {
    $db->where(implode(' AND ', $array_where));
}

$sth = $db->prepare($db->sql());
if ($check_like) {
    $keyword = '%' . addcslashes($data_search['q'], '_%') . '%';

    $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
    $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
}
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')->limit($per_page)->offset(($page - 1) * $per_page);

if ($order['lang']['order'] != 'NO') {
    $db->order('lang ' . $order['lang']['order']);
} elseif ($order['module']['order'] != 'NO') {
    $db->order('module_name ' . $order['module']['order']);
} elseif ($order['time']['order'] != 'NO') {
    $db->order('log_time ' . $order['time']['order']);
} else {
    $db->order('id DESC');
}
$sql = $db->sql();
$sth = $db->prepare($sql);
if ($check_like) {
    $keyword = '%' . addcslashes($data_search['q'], '_%') . '%';

    $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
    $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
}
$sth->execute();

while ($data_i = $sth->fetch()) {
    if ($data_i['userid'] != 0) {
        if (!in_array($data_i['userid'], $array_userid)) {
            $array_userid[] = $data_i['userid'];
        }
    }

    $data_i['time'] = nv_date('d/m/Y H:i:s', $data_i['log_time']);
    $data[] = $data_i;
}

// Chuyển về trang chính khi điều kiện lọc không có kết quả
if ($page > 1 and empty($data)) {
    nv_redirect_location($base_url);
}

$data_users = [];
$data_users[0] = 'system';
if (!empty($array_userid)) {
    $result_users = $db->query('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $array_userid) . ')');
    while ($data_i = $result_users->fetch()) {
        $data_users[$data_i['userid']] = $data_i['username'];
    }
    unset($data_i, $result_users);
}

$list_lang = nv_siteinfo_getlang();
$list_module = nv_siteinfo_getmodules();
$list_user = nv_siteinfo_getuser();
$logs_del = in_array('logs_del', $allow_func) ? true : false;

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA_SEARCH', $data_search);
$tpl->assign('ARRAY_LANG', $list_lang);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('ARRAY_MODULE', $list_module);
$tpl->assign('SITE_MODS', $site_mods);
$tpl->assign('ADMIN_MODS', $admin_mods);
$tpl->assign('ARRAY_USER', $list_user);
$tpl->assign('URL_CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('CHECKSESS', md5('siteinfo_' . NV_CHECK_SESSION . '_' . $admin_info['userid']));
$tpl->assign('URL_DEL', $base_url . '&' . NV_OP_VARIABLE . '=logs_del');
$tpl->assign('DATA_ORDER', $order);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $num_items, $per_page, $page));
$tpl->assign('ALLOW_DELETE', $logs_del);
$tpl->assign('DATA', $data);
$tpl->assign('DATA_USER', $data_users);

$contents = $tpl->fetch('logs.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
