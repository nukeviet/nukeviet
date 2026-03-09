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

use NukeViet\Module\users\Shared\Emails;

if (!defined('NV_IS_AJAX')) {
    nv_htmlOutput('Wrong URL');
}

$userids = $nv_Request->get_title('userid', 'post', '');
$userids = array_filter(array_unique(array_map('intval', array_map('trim', explode(',', $userids)))));

$error = '';
if (md5(NV_CHECK_SESSION . '_' . $module_name . '_main') == $nv_Request->get_string('checkss', 'post')) {
    foreach ($userids as $userid) {
        $sql = 'SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
        $admin_id = $db->query($sql)->fetchColumn();
        if ($admin_id) {
            continue;
        }

        $sql = 'SELECT group_id, username, first_name, last_name, gender, email, photo, in_groups, idsite, language FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
        $row = $db->query($sql)->fetch(3);
        if (empty($row)) {
            continue;
        }

        [$group_id, $username, $first_name, $last_name, $gender, $email, $photo, $in_groups, $idsite, $userlang] = $row;

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

            $in_groups = array_map('intval', explode(',', $in_groups));

            try {
                // Giảm thống kê số thành viên trong nhóm
                $db->exec('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid . ' AND approved = 1)');
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
            try {
                // Giảm thống kê số thành viên chính thức và số thành viên mới xuống
                $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=' . (($group_id == 7 or in_array(7, $in_groups, true)) ? 7 : 4));
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }

            $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid;
            $row_info = $db->query($sql)->fetch();
            unset($row_info['userid']);
            if (!empty($row_info)) {
                $array_field_config = nv_get_users_field_config();
                foreach ($row_info as $key => $value) {
                    if ($key != 'userid' and !empty($value)) {
                        if ($array_field_config[$key]['field_type'] == 'file') {
                            $value = array_map('trim', explode(',', $value));
                            foreach ($value as $file) {
                                $file_save_info = get_file_save_info($file);
                                if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                    delete_userfile($file_save_info);
                                }
                            }
                        }
                    }
                }
            }

            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_oldpass WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $userid);
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_deleted WHERE userid=' . $userid);

            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $admin_info['userid']);

            if (!empty($photo) and is_file(NV_ROOTDIR . '/' . $photo)) {
                @nv_deletefile(NV_ROOTDIR . '/' . $photo);
            }

            if (count($userids) < 5) {
                $maillang = NV_LANG_INTERFACE;
                if (!empty($userlang) and in_array($userlang, $global_config['setup_langs'], true)) {
                    if ($userlang != NV_LANG_INTERFACE) {
                        $maillang = $userlang;
                    }
                } elseif (NV_LANG_DATA != NV_LANG_INTERFACE) {
                    $maillang = NV_LANG_DATA;
                }

                $send_data = [[
                    'to' => $email,
                    'data' => [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'username' => $username,
                        'email' => $email,
                        'gender' => $gender,
                        'lang' => $maillang
                    ]
                ]];
                nv_sendmail_template_async([$module_name, Emails::USER_DELETE], $send_data, $maillang);
            }

            nv_apply_hook($module_name, 'user_delete', [$userid, $row]);
        }
    }

    $nv_Cache->delMod($module_name);
}

if ($error) {
    nv_htmlOutput('ERROR_' . $error);
}

nv_htmlOutput('OK');
