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

$page_title = $nv_Lang->getModule('draft_list');
$draft_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_drafts';

// Xóa bỏ 1 hoặc nhiều
if ($nv_Request->isset_request('delete', 'post')) {
    if (!csrf_check($nv_Request->get_title('delete', 'post', ''), $draft_csrf_key)) {
        nv_jsonOutput([
            'success' => 0,
            'text' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id = :id AND type = 1";
    if (!$NV_IS_ADMIN_FULL_MODULE) {
        $sql .= " AND admin_id = :admin_id";
    }
    $stmt_check = $db->prepare($sql);
    $stmt_delete = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id = :id");

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $stmt_check->bindValue(':id', $id, PDO::PARAM_INT);
        if (!$NV_IS_ADMIN_FULL_MODULE) {
            $stmt_check->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
        }
        $stmt_check->execute();
        $exists_id = $stmt_check->fetchColumn();

        if ($exists_id) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_DRAFT', $id, $admin_info['admin_id']);

            // Xóa
            $stmt_delete->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();
        }
    }
    nv_jsonOutput([
        'success' => 1,
        'text' => ''
    ]);
}

// Xác định các chuyên mục được sửa bài
$array_cat_edit = [];
foreach ($global_array_cat as $catid_i => $array_value) {
    $check_cat_edit = false;
    if (defined('NV_IS_ADMIN_MODULE')) {
        $check_cat_edit = true;
    } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
        $_cat_admin_i = $array_cat_admin[$admin_id][$catid_i];
        if ($_cat_admin_i['admin'] == 1) {
            $check_cat_edit = true;
        } elseif ($_cat_admin_i['edit_content'] == 1) {
            $check_cat_edit = true;
        }
    }
    if ($check_cat_edit) {
        $array_cat_edit[] = $catid_i;
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'nformat', 'nv_number_format');
$tpl->registerPlugin('modifier', 'dformat', 'nv_datetime_format');
$tpl->setTemplateDir(get_module_tpl_dir('drafts.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);

$array_search = [];
$array_search['from'] = nv_d2u_get($nv_Request->get_title('f', 'get', ''));
$array_search['to'] = nv_d2u_get($nv_Request->get_title('t', 'get', ''));

$search_count = 0;
$where = [];
$db_binds = [];
if (!$NV_IS_ADMIN_FULL_MODULE) {
    $where[] = 'admin_id = :admin_id';
    $db_binds[':admin_id'] = $admin_info['admin_id'];
}
$where[] = 'type = 1';
if (!empty($array_search['from'])) {
    $base_url .= '&amp;f=' . nv_u2d_get($array_search['from']);
    $where[] = "time_late >= :from";
    $db_binds[':from'] = $array_search['from'];
    $search_count++;
}
if (!empty($array_search['to'])) {
    $base_url .= '&amp;t=' . nv_u2d_get($array_search['to']);
    $where[] = "time_late <= :to";
    $db_binds[':to'] = $array_search['to'];
    $search_count++;
}

$sql_condition = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';
$sql_count = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp' . $sql_condition;

$stmt = $db->prepare($sql_count);
foreach ($db_binds as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_INT);
}
$stmt->execute();
$num_items = $stmt->fetchColumn();

$sql_data = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp' . $sql_condition . ' ORDER BY time_late DESC LIMIT :limit OFFSET :offset';
$stmt = $db->prepare($sql_data);
foreach ($db_binds as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();

$array = $new_ids = [];
while ($_row = $stmt->fetch()) {
    if (!empty($_row['new_id'])) {
        $new_ids[$_row['new_id']] = $_row['new_id'];
    }
    $_row['allowed_edit'] = true;
    $_row['my_draft'] = $admin_info['admin_id'] == $_row['admin_id'];

    $_row['properties'] = json_decode($_row['properties'], true);
    if (!is_array($_row['properties'])) {
        $_row['properties'] = [];
    }
    $_row['title'] = $_row['properties']['title'] ?? '';
    unset($_row['properties']);

    $array[$_row['id']] = $_row;
}
$stmt->closeCursor();

// Trong số các bài sửa tạm này tìm tiêu đề
$new_titles = [];
if (!empty($new_ids)) {
    $sql_rows = 'SELECT id, title, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $new_ids) . ')';
    $stmt_rows = $db->query($sql_rows);
    while ($_row_rows = $stmt_rows->fetch()) {
        $new_titles[$_row_rows['id']] = [
            'title' => $_row_rows['title'],
            'catids' => array_filter(explode(',', $_row_rows['listcatid']))
        ];
    }
    $stmt_rows->closeCursor();

    foreach ($array as $id => $row) {
        if (isset($new_titles[$row['new_id']])) {
            if (empty($row['title'])) {
                $array[$id]['title'] = $new_titles[$row['new_id']]['title'];
            }
            $array[$id]['allowed_edit'] = count(array_intersect($new_titles[$row['new_id']]['catids'], $array_cat_edit)) > 0;
        }
    }
}

$array_search['from'] = nv_u2d_get($array_search['from']);
$array_search['to'] = nv_u2d_get($array_search['to']);

$tpl->assign('ARRAY', $array);
$tpl->assign('SEARCH_COUNT', $search_count);
$tpl->assign('SEARCH', $array_search);
$tpl->assign('PAGINATION', nv_generate_page($base_url, $num_items, $per_page, $page));
$tpl->assign('DRAFTS_CHECKSS', csrf_create($draft_csrf_key));

$contents = $tpl->fetch('drafts.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
