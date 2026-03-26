<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$allowed_mods = array_unique(array_merge_recursive(array_keys($admin_mods), array_keys($site_mods)));
$page_title = $nv_Lang->getModule('notification');

if ($admin_info['level'] == 1) {
    /*
     * Quản trị tối cao xem được:
     * - Thông báo cấp dưới với điều kiện logic mode = 0
     * - Thông báo set cho cấp quản trị tối cao với điều kiện:
     * + Không chỉ định người nhận => Toàn bộ quản trị tối cao
     * + Hoặc chỉ định chính người nhận là mình
     */
    $sql_lev_admin = '((admin_view_allowed!=1 AND logic_mode=0) OR (
        admin_view_allowed=1 AND (send_to=\'\' OR FIND_IN_SET(' . (int) $admin_info['admin_id'] . ', send_to))
    ))';
} elseif ($admin_info['level'] == 2) {
    /*
     * Điều hành chung xem được:
     * - Thông báo cấp dưới với điều kiện logic mode = 0
     * - Thông báo set cho cấp điều hành chung với điều kiện:
     * + Không chỉ định người nhận => Toàn bộ điều hành chung
     * + Hoặc chỉ định chính người nhận là mình
     */
    $sql_lev_admin = '(admin_view_allowed!=1 AND (
        (admin_view_allowed!=2 AND logic_mode=0) OR (
            admin_view_allowed=2 AND (send_to=\'\' OR FIND_IN_SET(' . (int) $admin_info['admin_id'] . ', send_to))
        )
    ))';
} else {
    /*
     * Quản lý module xem được:
     * - Thông báo set cho toàn bộ
     * - Hoặc thông báo set cho chính mình
     */
    $sql_lev_admin = '(admin_view_allowed=0 AND (
        send_to=\'\' OR FIND_IN_SET(' . (int) $admin_info['admin_id'] . ', send_to)
    ))';
}

// Đánh dấu đã xem tất cả các thông báo
if ($nv_Request->isset_request('notification_reset', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_htmlOutput('NO');
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, 'READ_ALL_NOTIFICATION', '', $admin_info['userid']);
    $values = array_values($allowed_mods);
    $placeholders = implode(', ', array_map(fn($k) => ':module' . $k, array_keys($values)));

    $sql = 'UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view = 1
    WHERE view = 0 AND (area = 1 OR area = 2) AND module IN (' . $placeholders . ') AND language = :language AND ' . $sql_lev_admin;
    $stmt = $db->prepare($sql);
    foreach ($values as $k => $v) {
        $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    nv_htmlOutput('OK');
}

/**
 * Lấy số thông báo chưa đọc
 */
function get_unread_notification()
{
    global $db, $allowed_mods, $sql_lev_admin;

    $values = array_values($allowed_mods);
    $placeholders = implode(', ', array_map(fn($k) => ':module' . $k, array_keys($values)));

    $sql = 'SELECT COUNT(id) FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language = :language
    AND (area = 1 OR area = 2) AND view = 0 AND module IN (' . $placeholders . ') AND ' . $sql_lev_admin;
    $stmt = $db->prepare($sql);
    foreach ($values as $k => $v) {
        $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    $count = (int) $stmt->fetchColumn();

    return [
        'count' => $count,
        'count_formatted' => number_format($count)
    ];
}

// Lấy tổng số thông báo chưa xem
if ($nv_Request->isset_request('notification_get', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }
    nv_jsonOutput(get_unread_notification());
}

// Xóa một hoặc nhiều thông báo
if ($nv_Request->isset_request('delete', 'post')) {
    $respon = [
        'error' => 1,
        'data' => []
    ];
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput($respon);
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $ids = array_filter(array_unique(array_map('intval', explode(',', $id . ',' . $listid))));

    nv_insert_logs(NV_LANG_DATA, $module_name, 'DELETE_NOTIFICATION', json_encode($ids, NV_JSON_ENCODE), $admin_info['userid']);

    $values = array_values($allowed_mods);
    $placeholders = implode(', ', array_map(fn($k) => ':module' . $k, array_keys($values)));

    $stmt = $db->prepare('DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE id = :id AND module IN (' . $placeholders . ') AND (area = 1 OR area = 2) AND language = :language AND ' . $sql_lev_admin);
    foreach ($values as $k => $v) {
        $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);

    foreach ($ids as $id) {
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $respon['error'] = 0;
        }
    }

    $respon['data'] = get_unread_notification();
    nv_jsonOutput($respon);
}

// Đánh dấu đã đọc/chưa đọc một thông báo
if ($nv_Request->isset_request('toggle', 'post')) {
    $respon = [
        'error' => 1,
        'data' => [],
        'view' => null
    ];
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        $respon['message'] = $nv_Lang->getGlobal('error_checkss');
        nv_jsonOutput($respon);
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $ids = array_filter(array_unique(array_map('intval', explode(',', $id . ',' . $listid))));
    $direct_view = $nv_Request->get_int('direct_view', 'post', -1);
    if ($direct_view == 1 or $direct_view == 0) {
        $view = $direct_view;
    } else {
        $direct_view = -1;
        $view = 'IF(view=0, 1, 0)';
    }

    $values = array_values($allowed_mods);
    $placeholders = implode(', ', array_map(fn($k) => ':module' . $k, array_keys($values)));

    $stmt = $db->prepare('UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view = ' . $view . ' WHERE id = :id AND module IN (' . $placeholders . ') AND (area = 1 OR area = 2) AND language = :language AND ' . $sql_lev_admin);
    foreach ($values as $k => $v) {
        $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
    }
    $stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);

    $stmt2 = $db->prepare('SELECT view FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE id = :id');

    foreach ($ids as $id) {
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute() or $direct_view != -1) {
            $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();
            $respon['error'] = 0;
            $respon['view'] = intval($stmt2->fetchColumn());
        }
    }

    $respon['data'] = get_unread_notification();
    nv_jsonOutput($respon);
}

