<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id=' . $id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$contact_allowed = nv_getAllowed();

if (!isset($contact_allowed['view'][$row['cid']])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$is_read = (int) ($row['is_read']);
$processed = (int) ($row['is_processed']);
$mark = $nv_Request->get_title('mark', 'post', '');

if ($mark == 'unread') {
    if ($is_read) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=0, is_processed=0, processed_by=0, processed_time=0 WHERE id=' . $id);
        nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $id, 0);
    }

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
    ]);
} elseif ($mark == 'toogle_process') {
    if ($processed) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_processed=0, processed_by=0, processed_time=0 WHERE id=' . $id);
        nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $id, 0);
    } else {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_processed=1, processed_by=' . $admin_info['userid'] . ', processed_time=' . NV_CURRENTTIME . ' WHERE id=' . $id);
        nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $id, 0);
    }
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
    ]);
}

if (!$is_read) {
    nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $row['id']);
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=1 WHERE id=' . $id);
    $is_read = 1;
}

$page_title = $module_info['site_title'];

$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$sender_name = $row['sender_name'];
$sender_id = (int) ($row['sender_id']);

if ($sender_id) {
    $sender_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $sender_id . '">' . $sender_name . '</a>';
}

if ($processed) {
    $xtpl->assign('MARK_PROCESS', $lang_module['mark_as_unprocess']);
} else {
    $xtpl->assign('MARK_PROCESS', $lang_module['mark_as_processed']);
}

$row['send_name'] = $sender_name;
$row['time'] = nv_date('H:i d/m/Y', $row['send_time']);

$part_row_title = $contact_allowed['view'][$row['cid']];
$part_row_title = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row&amp;id=' . $row['cid'] . '">' . $part_row_title . '</a>';

$row['part_row_title'] = $part_row_title;
$row['url_back'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$xtpl->assign('DATA', $row);

if (!empty($row['sender_phone'])) {
    $xtpl->parse('main.sender_phone');
}

if (isset($contact_allowed['reply'][$row['cid']])) {
    $xtpl->assign('URL_REPLY', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reply&amp;id=' . $row['id']);

    $xtpl->parse('main.reply');
}

$xtpl->assign('URL_FORWARD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=forward&amp;id=' . $row['id']);

if ($row['is_reply'] >= 1) {
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id=' . $id);
    while ($row = $result->fetch()) {
        $sql = 'SELECT t2.username as admin_login, t2.email as admin_email, t2.first_name, t2.last_name FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.admin_id=' . (int) ($row['reply_aid']);
        $adm_row = $db->query($sql)->fetch();
        $reply_name = nv_show_name_user($adm_row['first_name'], $adm_row['last_name'], $adm_row['admin_login']);
        $reply_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . (int) ($row['reply_aid']) . '">' . $reply_name . '</a>';

        $adm_row['reply_name'] = $reply_name;
        $adm_row['reply_time'] = nv_date('H:i d/m/Y', $row['reply_time']);
        $adm_row['sender_name'] = $sender_name;
        $adm_row['reply_content'] = $row['reply_content'];

        $xtpl->assign('REPLY', $adm_row);

        $xtpl->parse('main.data_reply');
    }
}

if ($row['is_processed']) {
    $sql = 'SELECT username, email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['processed_by'];
    $adm_row = $db->query($sql)->fetch();
    if (!empty($adm_row)) {
        $reply_name = $adm_row['username'];
        $reply_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $row['processed_by'] . '">' . $reply_name . '</a>';
        $reply_email = $adm_row['email'];
    } else {
        $reply_name = 'N/A';
        $reply_email = '';
    }

    $xtpl->assign('PROCESSED_DATA', [
        'user' => $reply_name,
        'email' => $reply_email,
        'time' => nv_date('H:i d/m/Y', $row['processed_time'])
    ]);
    $xtpl->parse('main.data_processed');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
