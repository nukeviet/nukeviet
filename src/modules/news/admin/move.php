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
 * Khi di chuyển bài viết sẽ làm mất hoàn toàn các chuyên mục cũ đó đó nếu bài viết
 * đang bị đình chỉ thì chúng sẽ được trả lại trạng thái trước đó.
 */
$page_title = $nv_Lang->getModule('move');
$action_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_action';

$id_array = [];
$listid = $nv_Request->get_string('listid', 'get,post', '');
$catids = array_unique($nv_Request->get_typed_array('catids', 'post', 'int', []));
$catid = $nv_Request->get_int('catid', 'get,post', 0);

if ($nv_Request->isset_request('idcheck', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $action_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    // Kiểm tra ID các chuyên mục phải hợp lệ
    $array_catid_allowed = [];
    foreach ($global_array_cat as $catid_i => $array_value) {
        if (in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true)) {
            $array_catid_allowed[$catid_i] = $catid_i;
        }
    }
    $catids = array_intersect($catids, $array_catid_allowed);
    $id_array = array_unique($nv_Request->get_typed_array('idcheck', 'post', 'int', []));

    if (empty($id_array)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('topic_nocheck')
        ]);
    }

    if (empty($catids)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('nocatpage')
        ]);
    }

    $listcatid = implode(',', $catids);
    if (empty($catid) or !in_array($catid, array_map('intval', $catids), true)) {
        $catid = $catids[0];
    }

    $result = $db->query('SELECT id, listcatid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $id_array) . ')');

    $stmt_update_row = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid = :catid, listcatid = :listcatid, status = :status WHERE id = :id');
    $stmt_update_row_no_status = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid = :catid, listcatid = :listcatid WHERE id = :id');

    // Chuẩn bị sẵn các statement DELETE cho từng chuyên mục đích (table name động)
    $stmt_insert_map = [];
    $stmt_del_new_map = [];
    foreach ($catids as $catid_i) {
        $catid_i_int = intval($catid_i);
        $stmt_insert_map[$catid_i_int] = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i_int . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
        $stmt_del_new_map[$catid_i_int] = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i_int . ' WHERE id = :id');
    }

    while ($_row = $result->fetch()) {
        // Xóa hết các chuyên mục cũ đi
        $array_catid_old = explode(',', $_row['listcatid']);
        foreach ($array_catid_old as $catid_old_i) {
            $stmt_del_old = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . intval($catid_old_i) . ' WHERE id = :id');
            $stmt_del_old->bindValue(':id', $_row['id'], PDO::PARAM_INT);
            $stmt_del_old->execute();
        }

        // Nếu bài viết đang bị khóa bởi chuyên mục thì sau khi di chuyển sẽ trở lại trạng thái ban đầu
        if ($_row['status'] > $global_code_defined['row_locked_status']) {
            $new_status = $_row['status'] - ($global_code_defined['row_locked_status'] + 1);
            $stmt_update_row->bindValue(':catid', $catid, PDO::PARAM_INT);
            $stmt_update_row->bindValue(':listcatid', $listcatid, PDO::PARAM_STR);
            $stmt_update_row->bindValue(':status', $new_status, PDO::PARAM_INT);
            $stmt_update_row->bindValue(':id', $_row['id'], PDO::PARAM_INT);
            $stmt_update_row->execute();
        } else {
            $stmt_update_row_no_status->bindValue(':catid', $catid, PDO::PARAM_INT);
            $stmt_update_row_no_status->bindValue(':listcatid', $listcatid, PDO::PARAM_STR);
            $stmt_update_row_no_status->bindValue(':id', $_row['id'], PDO::PARAM_INT);
            $stmt_update_row_no_status->execute();
        }

        foreach ($catids as $catid_i) {
            $catid_i_int = intval($catid_i);
            try {
                $stmt_insert_map[$catid_i_int]->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                $stmt_insert_map[$catid_i_int]->execute();
            } catch (Throwable $e) {
                trigger_error($e);
                $stmt_del_new_map[$catid_i_int]->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                $stmt_del_new_map[$catid_i_int]->execute();

                $stmt_insert_map[$catid_i_int]->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                $stmt_insert_map[$catid_i_int]->execute();
            }
        }
    }
    $result->closeCursor();

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('move'), 'ids: ' . implode(', ', $id_array) . ' --> catid: ' . $catid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true)
    ]);
} else {
    $id_array = array_map('intval', explode(',', $listid));
}

if (empty($id_array)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sql_rows = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $id_array) . ') ORDER BY id DESC';
$result = $db->query($sql_rows);

$rows = [];
while ($_row = $result->fetch()) {
    $rows[] = [
        'id' => (int) $_row['id'],
        'title' => $_row['title'],
        'checked' => in_array((int) $_row['id'], $id_array, true)
    ];
}
$result->closeCursor();

$catids_int = array_map('intval', $catids);
$cats = [];
foreach ($global_array_cat as $catid_i => $array_value) {
    if (in_array((int) $array_value['status'], array_map('intval', $global_code_defined['cat_visible_status']), true)) {
        $is_checked = in_array((int) $catid_i, $catids_int, true);
        $cats[] = [
            'catid' => $catid_i,
            'space' => (int) ($array_value['lev']) * 30,
            'title' => $array_value['title'],
            'checked' => $is_checked,
            'catidchecked' => ($catid_i == $catid),
            'show_radio' => count($catids_int) > 1 && $is_checked
        ];
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('move.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($action_csrf_key));
$tpl->assign('ROWS', $rows);
$tpl->assign('CATS', $cats);
$contents = $tpl->fetch('move.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
