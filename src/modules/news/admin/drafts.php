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

// Xóa bỏ 1 hoặc nhiều
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id=" . $id . " AND type=1";
        if ($NV_IS_ADMIN_FULL_MODULE) {
            $sql .= " AND admin_id=" . $admin_info['admin_id'];
        }
        $array = $db->query($sql)->fetch();
        if (!empty($array)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_DRAFT', $id, $admin_info['admin_id']);

            // Xóa
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tmp WHERE id=" . $id;
            $db->query($sql);
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

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_tmp');

$search_count = 0;
$where = [];
if (!$NV_IS_ADMIN_FULL_MODULE) {
    $where[] = 'admin_id=' . $admin_info['admin_id'];
}
$where[] = 'type=1';
if (!empty($array_search['from'])) {
    $base_url .= '&amp;f=' . nv_u2d_get($array_search['from']);
    $where[] = "time_late>=" . $array_search['from'];
    $search_count++;
}
if (!empty($array_search['to'])) {
    $base_url .= '&amp;t=' . nv_u2d_get($array_search['to']);
    $where[] = "time_late<=" . $array_search['to'];
    $search_count++;
}

$db->where(implode(' AND ', $where));
$num_items = $db->query($db->sql())->fetchColumn();
$db->select('*')->order('time_late DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());

$array = $new_ids = [];
while ($row = $result->fetch()) {
    if (!empty($row['new_id'])) {
        $new_ids[$row['new_id']] = $row['new_id'];
    }
    $row['allowed_edit'] = true;
    $row['my_draft'] = $admin_info['admin_id'] == $row['admin_id'];

    $row['properties'] = json_decode($row['properties'], true);
    if (!is_array($row['properties'])) {
        $row['properties'] = [];
    }
    $row['title'] = $row['properties']['title'] ?? '';
    unset($row['properties']);

    $array[$row['id']] = $row;
}
$result->closeCursor();

// Trong số các bài sửa tạm này tìm tiêu đề
$new_titles = [];
if (!empty($new_ids)) {
    $db->sqlreset()->select('id, title, listcatid')->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('id IN (' . implode(',', $new_ids) . ')');
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $new_titles[$row['id']] = [
            'title' => $row['title'],
            'catids' => array_filter(explode(',', $row['listcatid']))
        ];
    }

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

$contents = $tpl->fetch('drafts.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
