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
$sql = 'SELECT id, title, blang FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);

$array_plans = [];
$plans = [];
while ($row = $result->fetch()) {
    $blang_name = !empty($row['blang']) ? ($language_array[$row['blang']]['name'] ?? $row['blang']) : $nv_Lang->getModule('blang_all');
    $row['blang_name'] = $blang_name;
    $array_plans[] = $row;
    $plans[$row['id']] = $row['title'] . ' (' . $blang_name . ')';
}

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
if (!empty($array_search['keyword'])) {
    $kw = $db->dblikeescape($array_search['keyword']);
    $where[] = "(title LIKE '%" . $kw . "%' OR file_alt LIKE '%" . $kw . "%' OR click_url LIKE '%" . $kw . "%' OR bannerhtml LIKE '%" . $kw . "%')";
}
if ($array_search['pid'] > 0 && isset($plans[$array_search['pid']])) {
    $where[] = 'pid=' . $array_search['pid'];
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
    $where_card[] = 'act=' . $s;
    $status_cards[] = [
        'act'   => $s,
        'count' => (int) $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE ' . implode(' AND ', $where_card))->fetchColumn(),
        'title' => $nv_Lang->getModule('banner_act_' . $s),
        'url'   => $base_url . '&amp;act=' . $s,
        'color' => $act_colors[$s],
    ];
}

// Phân trang
$per_page = 20;

$where_list = $where;
if ($array_search['act'] >= 0) {
    $where_list[] = 'act=' . $array_search['act'];
}

$where_list_sql = !empty($where_list) ? 'WHERE ' . implode(' AND ', $where_list) : '';
$num_items = (int) $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows ' . $where_list_sql)->fetchColumn();

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

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows ' . $where_list_sql . ' ORDER BY id DESC LIMIT ' . $per_page . ' OFFSET ' . ($array_search['page'] - 1) * $per_page;
$result = $db->query($sql);

$array = [];
$array_userids = $array_users = [];

while ($row = $result->fetch()) {
    if ($row['exp_time'] != 0 && $row['exp_time'] <= NV_CURRENTTIME) {
        $db->exec('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act=2 WHERE id=' . $row['id']);
        $row['act'] = 2;
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

// Xác định người đăng
if (!empty($array_userids)) {
    $sql = 'SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN(' . implode(',', $array_userids) . ')';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_users[$row['userid']] = $row;
    }
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
