<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-10-2010 14:43
 */

if (! defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

// Eg: $id = nv_insert_logs('lang','module name','name key','note',1, 'link acess');

// Call jquery datepicker
$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery-ui/jquery-ui.min.css\" rel=\"stylesheet\" />\n";

$my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery-ui/jquery-ui.min.js\"></script>\n";
$my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

$page_title = $lang_module['logs_title'];

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;
$data = array();
$array_userid = array();
$disabled = ' disabled="disabled"';

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

// Search data
$data_search = array(
    'q' => $lang_module['filter_enterkey'],
    'from' => '',
    'to' => '',
    'lang' => '',
    'module' => '',
    'user' => ''
);

$array_where = array();

$check_like = false;
if ($nv_Request->isset_request('filter', 'get') and $nv_Request->isset_request('checksess', 'get')) {
    $checksess = $nv_Request->get_title('checksess', 'get', '');

    if ($checksess != md5('siteinfo_' . NV_CHECK_SESSION . '_' . $admin_info['userid'])) {
        nv_insert_logs(NV_LANG_DATA, $module_name, sprintf($lang_module['filter_check_log'], $op), $admin_info['username'] . ' - ' . $admin_info['userid'], 0);

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $data_search = array(
        'q' => $nv_Request->get_title('q', 'get', ''),
        'from' => $nv_Request->get_title('from', 'get', ''),
        'to' => $nv_Request->get_title('to', 'get', ''),
        'lang' => $nv_Request->get_title('lang', 'get', ''),
        'module' => $nv_Request->get_title('module', 'get', ''),
        'user' => $nv_Request->get_title('user', 'get', '')
    );

    $base_url .= '&amp;filter=1&amp;checksess=' . $checksess;
    $disabled = '';

    if (! empty($data_search['q']) and $data_search['q'] != $lang_module['filter_enterkey']) {
        $base_url .= '&amp;q=' . $data_search['q'];
        $array_where[] = "( name_key LIKE :keyword1 OR note_action LIKE :keyword2 )";
        $check_like = true;
    }

    if (! empty($data_search['from'])) {
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $data_search['from'], $match)) {
            $from = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
            $array_where[] = 'log_time >= ' . $from;
            $base_url .= '&amp;from=' . $data_search['from'];
        }
    }

    if (! empty($data_search['to'])) {
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $data_search['to'], $match)) {
            $to = mktime(23, 59, 59, $match[2], $match[1], $match[3]);
            $array_where[] = 'log_time <= ' . $to;
            $base_url .= '&amp;to=' . $data_search['to'];
        }
    }

    if (! empty($data_search['lang'])) {
        if (in_array($data_search['lang'], array_keys($language_array))) {
            $array_where[] = 'lang=' . $db->quote($data_search['lang']);
            $base_url .= '&amp;lang=' . $data_search['lang'];
        }
    }

    if (! empty($data_search['module'])) {
        $array_where[] = 'module_name=' . $db->quote($data_search['module']);
        $base_url .= '&amp;module=' . $data_search['module'];
    }

    if (! empty($data_search['user'])) {
        $user_tmp = ($data_search['user'] == 'system') ? 0 : ( int )$data_search['user'];

        $array_where[] = 'userid=' . $user_tmp;
        $base_url .= '&amp;user=' . $data_search['user'];
    }
}

// Order data
$order = array();
$check_order = array( 'ASC', 'DESC', 'NO' );
$opposite_order = array(
    'NO' => 'ASC',
    'DESC' => 'ASC',
    'ASC' => 'DESC'
);

$lang_order_1 = array(
    'NO' => $lang_module['filter_lang_asc'],
    'DESC' => $lang_module['filter_lang_asc'],
    'ASC' => $lang_module['filter_lang_desc']
);

$lang_order_2 = array(
    'lang' => strtolower($lang_module['log_lang']),
    'module' => strtolower($lang_module['moduleName']),
    'time' => strtolower($lang_module['log_time'])
);

$order['lang']['order'] = $nv_Request->get_title('order_lang', 'get', 'NO');
$order['module']['order'] = $nv_Request->get_title('order_module', 'get', 'NO');
$order['time']['order'] = $nv_Request->get_title('order_time', 'get', 'NO');