$page = $nv_Request->get_page('page', 'get', 1);
$last_id = $nv_Request->get_int('last_id', 'get', 0);
$is_ajax = $nv_Request->isset_request('ajax', 'post,get');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$values = array_values($allowed_mods);
$placeholders = implode(', ', array_map(fn($k) => ':module' . $k, array_keys($values)));

$where = 'language = :language AND (area = 1 OR area = 2) AND module IN (' . $placeholders . ') AND ' . $sql_lev_admin;

// Ajax lấy số thông báo bằng cách lấy dần từ id cuối, không phân trang
if (!$is_ajax) {
    $last_id = 0;
    $per_page = 20;
} else {
    $page = 1;
    $per_page = 10;
}
if ($last_id > 0) {
    $where .= ' AND id < :last_id';
}

$array_data = [];
$array_search = [
    'v' => $nv_Request->get_int('v', 'get', 0)
];
if ($array_search['v'] < 0 or $array_search['v'] > 2 or $is_ajax) {
    $array_search['v'] = 0;
}
if ($array_search['v']) {
    $base_url .= '&amp;v=' . $array_search['v'];
    $where .= ' AND view = :view';
}

$sql = 'SELECT COUNT(*) FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE ' . $where;
$stmt = $db->prepare($sql);
foreach ($values as $k => $v) {
    $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);
if ($last_id > 0) {
    $stmt->bindValue(':last_id', $last_id, PDO::PARAM_INT);
}
if ($array_search['v']) {
    $stmt->bindValue(':view', $array_search['v'] - 1, PDO::PARAM_INT);
}
$stmt->execute();
$all_pages = $stmt->fetchColumn();

