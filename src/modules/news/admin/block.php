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

/**
 * Xử lý xem tin bài trong nhóm tin
 * Và xử lý thêm bài viết vào nhóm tin.
 */

$page_title = $nv_Lang->getModule('block');

// Lấy danh sách nhóm tin
$sql = 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db_slave->query($sql);

$array_block = [];
while ($_scratch = $result->fetch(3)) {
    [$bid_i, $title_i] = $_scratch;
    unset($_scratch);
    $bid_i = (int) $bid_i;
    $array_block[$bid_i] = $title_i;
}
if (empty($array_block)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blockcat');
}

$cookie_bid = $nv_Request->get_int('ibid', 'cookie', 0);
if (empty($cookie_bid) or !isset($array_block[$cookie_bid])) {
    $cookie_bid = 0;
}

$bid = $nv_Request->get_int('bid', 'get,post', $cookie_bid);
if (!in_array($bid, array_keys($array_block), true)) {
    $bid_array_id = array_keys($array_block);
    $bid = $bid_array_id[0];
}

if ($cookie_bid != $bid) {
    $nv_Request->set_Cookie('ibid', $bid, NV_LIVE_COOKIE_TIME);
}
$page_title = $array_block[$bid];

// Thêm bài viết vào nhóm tin
if ($nv_Request->isset_request('addtoblock', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid;
    $result = $db_slave->query($sql);
    $_id_array_exit = [];
    while ($_scratch = $result->fetch(3)) {
        [$_id] = $_scratch;
        unset($_scratch);
        $_id_array_exit[] = (int) $_id;
    }

    $id_array = array_map('intval', $nv_Request->get_array('idcheck', 'post'));
    foreach ($id_array as $id) {
        if (!in_array($id, $_id_array_exit, true)) {
            try {
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid . ', ' . $id . ', 0)');
            } catch (Throwable $e) {
                trigger_error($e);
            }
        }
    }
    nv_news_fix_block($bid);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid, true)
    ]);
}

// Sắp xếp lại theo thời gian đăng
if ($bid > 0 and defined('NV_IS_SPADMIN') and $nv_Request->get_string('order_publtime', 'get') == md5($bid . NV_CHECK_SESSION)) {
    $_result = $db->query('SELECT t1.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' ORDER BY t1.' . $order_articles_by . ' DESC, t2.weight ASC');
    $weight = 0;
    while ($_row = $_result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $_row['id'];
        $db->query($sql);
    }
    $_result->closeCursor();
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
}

// Thay đổi thứ tự bài viết trong nhóm tin
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    if ($bid > 0 and $id > 0 and $new_weight > 0) {
        $result = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id!=' . $id . ' ORDER BY weight ASC');
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . (int) $row['id']);
        }
        $result->closeCursor();
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $new_weight . ' WHERE bid=' . $bid . ' AND id=' . $id);
        nv_news_fix_block($bid);
        $nv_Cache->delMod($module_name);
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa bài viết khỏi nhóm tin
if ($nv_Request->isset_request('delete_items', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $ids = array_map('intval', $nv_Request->get_array('ids', 'post'));
    foreach ($ids as $id) {
        if ($id > 0) {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $id);
        }
    }
    nv_news_fix_block($bid);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

$select_options = [];
foreach ($array_block as $xbid => $blockname) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;bid=' . $xbid] = $blockname;
}

$listid = $nv_Request->get_string('listid', 'get', '');
$tplFile = ($listid === '' and $bid > 0) ? 'group-articles.tpl' : 'group-add-news.tpl';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir($tplFile));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('BID', $bid);

if ($tplFile === 'group-articles.tpl') {
    $global_array_cat[0] = ['alias' => 'Other'];

    $sql = 'SELECT t1.id, t1.catid, t1.title, t1.alias, t1.publtime, t1.status, t1.hitstotal, t1.hitscm, t2.weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' AND t1.status=1 ORDER BY t2.weight ASC';
    $array_block_rows = $db_slave->query($sql)->fetchAll();
    $num = count($array_block_rows);

    $block_rows = [];
    foreach ($array_block_rows as $row) {
        $block_rows[] = [
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . ($global_array_cat[$row['catid']]['alias'] ?? 'Other') . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
            'publtime' => nv_datetime_format($row['publtime'], 1),
            'status' => $nv_Lang->getModule('status_' . $row['status']),
            'hitstotal' => nv_number_format($row['hitstotal']),
            'hitscm' => nv_number_format($row['hitscm']),
            'weight' => (int) $row['weight']
        ];
    }

    $tpl->assign('BLOCK_ROWS', $block_rows);
    $tpl->assign('NUM_ROWS', $num);
    $tpl->assign('IS_SPADMIN', defined('NV_IS_SPADMIN'));
    $tpl->assign('ORDER_PUBLTIME_KEY', defined('NV_IS_SPADMIN') ? md5($bid . NV_CHECK_SESSION) : '');
} else {
    $page_title = $nv_Lang->getModule('addtoblock');
    $id_array = array_map('intval', explode(',', $listid));

    $db_slave->sqlreset()
        ->select('id, title')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->order($order_articles_by . ' DESC')
        ->where('status=1 AND id IN (' . implode(',', $id_array) . ')');

    $result = $db_slave->query($db_slave->sql());

    $news_rows = [];
    while ($_scratch = $result->fetch(3)) {
        [$id, $title] = $_scratch;
        unset($_scratch);
        $news_rows[] = [
            'id' => (int) $id,
            'title' => $title,
            'checked' => in_array((int) $id, $id_array, true)
        ];
    }

    $block_options = [];
    foreach ($array_block as $xbid => $blockname) {
        $block_options[] = [
            'bid' => $xbid,
            'title' => $blockname,
            'selected' => ($xbid == $bid)
        ];
    }

    $tpl->assign('NEWS_ROWS', $news_rows);
    $tpl->assign('BLOCK_OPTIONS', $block_options);
}

$contents = $tpl->fetch($tplFile);

$set_active_op = 'groups';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
