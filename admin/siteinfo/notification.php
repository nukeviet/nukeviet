<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$allowed_mods = array_unique(array_merge_recursive(array_keys($admin_mods), array_keys($site_mods)));
$page_title = $lang_module['notification'];

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

// Đánh dấu đã xem tất cả các thông báo
if ($nv_Request->isset_request('notification_reset', 'post')) {
    $sql = 'UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view=1
    WHERE view=0 AND (area = 1 OR area = 2) AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND language=' . $db->quote(NV_LANG_DATA) .
    ' AND ' . $sql_lev_admin;
    $db->query($sql);
    nv_htmlOutput('');
}

// Lấy tổng số thông báo chưa xem
if ($nv_Request->isset_request('notification_get', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $last_time_call = $nv_Request->get_int('timestamp', 'post', 0);
    $last_time = 0;
    $count = 0;
    $return = [];

    $sql = 'SELECT add_time FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language="' . NV_LANG_DATA . '"
    AND (area = 1 OR area = 2) AND view=0 AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin . '
    ORDER BY id DESC';
    $result = $db->query($sql);
    $count = $result->rowCount();
    if ($result) {
        $last_time = $result->fetchColumn();
    }

    if ($last_time > $last_time_call) {
        $return = [
            'data_from_file' => $count,
            'timestamp' => $last_time
        ];
    }

    nv_jsonOutput($return);
}

// Xóa một thông báo
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    if ($id) {
        $sql = 'DELETE FROM ' . NV_NOTIFICATION_GLOBALTABLE . '
        WHERE id=' . $id . ' AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND (area = 1 OR area = 2) AND language=\'' . NV_LANG_DATA . '\' AND ' . $sql_lev_admin;
        $db->query($sql);
        nv_htmlOutput('OK');
    }

    nv_htmlOutput('ERROR');
}

$page = $nv_Request->get_int('page', 'get', 1);
$is_ajax = $nv_Request->isset_request('ajax', 'post,get');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = $is_ajax ? 10 : 20;
$array_data = [];

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_NOTIFICATION_GLOBALTABLE)
    ->where('language = "' . NV_LANG_DATA . '" AND (area = 1 OR area = 2) AND module IN(\'' . implode("', '", $allowed_mods) . '\') AND ' . $sql_lev_admin);

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
        $data['content'] = !empty($data['content']) ? unserialize($data['content']) : '';

        // Hien thi thong bao tu cac module he thong
        if ($data['module'] == 'modules') {
            if ($data['type'] == 'auto_deactive_module') {
                $data['title'] = sprintf($lang_module['notification_module_auto_deactive'], $data['content']['custom_title']);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'];
            }
        }

        if ($data['module'] == 'settings') {
            if ($data['type'] == 'auto_deactive_cronjobs') {
                $cron_title = $db->query('SELECT ' . NV_LANG_DATA . '_cron_name FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $data['content']['cron_id'])->fetchColumn();
                $data['title'] = sprintf($lang_module['notification_cronjobs_auto_deactive'], $cron_title);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=cronjobs';
            } elseif ($data['type'] == 'sendmail_failure') {
                $data['title'] = sprintf($lang_module['notification_email_failure'], $data['content'][0], $data['content'][1]);
                $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=smtp';
            }
        }

        // Hien thi tu cac module
        if (isset($site_mods[$data['module']]) and file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php')) {
            // Hien thi thong bao tu cac module site
            if ($data['send_from'] > 0) {
                $user_info = $db->query('SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $data['send_from'])->fetch();
                if ($user_info) {
                    $data['send_from'] = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
                } else {
                    $data['send_from'] = $lang_global['level5'];
                }

                if (!empty($user_info['avata'])) {
                    $data['photo'] = $user_info['avata'];
                } else {
                    $data['photo'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
                }
            } else {
                $data['photo'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
                $data['send_from'] = $lang_global['level5'];
            }

            include NV_ROOTDIR . '/modules/' . $site_mods[$data['module']]['module_file'] . '/notification.php';
        }

        $data['add_time_iso'] = nv_date(DATE_ISO8601, $data['add_time']);
        $data['add_time'] = nv_date('H:i d/m/Y', $data['add_time']);

        if (!empty($data['title'])) {
            $array_data[$data['id']] = $data;
        }
    }
}

$xtpl = new XTemplate('notification.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/siteinfo');
$xtpl->assign('LANG', $lang_module);

if (!empty($array_data)) {
    foreach ($array_data as $data) {
        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }

    if ($is_ajax) {
        $contents = $xtpl->text('main.loop');
    } else {
        $generate_page = nv_generate_page($base_url, $all_pages, $per_page, $page);
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    }
} elseif ($is_ajax) {
    $contents = $page == 1 ? $lang_module['notification_empty'] : '';
} else {
    if ($page != 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    $xtpl->parse('empty');
    $contents = $xtpl->text('empty');
}

include NV_ROOTDIR . '/includes/header.php';
echo $is_ajax ? $contents : nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
