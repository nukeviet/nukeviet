<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    exit('Stop!!!');
}

// Xóa mẫu email
if ($nv_Request->isset_request('deltpl', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit($nv_Lang->getGlobal('error_checkss'));
    }

    $emailid = $nv_Request->get_int('emailid', 'post', 0);

    $stmt = $db->prepare('SELECT emailid, is_system, module_name FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = :emailid');
    $stmt->bindValue(':emailid', $emailid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($row) or $row['is_system'] or $row['module_name']) {
        exit('NO_' . $emailid);
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = :emailid');
    $stmt->bindValue(':emailid', $emailid, PDO::PARAM_INT);

    if ($stmt->execute() and $stmt->rowCount()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete tpl', 'ID: ' . $emailid, $admin_info['userid']);
        nv_apply_hook('', 'emailtemplates_after_delete', [$emailid]);
        $nv_Cache->delMod($module_name);
    } else {
        exit('NO_' . $emailid);
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $emailid;
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($global_array_cat)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=categories';
    nv_redirect_location($url);
}

$page_title = $nv_Lang->getModule('tpl_list');

$per_page = 20;
$page = $nv_Request->get_absint('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Phần tìm kiếm
$is_search = 0;
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['from'] = nv_d2u_get($nv_Request->get_title('f', 'get', ''));
$array_search['to'] = nv_d2u_get($nv_Request->get_title('t', 'get', ''), 23, 59, 59);
$array_search['catid'] = $nv_Request->get_absint('c', 'get', 0);
$array_search['module_name'] = $nv_Request->get_title('m', 'get', '');
$array_search['lang'] = $nv_Request->get_title('l', 'get', '');

// Xử lý dữ liệu tìm kiếm
if (!isset($global_array_cat[$array_search['catid']])) {
    $array_search['catid'] = 0;
}

$bind_params = [];
$where = [];
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dblikekey = $db->dblikeescape($array_search['q'], true);

    $where_or = [];
    foreach ($global_config['setup_langs'] as $lang) {
        $where_or[] = $lang . "_title LIKE :q_" . $lang . "_title";
        $where_or[] = $lang . "_subject LIKE :q_" . $lang . "_subject";

        $bind_params[':q_' . $lang . '_title'] = '%' . $dblikekey . '%';
        $bind_params[':q_' . $lang . '_subject'] = '%' . $dblikekey . '%';
    }
    $where[] = "(" . implode(' OR ', $where_or) . ")";
    $is_search++;
}
if (!empty($array_search['from'])) {
    $base_url .= '&amp;f=' . nv_u2d_get($array_search['from']);
    $where[] = "time_add >= :from";
    $bind_params[':from'] = $array_search['from'];
    $is_search++;
}
if (!empty($array_search['to'])) {
    $base_url .= '&amp;t=' . nv_u2d_get($array_search['to']);
    $where[] = "time_add <= :to";
    $bind_params[':to'] = $array_search['to'];
    $is_search++;
}
if (!empty($array_search['catid'])) {
    $base_url .= '&amp;c=' . $array_search['catid'];
    $where[] = "catid = :catid";
    $bind_params[':catid'] = $array_search['catid'];
    $is_search++;
}
if (!empty($array_search['module_name'])) {
    $base_url .= '&amp;m=' . urlencode($array_search['module_name']);
    $where[] = "module_name = :module_name";
    $bind_params[':module_name'] = $array_search['module_name'];
    $is_search++;
    $per_page = 200;
}
if (!empty($array_search['lang'])) {
    $base_url .= '&amp;l=' . urlencode($array_search['lang']);
    $where[] = "lang = :lang";
    $bind_params[':lang'] = $array_search['lang'];
    $is_search++;
}

// Phần sắp xếp
$array_order = [];
$array_order['field'] = $nv_Request->get_title('of', 'get', '');
$array_order['value'] = $nv_Request->get_title('ov', 'get', '');
$base_url_order = $base_url;
if ($page > 1) {
    $base_url_order .= '&amp;page=' . $page;
}

// Định nghĩa các field và các value được phép sắp xếp
$order_fields = ['title', 'time_add', 'time_update'];
$order_values = ['asc', 'desc'];

if (!in_array($array_order['field'], $order_fields)) {
    $array_order['field'] = '';
}
if (!in_array($array_order['value'], $order_values)) {
    $array_order['value'] = '';
}

$where_sql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . $where_sql);
foreach ($bind_params as $pk => $pv) {
    if (is_int($pv)) {
        $stmt->bindValue($pk, $pv, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($pk, $pv, PDO::PARAM_STR);
    }
}
$stmt->execute();
$num_items = $stmt->fetchColumn();

if (!empty($array_order['field']) and !empty($array_order['value'])) {
    $order = $array_order['field'] . ' ' . $array_order['value'];
} else {
    $order = 'emailid DESC';
}

$stmt = $db->prepare('SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . $where_sql . ' ORDER BY ' . $order . ' LIMIT :limit OFFSET :offset');
foreach ($bind_params as $pk => $pv) {
    if (is_int($pv)) {
        $stmt->bindValue($pk, $pv, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($pk, $pv, PDO::PARAM_STR);
    }
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();

$array = [];
while ($row = $stmt->fetch()) {
    $row['title'] = $row[NV_LANG_DATA . '_title'];
    $array[$row['emailid']] = $row;
}
$stmt->closeCursor();

if (empty($array) and $page = 1 and empty($is_search)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=contents';
    nv_redirect_location($url);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'date', 'nv_datetime_format');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('OP', $op);
$tpl->assign('LANGS', $language_array);
$tpl->assign('MODULES', $all_modules);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('DATE_FORMAT', nv_region_config('jsdate_get'));

$array_search['from'] = nv_u2d_get($array_search['from']);
$array_search['to'] = nv_u2d_get($array_search['to']);

$tpl->assign('DATA', $array);
$tpl->assign('CATS', $global_array_cat);
$tpl->assign('SEARCH', $array_search);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $num_items, $per_page, $page));

// Xuất các phần sắp xếp
foreach ($order_fields as $field) {
    $url = $base_url_order;
    if ($array_order['field'] == $field) {
        if (empty($array_order['value'])) {
            $url .= '&amp;of=' . $field . '&amp;ov=asc';
            $icon = '<i class="fa fa-sort" aria-hidden="true"></i>';
        } elseif ($array_order['value'] == 'asc') {
            $url .= '&amp;of=' . $field . '&amp;ov=desc';
            $icon = '<i class="fa fa-sort-asc" aria-hidden="true"></i>';
        } else {
            $icon = '<i class="fa fa-sort-desc" aria-hidden="true"></i>';
        }
    } else {
        $url .= '&amp;of=' . $field . '&amp;ov=asc';
        $icon = '<i class="fa fa-sort" aria-hidden="true"></i>';
    }

    $tpl->assign(strtoupper('URL_ORDER_' . $field), $url);
    $tpl->assign(strtoupper('ICON_ORDER_' . $field), $icon);
}

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
