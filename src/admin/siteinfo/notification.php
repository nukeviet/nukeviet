<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-10-2010 14:43
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$allowed_mods = array_unique(array_merge_recursive(array_keys($admin_mods), array_keys($site_mods)));
$page_title = $nv_Lang->getGlobal('notification');

if ($admin_info['level'] == 1) {
    /*
     * Quản trị tối cao xem được:
     * - Thông báo cấp dưới với điều kiện logic mode = 0
     * - Thông báo set cho cấp quản trị tối cao với điều kiện:
     * + Không chỉ định người nhận => Toàn bộ quản trị tối cao
     * + Hoặc chỉ định chính người nhận là mình
     */
    $sql_lev_admin = '((admin_view_allowed!=1 AND logic_mode=0) OR (
        admin_view_allowed=1 AND (send_to=\'\' OR FIND_IN_SET(' . $admin_info['admin_id'] . ', send_to))
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
            admin_view_allowed=2 AND (send_to=\'\' OR FIND_IN_SET(' . $admin_info['admin_id'] . ', send_to))
        )
    ))';
} else {
    /*
     * Quản lý module xem được:
     * - Thông báo set cho toàn bộ
     * - Hoặc thông báo set cho chính mình
     */
    $sql_lev_admin = '(admin_view_allowed=0 AND (
        send_to=\'\' OR FIND_IN_SET(' . $admin_info['admin_id'] . ', send_to)
    ))';
}

// Đánh dấu tất cả các thông báo đã đọc
if ($nv_Request->isset_request('notification_reset', 'post')) {
    $sql = 'UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view=1
    WHERE view=0 AND (area = 1 OR area = 2) AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND language=' . $db->quote(NV_LANG_DATA) .
    ' AND ' . $sql_lev_admin;
    $db->query($sql);
    nv_htmlOutput('');
}

// Lấy số thông báo chưa xem
if ($nv_Request->isset_request('notification_get', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $return = [
        'total' => $db->query('SELECT COUNT(*) FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language="' . NV_LANG_DATA . '" AND area=1 AND view=0 AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin)->fetchColumn(),
        'new' => $db->query('SELECT COUNT(*) FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language="' . NV_LANG_DATA . '" AND area=1 AND is_new=1 AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin)->fetchColumn()
    ];

    nv_jsonOutput($return);
}

// Xóa thông báo
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $ids = $nv_Request->get_title('ids', 'post', '');
    $ids = explode(',', $ids);
    $ids[] = $id;
    $ids = array_filter(array_unique(array_map('intval', $ids)));

    if (!empty($ids)) {
        $db->query("DELETE FROM " . NV_NOTIFICATION_GLOBALTABLE . " WHERE id IN(" . implode(',', $ids) . ') AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin);
        nv_htmlOutput('OK');
    }

    nv_htmlOutput('ERROR');
}

// Đánh dấu đã xem thông báo
if ($nv_Request->isset_request('setviewed', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $ids = $nv_Request->get_title('ids', 'post', '');
    $ids = explode(',', $ids);
    $ids[] = $id;
    $ids = array_filter(array_unique(array_map('intval', $ids)));

    if (!empty($ids)) {
        $db->query("UPDATE " . NV_NOTIFICATION_GLOBALTABLE . " SET view=1 WHERE id IN(" . implode(',', $ids) . ') AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin);
        nv_htmlOutput('OK');
    }

    nv_htmlOutput('ERROR');
}

$page = $nv_Request->get_int('page', 'get', 1);
$is_ajax = $nv_Request->isset_request('ajax', 'post,get');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = $is_ajax ? 10 : 20;
$array_data = array();
$array_search = [
    'v' => $nv_Request->get_int('v', 'get', 0)
];
if ($array_search['v'] < 0 or $array_search['v'] > 2 or $is_ajax) {
    $array_search['v'] = 0;
}
if ($array_search['v']) {
    $base_url .= '&amp;v=' . $array_search['v'];
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_NOTIFICATION_GLOBALTABLE)
    ->where('language = "' . NV_LANG_DATA . '" AND (area = 1 OR area = 2) AND module IN(\'' . implode("', '", $allowed_mods) . '\')' . ($array_search['v'] > 0 ? (' AND view=' . ($array_search['v'] - 1)) : '') . ' AND ' . $sql_lev_admin);

$all_pages = $db->query($db->sql())
    ->fetchColumn();

$db->select('*')
    ->order('id DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());
$num_rows = $result->rowCount();

while ($data = $result->fetch()) {
    if (isset($admin_mods[$data['module']]) or isset($site_mods[$data['module']])) {
        $mod = $data['module'];
        $data['content'] = !empty($data['content']) ? unserialize($data['content']) : [];

        if ($data['module'] == 'modules') {
            // Thông báo từ phần quản lý module
            if ($data['type'] == 'auto_deactive_module') {
                $data['title'] = $nv_Lang->getModule('notification_module_auto_deactive', $data['content']['custom_title']);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'];
                $data['send_from'] = $nv_Lang->getGlobal('system');
            }
        } elseif ($data['module'] == 'settings') {
            // Thông báo từ phần cronjobs
            if ($data['type'] == 'auto_deactive_cronjobs') {
                $cron_title = $db->query('SELECT ' . NV_LANG_DATA . '_cron_name FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $data['content']['cron_id'])->fetchColumn();
                $data['title'] = $nv_Lang->getModule('notification_cronjobs_auto_deactive', $cron_title);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=cronjobs';
                $data['send_from'] = $nv_Lang->getGlobal('system');
            }
        } elseif (isset($site_mods[$data['module']]) and file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php')) {
            // Thông báo từ các module ngoài site
            if ($data['send_from'] > 0) {
                $user_info = $db->query('SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $data['send_from'])->fetch();
                if ($user_info) {
                    $data['send_from'] = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
                } else {
                    $data['send_from'] = $nv_Lang->getGlobal('level5');
                }

                if (!empty($user_info['photo']) and file_exists(NV_ROOTDIR . '/' . $user_info['photo'])) {
                    $data['photo'] = NV_BASE_SITEURL . $user_info['photo'];
                } else {
                    $data['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/Users/no-avatar.png';
                }
            } else {
                $data['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/Users/no-avatar.png';
                $data['send_from'] = $nv_Lang->getGlobal('level5');
            }

            // Đọc tạm ngôn ngữ của module
            $nv_Lang->loadModule($site_mods[$data['module']]['module_file'], false, true);

            include NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php';

            // Xóa ngôn ngữ đã đọc tạm
            $nv_Lang->changeLang();
        }

        $data['add_time_iso'] = nv_date(DATE_ISO8601, $data['add_time']);
        $data['add_time_d'] = nv_date('d/m/Y', $data['add_time']);
        $data['add_time_h'] = nv_date('H:i', $data['add_time']);
        $data['add_time'] = nv_date('H:i d/m/Y', $data['add_time']);

        if (!empty($data['title'])) {
            $data['title2'] = nv_ucfirst($data['title']);
            $array_data[$data['id']] = $data;
        }
    }
}

// Dữ liệu ajax
if ($is_ajax) {
    $contents = '';

    if (!empty($array_data)) {
        $tpl = new \NukeViet\Template\Smarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('DATA', $array_data);

        $contents = $tpl->fetch('notification_ajax.tpl');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($array_data) and $page != 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $array_data);
$tpl->assign('DATA_SEARCH', $array_search);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('GENERATE_PAGE', nv_generate_page($base_url, $all_pages, $per_page, $page));

$contents = $tpl->fetch('notification.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
