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

$page_title = $lang_module['block'];

$sql = 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db_slave->query($sql);

$array_block = [];
while (list($bid_i, $title_i) = $result->fetch(3)) {
    $bid_i = (int) $bid_i;
    $array_block[$bid_i] = $title_i;
}
if (empty($array_block)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blockcat');
}

$cookie_bid = $nv_Request->get_int('int_bid', 'cookie', 0);
if (empty($cookie_bid) or !isset($array_block[$cookie_bid])) {
    $cookie_bid = 0;
}

$bid = $nv_Request->get_int('bid', 'get,post', $cookie_bid);
if (!in_array($bid, array_keys($array_block), true)) {
    $bid_array_id = array_keys($array_block);
    $bid = $bid_array_id[0];
}

if ($cookie_bid != $bid) {
    $nv_Request->set_Cookie('int_bid', $bid, NV_LIVE_COOKIE_TIME);
}
$page_title = $array_block[$bid];

if ($nv_Request->isset_request('checkss,idcheck', 'post') and $nv_Request->get_string('checkss', 'post') == NV_CHECK_SESSION) {
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid;
    $result = $db_slave->query($sql);
    $_id_array_exit = [];
    while (list($_id) = $result->fetch(3)) {
        $_id_array_exit[] = (int) $_id;
    }

    $id_array = array_map('intval', $nv_Request->get_array('idcheck', 'post'));
    foreach ($id_array as $id) {
        if (!in_array($id, $_id_array_exit, true)) {
            try {
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid . ', ' . $id . ', 0)');
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }
    nv_news_fix_block($bid);
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
}

if ($bid > 0 and defined('NV_IS_SPADMIN') and $nv_Request->get_string('order_publtime', 'get') == md5($bid . NV_CHECK_SESSION)) {
    $_result = $db->query('SELECT t1.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' ORDER BY t1.' . $order_articles_by . ' DESC, t2.weight ASC');
    $weight = 0;
    while ($_row = $_result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $_row['id'];
        $db->query($sql);
    }
    $result->closeCursor();
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
}

$select_options = [];
foreach ($array_block as $xbid => $blockname) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;bid=' . $xbid] = $blockname;
}

$xtpl = new XTemplate('block.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$listid = $nv_Request->get_string('listid', 'get', '');
if ($listid == '' and $bid) {
    $xtpl->assign('BLOCK_LIST', nv_show_block_list($bid));
} else {
    $page_title = $lang_module['addtoblock'];
    $id_array = array_map('intval', explode(',', $listid));

    $db_slave->sqlreset()
        ->select('id, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->order($order_articles_by . ' DESC')
        ->where('status=1 AND id IN (' . implode(',', $id_array) . ')');

    $result = $db_slave->query($db_slave->sql());

    while (list($id, $title) = $result->fetch(3)) {
        $xtpl->assign('ROW', [
            'checked' => in_array((int) $id, $id_array, true) ? ' checked="checked"' : '',
            'title' => $title,
            'id' => $id
        ]);

        $xtpl->parse('main.news.loop');
    }

    foreach ($array_block as $xbid => $blockname) {
        $xtpl->assign('BID', [
            'key' => $xbid,
            'title' => $blockname,
            'selected' => $xbid == $bid ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.news.bid');
    }

    $xtpl->parse('main.news');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'groups';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
