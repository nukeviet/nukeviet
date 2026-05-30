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

$page_title = $nv_Lang->getModule('banners_list');

// Load plans
$stmt = $db->prepare('SELECT id, title, blang FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC');
$stmt->execute();

$array_plans = [];
$plans = [];
while ($row = $stmt->fetch()) {
    $blang_name = !empty($row['blang']) ? ($language_array[$row['blang']]['name'] ?? $row['blang']) : $nv_Lang->getModule('blang_all');
    $row['blang_name'] = $blang_name;
    $array_plans[] = $row;
    $plans[$row['id']] = $row['title'] . ' (' . $blang_name . ')';
}
$stmt->closeCursor();

// Search params
$array_search = [
    'keyword' => $nv_Request->get_title('q', 'get', ''),
    'pid'     => $nv_Request->get_int('pid', 'get', 0),
    'act'     => $nv_Request->get_int('act', 'get', -1),
    'page'    => $nv_Request->get_page('page', 'get', 1),
];

if (!in_array($array_search['act'], [0, 1, 2, 3, 4], true)) {
    $array_search['act'] = -1;
}

$array_search['is_filtered'] = (
    $array_search['act'] >= 0
    || !empty($array_search['keyword'])
    || ($array_search['pid'] > 0 && isset($plans[$array_search['pid']]))
);

// WHERE chung dùng cho cả đếm lẫn list
$where = [];
$params = [];
if (!empty($array_search['keyword'])) {
    $where[] = '(title LIKE :keyword OR file_alt LIKE :keyword OR click_url LIKE :keyword OR bannerhtml LIKE :keyword)';
    $params[':keyword'] = '%' . $array_search['keyword'] . '%';
}
if ($array_search['pid'] > 0 && isset($plans[$array_search['pid']])) {
    $where[] = 'pid = :pid';
    $params[':pid'] = $array_search['pid'];
}

// Màu và nhãn ngắn theo trạng thái
$act_colors = [
    0 => 'secondary',
    1 => 'success',
    2 => 'warning',
    3 => 'danger',
    4 => 'info',
];

// Đếm số quảng cáo theo từng trạng thái + build status cards
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($array_search['keyword'])) {
    $base_url .= '&amp;q=' . rawurlencode($array_search['keyword']);
}
if ($array_search['pid'] > 0 && isset($plans[$array_search['pid']])) {
    $base_url .= '&amp;pid=' . $array_search['pid'];
}

$status_cards = [];
foreach ([0, 1, 2, 3, 4] as $s) {
    $where_card = $where;
    $where_card[] = 'act = :act' . $s;
    $params_card = $params;
    $params_card[':act' . $s] = $s;

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE ' . implode(' AND ', $where_card));
    foreach ($params_card as $p => $v) {
        $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $count = (int) $stmt->fetchColumn();
    $stmt->closeCursor();

    $status_cards[] = [
        'act'   => $s,
        'count' => $count,
        'title' => $nv_Lang->getModule('banner_act_' . $s),
        'url'   => $base_url . '&amp;act=' . $s,
        'color' => $act_colors[$s],
    ];
}

// Phân trang
$per_page = 20;

$where_list = $where;
$params_list = $params;
if ($array_search['act'] >= 0) {
    $where_list[] = 'act = :act';
    $params_list[':act'] = $array_search['act'];
}

$where_list_sql = !empty($where_list) ? 'WHERE ' . implode(' AND ', $where_list) : '';
$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows ' . $where_list_sql);
foreach ($params_list as $p => $v) {
    $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$num_items = (int) $stmt->fetchColumn();
$stmt->closeCursor();

$page_base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if ($array_search['act'] >= 0) {
    $page_base_url .= '&amp;act=' . $array_search['act'];
}
if (!empty($array_search['keyword'])) {
    $page_base_url .= '&amp;q=' . rawurlencode($array_search['keyword']);
}
if ($array_search['pid'] > 0 && isset($plans[$array_search['pid']])) {
    $page_base_url .= '&amp;pid=' . $array_search['pid'];
}

$pagination = nv_generate_page($page_base_url, $num_items, $per_page, $array_search['page']);

$limit  = $per_page;
$offset = ($array_search['page'] - 1) * $per_page;

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows ' . $where_list_sql . ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
$stmt = $db->prepare($sql);
foreach ($params_list as $p => $v) {
    $stmt->bindValue($p, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$stmt_update_act = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act = 2 WHERE id = :id');
$stmt_activate_act = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act = 1 WHERE id = :id');

$array = [];
$array_userids = $array_users = [];
$had_expiry_update = false;

while ($row = $stmt->fetch()) {
    if ($row['exp_time'] != 0 && $row['exp_time'] <= NV_CURRENTTIME) {
        $stmt_update_act->bindValue(':id', $row['id'], PDO::PARAM_INT);
        $stmt_update_act->execute();
        $row['act'] = 2;
        $had_expiry_update = true;
    } elseif ($row['act'] == 0 && $row['publ_time'] <= NV_CURRENTTIME) {
        $stmt_activate_act->bindValue(':id', $row['id'], PDO::PARAM_INT);
        $stmt_activate_act->execute();
        $row['act'] = 1;
        $had_expiry_update = true;
    }

    $item = [
        'id'        => $row['id'],
        'title'     => $row['title'],
        'view_url'  => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info-banner&amp;id=' . $row['id'],
        'pid'       => $row['pid'],
        'pid_title' => $plans[$row['pid']] ?? '',
        'pid_url'   => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info-plan&amp;id=' . $row['pid'],
        'clid'      => $row['clid'],
        'publ_date' => !empty($row['publ_time']) ? nv_datetime_format($row['publ_time']) : '',
        'exp_date'  => !empty($row['exp_time']) ? nv_datetime_format($row['exp_time']) : $nv_Lang->getModule('unlimited'),
        'act'       => (int) $row['act'],
        'act_label' => $nv_Lang->getModule('banner_act_' . $row['act']),
        'act_color' => $act_colors[(int) $row['act']] ?? 'secondary',
        'edit_url'  => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=banner-content&amp;id=' . $row['id'],
        'user'      => null,
    ];

    $array[$row['id']] = $item;

    if (!empty($row['clid'])) {
        $array_userids[$row['clid']] = $row['clid'];
    }
}
$stmt->closeCursor();

if ($had_expiry_update) {
    nv_CreateXML_bannerPlan();
}

// Xác định người đăng
if (!empty($array_userids)) {
    $in = implode(',', array_fill(0, count($array_userids), '?'));
    $stmt_user = $db->prepare('SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $in . ')');
    foreach (array_values($array_userids) as $k => $uid) {
        $stmt_user->bindValue(($k + 1), (int) $uid, PDO::PARAM_INT);
    }
    $stmt_user->execute();
    while ($row = $stmt_user->fetch()) {
        $array_users[$row['userid']] = $row;
    }
    $stmt_user->closeCursor();
}

$is_allowed_viewuser = nv_user_in_groups($global_config['whoviewuser']);
foreach ($array as &$item) {
    if (!empty($item['clid']) && isset($array_users[$item['clid']])) {
        $user = $array_users[$item['clid']];
        $user['link'] = $is_allowed_viewuser
            ? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=memberlist/' . change_alias($user['username']) . '-' . $user['md5username']
            : '';
        $item['user'] = $user;
    }
}
unset($item);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ARRAY_SEARCH', $array_search);
$tpl->assign('ARRAY_PLANS', $array_plans);
$tpl->assign('STATUS_CARDS', $status_cards);
$tpl->assign('ARRAY', $array);
$tpl->assign('PAGINATION', $pagination);

$content = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