$sql = 'SELECT * FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE ' . $where . ' ORDER BY id DESC LIMIT ' . $per_page;
if (!$last_id) {
    $sql .= ' OFFSET ' . (($page - 1) * $per_page);
}
$stmt = $db->prepare($sql);
foreach ($values as $k => $v) {
    $stmt->bindValue(':module' . $k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':language', NV_LANG_DATA, PDO::PARAM_STR);
if ($last_id > 0) {
    $stmt->bindValue(':last_id', $last_id, PDO::PARAM_INT);
}
if ($array_search['v']) {
    $stmt->bindValue(':view', $array_search['v'] - 1, PDO::PARAM_INT);
}
$stmt->execute();

$stmt_cron = $db->prepare('SELECT ' . NV_LANG_DATA . '_cron_name FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = :id');
$stmt_user = $db->prepare('SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');

while ($data = $stmt->fetch()) {
    if (isset($admin_mods[$data['module']]) or isset($site_mods[$data['module']])) {
        $mod = $data['module'];
        $data['content'] = !empty($data['content']) ? unserialize($data['content'], NV_UNSERIALIZE_SAFE) : '';
        $data['send_from_id'] = $data['send_from'];

        // Hien thi thong bao tu cac module he thong
        if ($data['module'] == 'modules') {
            if ($data['type'] == 'auto_deactive_module') {
                $data['title'] = $nv_Lang->getModule('notification_module_auto_deactive', $data['content']['custom_title']);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'];
            }
        }

        if ($data['module'] == 'settings') {
            if ($data['type'] == 'auto_deactive_cronjobs') {
                $stmt_cron->bindValue(':id', $data['content']['cron_id'], PDO::PARAM_INT);
                $stmt_cron->execute();
                $cron_title = $stmt_cron->fetchColumn();
                $data['title'] = $nv_Lang->getModule('notification_cronjobs_auto_deactive', $cron_title);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=cronjobs';
            } elseif ($data['type'] == 'sendmail_failure') {
                $data['title'] = $nv_Lang->getModule('notification_email_failure', $data['content'][0], $data['content'][1]);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=smtp';
            } elseif ($data['type'] == 'server_config_file_changed') {
                $data['title'] = $nv_Lang->getModule('server_config_file_changed', $data['content']['file']);
                $data['link'] = '#';
            }
        }

        // Thông báo từ các module ngoài site
        if (isset($site_mods[$data['module']]) and file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php')) {
            if ($data['send_from'] > 0) {
                $stmt_user->bindValue(':userid', $data['send_from'], PDO::PARAM_INT);
                $stmt_user->execute();
                $user = $stmt_user->fetch();
                $stmt_user->closeCursor();
                if ($user) {
                    $data['send_from'] = nv_show_name_user($user['first_name'], $user['last_name'], $user['username']);
                } else {
                    $data['send_from'] = $nv_Lang->getGlobal('level5');
                }

                if (!empty($user['photo'])) {
                    $data['photo'] = NV_STATIC_URL . $user['photo'];
                }
            } else {
                $data['send_from'] = $nv_Lang->getGlobal('level5');
            }

            // Đọc tạm ngôn ngữ của module
            $nv_Lang->loadModule($site_mods[$data['module']]['module_file'], false, true);

            include NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php';
        } else {
            $data['send_from'] = $nv_Lang->getGlobal('system');
        }

        $data['add_time_iso'] = nv_date("Y-m-d\TH:i:sO", $data['add_time']);
        $data['add_time'] = nv_datetime_format($data['add_time'], 1);

        if (!empty($data['title'])) {
            $array_data[$data['id']] = $data;
        }
    }
}
$stmt->closeCursor();

// Danh sách dạng ajax
if ($is_ajax) {
    $tpl = new NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $array_data);
    $tpl->assign('LAST_ID', $last_id);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));

    $contents = $tpl->fetch('notification_ajax.tpl');
    nv_jsonOutput([
        'html' => nv_url_rewrite(trim($contents))
    ]);
}

// Danh sách đầy đủ
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('notification.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('DATA', $array_data);
$tpl->assign('DATA_SEARCH', $array_search);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $all_pages, $per_page, $page));
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('notification.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
