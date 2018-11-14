<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    nv_htmlOutput('Wrong URL');
}

$userids = $nv_Request->get_title('userid', 'post', '');
$userids = array_filter(array_unique(array_map('intval', array_map('trim', explode(',', $userids)))));

$error = '';

foreach ($userids as $userid) {
    $sql = 'SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
    $admin_id = $db->query($sql)->fetchColumn();
    if ($admin_id) {
        continue;
    }

    $sql = 'SELECT group_id, username, first_name, last_name, email, photo, in_groups, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch(3);
    if (empty($row)) {
        continue;
    }

    list($group_id, $username, $first_name, $last_name, $email, $photo, $in_groups, $idsite) = $row;

    if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
        continue;
    }

    $query = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id IN (1,2,3) AND userid=' . $userid);
    if ($query->fetchColumn()) {
        $error = $lang_module['delete_group_system'];
    } else {
        $userdelete = (!empty($first_name)) ? $first_name . ' (' . $username . ')' : $username;

        $result = $db->exec('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
        if (!$result) {
            continue;
        }

        $in_groups = explode(',', $in_groups);

        $_number = $db->exec('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid . ' AND approved = 1)');
        if ($_number) {
            $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=' . (($group_id == 7 or in_array(7, $in_groups)) ? 7 : 4));
        }
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $userid);
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $admin_info['userid']);

        if (!empty($photo) and is_file(NV_ROOTDIR . '/' . $photo)) {
            @nv_deletefile(NV_ROOTDIR . '/' . $photo);
        }
        
        if (sizeof($userids) < 5) {
            $subject = $lang_module['delconfirm_email_title'];
            $message = sprintf($lang_module['delconfirm_email_content'], $userdelete, $global_config['site_name']);
            $message = nl2br($message);
            nv_sendmail($global_config['site_email'], $email, $subject, $message);
        }
    }
}

$nv_Cache->delMod($module_name);

if ($error) {
    nv_htmlOutput('ERROR_' . $error);
}

nv_htmlOutput('OK');