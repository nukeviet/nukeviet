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
$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_main';
if (csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    $stmt_check_admin = $db->prepare('SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id = :userid');
    $stmt_get_user = $db->prepare('SELECT group_id, username, first_name, last_name, gender, email, photo, in_groups, idsite, language FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
    $stmt_check_sys_group = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id IN (1,2,3) AND userid = :userid');
    $stmt_delete_user = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid = :userid');
    $stmt_update_group_num = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers - 1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid AND approved = 1)');
    $stmt_update_std_group = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers - 1 WHERE group_id = :gid');
    $stmt_get_info = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid = :userid');
    $stmt_del_groups_users = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid = :userid');
    $stmt_del_openid = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid = :userid');
    $stmt_del_info = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid = :userid');
    $stmt_del_oldpass = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_oldpass WHERE userid = :userid');
    $stmt_del_login = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid = :userid');
    $stmt_del_passkey = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_passkey WHERE userid = :userid');
    $stmt_del_deleted = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_deleted WHERE userid = :userid');

    foreach ($userids as $userid) {
        $stmt_check_admin->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt_check_admin->execute();
        if ($stmt_check_admin->fetchColumn()) {
            $stmt_check_admin->closeCursor();
            continue;
        }
        $stmt_check_admin->closeCursor();

        $stmt_get_user->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt_get_user->execute();
        $row = $stmt_get_user->fetch();
        $stmt_get_user->closeCursor();
        if (empty($row)) {
            continue;
        }

        $group_id = $row['group_id'];
        $username = $row['username'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $email = $row['email'];
        $photo = $row['photo'];
        $in_groups = $row['in_groups'];
        $idsite = $row['idsite'];
        $userlang = $row['language'];

        if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
            continue;
        }

        $stmt_check_sys_group->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt_check_sys_group->execute();
        if ($stmt_check_sys_group->fetchColumn()) {
            $stmt_check_sys_group->closeCursor();
            $error = $nv_Lang->getModule('delete_group_system');
        } else {
            $stmt_check_sys_group->closeCursor();
            $stmt_delete_user->bindValue(':userid', $userid, PDO::PARAM_INT);
            if (!$stmt_delete_user->execute()) {
                continue;
            }

            $in_groups = array_map('intval', explode(',', $in_groups));

            try {
                // Giảm thống kê số thành viên trong nhóm
                $stmt_update_group_num->bindValue(':userid', $userid, PDO::PARAM_INT);
                $stmt_update_group_num->execute();
            } catch (Throwable $e) {
                trigger_error($e);
            }
            try {
                // Giảm thống kê số thành viên chính thức và số thành viên mới xuống
                $stmt_update_std_group->bindValue(':gid', (($group_id == 7 or in_array(7, $in_groups, true)) ? 7 : 4), PDO::PARAM_INT);
                $stmt_update_std_group->execute();
            } catch (Throwable $e) {
                trigger_error($e);
            }

            $stmt_get_info->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_get_info->execute();
            $row_info = $stmt_get_info->fetch();
            $stmt_get_info->closeCursor();
            if (!empty($row_info)) {
                unset($row_info['userid']);
                $array_field_config = nv_get_users_field_config();
                foreach ($row_info as $key => $value) {
                    if ($key != 'userid' and !empty($value)) {
                        if (isset($array_field_config[$key]) and $array_field_config[$key]['field_type'] == 'file') {
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

            $stmt_del_groups_users->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_groups_users->execute();
            $stmt_del_openid->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_openid->execute();
            $stmt_del_info->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_info->execute();
            $stmt_del_oldpass->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_oldpass->execute();
            $stmt_del_login->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_login->execute();
            $stmt_del_passkey->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_passkey->execute();
            $stmt_del_deleted->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del_deleted->execute();

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
} else {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$nv_Cache->delMod($module_name);

if ($error) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $error
    ]);
}

nv_jsonOutput([
    'status' => 'ok'
]);
