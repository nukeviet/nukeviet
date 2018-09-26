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
$send_data = [];

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
        $error = $nv_Lang->getModule('delete_group_system');
    } else {
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
            $send_data[] = [
                'to' => [$email],
                'data' => [
                    $group_id,
                    $username,
                    $first_name,
                    $last_name,
                    $email,
                    $photo,
                    $in_groups,
                    $idsite,
                    $global_config
                ]
            ];
        }
    }

    nv_apply_hook($module_name, 'user_delete', array($userid));
}

if (!empty($send_data)) {
    nv_sendmail_from_template(NukeViet\Template\Email\Tpl::E_USER_DELETE, $send_data);
}

$nv_Cache->delMod($module_name);

if ($error) {
    nv_htmlOutput('ERROR_' . $error);
}

nv_htmlOutput('OK');
