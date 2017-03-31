<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$userid = $nv_Request->get_int('userid', 'post', 0);

$sql = 'SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
$admin_id = $db->query($sql)->fetchColumn();
if ($admin_id) {
    die('NO');
}

$sql = 'SELECT group_id, username, first_name, last_name, email, photo, in_groups, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
$row = $db->query($sql)->fetch(3);
if (empty($row)) {
    die('NO');
}

list($group_id, $username, $first_name, $last_name, $email, $photo, $in_groups, $idsite) = $row;

if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
    die('NO');
}

$query = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id IN (1,2,3) AND userid=' . $userid);
if ($query->fetchColumn()) {
    die('ERROR_' . $lang_module['delete_group_system']);
} else {
    $userdelete = (! empty($first_name)) ? $first_name . ' (' . $username . ')' : $username;

    $result = $db->exec('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
    if (! $result) {
        die('NO');
    }
    
    $in_groups = explode(',', $in_groups);
    
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid . ' AND approved = 1)');
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=' . (($group_id == 7 or in_array(7, $in_groups)) ? 7 : 4));
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $userid);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $admin_info['userid']);

    if (! empty($photo) and is_file(NV_ROOTDIR . '/' . $photo)) {
        @nv_deletefile(NV_ROOTDIR . '/' . $photo);
    }
    $nv_Cache->delMod($module_name);
    
    $subject = $lang_module['delconfirm_email_title'];
    $message = sprintf($lang_module['delconfirm_email_content'], $userdelete, $global_config['site_name']);
    $message = nl2br($message);
    nv_sendmail($global_config['site_email'], $email, $subject, $message);
    die('OK');
}
