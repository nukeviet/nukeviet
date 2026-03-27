<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;

/**
 * Tiến trình xóa dữ liệu người dùng đã thiết lập lịch xóa
 *
 * @return true
 */
function cron_user_datadeletion_handling()
{
    global $db, $nv_Lang;

    $skiped = nv_apply_hook('', 'cron_user_datadeletion_handling');
    if (!is_null($skiped)) {
        return true;
    }

    $offset_time = NV_CURRENTTIME - (7 * 86400);
    $min_time = NV_CURRENTTIME - (90 * 86400);
    $uniqid = uniqid('', true);

    // Xử lý chống trùng lặp
    $sql = "UPDATE " . NV_USERS_GLOBALTABLE . "_deleted SET
        status = :status,
        uniqid = :uniqid
    WHERE request_time <= :offset_time AND request_time >= :min_time AND uniqid = '' AND status = 0
    ORDER BY request_time ASC LIMIT 10";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':status', -NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':uniqid', $uniqid, PDO::PARAM_STR);
    $stmt->bindValue(':offset_time', $offset_time, PDO::PARAM_INT);
    $stmt->bindValue(':min_time', $min_time, PDO::PARAM_INT);
    $stmt->execute();
    $exec = $stmt->rowCount();
    if (empty($exec)) {
        return true;
    }

    // Lấy danh sách thông tin xóa
    $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . "_deleted WHERE uniqid = :uniqid";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uniqid', $uniqid, PDO::PARAM_STR);
    $stmt->execute();
    $deleted_users = $array_userids = [];
    while ($row = $stmt->fetch()) {
        $deleted_users[] = $row;
        $array_userids[$row['userid']] = $row['userid'];
    }
    $stmt->closeCursor();
    if (empty($deleted_users)) {
        return true;
    }

    // Thông tin thành viên của những yêu cầu xóa
    $array_users = [];
    if (!empty($array_userids)) {
        $ids = implode(',', array_map('intval', $array_userids));
        $stmt = $db->prepare("SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (" . $ids . ")");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $array_users[$row['userid']] = $row;
        }
        $stmt->closeCursor();
    }

    // Xác định cấu hình giữ username
    $stmt = $db->prepare("SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config = 'hold_deleted_username'");
    $stmt->execute();
    $hold_deleted_username = intval($stmt->fetchColumn() ?: 0);

    // Chuẩn bị các câu lệnh lặp
    $stmt_del_deleted = $db->prepare("UPDATE " . NV_USERS_GLOBALTABLE . "_deleted SET status = :status, uniqid = '' WHERE id = :id");
    $stmt_del_info = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_info WHERE userid = :userid");
    $stmt_ins_info = $db->prepare("INSERT INTO " . NV_USERS_GLOBALTABLE . "_info (userid) VALUES (:userid)");
    $stmt_del_openid = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_openid WHERE userid = :userid");
    $stmt_del_codes = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_backupcodes WHERE userid = :userid");
    $stmt_del_edit = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_edit WHERE userid = :userid");
    $stmt_del_login = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_login WHERE userid = :userid");
    $stmt_del_passkey = $db->prepare("DELETE FROM " . NV_USERS_GLOBALTABLE . "_passkey WHERE userid = :userid");
    $stmt_upd_user = $db->prepare("UPDATE " . NV_USERS_GLOBALTABLE . " SET
            username = :username,
            md5username = :md5username,
            email = :email,
            first_name = :first_name,
            last_name = :last_name,
            gender = 'N', birthday = 0, sig = '', question = '', answer = '',
            photo = '', active = 0, checknum = ''
        WHERE userid = :userid");

    // Lặp và xử lý từng người một
    foreach ($deleted_users as $row) {
        if (!isset($array_users[$row['userid']])) {
            // Thành viên không tồn tại, đánh dấu đã xóa
            $stmt_del_deleted->bindValue(':status', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_del_deleted->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $stmt_del_deleted->execute();
            continue;
        }

        // Thành viên tồn tại, tiến hành xóa dữ liệu
        $db->beginTransaction();
        try {
            $new_data = [];
            $new_data['username'] = 'deleteduser.' . nv_genpass(8);
            $new_data['first_name'] = 'User';
            $new_data['last_name'] = 'Deleted';
            $new_data['email'] = $new_data['username'] . '@' . NV_SERVER_NAME;

            // Xóa các dữ liệu liên quan
            $stmt_del_info->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_info->execute();

            $stmt_ins_info->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_ins_info->execute();

            $stmt_del_openid->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_openid->execute();

            $stmt_del_codes->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_codes->execute();

            $stmt_del_edit->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_edit->execute();

            $stmt_del_login->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_login->execute();

            $stmt_del_passkey->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_del_passkey->execute();

            // Hủy thông tin cá nhân
            $stmt_upd_user->bindValue(':username', $new_data['username'], PDO::PARAM_STR);
            $stmt_upd_user->bindValue(':md5username', nv_md5safe($new_data['username']), PDO::PARAM_STR);
            $stmt_upd_user->bindValue(':email', $new_data['email'], PDO::PARAM_STR);
            $stmt_upd_user->bindValue(':first_name', $new_data['first_name'], PDO::PARAM_STR);
            $stmt_upd_user->bindValue(':last_name', $new_data['last_name'], PDO::PARAM_STR);
            $stmt_upd_user->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt_upd_user->execute();

            // Đánh dấu hoàn thành
            $stmt_del_deleted->bindValue(':status', NV_CURRENTTIME, PDO::PARAM_INT);
            $stmt_del_deleted->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $stmt_del_deleted->execute();

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            trigger_error($e);
            continue;
        }

        // Gửi email thông báo
        $user_info = $array_users[$row['userid']];
        $lang = $user_info['language'] ?: NV_LANG_DATA;

        $nv_Lang->changeLang($lang);
        $nv_Lang->loadModule('users', false, true);
        if (empty($hold_deleted_username)) {
            $hold_message = $nv_Lang->getModule('delaccount_explain12');
        } elseif ($hold_deleted_username > 999) {
            $hold_message = $nv_Lang->getModule('delaccount_explain11');
        } else {
            // Nếu số ngày giữ là bội số năm
            if ($hold_deleted_username % 365 == 0) {
                $hold_message = $nv_Lang->getModule('delaccount_explain9', $hold_deleted_username / 365);
            } else {
                $hold_message = $nv_Lang->getModule('delaccount_explain10', $hold_deleted_username);
            }
        }

        $send_data = [[
            'to' => $user_info['email'],
            'data' => [
                'first_name' => $user_info['first_name'],
                'last_name' => $user_info['last_name'],
                'username' => $user_info['username'],
                'email' => $user_info['email'],
                'gender' => $user_info['gender'],
                'newvalue' => $hold_message,
                'lang' => $lang
            ]
        ]];
        nv_sendmail_template_async(['users', Emails::DELETE_ACCOUNT_COMPLETED], $send_data, $lang);
        nv_apply_hook('', 'cron_user_datadeletion_handling_row', [$row, $user_info]);
    }

    $nv_Lang->changeLang(NV_LANG_INTERFACE);

    return true;
}
