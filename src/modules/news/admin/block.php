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
$result = $db->query($sql);

$array_block = [];
while ($_row = $result->fetch()) {
    $array_block[(int) $_row['bid']] = $_row['title'];
}
$result->closeCursor();
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
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid = :bid');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $_id_array_exit = [];
    while ($_row = $stmt->fetch()) {
        $_id_array_exit[] = (int) $_row['id'];
    }
    $stmt->closeCursor();

    $id_array = array_map('intval', $nv_Request->get_array('idcheck', 'post'));
    $stmt_insert = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (:bid, :id, 0)');
    foreach ($id_array as $id) {
        if (!in_array($id, $_id_array_exit, true)) {
            try {
                $stmt_insert->bindValue(':bid', $bid, PDO::PARAM_INT);
                $stmt_insert->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt_insert->execute();
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
if ($bid > 0 and defined('NV_IS_SPADMIN') and csrf_check($nv_Request->get_string('order_publtime', 'get'), $csrf_key . '_block_order_' . $bid)) {
    $stmt = $db->prepare('SELECT t1.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid = :bid ORDER BY t1.' . $order_articles_by . ' DESC, t2.weight ASC');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    
    $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight = :weight WHERE bid = :bid AND id = :id');
    $weight = 0;
    while ($_row = $stmt->fetch()) {
        ++$weight;
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $_row['id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
}

// Thay đổi thứ tự bài viết trong nhóm tin
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    if ($bid > 0 and $id > 0 and $new_weight > 0) {
        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid = :bid AND id != :id ORDER BY weight ASC');
        $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight = :weight WHERE bid = :bid AND id = :id');
        $weight = 0;
        while ($_row = $stmt->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
            $stmt_update->bindValue(':id', $_row['id'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $stmt->closeCursor();
        
        $stmt_update->bindValue(':weight', $new_weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt_update->execute();
        
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
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $ids = array_map('intval', $nv_Request->get_array('ids', 'post'));
    $stmt_delete = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid = :bid AND id = :id');
    foreach ($ids as $id) {
        if ($id > 0) {
            $stmt_delete->bindValue(':bid', $bid, PDO::PARAM_INT);
            $stmt_delete->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();
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

    $stmt = $db->prepare('SELECT t1.id, t1.catid, t1.title, t1.alias, t1.publtime, t1.status, t1.hitstotal, t1.hitscm, t2.weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid = :bid AND t1.status=1 ORDER BY t2.weight ASC');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $array_block_rows = $stmt->fetchAll();
    $num = count($array_block_rows);

    $block_rows = [];
    foreach ($array_block_rows as $_row) {
        $block_rows[] = [
            'id' => (int) $_row['id'],
            'title' => $_row['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . ($global_array_cat[$_row['catid']]['alias'] ?? 'Other') . '/' . $_row['alias'] . '-' . $_row['id'] . $global_config['rewrite_exturl'],
            'publtime' => nv_datetime_format($_row['publtime'], 1),
            'status' => $nv_Lang->getModule('status_' . $_row['status']),
            'hitstotal' => nv_number_format($_row['hitstotal']),
            'hitscm' => nv_number_format($_row['hitscm']),
            'weight' => (int) $_row['weight']
        ];
    }

    $tpl->assign('BLOCK_ROWS', $block_rows);
    $tpl->assign('NUM_ROWS', $num);
    $tpl->assign('IS_SPADMIN', defined('NV_IS_SPADMIN'));
    $tpl->assign('ORDER_PUBLTIME_KEY', defined('NV_IS_SPADMIN') ? csrf_create($csrf_key . '_block_order_' . $bid) : '');
} else {
    $page_title = $nv_Lang->getModule('addtoblock');
    $id_array = array_map('intval', explode(',', $listid));

    $sql_rows = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status = 1 AND id IN (' . implode(',', $id_array) . ') ORDER BY ' . $order_articles_by . ' DESC';
    $result = $db->query($sql_rows);

    $news_rows = [];
    while ($_row = $result->fetch()) {
        $news_rows[] = [
            'id' => (int) $_row['id'],
            'title' => $_row['title'],
            'checked' => in_array((int) $_row['id'], $id_array, true)
        ];
    }
    $result->closeCursor();

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
