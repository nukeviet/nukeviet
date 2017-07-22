<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (@) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id=' . $id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$contact_allowed = nv_getAllowed();

if (! isset($contact_allowed['view'][$row['cid']])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$is_read = intval($row['is_read']);
$mark = $nv_Request->get_title('mark', 'post', '');

if ($mark == 'unread') {
    if ($is_read) {
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=0 WHERE id=' . $id);
        nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $id, 0);
    }

    nv_jsonOutput(array( 'status' => 'ok', 'mess' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name ));
}

if (! $is_read) {
    nv_status_notification(NV_LANG_DATA, $module_name, 'contact_new', $row['id']);
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=1 WHERE id=' . $id);
    $is_read = 1;
}

$page_title = $module_info['site_title'];

$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$sender_name = $row['sender_name'];
$sender_id = intval($row['sender_id']);

if ($sender_id) {
    $sender_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $sender_id . '">' . $sender_name . '</a>';
}

$row['send_name'] = $sender_name;
$row['time'] = nv_date('H:i d/m/Y', $row['send_time']);

$part_row_title = $contact_allowed['view'][$row['cid']];
$part_row_title = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=row&amp;id=' . $row['cid'] . '">' . $part_row_title . '</a>';

$row['part_row_title'] = $part_row_title;
$row['url_back'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$xtpl->assign('DATA', $row);

if (! empty($row['sender_phone'])) {
    $xtpl->parse('main.sender_phone');
}

if (isset($contact_allowed['reply'][$row['cid']])) {
    $xtpl->assign('URL_REPLY', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reply&amp;id=' . $row['id']);

    $xtpl->parse('main.reply');
}

$xtpl->assign('URL_FORWARD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=forward&amp;id=' . $row['id']);


if ($row['is_reply']>=1) {
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id=' . $id);
    while ($row = $result->fetch()) {
        $sql = 'SELECT t2.username as admin_login, t2.email as admin_email, t2.first_name, t2.last_name FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.admin_id=' . intval($row['reply_aid']);
        $adm_row = $db->query($sql)->fetch();
        $reply_name = nv_show_name_user($adm_row['first_name'], $adm_row['last_name'], $adm_row['admin_login']);
        $reply_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . intval($row['reply_aid']) . '">' . $reply_name . '</a>';

        $adm_row['reply_name'] = $reply_name;
        $adm_row['reply_time'] = nv_date('H:i d/m/Y', $row['reply_time']);
        $adm_row['sender_name'] = $sender_name;
        $adm_row['reply_content'] = $row['reply_content'];

        $xtpl->assign('REPLY', $adm_row);

        $xtpl->parse('main.data_reply');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