foreach ($order as $key => $check) {
    if (! in_array($check['order'], $check_order)) {
        $order[$key]['order'] = 'NO';
    }

    $order[$key]['data'] = array(
        'class' => 'order' . strtolower($order[$key]['order']),
        'url' => $base_url . '&amp;order_' . $key . '=' . $opposite_order[$order[$key]['order']],
        'title' => sprintf($lang_module['filter_order_by'], $lang_order_2[$key]) . ' ' . $lang_order_1[$order[$key]['order']]
    );
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_logs');
if (! empty($array_where)) {
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
        if (! in_array($data_i['userid'], $array_userid)) {
            $array_userid[] = $data_i['userid'];
        }
    }

    $data_i['time'] = nv_date('d/m/Y h:i:s A', $data_i['log_time']);
    $data[] = $data_i;
    unset($data_i);
}

$data_users = array();
$data_users[0] = 'system';
if (! empty($array_userid)) {
    $result_users = $db->query('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $array_userid) . ')');
    while ($data_i = $result_users->fetch()) {
        $data_users[$data_i['userid']] = $data_i['username'];
    }
    unset($data_i, $result_users);
}

$list_lang = nv_siteinfo_getlang();
$array_lang = array();
$array_lang[] = array(
    'key' => '',
    'title' => $lang_module['filter_lang'],
    'selected' => ($data_search['lang'] == '') ? ' selected="selected"' : ''
);

foreach ($list_lang as $lang) {
    $array_lang[] = array(
        'key' => $lang,
        'title' => $language_array[$lang]['name'],
        'selected' => ($data_search['lang'] == $lang) ? ' selected="selected"' : ''
    );
}

$list_module = nv_siteinfo_getmodules();
$array_module = array();
$array_module[] = array(
    'key' => '',
    'title' => $lang_module['filter_module'],
    'selected' => ($data_search['module'] == '') ? ' selected="selected"' : ''
);

foreach ($list_module as $module) {
    $array_module[] = array(
        'key' => $module,
        'title' => isset($site_mods[$module]) ? $site_mods[$module]['custom_title'] : (isset($admin_mods[$module]) ? $admin_mods[$module]['custom_title'] : $module),
        'selected' => ($data_search['module'] == $module) ? ' selected="selected"' : ''
    );
}

$list_user = nv_siteinfo_getuser();
$array_user = array();
$array_user[] = array(
    'key' => '',
    'title' => $lang_module['filter_user'],
    'selected' => ($data_search['user'] == '') ? ' selected="selected"' : ''
);
$array_user[] = array(
    'key' => 'system',
    'title' => $lang_module['filter_system'],
    'selected' => ($data_search['user'] == 'system') ? ' selected="selected"' : ''
);

foreach ($list_user as $user) {
    $array_user[] = array(
        'key' => $user['userid'],
        'title' => $user['username'],
        'selected' => (( int )$data_search['user'] == $user['userid']) ? ' selected="selected"' : ''
    );
}

$logs_del = in_array('logs_del', $allow_func) ? true : false;

$xtpl = new XTemplate('logs.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('checksess', md5('siteinfo_' . NV_CHECK_SESSION . '_' . $admin_info['userid']));
$xtpl->assign('URL_DEL', $base_url . '&' . NV_OP_VARIABLE . '=logs_del');
$xtpl->assign('URL_CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DISABLE', $disabled);
$xtpl->assign('DATA_SEARCH', $data_search);
$xtpl->assign('DATA_ORDER', $order);

foreach ($array_lang as $lang) {
    $xtpl->assign('lang', $lang);
    $xtpl->parse('main.lang');
}

foreach ($array_module as $module) {
    $xtpl->assign('module', $module);
    $xtpl->parse('main.module');
}

foreach ($array_user as $user) {
    $xtpl->assign('user', $user);
    $xtpl->parse('main.user');
}

$a = 0;
foreach ($data as $data_i) {
    if (! empty($data_users[$data_i['userid']])) {
        $data_i['username'] = $data_users[$data_i['userid']];
    } else {
        $data_i['username'] = 'unknown';
    }

    $data_i['custom_title'] = isset($site_mods[$data_i['module_name']]) ? $site_mods[$data_i['module_name']]['custom_title'] : (isset($admin_mods[$data_i['module_name']]) ? $admin_mods[$data_i['module_name']]['custom_title'] : $data_i['module_name']);

    $xtpl->assign('DATA', $data_i);
    if ($logs_del) {
        $xtpl->assign('DEL_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=log&amp;' . NV_OP_VARIABLE . '=logs_del&amp;id=' . $data_i['id']);
        $xtpl->parse('main.row.delete');
    }
    $xtpl->parse('main.row');
    ++$a;
}

if ($logs_del) {
    $xtpl->parse('main.foot_delete');
    $xtpl->parse('main.head_delete');
}
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
