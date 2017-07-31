<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!$pro_config['active_warehouse']) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
    die();
}

$wid = $nv_Request->get_int('wid', 'get', 0);
$array_search = array();
$array_warehouse = array();

if ($wid > 0) {
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_warehouse t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.user_id=t2.userid WHERE t1.wid=' . $wid);
    if ($result->rowCount() == 0) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=warehouse');
        die();
    }
    $array_warehouse = $result->fetch();
    $page_title = $array_warehouse['title'];

    $array_warehouse['logs'] = array();
    $result = $db->query('SELECT t1.*, t2.' . NV_LANG_DATA . '_title title FROM ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_rows t2 ON t1.pro_id=t2.id WHERE t1.wid=' . $array_warehouse['wid']);
    while ($row = $result->fetch()) {
        $row['group_info'] = array();
        $result1 = $db->query('SELECT listgroup, quantity, price, money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs_group WHERE logid=' . $row['logid']);
        while (list($listgroup, $quantity, $price, $money_unit) = $result1->fetch(3)) {
            $row['group_info'][] = array( 'listgroup' => $listgroup, 'quantity' => $quantity, 'price' => $price, 'money_unit' => $money_unit ) ;
        }
        $array_warehouse['logs'][$row['logid']] = $row;
    }
} else {
    $page_title = $lang_module['warehouse_logs'];
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $array_search = array();
    $array_search['keywords'] = $nv_Request->get_title('keywords', 'get', '');
    $array_search['from'] = $nv_Request->get_string('from', 'get', '');
    $array_search['to'] = $nv_Request->get_string('to', 'get', '');

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_' . $module_data . '_warehouse t1')
        ->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.user_id=t2.userid');

    $where = '';
    if (!empty($array_search['keywords'])) {
        $where .= ' AND title LIKE :q_title OR note LIKE :q_note OR username LIKE :q_username';
    }

    if (!empty($array_search['from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['from'], $m)) {
        $array_search['from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        $where .= ' AND addtime >= ' . $array_search['from'] . '';
    } else {
        $array_search['from'] = '';
    }

    if (!empty($array_search['to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['to'], $m)) {
        $array_search['to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        $where .= ' AND addtime <= ' . $array_search['to'] . '';
    } else {
        $array_search['to'] = '';
    }

    if (! empty($where)) {
        $db->where('1=1' . $where);
    }

    $sth = $db->prepare($db->sql());

    if (!empty($array_search['keywords'])) {
        $sth->bindValue(':q_title', '%' . $array_search['keywords'] . '%');
        $sth->bindValue(':q_note', '%' . $array_search['keywords'] . '%');
        $sth->bindValue(':q_username', '%' . $array_search['keywords'] . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('t1.*, t2.first_name, t2.last_name, t2.username')->order('t1.addtime DESC')->limit($per_page)->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    if (!empty($array_search['keywords'])) {
        $sth->bindValue(':q_title', '%' . $array_search['keywords'] . '%');
        $sth->bindValue(':q_note', '%' . $array_search['keywords'] . '%');
        $sth->bindValue(':q_username', '%' . $array_search['keywords'] . '%');
    }
    $sth->execute();

    $array_search['from'] = !empty($array_search['from']) ? nv_date('d/m/Y', $array_search['from']) : '';
    $array_search['to'] = !empty($array_search['to']) ? nv_date('d/m/Y', $array_search['to']) : '';
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('MONEY_UNIT', $money_config[$pro_config['money_unit']]['symbol']);

if ($wid > 0) {
    $array_warehouse['addtime'] = nv_date('H:i d/m/Y', $array_warehouse['addtime']);
    $array_warehouse['full_name'] = !empty($array_warehouse['last_name']) ? $array_warehouse['first_name'] . ' ' . $array_warehouse['last_name'] : $array_warehouse['username'];

    $xtpl->assign('DATA', $array_warehouse);

    if (!empty($array_warehouse['logs'])) {
        $i=1;
        foreach ($array_warehouse['logs'] as $logs) {
            $have_group = 0;
            $logs['no'] = $i;
            $logs['price'] = nv_number_format($logs['price']);
            $xtpl->assign('LOGS', $logs);

            if (!empty($logs['group_info'])) {
                foreach ($logs['group_info'] as $logs_info) {
                    if ($logs_info['listgroup']) {
                        $have_group = 1;
                        $logs_info['price'] = nv_number_format($logs_info['price']);
                        $logs_info['listgroup'] = explode(',', $logs_info['listgroup']);
                        foreach ($logs_info['listgroup'] as $groupid) {
                            $group = $global_array_group[$groupid];
                            $group['parent_title'] = $global_array_group[$group['parentid']]['title'];
                            $xtpl->assign('GROUP', $group);
                            $xtpl->parse('main.view.loop.group.loop.group_logs');
                        }
                        $xtpl->assign('G_LOGS', $logs_info);
                        $xtpl->parse('main.view.loop.group.loop');
                    }
                }
            }

            if (!$have_group) {
                $xtpl->parse('main.view.loop.product_number');
            } else {
                $xtpl->parse('main.view.loop.group');
            }

            $xtpl->parse('main.view.loop');
            $i++;
        }
    }
    if (!empty($array_warehouse['note'])) {
        $xtpl->parse('main.view.note');
    }
    $xtpl->parse('main.view');
} else {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    if (!empty($array_search['keywords'])) {
        $base_url .= '&keywords=' . $array_search['keywords'];
    }
    if (!empty($array_search['from'])) {
        $base_url .= '&from=' . $array_search['from'];
    }
    if (!empty($array_search['to'])) {
        $base_url .= '&to=' . $array_search['to'];
    }

    while ($view = $sth->fetch()) {
        $view['full_name'] = !empty($view['last_name']) ? $view['first_name'] . ' ' . $view['last_name'] : $view['username'];
        $view['addtime'] = nv_date('H:i d/m/Y', $view['addtime']);
        $result = $db->query('SELECT price, money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs WHERE wid=' . $view['wid']);
        $view['total_product'] = $result->rowCount();
        $view['total_price'] = 0;
        while ($row = $result->fetch()) {
            $view['total_price'] += nv_currency_conversion($row['price'], $row['money_unit'], $pro_config['money_unit']);
        }
        $view['total_price'] = nv_number_format($view['total_price']);
        $view['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;wid=' . $view['wid'];
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.list.loop');
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.list.generate_page');
    }
    $xtpl->parse('main.list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
