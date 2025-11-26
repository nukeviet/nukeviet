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

    $offset_time = NV_CURRENTTIME - (7 * 86400);
    $min_time = NV_CURRENTTIME - (90 * 86400);
    $uniqid = uniqid('', true);

    // Xử lý chống trùng lặp
    $sql = "UPDATE " . NV_USERS_GLOBALTABLE . "_deleted SET
        status=-" .  NV_CURRENTTIME . ",
        uniqid='" . $uniqid . "'
    WHERE request_time<=" . $offset_time . " AND request_time>=" . $min_time . " AND uniqid='' AND status=0
    ORDER BY request_time ASC LIMIT 10";
    $exec = $db->exec($sql);
    if (empty($exec)) {
        return true;
    }

    // Lấy danh sách thông tin xóa
    $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . "_deleted WHERE uniqid='" . $uniqid . "'";
    $result = $db->query($sql);
    $deleted_users = $array_userids = [];
    while ($row = $result->fetch()) {
        $deleted_users[] = $row;
        $array_userids[$row['userid']] = $row['userid'];
    }
    if (empty($deleted_users)) {
        return true;
    }

    // Thông tin thành viên của những yêu cầu xóa
    $array_users = [];
    if (!empty($array_userids)) {
        $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (" . implode(',', $array_userids) . ")";
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $array_users[$row['userid']] = $row;
        }
    }

    // Xác định cấu hình giữ username
    $sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='hold_deleted_username'";
    $hold_deleted_username = intval($db->query($sql)->fetchColumn() ?: 0);

    // Lặp và xử lý từng người một
    foreach ($deleted_users as $row) {
        if (!isset($array_users[$row['userid']])) {
            // Thành viên không tồn tại, đánh dấu đã xóa
            $sql = "UPDATE " . NV_USERS_GLOBALTABLE . "_deleted SET
                status=" . NV_CURRENTTIME . ", uniqid=''
            WHERE id=" . $row['id'];
            $db->exec($sql);
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
            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_info WHERE userid=" . $row['userid'];
            $db->query($sql);

            $sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_info (userid) VALUES (" . $row['userid'] . ")";
            $db->query($sql);

            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_openid WHERE userid=" . $row['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_backupcodes WHERE userid=" . $row['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_edit WHERE userid=" . $row['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_login WHERE userid=" . $row['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_USERS_GLOBALTABLE . "_passkey WHERE userid=" . $row['userid'];
            $db->query($sql);

            // Hủy thông tin cá nhân
            $sql = "UPDATE " . NV_USERS_GLOBALTABLE . " SET
                username=" . $db->quote($new_data['username']) . ",
                md5username=" . $db->quote(nv_md5safe($new_data['username'])) . ",
                email=" . $db->quote($new_data['email']) . ",
                first_name=" . $db->quote($new_data['first_name']) . ",
                last_name=" . $db->quote($new_data['last_name']) . ",
                gender='N', birthday=0, sig='', question='', answer='',
                photo='', active=0, checknum=''
            WHERE userid=" . $row['userid'];
            $db->query($sql);

            // Đánh dấu hoàn thành
            $sql = "UPDATE " . NV_USERS_GLOBALTABLE . "_deleted SET
                status=" . NV_CURRENTTIME . ", uniqid=''
            WHERE id=" . $row['id'];
            $db->exec($sql);

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            trigger_error(print_r($e, true));
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
    }

    $nv_Lang->changeLang(NV_LANG_INTERFACE);

    return true;
}
